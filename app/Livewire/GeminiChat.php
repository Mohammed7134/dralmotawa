<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Livewire\Component;

class GeminiChat extends Component
{
    public string $message      = '';
    public array  $conversation = [];
    public bool   $loading      = false;

    private function buildContents(): array
    {
        // Inject the uploaded wisdoms file as context at the very start
        // of every request — Gemini reads it from their servers, not from us
        $contextTurn = [
            [
                'role'  => 'user',
                'parts' => [
                    [
                        'fileData' => [
                            'mimeType' => 'text/plain',
                            'fileUri'  => config('services.gemini.wisdoms_file_uri'),
                        ],
                    ],
                    ['text' => 'هذه هي قائمة الحكم المتاحة. استخدمها فقط عند الإجابة.'],
                ],
            ],
            [
                'role'  => 'model',
                'parts' => [['text' => 'حسناً، اطلعت على الحكم المتاحة وسألتزم بها فقط.']],
            ],
        ];

        // Append the actual conversation history after the context
        $conversationTurns = collect($this->conversation)->map(fn($msg) => [
            'role'  => $msg['role'] === 'user' ? 'user' : 'model',
            'parts' => [['text' => $msg['text']]],
        ])->toArray();

        return array_merge($contextTurn, $conversationTurns);
    }

    public function sendMessage(): void
    {
        if (empty(trim($this->message))) return;

        $userMessage   = $this->message;
        $this->message = '';
        $this->loading = true;

        $this->conversation[] = ['role' => 'user', 'text' => $userMessage];

        $response = Http::timeout(60)->withHeaders([
            'Content-Type' => 'application/json',
        ])->post(
            'https://generativelanguage.googleapis.com/v1beta/models/'
                . config('services.gemini.model')
                . ':generateContent?key='
                . config('services.gemini.api_key'),
            [
                'system_instruction' => [
                    'parts' => [[
                        'text' => <<<PROMPT
أنت مساعد للحكم العربية. التزم بهذه القواعد:
- اختر حكمة واحدة أو اثنتين فقط الأنسب للسؤال
- اقتبسها حرفياً بين علامتي « »
- لا تعدّل كلمة واحدة في نص الحكمة
- علّق بإيجاز في جملتين فقط بعد الاقتباس
- لا تخترع حكماً غير موجودة في القائمة
PROMPT
                    ]],
                ],
                'contents'          => $this->buildContents(),
                'generationConfig'  => [
                    'temperature'     => 0.7,
                    'maxOutputTokens' => 1000,
                    'thinkingConfig'  => ['thinkingBudget' => 0],
                ],
            ]
        );

        $reply = $response->json('candidates.0.content.parts.0.text')
            ?? 'لم أتمكن من الرد. حاول مرة أخرى.';

        $this->conversation[] = ['role' => 'model', 'text' => $reply];
        $this->loading        = false;
    }

    public function clearChat(): void
    {
        $this->conversation = [];
    }

    public function render()
    {
        return view('livewire.gemini-chat');
    }
}
