<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use Sentiment\Analyzer;
use Illuminate\Http\Request;

class SentimentController extends Controller
{
    private $analyzer;

    public function __construct()
    {
        $this->analyzer = new Analyzer();
    }

    public function index()
    {
        $sentiments = Sentiment::latest()->get();
        
        $trends = $sentiments->groupBy(function($item) {
            return $item->created_at->format('Y-m-d');
        })->map(function($group) {
            return round($group->avg('compound_score'), 2);
        });

        return view('sentiment.dashboard', compact('sentiments', 'trends'));
    }

    public function analyze(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string|min:3'
            ]);

            $text = $request->input('text');
            $scores = $this->analyzer->getSentiment($text);

            $sentiment = Sentiment::create([
                'text' => $text,
                'pos_score' => $scores['pos'],
                'neg_score' => $scores['neg'],
                'neu_score' => $scores['neu'],
                'compound_score' => $scores['compound']
            ]);

            return response()->json([
                'success' => true,
                'data' => $sentiment
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}