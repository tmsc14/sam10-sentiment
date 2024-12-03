<?php

namespace App\Http\Controllers;

use App\Models\Sentiment;
use Sentiment\Analyzer;
use Illuminate\Http\Request;

class SentimentController extends Controller
{
    private $analyzer;

    private $sentimentKeywords = [
        'positive' => ['excellent', 'amazing', 'good', 'great', 'awesome', 'fantastic', 'outstanding', 'perfect', 'wonderful', 'happy'],
        'negative' => ['terrible', 'bad', 'poor', 'awful', 'horrible', 'disappointing', 'worst', 'hate', 'frustrated', 'angry', 'sad'],
        'neutral' => ['okay', 'fine', 'average', 'normal', 'standard', 'typical', 'moderate', 'fair', 'regular', 'usual']
    ];

    private $topicKeywords = [
        'customer_service' => [
            'service', 'support', 'staff', 'representative', 'help', 'assistance', 
            'customer', 'agent', 'response', 'communication'
        ],
        'product_quality' => [
            'quality', 'product', 'durability', 'reliable', 'performance', 'works', 
            'broken', 'defective', 'feature', 'functionality'
        ],
        'pricing' => [
            'price', 'cost', 'expensive', 'cheap', 'affordable', 'value', 
            'worth', 'pricing', 'money', 'payment'
        ],
        'delivery' => [
            'delivery', 'shipping', 'arrived', 'package', 'shipment', 'late', 
            'quick', 'fast', 'slow', 'received'
        ],
        'user_experience' => [
            'easy', 'difficult', 'simple', 'complicated', 'intuitive', 'confusing', 
            'user-friendly', 'interface', 'experience', 'using'
        ]
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

        $topicStats = $sentiments->groupBy('topic')
            ->map(function ($group) {
                return [
                    'count' => $group->count(),
                    'average_sentiment' => $group->avg('compound_score'),
                    'positive_count' => $group->where('compound_score', '>=', 0.05)->count(),
                    'negative_count' => $group->where('compound_score', '<=', -0.05)->count(),
                    'neutral_count' => $group->whereBetween('compound_score', [-0.05, 0.05])->count(),
                ];
            });

        return view('sentiment.dashboard', compact('sentiments', 'trends', 'topicStats'));
    }

    public function analyze(Request $request)
    {
        try {
            $request->validate([
                'text' => 'required|string|min:3'
            ]);

            $text = $request->input('text');
            $scores = $this->analyzer->getSentiment($text);

            $keywords = $this->findInfluentialKeywords($text);
            $topic = $this->detectBasicTopic($text);

            $sentiment = Sentiment::create([
                'text' => $text,
                'pos_score' => $scores['pos'],
                'neg_score' => $scores['neg'],
                'neu_score' => $scores['neu'],
                'compound_score' => $scores['compound'],
                'keywords' => $keywords,
                'topic' => $topic
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

    private function detectBasicTopic($text)
    {
        $text = strtolower($text);
        foreach ($this->topicKeywords as $topic => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    return $topic;
                }
            }
        }
        return 'general';
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