<header class="lg:pt-3 bg-blue-400 w-full">
    <!-- Desktop -->
    <div class="w-11/12 lg:w-4/6 hidden lg:block mx-auto ">
        <!-- Navigation -->
        @include('components.navigation')
        <div class="text-white flex justify-center items-center gap-3 flex-wrap sm:flex-nowrap">
            <div class="text-center">
                <p class="text-sm">
                    авторська онлайн-школа
                </p>
                <p class="text-2xl">
                    “серафим”
                </p>
                <p class="text-sm">
                    від солдата - для людей
                </p>
            </div>

            <img src="{{ asset('img/line_vertical.svg') }}" alt="">

            <img class="sm:h-[81px] sm:w-[81px] h-[75px] w-[75px]" src="{{ asset('img/logo.png') }}" alt="logo">

            <img class="h-0 w-0 sm:h-[81px] w-[1px]" src="{{ asset('img/line_vertical.svg') }}" alt="">

            <div class="sm:w-2/6 flex items-start flex-col sm:items-start">
                <p class="text-left text-base font-bold flex-1">
                    Стаття 68 Конституції України:
                </p>
                <p class="text-sm/[1]">
                    Кожен зобов'язаний неухильно додержуватися Конституції
                    України та законів України, не посягати на права і свободи, честь і гідність інших людей.
                </p>
            </div>
        </div>

        <hr class="mt-3 bg-white border-0 h-px mx-auto w-full">
    </div>

    <!-- Mobile -->
    <div class="lg:hidden mx-auto">

        <nav class="bg-blue-400">
        <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2" >

            <button data-collapse-toggle="navbar-default" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-white rounded-lg lg:hidden focus:outline-none dark:text-white dark:hover:bg-gray-700 " aria-controls="navbar-default" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>

            <!-- Social media -->

            <div class="flex no-wrap gap-2 justify-center sm:flex hidden">
                <a href="https://youtube.com/@serafim_ngu"><img class="w-[30px] h-[30px]" src="{{ asset('img/youtube_footer.svg') }}" alt=""></a>
                <a href=""><img class="w-[30px] h-[30px]" src="{{ asset('img/facebook_footer.svg') }}" alt=""></a>
                <a href="tiktok.com/@serafim_ngu"><img class="w-[30px] h-[30px]" src="{{ asset('img/tiktok_footer.svg') }}" alt=""></a>
                <a href=""><img class="w-[30px] h-[30px]" src="{{ asset('img/telegram_footer.svg') }}" alt=""></a>
                <a href="https://www.instagram.com/serafim_ngu"><img class="w-[30px] h-[30px]" src="{{ asset('img/instagram_footer.svg') }}" alt=""></a>
            </div>


            <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
                <div class="text-right text-white">
                <p class="text-xs">
                    авторська онлайн-школа
                </p>
                <p class="text-xl">
                    “серафим”
                </p>
                <p class="text-xs">
                    від солдата - для людей
                </p>
            </div>
                <img class="h-[65px] w-[65px]" src="{{ asset('img/logo.png') }}" alt="logo">
            </a>
            <div class="hidden w-full lg:block lg:w-auto" id="navbar-default">
            <ul class="bg-gray-800 font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg lg:flex-row lg:space-x-8 rtl:space-x-reverse lg:mt-0 lg:border-0d lg:dark:bg-blue-400 dark:border-gray-700">
                <li class="m-0">
                <a href="#" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item">що таке комплект знань</a>
                </li>
                <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item">обрати комплект</a>
                </li>
                <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="about_author">про автора</a>
                </li>
                <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="social">соцмережі</a>
                </li>
                <li>
                <a href="#" class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="for_free">безкоштовно</a>
                </li>
            </ul>
            </div>
        </div>
        </nav>

    </div>
</header>


