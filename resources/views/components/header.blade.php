<header class="pt-3 bg-blue-400 w-full">
<div class="w-11/12 lg:w-4/6  mx-auto">

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
</header>


