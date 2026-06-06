<?php

namespace App\Livewire;

use App\Models\Wisdom;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class WisdomCard extends Component
{
    public Wisdom $wisdom;

    public bool    $liked           = false;
    public bool    $translating     = false;
    public ?string $translation     = null;
    public bool    $generatingImage = false;
    public ?string $imageUrl        = null;
    public bool    $showImage       = false;

    public function like(): void
    {
        if (!$this->liked) {
            $this->wisdom->incrementLike();
            $this->liked = true;
            $this->dispatch('show-toast', [
                'message' => 'أُضيف إعجابك',
                'type'    => 'success',
                'icon'    => '❤',
            ]);
        }
    }

    public function translate(): void
    {
        if ($this->translation) {
            $this->translation = null;
            return;
        }

        $this->translating = true;

        $response = Http::timeout(15)->post(
            'https://generativelanguage.googleapis.com/v1beta/models/'
                . config('services.gemini.model')
                . ':generateContent?key='
                . config('services.gemini.api_key'),
            [
                'contents' => [[
                    'parts' => [[
                        'text' => 'Translate this Arabic wisdom to English. Return only the translation, nothing else: '
                            . $this->wisdom->text,
                    ]],
                ]],
                'generationConfig' => ['temperature' => 0.1, 'maxOutputTokens' => 1000],
            ]
        );

        $this->translation = $response->json('candidates.0.content.parts.0.text')
            ?? 'Translation unavailable.';

        $this->translating = false;

        $this->dispatch('show-toast', [
            'message' => 'تمت الترجمة بنجاح',
            'type'    => 'info',
            'icon'    => '🌐',
        ]);
    }

    public function generateImage(): void
    {
        // Toggle off if already showing
        if ($this->showImage) {
            $this->showImage = false;
            return;
        }

        // Re-show cached image without another API call
        if ($this->imageUrl) {
            $this->showImage = true;
            return;
        }
        // Check if we already generated and cached this image on disk
        $cachePath = 'ai-images/' . $this->wisdom->id . '.jpg';
        if (Storage::disk('public')->exists($cachePath)) {
            $this->imageUrl  = asset('storage/' . $cachePath);
            $this->showImage = true;
            return;
        }

        $this->generatingImage = true;

        // Step 1 — Gemini writes a vivid English art prompt
        $promptResponse = Http::timeout(15)->post(
            'https://generativelanguage.googleapis.com/v1beta/models/'
                . config('services.gemini.model')
                . ':generateContent?key='
                . config('services.gemini.api_key'),
            [
                'contents' => [[
                    'parts' => [[
                        'text' => 'Write a 6-10 word English image prompt for this Arabic wisdom: "'
                            . $this->wisdom->text
                            . '". Style: cinematic, golden light, Arabic art, dramatic. Return ONLY the prompt, no punctuation.',
                    ]],
                ]],
                'generationConfig' => ['temperature' => 0.7, 'maxOutputTokens' => 30],
            ]
        );

        $prompt = trim(
            $promptResponse->json('candidates.0.content.parts.0.text')
                ?? 'golden arabic calligraphy wisdom cinematic dramatic art'
        );

        // Step 2 — Search Lexica.art for matching AI-generated images
        // Free, no API key, no rate limits, returns beautiful AI art
        // Step 2 — Try Hugging Face FLUX.1-schnell (free, best quality)
        $hfToken = config('services.huggingface.token');

        if ($hfToken) {
            $imageUrl = $this->generateViaHuggingFace($prompt, $cachePath, $hfToken);
        }
        // Step 3 — Fallback: beautiful deterministic photo from Picsum (always works, no API key)
        if (empty($imageUrl)) {
            $imageUrl = 'https://picsum.photos/seed/' . $this->wisdom->id . '/800/400';
        }
        $this->imageUrl        = $imageUrl;
        $this->showImage       = true;
        $this->generatingImage = false;
        $this->dispatch('show-toast', [
            'message' => 'تم توليد الصورة',
            'type'    => 'success',
            'icon'    => '🎨',
        ]);
    }
    private function generateViaHuggingFace(string $prompt, string $cachePath, string $token): ?string
    {
        $response = Http::timeout(60)
            ->withToken($token)
            ->withHeaders(['Accept' => 'image/jpeg'])
            ->post('https://api-inference.huggingface.co/models/black-forest-labs/FLUX.1-schnell', [
                'inputs' => $prompt,
                'parameters' => ['num_inference_steps' => 4],
            ]);
        if ($response->successful() && str_starts_with($response->header('Content-Type'), 'image/')) {
            Storage::disk('public')->makeDirectory('ai-images');
            Storage::disk('public')->put($cachePath, $response->body());
            return asset('storage/' . $cachePath);
        }
        return null;
    }

    public function render()
    {
        return view('livewire.wisdom-card');
    }
}
