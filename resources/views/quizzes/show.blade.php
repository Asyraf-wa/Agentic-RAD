<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 leading-tight">{{ $quiz->title }}</h2>
            <span class="px-2 py-1 rounded text-xs {{ $quiz->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                {{ ucfirst($quiz->status) }}
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 space-y-6">
            @if (session('success'))
                <div class="bg-green-50 text-green-800 rounded-md p-4 text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900">Questions</h3>
                <p class="mt-2 text-sm text-gray-600">Questions are added in the next chapter.</p>
            </div>
        </div>
    </div>
</x-app-layout>
