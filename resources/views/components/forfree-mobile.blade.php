<div class="w-full px-4 sm:px-6 lg:w-5/6 xl:w-4/6 mx-auto mt-6 sm:my-20 lg:my-60" id="for_free">
    <div class="w-full lg:w-5/6 xl:w-3/5 lg:mb-10 text-center">
        <p class="text-xl sm:text-2xl lg:text-4xl font-bold leading-tight">Сайти із безкоштовною</p>
        <p class="text-xl sm:text-2xl lg:text-4xl font-bold leading-tight">
            юридичною допомогою <span class="text-yellow-400">(бонус)</span>
        </p>
        <p class="text-xs font-bold sm:text-xl text-slate-600">
            сайти з безкоштовною юридичною допомогою для військовослужбовців та членів їх родин...
        </p>
    </div>
</div>

<!-- Carousel -->


    <div id="indicators-carousel" class="relative w-full" data-carousel="static">
        <!-- Carousel wrapper -->

        <div class="relative h-[300px] overflow-hidden rounded-lg md:h-[450px] xl:h-96">
            <!-- Item 1 -->

            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item="active">
                <div class="flex gap-2 my-16 justify-center items-center">
                <div class="flex flex-col items-center gap-2 min-w-0">
                    <img src="{{ asset('img/legal_100.png') }}" alt="legal_100 png">
                    <p class="text-xl sm:text-2xl font-bold underline text-center">юридична сотня</p>
                    <a class="flex items-center text-slate-600" href="tel:0-800-308-100">
                        <img class="w-5 h-4" src="{{ asset('img/phone.svg') }}" alt="phone svg">
                        <div class="whitespace-nowrap">0-800-308-100</div>
                    </a>
                </div>
                </div>
            </div>


            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center items-center">
                    <div class="flex flex-col items-center gap-2 min-w-0">
                    <img src="{{ asset('img/BPD.png') }}" alt="bpd png">
                    <p class="text-xl sm:text-2xl font-bold underline text-center">БПД</p>
                    <a class="flex items-center text-slate-600" href="tel:0 800 213 103">
                        <img class="w-5 h-4" src="{{ asset('img/phone.svg') }}" alt="phone svg">
                        <div class="whitespace-nowrap">0 800 213 103</div>
                    </a>
                </div>
                </div>
            </div>


            <!-- Item 3 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center">
                   <div class="flex flex-col items-center gap-2 min-w-0">
                        <img src="{{ asset('img/principle.png') }}" alt="principle png">
                        <p class="text-xl sm:text-2xl font-bold underline text-center">принцип</p>
                        <a class="flex items-center text-slate-600" href="tel:0-800-308-100">
                            <img class="w-5 h-4" src="{{ asset('img/phone.svg') }}" alt="phone svg">
                            <div class="whitespace-nowrap">0-800-308-100</div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
        <!-- Slider indicators -->
        <div
            class="absolute z-50 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-[-20px] w-full justify-center">
            <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="true"
                    aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 2" data-carousel-slide-to="1"></button>
            <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 3" data-carousel-slide-to="2"></button>
        </div>
        <!-- Slider controls -->

        <button type="button"
                class="absolute top-0 start-0 z-50 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
            <span class="inline-flex items-center justify-center">
                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180"
                        src="{{ asset('img/button_carousel_left.svg') }}" alt="button_carousel_left">
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button"
                class="absolute top-0 end-0 z-50 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
            <span class="inline-flex items-center justify-center group-focus:outline-none">
                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180"
                        src="{{ asset('img/button_carousel_right.svg') }}" alt="button_carousel_right">
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>
</div>
