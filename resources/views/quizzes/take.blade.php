<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900 leading-tight">{{ $quiz->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            @if ($errors->any())
                <div class="mb-4 bg-red-50 text-red-800 rounded-md p-4 text-sm">Please answer every question before submitting.</div>
            @endif

            <form method="POST" action="{{ route('attempts.store', $quiz) }}" class="space-y-4">
                @csrf
                @foreach ($quiz->questions as $i => $question)
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="font-medium text-gray-900">{{ $i + 1 }}. {{ $question->question_text }}</p>
                        <div class="mt-3 space-y-2">
                            @foreach ($question->options as $option)
                                <label class="flex items-center gap-3 text-sm text-gray-700">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}"
                                           @checked((int) old('answers.' . $question->id) === $option->id)
                                           class="text-indigo-600 focus:ring-indigo-500">
                                    {{ $option->option_text }}
                                </label>
                            @endforeach
                        </div>
                        @error('answers.' . $question->id) <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                @endforeach

                <button class="px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white">Submit answers</button>
            </form>
        </div>
    </div>
</x-app-layout>
