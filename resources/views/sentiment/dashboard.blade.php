<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analysis</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Analysis Form -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4">Analyze Text</h2>
            <form id="analysisForm" class="space-y-4">
                @csrf
                <textarea 
                    id="textInput"
                    name="text"
                    class="w-full p-4 border rounded-lg"
                    rows="4"
                    placeholder="Enter text to analyze..."
                    required
                ></textarea>
                <button 
                    type="submit"
                    class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600"
                >
                    Analyze
                </button>
            </form>
        </div>

        <!-- Charts and Results -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Sentiment Trends Chart -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Sentiment Trends</h2>
                <canvas id="trendChart"></canvas>
            </div>

            <!-- Latest Results -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Latest Results</h2>
                <div id="results" class="space-y-4 overflow-y-auto max-h-[500px]">
                    @foreach($sentiments as $sentiment)
                        <div class="border-b pb-4">
                            <!-- Original Text with Highlighted Keywords -->
                            <div class="mb-3">
                                <p class="text-sm font-semibold text-gray-600 mb-1">Analyzed Text:</p>
                                <p class="mb-2">{!! $sentiment->highlighted_text !!}</p>
                            </div>

                            <!-- Topic Badge -->
                            <div class="mb-3">
                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                    Topic: {{ str_replace('_', ' ', ucfirst($sentiment->topic ?? 'General')) }}
                                </span>
                            </div>

                            <!-- Influential Keywords Section -->
                            @if($sentiment->keywords && count($sentiment->keywords) > 0)
                                <div class="mb-3 bg-gray-50 p-3 rounded-lg">
                                    <p class="text-sm font-semibold mb-2">Key Words Found:</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($sentiment->keywords as $keyword)
                                            <span class="text-sm px-2 py-1 rounded-full
                                                {{ $keyword['type'] === 'positive' ? 'bg-green-100 text-green-800' : 
                                                   ($keyword['type'] === 'negative' ? 'bg-red-100 text-red-800' : 
                                                    'bg-gray-100 text-gray-800') }}">
                                                {{ $keyword['word'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Sentiment Emoji Indicator -->
                            <div class="mb-3">
                                @if($sentiment->compound_score >= 0.05)
                                    <span class="text-2xl" title="Positive">😊</span>
                                @elseif($sentiment->compound_score <= -0.05)
                                    <span class="text-2xl" title="Negative">😢</span>
                                @else
                                    <span class="text-2xl" title="Neutral">😐</span>
                                @endif
                            </div>

                            <!-- Sentiment Scores with Progress Bars -->
                            <div class="space-y-3">
                                <!-- Positive Score -->
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Positive</span>
                                        <span>{{ number_format($sentiment->pos_score * 100, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" 
                                             style="width: {{ $sentiment->pos_score * 100 }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Negative Score -->
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Negative</span>
                                        <span>{{ number_format($sentiment->neg_score * 100, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-red-600 h-2 rounded-full" 
                                             style="width: {{ $sentiment->neg_score * 100 }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Neutral Score -->
                                <div>
                                    <div class="flex justify-between text-sm mb-1">
                                        <span>Neutral</span>
                                        <span>{{ number_format($sentiment->neu_score * 100, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gray-600 h-2 rounded-full" 
                                             style="width: {{ $sentiment->neu_score * 100 }}%">
                                        </div>
                                    </div>
                                </div>

                                <!-- Overall Score (Compound) -->
                                <div class="mt-3 text-sm">
                                    <strong>Overall Score:</strong> 
                                    <span class="{{ $sentiment->compound_score >= 0.05 ? 'text-green-600' : ($sentiment->compound_score <= -0.05 ? 'text-red-600' : 'text-gray-600') }}">
                                        {{ number_format($sentiment->compound_score, 3) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Analysis Date -->
                            <div class="mt-2 text-xs text-gray-500">
                                Analyzed on: {{ $sentiment->created_at->format('M d, Y h:i A') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Topic Analysis Section
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Topic Analysis</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @foreach($topicStats ?? [] as $topic => $stats)
                    <div class="border rounded-lg p-4">
                        <h3 class="font-semibold mb-2 capitalize">{{ str_replace('_', ' ', $topic) }}</h3>
                        <div class="space-y-2">
                            <p class="text-sm">
                                Total Feedback: <span class="font-semibold">{{ $stats['count'] }}</span>
                            </p>
                            <div class="flex items-center gap-2">
                                <span class="text-green-600">Positive: {{ $stats['positive_count'] }}</span>
                                <span class="text-gray-600">Neutral: {{ $stats['neutral_count'] }}</span>
                                <span class="text-red-600">Negative: {{ $stats['negative_count'] }}</span>
                            </div>
                            <div class="text-sm">
                                Average Sentiment: 
                                <span class="{{ $stats['average_sentiment'] >= 0.05 ? 'text-green-600' : ($stats['average_sentiment'] <= -0.05 ? 'text-red-600' : 'text-gray-600') }}">
                                    {{ number_format($stats['average_sentiment'] * 100, 1) }}%
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        -->

        <!-- Overall Stats -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-bold mb-4">Overall Statistics</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="p-4 bg-green-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-green-800">Average Positive</h3>
                    <p class="text-2xl font-bold text-green-600">
                        {{ number_format($sentiments->avg('pos_score') * 100, 1) }}%
                    </p>
                </div>
                <div class="p-4 bg-red-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-red-800">Average Negative</h3>
                    <p class="text-2xl font-bold text-red-600">
                        {{ number_format($sentiments->avg('neg_score') * 100, 1) }}%
                    </p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-gray-800">Average Neutral</h3>
                    <p class="text-2xl font-bold text-gray-600">
                        {{ number_format($sentiments->avg('neu_score') * 100, 1) }}%
                    </p>
                </div>
                <div class="p-4 bg-blue-50 rounded-lg">
                    <h3 class="text-sm font-semibold text-blue-800">Overall Sentiment</h3>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ $sentiments->avg('compound_score') >= 0.05 ? '😊 Positive' : ($sentiments->avg('compound_score') <= -0.05 ? '😢 Negative' : '😐 Neutral') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Trend Chart
        const trendChart = new Chart(
            document.getElementById('trendChart'),
            {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_keys($trends->toArray())) !!},
                    datasets: [{
                        label: 'Sentiment Score',
                        data: {{ json_encode(array_values($trends->toArray())) }},
                        borderColor: '#2196F3',
                        tension: 0.1,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 1,
                            ticks: {
                                callback: function(value) {
                                    return (value * 100) + '%';
                                }
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Sentiment: ' + (context.raw * 100).toFixed(1) + '%';
                                }
                            }
                        }
                    }
                }
            }
        );

        // Form Submission Handler
        document.getElementById('analysisForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const text = document.getElementById('textInput').value;
            
            try {
                const response = await fetch('/analyze', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ text: text })
                });

                const result = await response.json();
                
                if (result.success) {
                    // Clear the form
                    document.getElementById('textInput').value = '';
                    // Reload the page to show new results
                    window.location.reload();
                } else {
                    alert('Error: ' + (result.message || 'Failed to analyze text'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error analyzing text');
            }
        });
    </script>
</body>
</html>