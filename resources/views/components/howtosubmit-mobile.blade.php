 <div class="w-full px-4 sm:px-6 my-16">
    <div class="lg:mb-10 text-center">
        <p class="text-[20px] leading-tight w-full mx-auto xl:text-4xl font-bold">Кожен військовий і громадянин <span class="text-yellow-400">має знати як</span>
            працювати з документами,</p>
        <!-- <p class="text-[20px] xl:text-4xl font-bold">Скаргу, клопотання, заяву, рапорт, позов</p> -->
        <p class="text-sm font-bold text-slate-600">кому, коли і як їх подавати....</p>
    </div>

    <!-- Carousel -->


    <div id="indicators-carousel" class="relative w-full" data-carousel="static">
        <!-- Carousel wrapper -->

        <div class="relative h-[350px] overflow-hidden rounded-lg md:h-[350px] xl:h-96">

            <!-- Item 1 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item="active">
                <div class="flex gap-2 my-16 justify-center items-center">
                    <div class="flex flex-col items-center md:w-2/5 w-3/5 gap-1">
                        <img class="w-[150px] h-[150px]" src="{{ asset('img/skarga.jpg') }}" alt="">
                        <p class="text-xl text-center">Скарга</p>
                    </div>
                </div>
            </div>


            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center items-center">
                    <div class="flex flex-col items-center md:w-2/5 w-3/5 gap-1">
                        <img class="w-[150px] h-[150px]" src="{{ asset('img/klopotanya.jpg') }}" alt="">
                        <p class="text-xl text-center">Клопотання</p>
                    </div>
                </div>
            </div>


            <!-- Item 3 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center">
                   <div class="flex flex-col items-center md:w-2/5 w-3/5 gap-1">
                        <img class="w-[150px] h-[150px]" src="{{ asset('img/raport.jpg') }}" alt="">
                        <p class="text-xl text-center">Рапорт</p>
                    </div>
                </div>
            </div>


            <!-- Item 4 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center">
                    <div class="flex flex-col items-center md:w-2/5 w-3/5 gap-1">
                        <img class="w-[150px] h-[150px]" src="{{ asset('img/zajava.jpg') }}" alt="command img">
                        <p class="text-xl text-center">Заява</p>
                    </div>
                </div>
            </div>

            <!-- Item 5 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center">
                    <div class="flex flex-col items-center md:w-2/5 w-3/5 gap-1">
                        <img class="w-[150px] h-[150px]" src="{{ asset('img/posov.webp') }}" alt="posov img">
                        <p class="text-base text-center">Позов</p>
                    </div>
                </div>
            </div>


        </div>
        <!-- Slider indicators -->
        <div
            class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-[-20px] w-full justify-center">
            <button type="button" class="w-[20%] h-[1px] rounded-full  slide_button" aria-current="true"
                    aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-[20%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 2" data-carousel-slide-to="1"></button>
            <button type="button" class="w-[20%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 3" data-carousel-slide-to="2"></button>
            <button type="button" class="w-[20%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 4" data-carousel-slide-to="3"></button>
            <button type="button" class="w-[20%] h-[1px] rounded-full  slide_button" aria-current="false"
                    aria-label="Slide 5" data-carousel-slide-to="3"></button>
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
