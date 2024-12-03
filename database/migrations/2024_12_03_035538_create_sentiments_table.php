<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sentiments', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->float('pos_score');
            $table->float('neg_score');
            $table->float('neu_score');
            $table->float('compound_score');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sentiments');
    }
};