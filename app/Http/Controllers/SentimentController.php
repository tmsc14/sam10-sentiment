<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use Sentiment\Analyzer;
use Illuminate\Http\Request;

class SentimentController extends Controller
{
    private $analyzer;

    // Defining sentiment keywords
    private $sentimentKeywords = [
        'positive' => ['excellent', 'amazing', 'good', 'great', 'awesome', 'fantastic', 'outstanding', 'perfect', 'wonderful', 'happy'],
        'negative' => ['terrible', 'bad', 'poor', 'awful', 'horrible', 'disappointing', 'worst', 'hate', 'frustrated', 'angry'],
        'neutral' => ['okay', 'fine', 'average', 'normal', 'standard', 'typical', 'moderate', 'fair', 'regular', 'usual']
    ];

    public function __construct()
    {
        $this->analyzer = new Analyzer();
    }

    public function index()
    {
        $sentiments = Sentiment::latest()->get()->map(function ($sentiment) {
            $sentiment->highlighted_text = $this->highlightKeywords($sentiment->text);
            return $sentiment;
        });
        
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

            // Find influential keywords
            $keywords = $this->findInfluentialKeywords($text);

            $sentiment = Sentiment::create([
                'text' => $text,
                'pos_score' => $scores['pos'],
                'neg_score' => $scores['neg'],
                'neu_score' => $scores['neu'],
                'compound_score' => $scores['compound'],
                'keywords' => $keywords
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

    private function findInfluentialKeywords($text)
    {
        $words = str_word_count(strtolower($text), 1);
        $influential = [];

        foreach ($words as $word) {
            if (in_array($word, $this->sentimentKeywords['positive'])) {
                $influential[] = ['word' => $word, 'type' => 'positive'];
            } elseif (in_array($word, $this->sentimentKeywords['negative'])) {
                $influential[] = ['word' => $word, 'type' => 'negative'];
            } elseif (in_array($word, $this->sentimentKeywords['neutral'])) {
                $influential[] = ['word' => $word, 'type' => 'neutral'];
            }
        }

        return $influential;
    }

    private function highlightKeywords($text)
    {
        $words = explode(' ', $text);
        $highlighted = [];

        foreach ($words as $word) {
            $lowercaseWord = strtolower($word);
            if (in_array($lowercaseWord, $this->sentimentKeywords['positive'])) {
                $highlighted[] = "<span class='bg-green-200 px-1 rounded'>{$word}</span>";
            } elseif (in_array($lowercaseWord, $this->sentimentKeywords['negative'])) {
                $highlighted[] = "<span class='bg-red-200 px-1 rounded'>{$word}</span>";
            } elseif (in_array($lowercaseWord, $this->sentimentKeywords['neutral'])) {
                $highlighted[] = "<span class='bg-gray-200 px-1 rounded'>{$word}</span>";
            } else {
                $highlighted[] = $word;
            }
        }

        return implode(' ', $highlighted);
    }
}