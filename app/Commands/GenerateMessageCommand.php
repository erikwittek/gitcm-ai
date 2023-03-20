<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use OpenAI\Client;

class GenerateMessageCommand extends Command
{
    protected $signature = 'generate-message';

    protected $description = 'Generates a commit message based on your staged changes.';

    public function handle(Client $open_ai): void
    {
        $locale = config('app.locale');

        $diff = $this->getDiff();

        $prompt = "Write an insightful but concise Git commit message in a complete sentence in present tense for the following diff without prefacing it with anything, the response must be in the language $locale:\n$diff";

        $response = $open_ai->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        echo trim($response->choices[0]->message->content)."\n";
    }

    public function getDiff(): ?string
    {
        $excludeFromDiff = implode(
            ' ',
            array_map(fn ($file) => "':(exclude)$file'", [
                'package-lock.json',
                'pnpm-lock.yaml',

                // yarn.lock, Cargo.lock, Gemfile.lock, Pipfile.lock, etc.
                '*.lock',
            ])
        );

        $files = [];
        exec("git diff --cached --name-only $excludeFromDiff", $files);

        if (empty($files)) {
            return null;
        }

        $diff = [];
        exec("git diff --cached $excludeFromDiff", $diff);

        return implode("\n", $diff);
    }
}
