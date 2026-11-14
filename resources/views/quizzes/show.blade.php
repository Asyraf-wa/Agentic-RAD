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
            @if (session('error'))
                <div class="bg-red-50 text-red-800 rounded-md p-4 text-sm">{{ session('error') }}</div>
            @endif

            <div class="space-y-4">
                <h3 class="text-lg font-medium text-gray-900">Questions ({{ $quiz->questions->count() }})</h3>

                @forelse ($quiz->questions as $i => $question)
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-start justify-between">
                            <p class="text-gray-900 font-medium">{{ $i + 1 }}. {{ $question->question_text }}</p>
                            <form method="POST" action="{{ route('questions.destroy', $question) }}"
                                  onsubmit="return confirm('Delete this question?')">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-600 hover:underline">Delete</button>
                            </form>
                        </div>
                        <ul class="mt-3 space-y-1">
                            @foreach ($question->options as $option)
                                <li class="text-sm {{ $option->is_correct ? 'text-green-700 font-medium' : 'text-gray-600' }}">
                                    {{ $option->option_text }} @if ($option->is_correct) ✓ correct @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow p-6">
                        <p class="text-sm text-gray-600">No questions yet. Add the first one below.</p>
                    </div>
                @endforelse
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900">Add question</h3>
                <form method="POST" action="{{ route('questions.store', $quiz) }}" class="mt-4 space-y-4">
                    @csrf
                    <div>
                        <label for="question_text" class="block text-sm font-medium text-gray-700">Question</label>
                        <textarea id="question_text" name="question_text" rows="2" required
                                  class="mt-1 w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('question_text') }}</textarea>
                        @error('question_text') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @for ($i = 0; $i < 4; $i++)
                        <div class="flex items-center gap-3">
                            <input type="radio" name="correct" value="{{ $i }}" required
                                   @checked(old('correct') !== null && (int) old('correct') === $i)
                                   class="text-indigo-600 focus:ring-indigo-500">
                            <input type="text" name="options[{{ $i }}]" value="{{ old('options.' . $i) }}" required
                                   placeholder="Option {{ chr(65 + $i) }}"
                                   class="w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        @error('options.' . $i) <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    @endfor
                    @error('correct') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                    <p class="text-xs text-gray-500">Select the radio button next to the correct option.</p>

                    <button class="px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white">Add question</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
