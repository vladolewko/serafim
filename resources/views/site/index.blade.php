@extends('layouts.site')


@section('content')

<!-- @if ($products->isNotEmpty())
    @foreach ($products as $product)
        <a href="{{ route('product.show', $product->id) }}">{{ $product->name }}</a>
        <br>
    @endforeach
@endif -->

<div class="bg-blue-400 text-white w-full">
    <div class="w-4/6 mx-auto">
        <div class="flex items-center">
            <div class="w-3/6">
                <p class="text-5xl">Захищаєш Україну? Навчися <span class="text-yellow-400">захищати себе!</span></p>
                <p class="text-2xl text-slate-200 my-3">Комплекти перевірених юридичних знань для військових, НГУ, поліцейських і громадян.</p>
                <div class="flex">
                    <button class="bg-yellow-400 px-4 py-2 rounded-lg text-black text-xl">комплект знань</button>
                    <p class="text-black text-xl underline underline-offset-4 decoration-yellow-400 self-center mx-4">короткий ролик</p>
                </div>
            </div>
            <div class="w-3/6 ">
                <div class="justify-self-end">
                    <span class="text-2xl text-yellow-400 mt-12 inline-block">Тематика школи</span>
                <ul class="list-disc ml-8 text-sm/5 w-11/12">
                    <li>Взаємодія з державними органами влади та засобами масової інформації (ЗМІ)</li>
                    <li>Правовий захист військових і цивільних (СЗЧ, пільги, рапорти, переведення)</li>
                    <li>Психологічна підтримка мобілізованих, родин, повернення з війни</li>
                    <li>Освітні інструменти для родин зниклих безвісти, військовополонених</li>
                    <li>Антикорупційна боротьба, права викривачів, звернення до НАЗК/НАБУ</li>
                    <li>Соціальні курси (дискримінація, сексуальні домагання в армії)</li>
                    <li>Практичні гайди: шаблони, закони, інструкції, чек-листи</li>
                    <li>Цільова аудиторія: військові, їхні родини, мобілізовані, громадяни</li>
                </ul>
                <span class="text-2xl text-yellow-400 mt-6 inline-block">Мій підхід</span>
                <ul class="list-disc ml-8 mb-12 text-sm/5 w-11/12">
                    <li>Практичність: реальні інструкці, шаблони, приклади звернень</li>
                    <li>Соціальна місія: підтримка, допомога й відновлення справедливості</li>
                </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="flex my-52 w-4/6 mx-auto justify-between">
    <div class="w-5/12">
        <div class="mb-8">
        <p class="text-[2.5rem]">Чому це <span class="text-yellow-400">важливо?</span></p>
        <p class="text-slate-600">Ніхто не подбає про твої права краще за тебе самого...</p>
        </div>

        <div class="border border-blue-400 rounded-lg">
            <div class="flex items-center m-5 gap-3 mb-10">
                <img src="{{ asset('img/law_knowledge.svg') }}" alt="law_knowledge img">
                <p class="text-slate-600 font-bold">ТОП-7 Переваг знання законів та прав людини</p>
            </div>

            <ul class="list-disc w-11/12 ml-10 pr-2">
                <li class="text-slate-600 text-base/5">Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:</li>
            </ul>

            <div class="w-4/5 mx-auto my-10">
                <div class="flex gap-3 mb-3 item-center">
                    <img src="{{ asset('img/check.svg') }}" alt="check img">
                    <span class="text-slate-600">Правовий захист у будь-якій ситуації</sapn>
                </div>
                <div class="flex gap-3 mb-3">
                    <img src="{{ asset('img/check.svg') }}" alt="check img">
                    <p class="text-slate-600">Гарантована свобода та недоторканність</p>
                </div>
                <div class="flex gap-3 mb-3">
                    <img src="{{ asset('img/check.svg') }}" alt="check img">
                    <p class="text-slate-600">Можливість законного самозахисту</p>
                </div>
                <div class="flex gap-3 mb-3">
                    <img src="{{ asset('img/check.svg') }}" alt="check img">
                    <p class="text-slate-600">Контроль над державою, а не навпаки</p>
                </div>
                <div class="flex gap-3 mb-3">
                    <img src="{{ asset('img/check.svg') }}" alt="check img">
                    <p class="text-slate-600">Неможливість маніпуляцій</p>
                </div>
                <div class="flex gap-3 mb-3">
                    <img src="{{ asset('img/check.svg') }}" alt="check img">
                    <p class="text-slate-600">Готовність до міжнародного захисту</p>
                </div>
                <div class="flex gap-3 mb-3">
                    <img src="{{ asset('img/check.svg') }}" alt="check img">
                    <p class="text-slate-600">Повага і впевненість</p>
                </div>

            </div>
        </div>
        <div class="border border-blue-400 rounded-lg mt-10">
            <div class="flex mx-auto my-5 gap-5 justify-center">
                <img src="{{ asset('img/result.svg') }}" alt="result img">
                <div class="text-slate-600 w-4/5 text-base/5"><span class="font-bold">Результат: </span>Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.</div>
            </div>
        </div>
    </div>
    <div class="w-6/12">
        <div class="flex gap-2 justify-end mt-9">
            <div class="flex items-center gap-3 bg-yellow-400 rounded-lg px-2 py-2 checkbox-div">
                <input checked id="checkbox-1" class="w-4 h-4 cursor-pointer rounded-md text-blue-400 bg-white" type="checkbox">
                <label class="cursor-pointer text-black" for="checkbox-1">Громадянину</label>
            </div>

            <div class="flex items-center gap-3 bg-blue-400 rounded-lg px-2 py-2 checkbox-div">
                <input id="checkbox-2" class="w-4 h-4 cursor-pointer rounded-md text-blue-400 bg-white" type="checkbox">
                <label class="cursor-pointer text-white label" for="checkbox-2">Військовослужбовцю</label>
            </div>

            <div class="flex items-center gap-3 bg-blue-400 rounded-lg px-2 py-2 checkbox-div">
                <input id="checkbox-3" class="w-4 h-4 cursor-pointer rounded-md text-blue-400 bg-white" type="checkbox">
                <label class="cursor-pointer text-white label" for="checkbox-3">Поліцейському</label>
            </div>

            <div class="flex items-center gap-3 bg-blue-400 rounded-lg px-2 py-2 checkbox-div">
                <input id="checkbox-4" class="w-4 h-4 cursor-pointer rounded-md text-blue-400 bg-white" type="checkbox">
                <label class="cursor-pointer text-white label" for="checkbox-4">Юристу</label>
            </div>

        </div>
        <!-- posible image -->
        <div class="bg-gray-200 w-full h-[484px] rounded-lg mt-10"></div>
        <div class="flex justify-end mt-10 items-center gap-5 py-5">
            <div class="text-black text-4xl"><span class="text-yellow-400">600 </span>грн</div>
            <button class="bg-yellow-400 px-4 py-2 rounded-lg text-black text-xl">замовити</button>
        </div>
    </div>
    </div>

</div>

<div class="flex w-4/6 mx-auto justify-between my-72 section" id="wt_knowledge_pack">
    <div class="w-5/12">
        <div class="mb-6">
            <p class="text-4xl font-bold">Комплект знань — юридичний <span class="text-yellow-400">набір виживання</span></p>
            <p class="text-slate-600">Комплект, що <span class="text-red-600">100%</span> повинен бути у кожного під рукою...</p>
        </div>
        <div>
            <p class="text-2xl font-bold">Це набір матеріалів, які допоможуть:</p>
            <ul class="list-disc text-xl text-slate-600 ml-8 mt-3">
                <li>Знати свої права і обов’язки;</li>
                <li>Реагувати на порушення;</li>
                <li>Подати скаргу, заяву або звернення;</li>
                <li>Отримати захист — без адвоката і без паніки.</li>
            </ul>
        </div>
    </div>
    <div class="w-5/12 flex flex-col justify-center gap-10">
        <div class="flex justify-center">
            <div class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-[96px] w-[96px] gap-1">
                <img class="w-42 h-42" src="{{ asset('img/constitution.svg') }}" alt="constitution img">
                <p class="text-center text-xs text-slate-600">Конституція</p>
            </div>

                <img class="ml-[-6px] h-[20px] self-center bg-white z-10" src="{{ asset('img/arrow_right.svg') }}" alt="arrow_right img">

            <div class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-[96px] w-[96px] gap-1">
                <img class="w-[42px] h-[42px]" src="{{ asset('img/statutes.svg') }}" alt="statutes img">
                <p class="text-center text-xs text-slate-600">Статути</p>
            </div>

                <img class="ml-[-6px] h-[20px] self-center bg-white z-10" src="{{ asset('img/arrow_right.svg') }}" alt="arrow_right img">

            <div class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-[96px] w-[96px] gap-1">
                <img class="w-[42px] h-[42px]" src="{{ asset('img/codes.svg') }}" alt="codes img">
                <p class="text-center text-xs text-slate-600">Кодекси</p>
            </div>
        </div>

        <div class="flex justify-start">
            <div class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-[96px] w-[96px] gap-1">
                <img class="w-42 h-42" src="{{ asset('img/constitution.svg') }}" alt="constitution img">
                <p class="text-center text-xs text-slate-600">Конституція</p>
            </div>

            <img class="mr-[-6px] h-[20px] self-center bg-white z-10" src="{{ asset('img/arrow_left.svg') }}" alt="arrow_left img">

            <div class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-[96px] w-[96px] gap-1">
                <img class="w-[42px] h-[42px]" src="{{ asset('img/statutes.svg') }}" alt="statutes img">
                <p class="text-center text-xs text-slate-600">Статути</p>
            </div>

            <img class="mr-[-6px] h-[20px] self-center bg-white z-10" src="{{ asset('img/arrow_left.svg') }}" alt="arrow_left img">

            <div class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-[96px] w-[96px] gap-1">
                <img class="w-[42px] h-[42px]" src="{{ asset('img/codes.svg') }}" alt="codes img">
                <p class="text-center text-xs text-slate-600">Кодекси</p>
            </div>
        </div>
    </div>
</div>

<div class="w-4/6 mx-auto my-72">
    <div class="mb-10">
        <p class="text-4xl font-bold">Кожен українець <span class="text-yellow-400">повинен розуміти</span>, як подавати</p>
        <p class="text-4xl font-bold">Скаргу, клопотання, заяву, рапорт, позов</p>
        <p class="text-xl text-slate-600">І комплект знань закриє це питання...</p>
    </div>

    <!-- Carousel -->


    <div id="indicators-carousel" class="relative w-full" data-carousel="static" >
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
            <!-- Item 1 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center">
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/command.png') }}" alt="command img">
                        <p class="underline text-center">командування</p>
                        <p class="text-center">Першим кроком рекомендується звернутися зі скаргою до свого безпосереднього командира або начальника.</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/ministry.png') }}" alt="ministry img">
                        <p class="underline text-center">Міністерство оборони України</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> 1512 </p>
                        <p class="text-center">Міноборони забезпечує цілодобову «гарячу лінію»</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/commissioner.png') }}" alt="commissioner img">
                        <p class="underline text-center">Уповноважений Верховної Ради України з прав людини</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> 0 800 50 17 </p>
                        <p class="text-center">У випадках порушення прав людини військовослужбовці можуть звертатися до Омбудсмана</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/VSP.png') }}" alt="vsp img">
                        <p class="underline text-center">Військова служба правопорядку (ВСП)</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> (044) 454-73-08 </p>
                        <p class="text-center">У випадках порушень дисципліни або прав військовослужбовців можна звертатися до ВСП</p>
                    </div>
                </div>
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center">
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/command.png') }}" alt="command img">
                        <p class="underline text-center">командування</p>
                        <p class="text-center">Першим кроком рекомендується звернутися зі скаргою до свого безпосереднього командира або начальника.</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/ministry.png') }}" alt="ministry img">
                        <p class="underline text-center">Міністерство оборони України</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> 1512 </p>
                        <p class="text-center">Міноборони забезпечує цілодобову «гарячу лінію»</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/commissioner.png') }}" alt="commissioner img">
                        <p class="underline text-center">Уповноважений Верховної Ради України з прав людини</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> 0 800 50 17 </p>
                        <p class="text-center">У випадках порушення прав людини військовослужбовці можуть звертатися до Омбудсмана</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/VSP.png') }}" alt="vsp img">
                        <p class="underline text-center">Військова служба правопорядку (ВСП)</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> (044) 454-73-08 </p>
                        <p class="text-center">У випадках порушень дисципліни або прав військовослужбовців можна звертатися до ВСП</p>
                    </div>
                </div>
            </div>
            <!-- Item 3 -->
            <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-2 my-16 justify-center">
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/command.png') }}" alt="command img">
                        <p class="underline text-center">командування</p>
                        <p class="text-center">Першим кроком рекомендується звернутися зі скаргою до свого безпосереднього командира або начальника.</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/ministry.png') }}" alt="ministry img">
                        <p class="underline text-center">Міністерство оборони України</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> 1512 </p>
                        <p class="text-center">Міноборони забезпечує цілодобову «гарячу лінію»</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/commissioner.png') }}" alt="commissioner img">
                        <p class="underline text-center">Уповноважений Верховної Ради України з прав людини</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> 0 800 50 17 </p>
                        <p class="text-center">У випадках порушення прав людини військовослужбовці можуть звертатися до Омбудсмана</p>
                    </div>
                    <div class="flex flex-col items-center w-1/5 gap-1">
                        <img src="{{ asset('img/VSP.png') }}" alt="vsp img">
                        <p class="underline text-center">Військова служба правопорядку (ВСП)</p>
                        <p class="flex text-solate-500"> <img src="{{ asset('img/phone.svg') }}" alt="phone img"> (044) 454-73-08 </p>
                        <p class="text-center">У випадках порушень дисципліни або прав військовослужбовців можна звертатися до ВСП</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-1">
            <button type="button" class="w-80 h-[1px] rounded-full  slide_button" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-80 h-[1px] rounded-full  slide_button" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
            <button type="button" class="w-80 h-[1px] rounded-full  slide_button" aria-current="false" aria-label="Slide 3" data-carousel-slide-to="2"></button>
        </div>
        <!-- Slider controls -->
        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center">
                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180" src="{{ asset('img/button_carousel_left.svg') }}" alt="button_carousel_left">
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center group-focus:outline-none">
                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180" src="{{ asset('img/button_carousel_right.svg') }}" alt="button_carousel_right">
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>

</div>


<div class="w-4/6 mx-auto my-72 section" id="knowledge_pack">
    <div class="flex">
        <div>
            <p class="text-4xl font-bold">Обери свій <span class="text-yellow-400"> комплект знань!</span></p>
            <p class="text-xl text-slate-600">Замовляй свій набір та будь захищеним знанями...</p>
        </div>
        <img class="mt-3 mx-10" src="{{ asset('img/arrow_90.svg') }}" alt="arrow_90">
    </div>

    <!-- Carousel knowledge pack -->

    <div id="indicators-carousel" class="relative w-full mb-20" data-carousel="static" >
        <!-- Carousel wrapper -->
        <div class="relative h-56 overflow-hidden rounded-lg md:h-[500px]">
            <!-- Item 1 -->
            <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-20 mt-10 justify-center ">
                    <div class=" w-1/5 rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">
                        <div class="bg-white flex flex-col items-center rounded-xl ">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 m-3 slef-center"></div>

                            <p class="text-2xl/6 font-bold text-center">Комплект громадянина</p>
                            <p class="text-4xl text-center my-4">650 грн</p>
                            <button class="bg-yellow-400 px-8 m-3 rounded-lg text-black text-xl">Переглянути</button>
                        </div>
                    </div>
                    <div class=" w-1/5 rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">
                        <div class="bg-white flex flex-col items-center rounded-xl ">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 my-3 slef-center"></div>

                            <p class="text-2xl/6 font-bold text-center">Комплект громадянина</p>
                            <p class="text-4xl text-center my-4">650 грн</p>
                            <button class="bg-yellow-400 px-8 m-3 rounded-lg text-black text-xl">Переглянути</button>
                        </div>
                    </div>
                    <div class=" w-1/5 rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">
                        <div class="bg-white flex flex-col items-center rounded-xl ">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 my-3 slef-center"></div>

                            <p class="text-2xl/6 font-bold text-center">Комплект громадянина</p>
                            <p class="text-4xl text-center my-4">650 грн</p>
                            <button class="bg-yellow-400 px-8 m-3 rounded-lg text-black text-xl">Переглянути</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Item 2 -->
            <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                <div class="flex gap-20 mt-10 justify-center ">
                    <div class=" w-1/5 rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">
                        <div class="bg-white flex flex-col items-center rounded-xl ">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 my-3 slef-center"></div>

                            <p class="text-2xl/6 font-bold text-center">Комплект громадянина</p>
                            <p class="text-4xl text-center my-4">650 грн</p>
                            <button class="bg-yellow-400 px-8 m-3 rounded-lg text-black text-xl">Переглянути</button>
                        </div>
                    </div>
                    <div class=" w-1/5 rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">
                        <div class="bg-white flex flex-col items-center rounded-xl ">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 my-3 slef-center"></div>

                            <p class="text-2xl/6 font-bold text-center">Комплект громадянина</p>
                            <p class="text-4xl text-center my-4">650 грн</p>
                            <button class="bg-yellow-400 px-8 m-3 rounded-lg text-black text-xl">Переглянути</button>
                        </div>
                    </div>
                    <div class=" w-1/5 rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">
                        <div class="bg-white flex flex-col items-center rounded-xl ">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 my-3 slef-center"></div>

                            <p class="text-2xl/6 font-bold text-center">Комплект громадянина</p>
                            <p class="text-4xl text-center my-4">650 грн</p>
                            <button class="bg-yellow-400 px-8 m-3 rounded-lg text-black text-xl">Переглянути</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-0">
            <button type="button" class="w-[30rem] h-[1px] rounded-full  slide_button" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
            <button type="button" class="w-[30rem] h-[1px] rounded-full  slide_button" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button>
        </div>
        <!-- Slider controls -->
        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
            <span class="inline-flex items-center justify-center">
                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180" src="{{ asset('img/button_carousel_left.svg') }}" alt="button_carousel_left">
                <span class="sr-only">Previous</span>
            </span>
        </button>
        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
            <span class="inline-flex items-center justify-center group-focus:outline-none">
                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180" src="{{ asset('img/button_carousel_right.svg') }}" alt="button_carousel_right">
                <span class="sr-only">Next</span>
            </span>
        </button>
    </div>


</div>


<div class="w-full bg-blue-400 py-20 text-white section" id="about_author">
    <div class="w-4/6 mx-auto">
        <div class="flex justify-between">
            <div class="rounded-xl bg-gradient-to-t from-blue-300 to-white overflow-hidden">
                <img src="{{ asset('img/serafim.png') }}" alt="serafim">
            </div>
            <div class="flex flex-col w-5/12">
                <div class="font-xl">
                    <p class="text-4xl font-bold">Про <span class="text-yellow-400">автора</span></p>
                    <p>
                        Моя мета — не просто інформувати. Я хочу, щоб кожна людина змогла захистити себе, Україну а також інших людей від правового свавілля...
                    </p>

                    <div class="my-6">
                        <p>Моренець: <span class="font-bold">Євгеній Борисович</span></p>
                        <p>Позивний: <span class="font-bold">Серафим</span></p>
                    </div>
                </div>
                <div class="flex flex-col font-extrabold text-xl/6 gap-3 ">
                    <p>Правозахисник. Доброволець. Військовослужбовець Національної гвардії України.</p>
                    <p>Засновник serafim.info та автор комплектів знань, представлених на цьому сайті.</p>
                    <p>Від початку ініціативи Міністерства внутрішніх справ України «Гвардія наступу» — долучився добровольцем.</p>
                </div>
                <div class="mt-8">
                    ”Війна — це не лише передова. Це ще й боротьба за права, за людську гідність і проти беззаконня, яке, на жаль, існує навіть у формі.”
                </div>
            </div>
        </div>
    </div>
</div>

<div class="w-4/6 mx-auto my-60 flex justify-between">
        <div class="w-[51.5%]">

            <p class="text-4xl font-bold">Навіщо я створив <span class="text-yellow-400">“serafim.info”?</span></p>
            <p class="text-xl text-slate-600">Бо надто багато бачив хлопців і дівчат, які:</p>


            <ul class="list-disc ml-8 mt-4 text-xl font-bold">
                <li>потрапляють у правові пастки без жодної вини;</li>
                <li>не завжди розуміють що є злочином, за який є відповідальність</li>
                <li>не знають, куди звертатись;</li>
                <li>бояться сказати правду через страх перед командуванням;</li>
                <li>не мають доступу до юриста — але мають право на захист.</li>
            </ul>


            <p class="text-xl text-slate-600 my-5 w-[97%]">Усе почалося з того, що прийшовши на службу я сам собі створив комплект правових знань — щоб розуміти, як діяти в умовах служби, тиску чи порушень. І цей комплект мені реально допоміг.</p>

            <div class="flex justify-between items-center">
                <img class="h-[81px] w-[81px] ml-12" src="{{ asset('img/mission.svg') }}" alt="mission img">
                <p class="text-xl w-9/12">Тепер це моя місія: <span class="font-bold">допомогти іншим знати свої права, діяти грамотно, бути захищеним навіть там, де здається — ти сам.</span></p>
            </div>
        </div>

    <div class="flex flex-col place-content-around items-center w-3/6">
        <p class="text-xl text-slate-600">Я особисто пройшов через:</p>
        <div class="w-8/12">
            <div class="border border-blue-400 rounded-xl px-10 py-3 text-xl/5 font-bold">спроби переслідувань за правозахисну діяльність</div>
            <img class="mx-auto mt-[-5px]" src="{{ asset('img/arrow_bottom.svg') }}" alt="arrow_bottom">
            <div class="border border-blue-400 rounded-xl px-10 py-3 text-xl/5 font-bold">судові процеси, в яких відстояв свою честь, гідність і репутацію</div>
            <img class="mx-auto mt-[-5px]" src="{{ asset('img/arrow_bottom.svg') }}" alt="arrow_bottom">
            <div class="border border-blue-400 rounded-xl px-10 py-3 text-xl/5 font-bold">приймав участь в бойових діях на сході, де служив пліч-о-пліч з тими, кому потрібен не лише автомат, а й знання</div>
        </div>

    </div>

</div>


<div class="w-4/6 mx-auto my-60 section" id="for_free">
    <div class="w-3/5">
        <p class="text-4xl font-bold">Сайти із безкоштовною</p>
        <p class="text-4xl font-bold">юридичною допомогою <span class="text-yellow-400">(бонус)</span></p>
        <p class="text-xl text-slate-600">сайти з безкоштовною юридичною допомогою для військовослужбовців та членів їх родин...</p>
    </div>
    <div class="flex gap-20 justify-center mt-10">
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/legal_100.png') }}" alt="legal_100">
            <p class="text-2xl font-bold underline">юридична сотня</p>
            <div class="flex items-center text-slate-600"><img class="h-[17px] w-[21px]" src="{{ asset('img/phone.svg') }}" alt="phone svg"><div>0-800-308-100</div></div>
        </div>
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/BPD.png') }}" alt="BPD png">
            <p class="text-2xl font-bold underline">БПД</p>
            <div class="flex items-center text-slate-600"><img class="h-[17px] w-[21px]" src="{{ asset('img/phone.svg') }}" alt="phone svg"><div>0 800 213 103</div></div>
        </div>
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/principle.png') }}" alt="principle png">
            <p class="text-2xl font-bold underline">принцип</p>
            <div class="flex items-center text-slate-600"><img class="h-[17px] w-[21px]" src="{{ asset('img/phone.svg') }}" alt="phone svg"><div>0-800-308-100</div></div>
        </div>
    </div>
</div>

<div class="w-4/6 mx-auto my-60 section" id="social">
    <div class="w-6/12">
        <p class="text-4xl font-bold">Слідкуй в <span class="text-yellow-400">соцмережах</span></p>
        <p class="text-xl text-slate-600">Мій шлях — це не лише перемога в суді. Це приклад для тисяч. Приєднуйтесь у соцмережах.</p>
    </div>
    <div class="flex gap-5 justify-center my-10">
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/instagram.svg') }}" alt="instagran svg">
        </div>
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/tiktok.svg') }}" alt="tiktok svg">
        </div>
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/facebook.svg') }}" alt="facebook svg">
        </div>
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/youtube.svg') }}" alt="youtube svg">
        </div>
        <div class="flex flex-col items-center gap-1">
            <img class="mx-auto" src="{{ asset('img/telegram.svg') }}" alt="telegram svg">
        </div>
    </div>
    <div class="w-full flex justify-end">
        <div class="border border-blue-400 rounded-xl px-10 py-3 font-bold w-6/12 text-center">Тут — підтримка, дієві поради і спільнота, що не здається...</div>
    </div>

</div>
<script src="https://cdn.tailwindcss.com"></script>
@endsection
