<section>

    <div class="flex flex-wrap">

        <div class="w-full lg:w-1/2">
            @isset($rep->image)
                <x-fj-image :image="$rep->image" class="w-full" />
            @endisset       
        </div>
        <div class="w-full lg:w-1/2">
            {!! $rep->text !!}
        </div>
            
    </div>

</section>

