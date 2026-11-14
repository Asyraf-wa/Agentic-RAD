<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-900 leading-tight">Results — {{ $quiz->title }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            <div class="bg-white rounded-lg shadow overflow-hidden">
                @if ($attempts->isEmpty())
                    <p class="p-6 text-sm text-gray-600">No attempts yet.</p>
                @else
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50 text-left text-gray-700">
                            <tr>
                                <th class="px-4 py-3 font-medium">Student</th>
                                <th class="px-4 py-3 font-medium">Score</th>
                                <th class="px-4 py-3 font-medium">Submitted</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($attempts as $attempt)
                                <tr>
                                    <td class="px-4 py-3 text-gray-900">{{ $attempt->user->name }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $attempt->score }}/{{ $quiz->questions_count }}</td>
                                    <td class="px-4 py-3 text-gray-600">{{ $attempt->submitted_at->format('d M Y, H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
