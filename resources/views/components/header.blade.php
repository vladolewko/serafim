<header class="lg:pt-3 bg-blue-400 w-full sticky lg:static top-0 z-50">

    <!-- Desktop -->
    <div class="w-11/12 lg:w-9/12 hidden lg:block mx-auto ">
        <!-- Navigation -->
        @include('components.navigation')
        <div class="text-white flex justify-center items-center gap-3 flex-wrap sm:flex-nowrap">
            <div class="text-center">
                <p class="text-base font-bold">
                    Авторські курси правового виховання
                </p>
                <p class="text-2xl font-bold text-yellow-400">
                    СЕРАФИМ
                </p>
                <!-- <p class="text-sm">
                    Від солдата - для людей
                </p> -->
            </div>

            <img src="{{ asset('img/line_vertical.svg') }}" alt="">

            <img class="bg-white rounded-full sm:h-[81px] sm:w-[81px] h-[75px] w-[75px]" src="{{ asset('img/logo_1.webp') }}" alt="logo">

            <img class="h-0 w-0 sm:h-[81px] w-[1px]" src="{{ asset('img/line_vertical.svg') }}" alt="">

            <div class="sm:w-2/6 lg:w-6/12 xl:w-[45%] 2xl:w-5/12 flex items-start flex-col sm:items-start">
                <p class="text-left text-base font-bold flex-1">
                    Стаття 68 Конституції України:
                </p>
                <p class="text-[14px]/[1] 2xl:text-[16px]/[1]">
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
        <div class="max-w-screen-xl gap-1 flex flex-wrap items-center justify-between mx-auto p-2" >
            <button class="inline-flex items-center p-2  md:mt-0 text-sm text-white rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2  dark:hover:bg-gray-700" type="button" data-drawer-target="drawer-navigation" data-drawer-show="drawer-navigation" aria-controls="drawer-navigation">
            <span class="sr-only">Open sidebar</span>
               <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>


            <div id="drawer-navigation" class="fixed top-0 left-0 z-40 w-64 h-screen p-4 overflow-y-auto transition-transform -translate-x-full bg-blue-400" tabindex="-1" aria-labelledby="drawer-navigation-label">
                <!-- <h5 id="drawer-navigation-label" class="text-base font-semibold uppercase text-white">Меню</h5> -->
                <button type="button" data-drawer-hide="drawer-navigation" aria-controls="drawer-navigation" class="text-white bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 absolute top-2.5 end-2.5 inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" >
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close menu</span>
                </button>
              <div class="py-4 overflow-y-auto">
                  <ul class="space-y-2 font-medium">
                      <li class="">
                        <a class="block py-2 px-3 text-gray-900 rounded-sm lg:border-0 lg:p-0 dark:text-white nav-item" data-target="introduction">Про школу</a>
                    </li>
                    <li class="">
                        <a class="block py-2 px-3 text-gray-900 rounded-sm lg:border-0 lg:p-0 dark:text-white nav-item" data-target="how_to_submit">Державні органи</a>
                    </li>
                    <li class="">
                        <a class="block py-2 px-3 text-gray-900 rounded-sm lg:border-0 lg:p-0 dark:text-white nav-item" data-target="knowledge_pack">Комплекти знань</a>
                    </li>
                    <li class="">
                        <a class="block py-2 px-3 text-gray-900 rounded-sm lg:border-0 lg:p-0 dark:text-white nav-item" data-target="wt_knowledge_pack">Відеоуроки</a>
                    </li>
                    <li class="">
                        <a class="block py-2 px-3 text-gray-900 rounded-sm lg:border-0 lg:p-0 dark:text-white nav-item" data-target="about_author">Про автора</a>
                    </li>
                    <li class="">
                        <a class="block py-2 px-3 text-gray-900 rounded-sm lg:border-0 lg:p-0 dark:text-white nav-item" data-target="for_free">Безоплатна допомога</a>
                    </li>
                    <li class="">
                        <a class="block py-2 px-3 text-gray-900 rounded-sm lg:border-0 lg:p-0 dark:text-white nav-item" data-target="social">Контакти</a>
                    </li>
                  </ul>
               </div>
            </div>


            <!-- Social media -->

            <div class="flex no-wrap gap-2 justify-center sm:flex hidden">
                <a href="https://youtube.com/@serafim_ngu"><img class="w-[30px] h-[30px]" src="{{ asset('img/youtube_footer.svg') }}" alt=""></a>
                <a href="https://www.facebook.com/profile.php?id=61577691222873"><img class="w-[30px] h-[30px]" src="{{ asset('img/facebook_footer.svg') }}" alt=""></a>
                <a href="tiktok.com/@serafim_ngu"><img class="w-[30px] h-[30px]" src="{{ asset('img/tiktok_footer.svg') }}" alt=""></a>
                <a href="https://t.me/serafim_ngu"><img class="w-[30px] h-[30px]" src="{{ asset('img/telegram_footer.svg') }}" alt=""></a>
                <a href="https://www.instagram.com/serafim_ngu"><img class="w-[30px] h-[30px]" src="{{ asset('img/instagram_footer.svg') }}" alt=""></a>
            </div>


            <div class="text-right text-white">
                    <p class="text-[12px] font-extrabold">
                        Авторські курси правового виховання
                    </p>

                    <p class="text-2xl text-center font-bold text-yellow-400">
                        СЕРАФИМ
                    </p>
            </div>


            <img class="rounded-full h-[50px] w-[50px] md:h-[75px] md:w-[75px] object-cover m-0 p-0" style="background-color: white" src="{{ asset('img/logo_1.webp') }}" alt="logo">


            <div class="hidden w-full lg:block lg:w-auto" id="navbar-default">
            <ul class="bg-gray-800 font-medium flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg lg:flex-row lg:space-x-8 rtl:space-x-reverse lg:mt-0 lg:border-0d lg:dark:bg-blue-400 dark:border-gray-700">
                <li class="m-0">
                <a class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="wt_knowledge_pack">що таке комплект знань</a>
                </li>
                <li>
                <a class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="knowledge_pack">обрати комплект</a>
                </li>
                <li>
                <a class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="about_author">про автора</a>
                </li>
                <li>
                <a class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="social">соцмережі</a>
                </li>
                <li>
                <a class="block py-2 px-3 text-gray-900 rounded-sm hover:bg-gray-100 lg:hover:bg-transparent lg:border-0 md:hover:text-blue-700 lg:p-0 dark:text-white lg:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent nav-item" data-target="for_free">безкоштовно</a>
                </li>
            </ul>
            </div>
        </div>
        </nav>
    </div>
</header>


