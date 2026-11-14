<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 leading-tight">My quizzes</h2>
            <a href="{{ route('quizzes.create') }}"
               class="px-4 py-2 rounded-md text-sm font-medium bg-indigo-600 hover:bg-indigo-700 text-white">New quiz</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            @if (session('success'))
                <div class="mb-4 bg-green-50 text-green-800 rounded-md p-4 text-sm">{{ session('success') }}</div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if ($quizzes->isEmpty())
                    <p class="p-6 text-sm text-gray-600">No quizzes yet. Create your first quiz.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-gray-700">
                            <tr>
                                <th class="px-4 py-3 font-medium">Title</th>
                                <th class="px-4 py-3 font-medium">Status</th>
                                <th class="px-4 py-3 font-medium">Questions</th>
                                <th class="px-4 py-3 font-medium">Created</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($quizzes as $quiz)
                                <tr>
                                    <td class="px-4 py-3 text-gray-900">{{ $quiz->title }}</td>
                                    <td class="px-4 py-3">
                                        <span class="px-2 py-1 rounded text-xs {{ $quiz->status === 'published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                            {{ ucfirst($quiz->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600">{{ $quiz->questions_count ?? 0 }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $quiz->created_at->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-right whitespace-nowrap">
                                        <a href="{{ route('quizzes.show', $quiz) }}" class="text-indigo-600 hover:underline">Manage</a>
                                        <a href="{{ route('quizzes.edit', $quiz) }}" class="ml-3 text-indigo-600 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('quizzes.destroy', $quiz) }}" class="inline ml-3"
                                              onsubmit="return confirm('Delete this quiz?')">
                                            @csrf @method('DELETE')
                                            <button class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
