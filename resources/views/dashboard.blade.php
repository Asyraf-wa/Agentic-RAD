<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow p-6">
                @if (auth()->user()->role === 'lecturer')
                    <h3 class="text-lg font-medium text-gray-900">Welcome, {{ auth()->user()->name }}</h3>
                    <p class="mt-2 text-sm text-gray-600">You are signed in as a lecturer. Create quizzes, add questions, and publish them for your students.</p>
                @else
                    <h3 class="text-lg font-medium text-gray-900">Welcome, {{ auth()->user()->name }}</h3>
                    <p class="mt-2 text-sm text-gray-600">You are signed in as a student. Published quizzes will appear in your quiz list, ready to take.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
