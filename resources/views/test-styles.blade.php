<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Family Cloud - CSS Frameworks Test</title>

    <!-- CSS -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="container mx-auto py-8">
        <h1 class="text-4xl font-bold text-center mb-8 text-gray-800">Family Cloud</h1>
        <p class="text-center text-gray-600 mb-12">Testing Tailwind CSS and Bootstrap Integration</p>

        <!-- Tailwind CSS Examples -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-semibold mb-4 text-blue-600">Tailwind CSS Examples</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white p-4 rounded-lg">
                    <h3 class="font-bold text-lg mb-2">Card 1</h3>
                    <p class="text-sm">Beautiful gradient background with Tailwind utilities.</p>
                </div>

                <div class="bg-green-100 border-l-4 border-green-500 p-4 rounded">
                    <h3 class="font-bold text-green-800 mb-2">Card 2</h3>
                    <p class="text-green-700 text-sm">Success-styled card with custom border.</p>
                </div>

                <div class="bg-red-50 border border-red-200 p-4 rounded-lg">
                    <h3 class="font-bold text-red-800 mb-2">Card 3</h3>
                    <p class="text-red-600 text-sm">Alert-style card with subtle styling.</p>
                </div>
            </div>

            <div class="mt-6 flex space-x-4">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                    Tailwind Button
                </button>
                <button class="bg-transparent hover:bg-blue-500 text-blue-700 font-semibold hover:text-white py-2 px-4 border border-blue-500 hover:border-transparent rounded transition duration-300">
                    Outline Button
                </button>
            </div>
        </div>

        <!-- Bootstrap Examples -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-2xl font-semibold mb-4" style="color: #6f42c1;">Bootstrap Examples</h2>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Bootstrap Card 1</h5>
                            <p class="card-text">This card uses Bootstrap classes for styling.</p>
                            <a href="#" class="btn btn-primary">Primary Button</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h5 class="card-title text-success">Bootstrap Card 2</h5>
                            <p class="card-text">Another Bootstrap card with success styling.</p>
                            <a href="#" class="btn btn-success">Success Button</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h5 class="card-title text-warning">Bootstrap Card 3</h5>
                            <p class="card-text">Warning-styled Bootstrap card component.</p>
                            <a href="#" class="btn btn-warning">Warning Button</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="alert alert-info" role="alert">
                    <strong>Info!</strong> This is a Bootstrap alert component.
                </div>

                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-outline-secondary">Left</button>
                    <button type="button" class="btn btn-outline-secondary">Middle</button>
                    <button type="button" class="btn btn-outline-secondary">Right</button>
                </div>
            </div>
        </div>

        <!-- Combined Usage -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-8">
            <h2 class="text-2xl font-semibold mb-4 text-purple-600">Combined Usage</h2>
            <p class="text-gray-600 mb-4">You can use both frameworks together, though it's generally recommended to choose one primary framework.</p>

            <div class="alert alert-success rounded-lg" role="alert">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            This combines Bootstrap's alert with Tailwind's flexbox utilities!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="{{ mix('js/app.js') }}"></script>
</body>
</html>
