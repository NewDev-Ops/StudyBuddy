<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-sm">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900 animate-fade-in">{{ __('Send Feedback') }}</h2>
                <p class="text-xs text-gray-400 mt-0.5">We'd love to hear from you</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-sm border border-gray-200 p-6 animate-fade-in-up">

                @if(session('feedback_sent'))
                    <div class="bg-gradient-to-r from-emerald-50 to-emerald-100/50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg text-sm mb-6 flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">Thanks, feedback received.</span>
                    </div>
                @endif

                <div class="flex gap-3 p-4 bg-gradient-to-r from-blue-50/50 to-transparent rounded-xl mb-6 border border-blue-100/50">
                    <svg class="w-5 h-5 text-blue-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        Your feedback is <strong>completely anonymous</strong>. We cannot see who submitted it, and we cannot reply directly — but we read every submission.
                    </p>
                </div>

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
                            class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-6 py-2.5 rounded-lg transition-all text-sm shadow-sm hover:shadow-md">
                            Send Feedback
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
