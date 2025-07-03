<div class="relative w-[76%] mx-auto">
    <div class="bg-blue-500 py-2 px-4 text-xl text-white absolute top-[-20px] left-0">
        ВЧИМОСЯ ВЗАЄМОДІЯТИ З ОРГАНАМИ ВЛАДИ
    </div>

      <div id="indicators-carousel" class="relative pt-10 w-full" data-carousel="static">
        <!-- Carousel wrapper -->

        <div class="relative h-56 overflow-hidden rounded-lg md:h-[200px] my-8">
            <!-- Item 1 -->

            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-10  justify-center">
                    <div class="flex flex-col items-center">
                <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/general_procoratura.png') }}" alt="procoratura img">
                <p class="text-blue-400 text-center">Генеральна прокаратура</p>
             </div>

         <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/nabu.svg') }}" alt="">
             <p class="text-blue-400 text-center">НАБУ</p>
        </div>

        <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/national_police.png') }}" alt="">
             <p class="text-blue-400 text-center">Національна поліція</p>
        </div>

        <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/NAZK_logo.png') }}" alt="">
             <p class="text-blue-400 text-center">НАЗК</p>
        </div>
                </div>
            </div>
            <!-- Item 2 -->

            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-10  justify-center">
                    <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/MVS.png') }}" alt="">
             <p class="text-blue-400 text-center">МВС</p>
        </div>

        <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/SBU.png') }}" alt="">
             <p class="text-blue-400 text-center">СБУ</p>
        </div>

        <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/DBR_logo.png') }}" alt="">
             <p class="text-blue-400 text-center">ДБР</p>
        </div>

        <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/commissioner.png') }}" alt="">
             <p class="text-blue-400 text-center">Омбудсмен</p>
        </div>
                </div>
            </div>
            <!-- Item 3 -->

            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-10  justify-center">
                    <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/ministry.png') }}" alt="">
             <p class="text-blue-400 text-center">Міністерство оборони</p>
        </div>

        <div class="flex flex-col items-center">
            <img class="w-[150px] h-[150px] border border-blue-400 rounded-full" src="{{ asset('img/SUDY.png') }}" alt="">
            <p class="text-blue-400 text-center">СУДИ</p>
        </div>
                </div>
            </div>
        </div>
        <!-- Slider indicators -->
        <div
            class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-[-20px] w-full justify-center">
            <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="true"
                    aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 2" data-carousel-slide-to="1"></button>
            <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 3" data-carousel-slide-to="2"></button>
        </div>
        <!-- Slider controls -->

        <button type="button"
                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-prev>
        <span class="inline-flex items-center justify-center">
            <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180"
                    src="{{ asset('img/button_carousel_left.svg') }}" alt="button_carousel_left">
            <span class="sr-only">Previous</span>
        </span>
        </button>
        <button type="button"
                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                data-carousel-next>
        <span class="inline-flex items-center justify-center group-focus:outline-none">
            <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180"
                    src="{{ asset('img/button_carousel_right.svg') }}" alt="button_carousel_right">
            <span class="sr-only">Next</span>
        </span>
        </button>
    </div>





</div>
