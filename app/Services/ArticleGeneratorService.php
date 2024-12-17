<?php

namespace App\Services;

use OpenAI\Laravel\Facades\OpenAI;
use App\Helpers\TemplateHelper;
use App\Helpers\TokenCounter;

class ArticleGeneratorService
{
    public static function generateArticle($data)
    {
        $template = TemplateHelper::getTemplate();
        $prompt = str_replace(
            ['[A]', '[B]', '[C]', '[D]', '[E]', '[F]', '[G]', '[H]', '[I]', '[J]', '[K]', '[L]', '[M]', '[N]', '[O]', '[P]', '[Q]', '[R]'],
            [
                $data['title'] ?? '', $data['vorname'] ?? '', $data['nachname'] ?? '', $data['jobtitel'] ?? '', 
                $data['webseite'] ?? '', $data['telefonnummer_firma'] ?? '', $data['anrede'] ?? '', 
                $data['title'] ?? '', $data['vorname'] ?? '', $data['nachname'] ?? '', $data['jobtitel'] ?? '', 
                $data['webseite'] ?? '', $data['firmen_id'] ?? '', $data['strasse'] ?? '', 
                $data['beschreibung_nace_code_ebene_2'] ?? '', $data['wz_code'] ?? '', 
                $data['telefonnummer'] ?? ''
            ],
            $template
        );

        $tokenCount = TokenCounter::countTokens($prompt);

        if ($tokenCount > 4000) {
            throw new \Exception("Token limit exceeded! Current tokens: $tokenCount");
        }

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a professional article generator.'],
                ['role' => 'user', 'content' => $prompt],
            ],
            'temperature' => 0.7,
        ]);


        $articleContent = $response->choices[0]->message->content;
        $structuredContent = self::parseArticleContent($articleContent);
        return $structuredContent;
    }

    private static function parseArticleContent($content)
    {
        $lines = explode("\n", $content);
        $structuredData = [];
        $currentSection = null;

        foreach ($lines as $line) {
            $line = trim($line);

            if ($line === '') {
                continue;
            }

            if (preg_match('/^(H[1-3]):\s(.+)$/', $line, $matches)) {
                $currentSection = [
                    'type' => $matches[1],
                    'title' => $matches[2],
                    'content' => '',
                ];
                $structuredData[] = &$currentSection;
            } elseif (preg_match('/^Meta-Titel:\s(.+)$/', $line, $matches)) {
                $structuredData[] = [
                    'type' => 'meta_title',
                    'title' => 'Meta-Titel',
                    'content' => $matches[1],
                ];
            } elseif (preg_match('/^Meta-Beschreibung:\s(.+)$/', $line, $matches)) {
                $structuredData[] = [
                    'type' => 'meta_description',
                    'title' => 'Meta-Beschreibung',
                    'content' => $matches[1],
                ];
            } elseif ($currentSection) {
                $currentSection['content'] .= ($currentSection['content'] ? ' ' : '') . $line;
            }
        }
        return $structuredData;
    }

}