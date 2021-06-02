<div class="min-h-screen flex flex-col justify-start items-center pt-6 px-4 xs:pt-0 md:pt-10 bg-gray-100">
    @isset ($logo)
        <div>
            {{ $logo }}
        </div>
    @endisset

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
