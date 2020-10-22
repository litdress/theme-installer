<header>
    <div class="flex justify-between">
        <x-nav-main />

        <div>
            <x-fj-localize>
                @foreach(config('translatable.locales') as $locale)
                    <x-slot :name="$locale">
                        {{ $locale }}
                    </x-slot>
                @endforeach
            </x-fj-localize>
        </div>
    </div>
</header>