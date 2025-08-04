<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\RssParserService;

class RssParserController extends Controller
{
    public function __construct(protected RssParserService $rssParser) {}

    public function index(Request $request)
    {
        $keyword = $request->get('keyword', 'Google');
        $dateFrom = $request->get('dateFrom');
        $dateTo = $request->get('dateTo');

        try {
            $data = $this->rssParser->parse('https://www.seroundtable.com/index.xml', $keyword, $dateFrom, $dateTo);
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }

        return view('rss.index', [
            'count' => $data['count'],
            'occurrences' => $data['occurrences'],
            'keyword' => $keyword
        ]);
    }
}
