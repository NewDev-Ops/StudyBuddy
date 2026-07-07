<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-bold text-gray-900 animate-fade-in">
            {{ __('Send Feedback') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in-up">

                @if(session('feedback_sent'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Thanks, feedback received.
                    </div>
                @endif

                <p class="text-sm text-gray-500 mb-6">
                    Your feedback is completely anonymous. We cannot see who submitted it, and we cannot reply directly — but we read every submission.
                </p>

                <form method="POST" action="{{ route('feedback.store') }}">
                    @csrf

                    <div class="space-y-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Category <span class="text-gray-400 font-normal">(optional)</span></label>
                            <select name="category" id="category"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Select a category</option>
                                <option value="Bug" {{ old('category') === 'Bug' ? 'selected' : '' }}>Bug</option>
                                <option value="Suggestion" {{ old('category') === 'Suggestion' ? 'selected' : '' }}>Suggestion</option>
                                <option value="Other" {{ old('category') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            @error('category')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-500">*</span></label>
                            <textarea name="message" id="message" rows="6" maxlength="5000" required
                                placeholder="Tell us what's on your mind..."
                                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none">{{ old('message') }}</textarea>
                            @error('message')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <a href="{{ route('dashboard') }}"
                            class="border border-gray-300 text-gray-700 font-semibold px-4 py-2.5 rounded-lg transition text-sm hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2.5 rounded-lg transition text-sm">
                            Send Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
