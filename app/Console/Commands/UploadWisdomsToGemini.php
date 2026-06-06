<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ArabicWisdom;
use App\Models\Wisdom;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class UploadWisdomsToGemini extends Command
{
    protected $signature   = 'gemini:upload-wisdoms';
    protected $description = 'Export wisdoms from MySQL and upload to Gemini Files API';

    public function handle()
    {
        $this->info('Exporting wisdoms from database...');

        // Export only the text fields — skip IDs, timestamps, etc. in random order to get a good mix of wisdoms (old + new)
        $content = Wisdom::limit(25000)->select('text')
            ->inRandomOrder()
            ->get()
            ->map(
                fn($w) =>
                $w->text
            )
            ->implode("\n");

        $sizeKB = round(strlen($content) / 1024, 2);
        $count  = Wisdom::limit(25000)->select('text')->inRandomOrder()->get()->count();

        $this->info("Records: {$count} | Size: {$sizeKB} KB");

        if (strlen($content) > 20 * 1024 * 1024) {
            $this->error('Content exceeds 20MB Gemini limit. Consider trimming old or duplicate records.');
            return 1;
        }

        $apiKey = config('services.gemini.api_key');

        // Delete old file from Gemini if exists
        $oldFileName = Cache::get('gemini_wisdoms_file_name');
        if ($oldFileName) {
            $this->info('Deleting old file from Gemini...');
            Http::delete("https://generativelanguage.googleapis.com/v1beta/{$oldFileName}?key={$apiKey}");
        }

        $this->info('Uploading to Gemini Files API...');

        // Step 1 — initiate resumable upload
        $initResponse = Http::withHeaders([
            'X-Goog-Upload-Protocol'              => 'resumable',
            'X-Goog-Upload-Command'               => 'start',
            'X-Goog-Upload-Header-Content-Length' => strlen($content),
            'X-Goog-Upload-Header-Content-Type'   => 'text/plain',
            'Content-Type'                        => 'application/json',
        ])->post("https://generativelanguage.googleapis.com/upload/v1beta/files?key={$apiKey}", [
            'file' => ['display_name' => 'arabic_wisdoms_' . now()->format('Ymd_His')]
        ]);

        $uploadUrl = $initResponse->header('X-Goog-Upload-URL');

        if (!$uploadUrl) {
            $this->error('Failed to get upload URL: ' . $initResponse->body());
            Log::error('Gemini upload failed: ' . $initResponse->body());
            return 1;
        }

        // Step 2 — upload content
        $uploadResponse = Http::withHeaders([
            'Content-Type'          => 'text/plain',
            'X-Goog-Upload-Offset'  => '0',
            'X-Goog-Upload-Command' => 'upload, finalize',
        ])->withBody($content, 'text/plain')->post($uploadUrl);

        $fileUri  = $uploadResponse->json('file.uri');
        $fileName = $uploadResponse->json('file.name');

        if (!$fileUri) {
            $this->error('Upload failed: ' . $uploadResponse->body());
            Log::error('Gemini upload failed: ' . $uploadResponse->body());
            return 1;
        }

        // Save to cache — expires in 47 hours (just before Gemini deletes it)
        Cache::put('gemini_wisdoms_file_uri',  $fileUri,  now()->addHours(47));
        Cache::put('gemini_wisdoms_file_name', $fileName, now()->addHours(47));

        $this->info("✅ Upload successful!");
        $this->info("URI: {$fileUri}");
        $this->info("Records uploaded: {$count}");
        $this->info("Size: {$sizeKB} KB");

        Log::info('Gemini wisdoms uploaded', [
            'file_uri' => $fileUri,
            'records'  => $count,
            'size_kb'  => $sizeKB,
        ]);
        //change the .env variable to the new file URI so that it can be used by the app immediately without waiting for cache expiration
        // Note: This is a bit hacky since .env files are not meant to be modified at runtime, but it ensures the app uses the latest file without needing a cache refresh
        $envPath = base_path('.env');
        if (file_exists($envPath)) {
            $envContent = file_get_contents($envPath);
            $newEnvContent = preg_replace(
                '/GEMINI_WISDOMS_FILE_URI=.*/',
                'GEMINI_WISDOMS_FILE_URI=' . $fileUri,
                $envContent
            );
            file_put_contents($envPath, $newEnvContent);
            $this->info('Updated .env with new file URI.');
        } else {
            $this->warn('.env file not found. Please update GEMINI_WISDOMS_FILE_URI manually.');
        }
        //download the file locally for backup (optional)
        Storage::disk('local')->put('gemini_wisdoms_backup_' . now()->format('Ymd_His') . '.txt', $content);
        return 0;
    }
}
