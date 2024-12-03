<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentiment Analysis System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Hero Section -->
        <div class="lg:flex lg:items-center lg:justify-between py-12">
            <div class="lg:w-1/2">
                <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                    Sentiment Analysis System
                </h1>
                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Analyze text sentiment with advanced machine learning. Get instant insights into the emotional tone of any text.
                </p>
                <div class="mt-10">
                    <a href="{{ route('sentiment.analysis') }}" 
                       class="rounded-md bg-blue-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-500">
                        Try Sentiment Analysis
                    </a>
                </div>
            </div>
            
            <!-- Features Grid -->
            <div class="lg:w-1/2 mt-10 lg:mt-0">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <!-- Sentiment Analysis Feature -->
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-2xl mb-2">😊 😐 😢</div>
                        <h3 class="text-lg font-semibold text-gray-900">Emoji Indicators</h3>
                        <p class="mt-2 text-sm text-gray-600">Visual sentiment representation using emojis for quick understanding.</p>
                    </div>

                    <!-- Trend Analysis Feature -->
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-2xl mb-2">📊</div>
                        <h3 class="text-lg font-semibold text-gray-900">Sentiment Trends</h3>
                        <p class="mt-2 text-sm text-gray-600">Interactive charts showing sentiment changes over time.</p>
                    </div>

                    <!-- Keyword Analysis Feature -->
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-2xl mb-2">🔍</div>
                        <h3 class="text-lg font-semibold text-gray-900">Keyword Highlighting</h3>
                        <p class="mt-2 text-sm text-gray-600">Identifies and highlights influential words in green, red, or gray.</p>
                    </div>

                    <!-- Progress Bars Feature -->
                    <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                        <div class="text-2xl mb-2">📈</div>
                        <h3 class="text-lg font-semibold text-gray-900">Score Analysis</h3>
                        <p class="mt-2 text-sm text-gray-600">Visual progress bars showing positive, negative, and neutral scores.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Features Section -->
        <div class="mt-16 py-12 bg-white rounded-xl shadow-sm">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Current Features</h2>
                <p class="mt-4 text-lg text-gray-600">What our system offers</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-8">
                <div class="text-center">
                    <div class="bg-blue-50 rounded-full p-4 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold">Real-time Analysis</h3>
                    <p class="mt-2 text-gray-600">Instant sentiment scoring with percentage-based results</p>
                </div>

                <div class="text-center">
                    <div class="bg-green-50 rounded-full p-4 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold">Historical Data</h3>
                    <p class="mt-2 text-gray-600">Scrollable results section with timestamps</p>
                </div>

                <div class="text-center">
                    <div class="bg-purple-50 rounded-full p-4 mx-auto w-16 h-16 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold">Overall Statistics</h3>
                    <p class="mt-2 text-gray-600">Comprehensive sentiment metrics dashboard</p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>