<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">University</h2>
        <p class="mt-1 text-sm text-gray-600">Select your university to help find study partners near you.</p>
    </header>

    <form method="post" action="{{ route('profile.university.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <select name="university_id" id="university_id"
                class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm text-gray-700 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                <option value="">Not set</option>
                @foreach(\App\Models\University::orderBy('name')->get() as $uni)
                    <option value="{{ $uni->id }}" {{ old('university_id', $user->university_id) == $uni->id ? 'selected' : '' }}>
                        {{ $uni->name }}
                    </option>
                @endforeach
            </select>
            @error('university_id')
                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'university-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600 font-medium"
                >{{ __('University updated.') }}</p>
            @endif
        </div>
    </form>
</section>
