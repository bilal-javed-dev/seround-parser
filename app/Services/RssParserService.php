<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RssParserService
{
    public function parse(string $feedUrl, string $keyword = 'Google', ?string $fromDate = null, ?string $toDate = null): array
    {
        $response = Http::get($feedUrl);

        if (!$response->ok()) {
            throw new \Exception("Couldn't fetch RSS feed from $feedUrl");
        }

        $xml = simplexml_load_string($response->body());
        $items = $xml->channel->item ?? [];

        $count = 0;
        $occurrences = [];

        foreach ($items as $item) {
            $pubDate = new \DateTime((string)$item->pubDate);
            if (
                ($fromDate && $pubDate->format('Y-m-d') < (new \DateTime($fromDate))->format('Y-m-d')) ||
                ($toDate && $pubDate->format('Y-m-d') > (new \DateTime($toDate))->format('Y-m-d'))
            ) {
                continue;
            }

            $title = (string) $item->title;
            $description = (string) $item->description;

            $matchesTitle = substr_count(strtolower($title), strtolower($keyword));
            $matchesDesc = substr_count(strtolower($description), strtolower($keyword));
            $count += $matchesTitle + $matchesDesc;

            if ($matchesTitle || $matchesDesc) {
                $highlightedTitle = $this->highlightWord($title, $keyword);
                $highlightedDesc = $this->highlightWord($description, $keyword);
                $occurrences[] = [
                    'title' => $highlightedTitle,
                    'description' => $highlightedDesc,
                    'pubDate' => $pubDate->format('c')
                ];
            }
        }

        return [
            'count' => $count,
            'occurrences' => $occurrences,
        ];
    }

    private function highlightWord(string $text, string $word): string
    {
        return preg_replace("/(" . preg_quote($word, '/') . ")/i", '<mark>$1</mark>', $text);
    }
}
