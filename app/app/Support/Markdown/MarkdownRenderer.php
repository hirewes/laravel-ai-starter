<?php

namespace App\Support\Markdown;

use Illuminate\Support\Str;

class MarkdownRenderer
{
    public function toHtml(?string $markdown): string
    {
        if (blank($markdown)) {
            return '';
        }

        return (string) Str::markdown($markdown, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
    }
}
