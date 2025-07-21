<?php

namespace App\Http\Controllers;

use App\Services\RssParserService;

class RssParserController extends Controller
{
    public function __construct(protected RssParserService $rssParser) {}

    public function index()
    {
        try {
            $data = $this->rssParser->parse('https://www.seroundtable.com/index.xml');
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }

        return view('rss.index', [
            'count' => $data['count'],
            'occurrences' => $data['occurrences'],
        ]);
    }
}
