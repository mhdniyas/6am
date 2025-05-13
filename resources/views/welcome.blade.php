@extends('layouts.app')

@section('title', '6AM Morning Cricket Tournament')

@section('content')
<div class="container mx-auto px-4 py-8">
    <header class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-800">6AM Morning Cricket</h1>
        <p class="text-2xl text-gray-600 mt-2">One Day Tournament</p>
    </header>

    <section class="bg-white shadow-lg rounded-lg p-8 mb-10">
        <h2 class="text-3xl font-semibold text-center text-blue-600 mb-6">Official Sponsor</h2>
        <div class="flex justify-center items-center">
            {{-- Placeholder for Sponsor Logo/Name --}}
            <p class="text-xl text-gray-700">To be Announced</p>
        </div>
    </section>

    <section class="text-center mb-12">
        <h2 class="text-3xl font-semibold text-gray-700 mb-4">Tournament Highlights</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-blue-500 mb-2">Exciting Matches</h3>
                <p class="text-gray-600">Witness thrilling cricket action throughout the day.</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-blue-500 mb-2">Community Spirit</h3>
                <p class="text-gray-600">Join us for a day of fun, sportsmanship, and community.</p>
            </div>
            <div class="bg-gray-50 p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold text-blue-500 mb-2">Prizes & Recognition</h3>
                <p class="text-gray-600">Celebrating talent and passion for cricket.</p>
            </div>
        </div>
    </section>

    <section x-data="{ showDetails: false }" class="bg-white shadow-md rounded-lg p-8 mb-10">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-700">Team Expense Reports</h2>
            <button @click="showDetails = !showDetails" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                <span x-text="showDetails ? 'Hide Details' : 'Show Details'"></span>
            </button>
        </div>
        <div x-show="showDetails" x-transition class="mt-4">
            <p class="text-gray-600 mb-4">
                Detailed expense reports for teams will be available here.
                This section will provide an option to download PDF reports.
            </p>
            {{-- Placeholder for PDF export button/link --}}
            <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded opacity-50 cursor-not-allowed" disabled>
                Download Expense PDF (Coming Soon)
            </button>
        </div>
    </section>

    <footer class="text-center mt-12 py-6 border-t border-gray-300">
        <p class="text-gray-600">&copy; {{ date('Y') }} 6AM Morning Cricket. All rights reserved.</p>
    </footer>

</div>
@endsection

@push('scripts')
{{-- Alpine.js is already included via resources/js/app.js if you've set it up there --}}
{{-- If not, you might need to add: <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script> --}}
@endpush