
<footer class="w-full bg-blue-400 py-6 xl:py-10">
    <div class="flex flex-col xl:flex-row w-11/12 lg:w-5/6 xl:w-4/6 xl:justify-between items-center xl:items-start mx-auto gap-8 xl:gap-0">
        <!-- Логотип та соцмережі -->
        <div class="flex flex-col sm:flex-row items-center sm:items-start w-full lg:w-1/2 justify-center text-white gap-3 sm:gap-2 sm:mt-4">
            <img class="h-[100px] w-[100px] sm:h-[120px] sm:w-[120px] xl:h-[140px] xl:w-[140px]" src="{{ asset('img/logo.svg') }}" alt="">
            <img class="hidden sm:block self-center" src="{{ asset('img/line_vertical.svg') }}" alt="">
            <div class="flex flex-col gap-1 text-center sm:text-left self-center">
                <p class="text-xl sm:text-2xl lg:text-3xl font-bold">Серафим</p>
                <!-- <p class="text-xs sm:text-sm">від солдата - для людей</p> -->
                <div class="flex gap-2 justify-center sm:justify-start">
                    <a href="https://youtube.com/@serafim_ngu"><img src="{{ asset('img/youtube_footer.svg') }}" alt=""></a>
                    <a href="https://www.facebook.com/profile.php?id=61577691222873"><img src="{{ asset('img/facebook_footer.svg') }}" alt=""></a>
                    <a href="tiktok.com/@serafim_ngu"><img src="{{ asset('img/tiktok_footer.svg') }}" alt=""></a>
                    <a href="https://t.me/serafim_ngu"><img src="{{ asset('img/telegram_footer.svg') }}" alt=""></a>
                    <a href="https://www.instagram.com/serafim_ngu"><img src="{{ asset('img/instagram_footer.svg') }}" alt=""></a>
                </div>
                <a class="text-xs" href="mailto:serafim.nation@gmail.com">співпраця - <span class="underline decoration-white">serafim.nation@gmail.com</span></a>
            </div>
        </div>

        <!-- Навігаційні колонки -->
        <div class="flex flex-col sm:flex-row w-full xl:w-4/6 gap-6 sm:gap-4 xl:gap-8">
            <!-- Сайт -->
            <div class="w-full sm:w-1/3 lg:w-1/3 text-white flex flex-col gap-1">
                <p class="text-yellow-400 text-lg sm:text-xl lg:text-2xl decoration-yellow-400 decoration-4 lg:decoration-8 underline-offset-2 lg:underline-offset-4 mb-2 lg:mb-3"><u>са</u>йт</p>
                <p class="text-sm lg:text-base footer-link cursor-pointer" data-target="wt_knowledge_pack">Що таке комплект знань</p>
                <p class="text-sm lg:text-base footer-link cursor-pointer hidden lg:block" data-target="knowledge_pack">Обрати комплект знань</p>

                <p class="text-sm lg:text-base footer-link cursor-pointer lg:hidden" data-target="knowledge_pack_mobile">Обрати комплект знань</p>

                <p class="text-sm lg:text-base footer-link cursor-pointer">Юрист онлайн</p>
                <p class="text-sm lg:text-base footer-link cursor-pointer" data-target="about_author">Про автора</p>
                <p class="text-sm lg:text-base footer-link cursor-pointer" data-target="social">Соцмережі</p>
                <p class="text-sm lg:text-base footer-link cursor-pointer" data-target="for_free">Безкоштовно</p>
            </div>

            <!-- Комплекти -->
            <div class="w-full sm:w-1/3  xl:w-1/3 text-white flex flex-col gap-1">
                <p class="text-yellow-400 text-lg sm:text-xl lg:text-2xl decoration-yellow-400 decoration-4 lg:decoration-8 underline-offset-2 lg:underline-offset-4 mb-2 lg:mb-3"><u>ко</u>мплекти</p>
                <p class="text-sm lg:text-base">Комплект Людини</p>
                <p class="text-sm lg:text-base">Комплект ЗСУ</p>
                <p class="text-sm lg:text-base">Комплект НГУ</p>
                <p class="text-sm lg:text-base">Комплект поліцейського</p>
                <p class="text-sm lg:text-base">Зібрати власний комплект</p>
            </div>

            <!-- Куди звертатись -->
            <div class="w-full sm:w-1/3 xl:w-1/3 text-white flex flex-col gap-1">
                <p class="text-yellow-400 text-lg sm:text-xl lg:text-2xl decoration-yellow-400 decoration-4 lg:decoration-8 underline-offset-2 lg:underline-offset-4 mb-2 lg:mb-3"><u>ку</u>ди звертатись</p>
                <p class="text-sm lg:text-base">МОУ</p>
                <p class="text-sm lg:text-base">ВСП</p>
                <p class="text-sm lg:text-base">ДБР</p>
                <p class="text-sm lg:text-base">НАБУ</p>
                <p class="text-sm lg:text-base">Юридична сотня</p>
                <p class="text-sm lg:text-base">Генеральна прокуратура</p>
                <p class="text-sm lg:text-base">Омбудсман</p>
                <p class="text-sm lg:text-base">НАЗК</p>
            </div>
        </div>
    </div>

    <!-- Нижня частина -->
    <hr class="w-10/12 sm:w-8/12 xl:w-7/12 mx-auto mt-6 lg:mt-10">
    <div class="text-white my-3 flex flex-col sm:flex-row items-center justify-center gap-2 text-sm lg:text-base">
        <span id="terms" class="flex justify-center modal-trigger cursor-pointer">terms of use</span>
        <span class="hidden sm:inline text-2xl lg:text-4xl">|</span>
        <span id="policy" class="flex justify-center modal-trigger cursor-pointer">privacy policy</span>
    </div>
    <hr class="w-10/12 sm:w-8/12 xl:w-7/12 mx-auto">
</footer>

