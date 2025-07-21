<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\RssParserService;
use Illuminate\Support\Facades\Http;

class RssParserServiceTest extends TestCase
{
    public function test_it_parses_rss_and_counts_keyword_occurrences()
    {
        // Mocking RSS feed
        $mockRss = <<<XML
        <rss>
            <channel>
                <item>
                    <title>Google launches new feature</title>
                    <description>This new Google update changes everything.</description>
                </item>
                <item>
                    <title>Other news</title>
                    <description>No mention of the G-word here.</description>
                </item>
                <item>
                    <title>Google again in the news</title>
                    <description>Google, Google, and more Google.</description>
                </item>
            </channel>
        </rss>
        XML;

        // Faking the http response 
        Http::fake([
            'https://www.seroundtable.com/index.xml' => Http::response($mockRss, 200)
        ]);

        $service = new RssParserService();
        $result = $service->parse('https://www.seroundtable.com/index.xml', 'Google');

        $this->assertEquals(6, $result['count']); // Google appears 6 times in this 
        $this->assertCount(2, $result['occurrences']); // two posts would contain the keyword

        // checking for highlight
        foreach ($result['occurrences'] as $entry) {
            $this->assertStringContainsString('<mark>Google</mark>', $entry['title'] . $entry['description']);
        }
    }
}
