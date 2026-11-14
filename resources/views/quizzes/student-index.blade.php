<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900 leading-tight">Available quizzes</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            @if (session('success'))
                <div class="mb-4 bg-green-50 text-green-800 rounded-md p-4 text-sm">{{ session('success') }}</div>
            @endif

            @if ($quizzes->isEmpty())
                <div class="bg-white rounded-lg shadow p-6">
                    <p class="text-sm text-gray-600">No published quizzes yet. Check back later.</p>
                </div>
            @else
                <div class="grid gap-4 md:grid-cols-2">
                    @foreach ($quizzes as $quiz)
                        <div class="bg-white rounded-lg shadow p-6">
                            <h3 class="text-lg font-medium text-gray-900">{{ $quiz->title }}</h3>
                            <p class="mt-1 text-sm text-gray-600">{{ $quiz->questions_count ?? 0 }} questions</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
