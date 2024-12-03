<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sentiment extends Model
{
    protected $fillable = [
        'text',
        'pos_score',
        'neg_score',
        'neu_score',
        'compound_score'
    ];

    protected $casts = [
        'pos_score' => 'float',
        'neg_score' => 'float',
        'neu_score' => 'float',
        'compound_score' => 'float'
    ];
}