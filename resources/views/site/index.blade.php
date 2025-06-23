@extends('layouts.site')
{{--<script src="{{ asset('js/index.js') }}"></script>--}}
@section('header')
    @include('components.header')
@endsection

@section('content')



            <!-- Introduction and video -->
            <!-- @if(isset($_POST['screenWidth']) && $_POST['screenWidth'] > 1025)
                @include('components.introduction')
            @else
                @include('components.introduction-mobile')
            @endif -->

            <div class="lg:hidden"> @include('components.introduction-mobile') </div>
            <div class="hidden lg:block"> @include('components.introduction') </div>

            <!-- </div> -->
            <!-- Why Important Section -->
             @include('components.whyimportant')


            <!-- <div
                class="flex flex-col lg:flex-row my-16 lg:my-52 w-full px-4 sm:px-6 xl:w-4/6 lg:w-5/6 lg:mx-auto gap-8 lg:justify-between">
                <div class="w-full lg:w-5/12">
                    <div class="mb-6 lg:mb-8 flex-1">
                        <p class="text-2xl sm:text-3xl lg:text-[2.5rem] font-bold leading-tight">
                            Чому це <span class="text-yellow-400">важливо?</span>
                        </p>
                        <p class="text-slate-600 text-base lg:text-lg mt-2">
                            Ніхто не подбає про твої права краще за тебе самого...
                        </p>
                    </div>-->


                    <!-- Change text -->
                    <!-- <div class="border border-blue-400 rounded-lg p-4 lg:p-6">
                        <div class="flex items-center gap-3 mb-6 lg:mb-10"> -->
                            <!-- <div class="w-8 h-8 bg-blue-100 rounded flex-shrink-0"></div> -->
                            <!-- <img src="{{ asset('img/law_knowledge.svg') }}" alt="law_knowledge img">
                            <p class="text-slate-600 font-bold text-sm lg:text-base" id="top7">
                                ТОП-7 Переваг знання законів та прав людини
                            </p>
                        </div>

                        <ul class="list-disc ml-6 pr-2 mb-6">
                            <li class="text-slate-600 text-sm lg:text-base leading-relaxed" id="description">
                                Знання Конституції України, законів, прав людини та міжнародного права — це не просто
                                освіченість.
                                Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:
                            </li>
                        </ul>

                        <div class="space-y-3 mb-6">
                            <div class="flex gap-3 items-start"> -->
                                <!-- <div class="w-5 h-5 bg-green-500 rounded-full flex-shrink-0 mt-0.5"></div> -->
                               <!-- <img src="{{ asset('img/check.svg') }}" alt="check img">
                                <span class="text-slate-600 text-sm lg:text-base" id="argument-1">Правовий захист у будь-якій ситуації</span>
                            </div>
                            <div class="flex gap-3 items-start">
                                <img src="{{ asset('img/check.svg') }}" alt="check img">
                                <p class="text-slate-600 text-sm lg:text-base" id="argument-2">Гарантована свобода та
                                    недоторканність</p>
                            </div>
                            <div class="flex gap-3 items-start">
                                <img src="{{ asset('img/check.svg') }}" alt="check img">
                                <p class="text-slate-600 text-sm lg:text-base" id="argument-3">Можливість законного
                                    самозахисту</p>
                            </div>
                            <div class="flex gap-3 items-start">
                                <img src="{{ asset('img/check.svg') }}" alt="check img">
                                <p class="text-slate-600 text-sm lg:text-base" id="argument-4">Контроль над державою, а
                                    не навпаки</p>
                            </div>
                            <div class="flex gap-3 items-start">
                                <img src="{{ asset('img/check.svg') }}" alt="check img">
                                <p class="text-slate-600 text-sm lg:text-base" id="argument-5">Неможливість
                                    маніпуляцій</p>
                            </div>
                            <div class="flex gap-3 items-start">
                                <img src="{{ asset('img/check.svg') }}" alt="check img">
                                <p class="text-slate-600 text-sm lg:text-base" id="argument-6">Готовність до
                                    міжнародного захисту</p>
                            </div>
                            <div class="flex gap-3 items-start">
                                <img src="{{ asset('img/check.svg') }}" alt="check img">
                                <p class="text-slate-600 text-sm lg:text-base" id="argument-7">Повага і впевненість</p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-blue-400 rounded-lg mt-6 lg:mt-10 p-4">
                        <div class="flex gap-3 lg:gap-5 items-center justify-center"> -->
                            <!-- <div class="w-8 h-8 bg-orange-200 rounded flex-shrink-0"></div> -->
                            <!--<img src="{{ asset('img/result.svg') }}" alt="result img">
                            <div class="text-slate-600 text-sm lg:text-base leading-relaxed">
                                <span class="font-bold" id="result">Результат: </span>
                                <span id="result_desc">Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-full lg:w-[50.25%]">
                    <div class="flex gap-1 justify-start mt-0 lg:mt-8 flex-wrap">
                        @php
                            // Створюємо масиви для цін, ID та картинок
                            $prices = [];
                            $productIds = [];
                            $productImages = [];

                            foreach($productsForApplying as $product) {
                                $applyingKey = $product->applying instanceof \BackedEnum ? $product->applying->value : $product->applying;
                                $prices[$applyingKey] = $product->price;
                                $productIds[$applyingKey] = $product->id;

                                // Отримуємо URL першої картинки
                                $productImages[$applyingKey] = $product->getFirstMediaUrl('product_images') ?: '/images/default-product.jpg';
                            }
                        @endphp
                        @foreach($applyings as $key => $value)
                            <div class="flex items-center gap-2 bg-blue-400 rounded-lg px-2 py-2 radio-div">
                                <input id="checkbox-{{ $key }}"
                                       class="w-4 h-4 cursor-pointer rounded-md text-blue-400 bg-white"
                                       name="options"
                                       type="radio"
                                       value="{{ $key }}"
                                       data-price="{{ $prices[$key] ?? 0 }}"
                                       data-product-id="{{ $productIds[$key] ?? 0 }}"
                                       data-image-url="{{ $productImages[$key] ?? '/images/default-product.jpg' }}"
                                       data-href="{{ route('product.show', $productIds[$key] ?? 1) }}">
                                <label class="cursor-pointer text-white text-sm lg:text-base"
                                       for="checkbox-{{ $key }}">{{ $value }}</label>
                            </div>
                        @endforeach
                    </div>

                    <div class="bg-gray-200 w-full h-64 sm:h-80 lg:h-[516px] lg:flex-1 rounded-lg mt-6 lg:mt-8">
                        <img id="productImage"
                             src="/images/default-product.jpg"
                             alt="Product Image"
                             class="w-full h-full object-cover rounded-lg">
                    </div>

                    <div
                        class="flex flex-col sm:flex-row justify-between lg:justify-end mt-6 lg:my-5 items-center gap-4 lg:gap-5 pt-12">
                        <div class="text-black text-2xl lg:text-4xl">
                            <span class="text-yellow-400" id="price">600 </span>грн
                        </div>
                        <a href="" id="productHref"
                           class="bg-yellow-400 px-6 py-3 rounded-lg text-black text-lg lg:text-xl font-semibold">замовити</a>
                    </div>
                </div>
            </div> -->








            <!-- Knowledge Pack Section -->
             <div id="wt_knowledge_pack">
                @include('components.knowledgepack')
             </div>















            <!-- <div
                class="flex flex-col lg:flex-row w-full px-4 sm:px-6 lg:w-5/6 xl:w-4/6 lg:mx-auto lg:justify-between my-16 lg:my-72 gap-8"
                id="wt_knowledge_pack">
                <div class="w-full lg:w-5/12">
                    <div class="mb-6">
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight">
                            Комплект знань — юридичний <span class="text-yellow-400">набір виживання</span>
                        </p>
                        <p class="text-slate-600 text-base lg:text-lg mt-2">
                            Комплект, що <span class="text-red-600">100%</span> повинен бути у кожного під рукою...
                        </p>
                    </div>
                    <div>
                        <p class="text-xl lg:text-2xl font-bold mb-3">Це набір матеріалів, які допоможуть:</p>
                        <ul class="list-disc text-base lg:text-xl text-slate-600 ml-6 lg:ml-8 mt-3 space-y-1">
                            <li>Знати свої права і обов'язки;</li>
                            <li>Реагувати на порушення;</li>
                            <li>Подати скаргу, заяву або звернення;</li>
                            <li>Отримати захист — без адвоката і без паніки.</li>
                        </ul>
                    </div>
                </div>

                <div class="w-full lg:w-5/12 flex flex-col justify-center gap-8 lg:gap-10">
                    <div class="flex justify-center items-center">
                        <div
                            class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-20 w-20 lg:h-24 lg:w-24 gap-1"> -->
                            <!-- <div class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-200 rounded"></div> -->
                            <!-- <img class="h-[42px] w-[42px]" src="{{ asset('img/constitution.svg') }}"
                                 alt="constitution img">
                            <p class="text-center text-xs text-slate-600">Конституція</p>
                        </div> -->
                        <!-- <div class="w-4 h-0.5 bg-gray-300 mx-1"></div> -->
                     <!--   <img class="ml-[-6px] h-[20px] self-center bg-white z-10"
                             src="{{ asset('img/arrow_right.svg') }}" alt="arrow_right img">
                        <div
                            class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-20 w-20 lg:h-24 lg:w-24 gap-1"> -->
                            <!-- <div class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-200 rounded"></div> -->
                            <!-- <img class="h-[42px] w-[42px]" src="{{ asset('img/statutes.svg') }}" alt="statutes img">
                            <p class="text-center text-xs text-slate-600">Статути</p>
                        </div> -->
                        <!-- <div class="w-4 h-0.5 bg-gray-300 mx-1"></div> -->
                       <!-- <img class="ml-[-6px] h-[20px] self-center bg-white z-10"
                             src="{{ asset('img/arrow_right.svg') }}" alt="arrow_right img">
                        <div
                            class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-20 w-20 lg:h-24 lg:w-24 gap-1"> -->
                            <!-- <div class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-200 rounded"></div> -->
                           <!-- <img class="h-[42px] w-[42px]" src="{{ asset('img/codes.svg') }}" alt="codes img">
                            <p class="text-center text-xs text-slate-600">Кодекси</p>
                        </div>
                    </div>

                    <div class="flex lg:justify-start justify-center items-center">
                        <div
                            class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-20 w-20 lg:h-24 lg:w-24 gap-2"> -->
                            <!-- <div class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-200 rounded"></div> -->
                           <!-- <img class="h-[42px] w-[42px]" src="{{ asset('img/templates.svg') }}" alt="templates img">
                            <p class="text-center text-xs text-slate-600">Шаблони</p>
                        </div> -->
                        <!-- <div class="w-4 h-0.5 bg-gray-300 mx-1"></div> -->
                      <!--  <img class="mr-[-6px] h-[20px] self-center bg-white z-10"
                             src="{{ asset('img/arrow_left.svg') }}" alt="arrow_left img">
                        <div
                            class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-20 w-20 lg:h-24 lg:w-24 gap-2"> -->
                            <!-- <div class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-200 rounded"></div> -->
                            <!-- <img class="h-[42px] w-[42px]" src="{{ asset('img/memos.svg') }}" alt="">
                            <p class="text-center text-xs text-slate-600">Памятки</p>
                        </div>-->
                        <!-- <div class="w-4 h-0.5 bg-gray-300 mx-1"></div> -->
                        <!-- <img class="mr-[-6px] h-[20px] self-center bg-white z-10"
                             src="{{ asset('img/arrow_left.svg') }}" alt="arrow_left img">
                        <div
                            class="border border-blue-400 rounded-lg flex flex-col items-center p-3 h-20 w-20 lg:h-24 lg:w-24 gap-2"> -->
                            <!-- <div class="w-8 h-8 lg:w-10 lg:h-10 bg-blue-200 rounded"></div> -->
                           <!-- <img class="h-[42px] w-[42px]" src="{{ asset('img/algorithms.svg') }}" alt="">
                            <p class="text-center text-xs text-slate-600">Алгоритми</p>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- How to Submit Section Mobile -->
            <!-- <div class="lg:hidden w-full px-4 sm:px-6 lg:w-11/12 xl:w-4/6 lg:mx-auto my-16 lg:my-72">
                <div class="mb-6 lg:mb-10">
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight">
                        Кожен українець <span class="text-yellow-400">повинен розуміти</span>, як подавати
                    </p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold">Скаргу, клопотання, заяву, рапорт, позов</p>
                    <p class="text-lg lg:text-xl text-slate-600 mt-2">І комплект знань закриє це питання...</p>
                </div> -->

                <!-- Mobile/Tablet Carousel Simplified -->
                <!-- <div class="bg-white rounded-lg p-4 lg:p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-4">
                        <div class="flex flex-col items-center text-center space-y-3"> -->
                            <!-- <div class="w-16 h-16 lg:w-20 lg:h-20 bg-blue-200 rounded-lg"></div> -->
                            <!--<img class="w-20 h-20" src="{{ asset('img/command.png') }}" alt="command img">
                            <p class="underline font-semibold text-sm lg:text-base">командування</p>
                            <p class="text-xs lg:text-sm text-gray-600">
                                Першим кроком рекомендується звернутися зі скаргою до свого безпосереднього командира
                                або начальника.
                            </p>
                        </div>
                        <div class="flex flex-col items-center text-center space-y-3">
                            <img class="w-20 h-20" src="{{ asset('img/ministry.png') }}" alt="ministry img">
                            <p class="underline font-semibold text-sm lg:text-base">Міністерство оборони України</p>
                            <a class="flex items-center text-slate-500 text-sm" href="tel:1512">
                                <span class="mr-1">📞</span> 1512
                            </a>
                            <p class="text-xs lg:text-sm text-gray-600">Міноборони забезпечує цілодобову «гарячу
                                лінію»</p>
                        </div>
                        <div class="flex flex-col items-center text-center space-y-3">
                            <img class="w-20 h-20" src="{{ asset('img/commissioner.png') }}" alt="commissioner img">
                            <p class="underline font-semibold text-sm lg:text-base">Уповноважений ВР України з прав
                                людини</p>
                            <a class="flex items-center text-slate-500 text-sm" href="tel:0-800-50-17">
                                <span class="mr-1">📞</span> 0 800 50 17
                            </a>
                            <p class="text-xs lg:text-sm text-gray-600">
                                У випадках порушення прав людини військовослужбовці можуть звертатися до Омбудсмана
                            </p>
                        </div>
                        <div class="flex flex-col items-center text-center space-y-3">
                            <img class="w-20 h-20" src="{{ asset('img/VSP.png') }}" alt="vsp img">
                            <p class="underline font-semibold text-sm lg:text-base">Військова служба правопорядку
                                (ВСП)</p>
                            <a class="flex items-center text-slate-500 text-sm" href="tel:044-454-73-08">
                                <span class="mr-1">📞</span> (044) 454-73-08
                            </a>
                            <p class="text-xs lg:text-sm text-gray-600">
                                У випадках порушень дисципліни або прав військовослужбовців можна звертатися до ВСП
                            </p>
                        </div>
                    </div>
                </div>
            </div> -->









            <!-- How to Submit Section Desktop -->
            <div class="hidden lg:block">
                @include('components.howtosubmit')
            </div>

            <!-- How to Submit Section Mobile -->
            <div class="lg:hidden">
                @include('components.howtosubmit-mobile')
            </div>












          <!-- <div class="hidden lg:block w-full px-4 sm:px-6 lg:w-5/6 xl:w-4/6 lg:mx-auto my-16 lg:my-72">
                <div class="mb-10">
                    <p class="text-2xl xl:text-4xl font-bold">Кожен українець <span class="text-yellow-400">повинен розуміти</span>,
                        як подавати</p>
                    <p class="text-2xl xl:text-4xl font-bold">Скаргу, клопотання, заяву, рапорт, позов</p>
                    <p class="text-xl text-slate-600">І комплект знань закриє це питання...</p>
                </div> -->

                <!-- Carousel -->


                <!-- <div id="indicators-carousel" class="relative w-full" data-carousel="static"> -->
                    <!-- Carousel wrapper -->

                    <!-- <div class="relative h-56 overflow-hidden rounded-lg md:h-[450px] xl:h-96"> -->
                        <!-- Item 1 -->

                        <!--  <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                            <div class="flex gap-2 my-16 justify-center">
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/command.png') }}" alt="command img">
                                    <p class="underline text-center">командування</p>
                                    <p class="text-center">Першим кроком рекомендується звернутися зі скаргою до свого
                                        безпосереднього командира або начальника.</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/ministry.png') }}" alt="ministry img">
                                    <p class="underline text-center">Міністерство оборони України</p>
                                    <a class="flex text-solate-500" href="tel:1512"> <img
                                            src="{{ asset('img/phone.svg') }}" alt="phone img"> 1512 </a>
                                    <p class="text-center">Міноборони забезпечує цілодобову «гарячу лінію»</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/commissioner.png') }}" alt="commissioner img">
                                    <p class="underline text-center">Уповноважений Верховної Ради України з прав
                                        людини</p>
                                    <a class="flex text-solate-500" href="tel:0-800-50-17"> <img
                                            src="{{ asset('img/phone.svg') }}" alt="phone img"> 0 800 50 17 </a>
                                    <p class="text-center">У випадках порушення прав людини військовослужбовці можуть
                                        звертатися до Омбудсмана</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/VSP.png') }}" alt="vsp img">
                                    <p class="underline text-center">Військова служба правопорядку (ВСП)</p>
                                    <a class="flex text-solate-500" href="tel:(044) 454-73-08"> <img
                                            src="{{ asset('img/phone.svg') }}" alt="phone img"> (044) 454-73-08 </a>
                                    <p class="text-center">У випадках порушень дисципліни або прав військовослужбовців
                                        можна звертатися до ВСП</p>
                                </div>
                            </div>
                        </div> -->
                        <!-- Item 2 -->

                       <!-- <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                            <div class="flex gap-2 my-16 justify-center">
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/command.png') }}" alt="command img">
                                    <p class="underline text-center">командування</p>
                                    <p class="text-center">Першим кроком рекомендується звернутися зі скаргою до свого
                                        безпосереднього командира або начальника.</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/ministry.png') }}" alt="ministry img">
                                    <p class="underline text-center">Міністерство оборони України</p>
                                    <p class="flex text-solate-500"><img src="{{ asset('img/phone.svg') }}"
                                                                         alt="phone img"> 1512 </p>
                                    <p class="text-center">Міноборони забезпечує цілодобову «гарячу лінію»</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/commissioner.png') }}" alt="commissioner img">
                                    <p class="underline text-center">Уповноважений Верховної Ради України з прав
                                        людини</p>
                                    <p class="flex text-solate-500"><img src="{{ asset('img/phone.svg') }}"
                                                                         alt="phone img"> 0 800 50 17 </p>
                                    <p class="text-center">У випадках порушення прав людини військовослужбовці можуть
                                        звертатися до Омбудсмана</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/VSP.png') }}" alt="vsp img">
                                    <p class="underline text-center">Військова служба правопорядку (ВСП)</p>
                                    <p class="flex text-solate-500"><img src="{{ asset('img/phone.svg') }}"
                                                                         alt="phone img"> (044) 454-73-08 </p>
                                    <p class="text-center">У випадках порушень дисципліни або прав військовослужбовців
                                        можна звертатися до ВСП</p>
                                </div>
                            </div>
                        </div> -->
                        <!-- Item 3 -->

                      <!--  <div class="hidden duration-700 ease-in-out bg-white" data-carousel-item>
                            <div class="flex gap-2 my-16 justify-center">
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/command.png') }}" alt="command img">
                                    <p class="underline text-center">командування</p>
                                    <p class="text-center">Першим кроком рекомендується звернутися зі скаргою до свого
                                        безпосереднього командира або начальника.</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/ministry.png') }}" alt="ministry img">
                                    <p class="underline text-center">Міністерство оборони України</p>
                                    <p class="flex text-solate-500"><img src="{{ asset('img/phone.svg') }}"
                                                                         alt="phone img"> 1512 </p>
                                    <p class="text-center">Міноборони забезпечує цілодобову «гарячу лінію»</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/commissioner.png') }}" alt="commissioner img">
                                    <p class="underline text-center">Уповноважений Верховної Ради України з прав
                                        людини</p>
                                    <p class="flex text-solate-500"><img src="{{ asset('img/phone.svg') }}"
                                                                         alt="phone img"> 0 800 50 17 </p>
                                    <p class="text-center">У випадках порушення прав людини військовослужбовці можуть
                                        звертатися до Омбудсмана</p>
                                </div>
                                <div class="flex flex-col items-center w-1/5 gap-1">
                                    <img src="{{ asset('img/VSP.png') }}" alt="vsp img">
                                    <p class="underline text-center">Військова служба правопорядку (ВСП)</p>
                                    <p class="flex text-solate-500"><img src="{{ asset('img/phone.svg') }}"
                                                                         alt="phone img"> (044) 454-73-08 </p>
                                    <p class="text-center">У випадках порушень дисципліни або прав військовослужбовців
                                        можна звертатися до ВСП</p>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- Slider indicators -->
                   <!-- <div
                        class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-[-20px] w-full justify-center">
                        <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="true"
                                aria-label="Slide 1" data-carousel-slide-to="0"></button>
                        <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="false"
                                aria-label="Slide 2" data-carousel-slide-to="1"></button>
                        <button type="button" class="w-[30%] h-[1px] rounded-full  slide_button" aria-current="false"
                                aria-label="Slide 3" data-carousel-slide-to="2"></button>
                    </div> -->
                    <!-- Slider controls -->

                   <!-- <button type="button"
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
            </div> -->


            <!-- Knowledge Pack Selection Mobile -->
            <!-- <div class="lg:hidden w-full px-4 sm:px-6 lg:w-4/6 lg:mx-auto my-16 lg:my-72" id="knowledge_pack_mobile">
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4 mb-8">
                    <div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold">
                            Обери свій <span class="text-yellow-400"> комплект знань!</span>
                        </p>
                        <p class="text-lg lg:text-xl text-slate-600 mt-2">
                            Замовляй свій набір та будь захищеним знанями...
                        </p>
                    </div>
                    <div class="hidden lg:block ml-6">
                        <div class="w-8 h-8 bg-yellow-400 rounded transform rotate-90"></div>
                    </div>
                </div> -->

                <!-- Knowledge Pack Cards -->
                <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                    @foreach($products as $product)
                        <div class="rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-0.5">
                            <div class="bg-white flex flex-col items-center rounded-xl p-4">
                                <div class="h-32 w-32 lg:h-48 lg:w-48 rounded-xl bg-gray-200 mb-4">
                                    @if($product->getMedia('product_images')->isNotEmpty())
                                        <img  class="w-full h-full" src="{{ $product->getFirstMediaUrl('product_images') }}"
                                             alt="{{ $product->title }}">
                                    @endif
                                </div>
                                <p class="text-lg lg:text-2xl font-bold text-center mb-3">{{$product->name}}</p>
                                <p class="text-2xl lg:text-4xl text-center mb-4 font-bold">{{ $product->price }}
                                    грн</p>

                                <a class="bg-yellow-400 px-6 py-2 lg:px-8 lg:py-3 rounded-lg text-black text-lg lg:text-xl font-semibold"
                                   href="{{ route('product.show', $product->id) }}">перейти</a>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div> -->


            <!-- Choose Knowledge Pack Selection Desktop -->
            <div class="lg:hidden" id="knowledge_pack">
                @include('components.chooseknoweledge-mobile')
            </div>


            <div class="hidden lg:block">
                @include('components.chooseknoweledge')
            </div>



            <!-- <div class="hidden lg:block lg:w-5/6 xl:w-4/6 mx-auto my-72 section" id="knowledge_pack">
                <div class="flex">
                    <div>
                        <p class="text-4xl font-bold">Обери свій <span class="text-yellow-400"> комплект знань!</span>
                        </p>
                        <p class="text-xl text-slate-500">Замовляй свій набір та будь захищеним знанями...</p>
                    </div>
                    <img class="mt-3 mx-10" src="{{ asset('img/arrow_90.svg') }}" alt="arrow_90">
                </div> -->
                <!-- Carousel knowledge pack -->

                <!--<div id="indicators-carousel" class="relative w-full mb-20" data-carousel="static">-->
                    <!-- Carousel wrapper -->

                    <!-- <div class="relative h-56 overflow-hidden rounded-lg md:h-[500px] w-full"> -->
                        <!-- Item 1 -->
                        <!-- @foreach ($productsChunks as $perPage)

                            <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                                <div class="flex lg:gap-10 xl:gap-20 mt-10 justify-center ">
                                    @foreach($perPage as $product)
                                        <div
                                            class="lg:w-3/12 xl:w-1/5 h-full rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">

                                            <div class="bg-white flex flex-col items-center rounded-xl "> -->
                                                <!-- posible img -->

                                              <!--  <div
                                                    class="h-[212px] w-[212px] rounded-xl bg-gray-200  m-3 slef-center overflow-hidden">
                                                    @if($product->getMedia('product_images')->isNotEmpty())
                                                        <img class="w-full h-full" src="{{ $product->getFirstMediaUrl('product_images') }}"
                                                             alt="{{ $product->title }}">
                                                    @endif
                                                </div>

                                                <p class="text-2xl/6 font-bold text-center">{{$product->name}}</p>
                                                <p class="text-4xl text-center my-4">{{$product->price}} грн</p>
                                                <a class="flex items-center bg-yellow-400 px-8 m-3 rounded-lg inline-block align-middle h-10 text-black text-xl hover:bg-yellow-500 transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                                   href="{{ route('product.show', $product->id) }}">перейти</a>

                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                    </div> -->
                    <!-- Slider indicators -->

                    <!-- <div
                        class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-0 w-full justify-center"> -->
                        <!-- <button type="button" class="w-[45%] h-[1px] rounded-full  slide_button" aria-current="true" aria-label="Slide 1" data-carousel-slide-to="0"></button>
                        <button type="button" class="w-[45%] h-[1px] rounded-full  slide_button" aria-current="false" aria-label="Slide 2" data-carousel-slide-to="1"></button> -->

                        <!-- @foreach ($productsChunks as $index => $perPage)
                            <button
                                type="button"
                                class="h-[1px] rounded-full slide_button {{ $index === 0 ? 'bg-gray-800' : 'bg-gray-400' }}"
                                style="width: {{ 90 / count($productsChunks) }}%"
                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                aria-label="Slide {{ $index + 1 }}"
                                data-carousel-slide-to="{{ $index }}"
                            ></button>
                        @endforeach

                    </div> -->
                    <!-- Slider controls -->

                   <!-- <button type="button"
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


            </div> -->


            <!-- About Author Section -->
            <div class="lg:hidden" id="about_author">
                @include('components.aboutauthor-mobile')
            </div>

            <div class="hidden lg:block">
                @include('components.aboutauthor')
            </div>


            <!-- <div class="w-full bg-blue-400 py-12 lg:py-20 text-white" id="about_author">
                <div class="w-full px-4 sm:px-6 lg:w-4/6 lg:mx-auto">
                    <div class="flex flex-col lg:flex-row gap-8 lg:justify-between">
                        <div class="w-full lg:w-5/12 flex justify-center xl:justify-start items-center">
                            <div
                                class="rounded-xl inline-block h-min bg-gradient-to-t from-blue-300 to-white overflow-hidden">
                                <img src="{{ asset('img/serafim.png') }}" alt="serafim">
                            </div>
                        </div>
                        <div class="flex flex-col w-full lg:w-5/12 text-center lg:text-left">
                            <div>
                                <p class="text-2xl lg:text-4xl font-bold mb-4">
                                    Про <span class="text-yellow-400">автора</span>
                                </p>
                                <p class="text-base lg:text-lg mb-6">
                                    Моя мета — не просто інформувати. Я хочу, щоб кожна людина змогла захистити себе,
                                    Україну а також інших людей від правового свавілля...
                                </p>

                                <div class="mb-6">
                                    <p class="text-base lg:text-lg">Моренець: <span
                                            class="font-bold">Євгеній Борисович</span></p>
                                    <p class="text-base lg:text-lg">Позивний: <span class="font-bold">Серафим</span></p>
                                </div>
                            </div>
                            <div class="flex flex-col font-bold text-base lg:text-xl leading-relaxed gap-3">
                                <p>Правозахисник. Доброволець. Військовослужбовець Національної гвардії України.</p>
                                <p>Засновник serafim.info та автор комплектів знань, представлених на цьому сайті.</p>
                                <p>Від початку ініціативи Міністерства внутрішніх справ України «Гвардія наступу» —
                                    долучився добровольцем.</p>
                            </div>
                            <div class="mt-6 lg:mt-8 italic text-base lg:text-lg">
                                "Війна — це не лише передова. Це ще й боротьба за права, за людську гідність і проти
                                беззаконня,
                                яке, на жаль, існує навіть у формі."
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Why Created Section -->
            <div class="lg:hidden">
                @include('components.whycreated-mobile')
            </div>

            <div class="hidden lg:block">
                @include('components.whycreated')
            </div>

            <!-- <div
                class="w-full px-4 sm:px-6 xl:w-4/6 mx-auto my-12 sm:my-20 lg:my-60 flex flex-col xl:flex-row justify-between gap-8 lg:gap-0">-->
                <!-- Ліва частина -->
                <!-- <div class="w-full lg:w-5/6 lg:mx-auto xl:w-3/5">
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight">
                        Навіщо я створив <span class="text-yellow-400">"serafim.info"?</span>
                    </p>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4">
                        Бо надто багато бачив хлопців і дівчат, які:
                    </p>

                    <ul class="list-disc ml-6 sm:ml-8 mt-4 text-lg sm:text-xl font-bold space-y-2">
                        <li>потрапляють у правові пастки без жодної вини;</li>
                        <li>не завжди розуміють що є злочином, за який є відповідальність</li>
                        <li>не знають, куди звертатись;</li>
                        <li>бояться сказати правду через страх перед командуванням;</li>
                        <li>не мають доступу до юриста — але мають право на захист.</li>
                    </ul>

                    <p class="text-lg sm:text-xl text-slate-600 my-5">
                        Усе почалося з того, що прийшовши на службу я сам собі створив комплект правових знань — щоб
                        розуміти, як діяти в умовах служби, тиску чи порушень. І цей комплект мені реально допоміг.
                    </p>

                    <div class="flex flex-row sm:flex-row justify-between items-start sm:items-center gap-4">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 flex items-center justify-center flex-shrink-0">
                            <img src="{{ asset('img/mission.svg') }}" alt="">
                        </div>
                        <p class="text-lg sm:text-xl flex-1">
                            Тепер це моя місія: <span class="font-bold">допомогти іншим знати свої права, діяти грамотно, бути захищеним навіть там, де здається — ти сам.</span>
                        </p>
                    </div>
                </div> -->

                <!-- Права частина -->
               <!-- <div class="w-full xl:w-2/5 flex flex-col justify-end items-center space-y-6">
                    <p class="text-lg sm:text-xl text-slate-600 text-center mb-4">Я особисто пройшов через:</p>
                    <div class="w-full sm:w-10/12 lg:w-9/12">
                        <div
                            class="border border-blue-400 rounded-xl px-6 sm:px-8 py-3 text-base sm:text-lg lg:text-lg font-bold text-left">
                            спроби переслідувань за правозахисну діяльність
                        </div>

                        <img class="mx-auto mt-[-5px]" src="{{ asset('img/arrow_bottom.svg') }}" alt="arrow_bottom">

                        <div
                            class="border border-blue-400 rounded-xl px-6 sm:px-8 py-3 text-base sm:text-lg lg:text-lg font-bold text-left">
                            судові процеси, в яких відстояв свою честь, гідність і репутацію
                        </div>

                        <img class="mx-auto mt-[-5px]" src="{{ asset('img/arrow_bottom.svg') }}" alt="arrow_bottom">

                        <div
                            class="border border-blue-400 rounded-xl px-6 sm:px-8 py-3 text-base sm:text-lg lg:text-lg font-bold text-left">
                            приймав участь в бойових діях на сході, де служив пліч-о-пліч з тими, кому потрібен не лише
                            автомат, а й знання
                        </div>
                    </div>
                </div>
            </div> -->

            <!-- Секція безкоштовної допомоги -->

            <div class="lg:hidden">
                @include('components.forfree-mobile')
            </div>
            <div class="hidden lg:block">
                @include('components.forfree')
            </div>

            <!-- <div class="w-full px-4 sm:px-6 lg:w-5/6 xl:w-4/6 mx-auto my-12 sm:my-20 lg:my-60" id="for_free">
                <div class="w-full lg:w-5/6 xl:w-3/5 mb-8 lg:mb-10">
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight">Сайти із безкоштовною</p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight">
                        юридичною допомогою <span class="text-yellow-400">(бонус)</span>
                    </p>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4">
                        сайти з безкоштовною юридичною допомогою для військовослужбовців та членів їх родин...
                    </p>
                </div>

                <div class="flex flex-col sm:flex-row gap-8 sm:gap-12 lg:gap-20 justify-center items-center">
                    <div class="flex flex-col items-center gap-2 min-w-0">
                        <img src="{{ asset('img/legal_100.png') }}" alt="legal_100 png">
                        <p class="text-xl sm:text-2xl font-bold underline text-center">юридична сотня</p>
                        <a class="flex items-center text-slate-600" href="tel:0-800-308-100">
                            <img class="w-5 h-4" src="{{ asset('img/phone.svg') }}" alt="phone svg">
                            <div class="whitespace-nowrap">0-800-308-100</div>
                        </a>
                    </div>

                    <div class="flex flex-col items-center gap-2 min-w-0">
                        <img src="{{ asset('img/BPD.png') }}" alt="bpd png">
                        <p class="text-xl sm:text-2xl font-bold underline text-center">БПД</p>
                        <a class="flex items-center text-slate-600" href="tel:0 800 213 103">
                            <img class="w-5 h-4" src="{{ asset('img/phone.svg') }}" alt="phone svg">
                            <div class="whitespace-nowrap">0 800 213 103</div>
                        </a>
                    </div>

                    <div class="flex flex-col items-center gap-2 min-w-0">
                        <img src="{{ asset('img/principle.png') }}" alt="principle png">
                        <p class="text-xl sm:text-2xl font-bold underline text-center">принцип</p>
                        <a class="flex items-center text-slate-600" href="tel:0-800-308-100">
                            <img class="w-5 h-4" src="{{ asset('img/phone.svg') }}" alt="phone svg">
                            <div class="whitespace-nowrap">0-800-308-100</div>
                        </a>
                    </div>
                </div>
            </div> -->

            <!-- Секція соцмереж -->
            <div class="lg:hidden">
                @include('components.socialnetwork-mobile')
            </div>
            <div class="hidden lg:block">
                @include('components.socialnetwork')
            </div>

            <!-- <div class="w-full px-4 sm:px-6 lg:w-4/6 mx-auto my-12 sm:my-20 lg:my-60" id="social">
                <div class="w-full lg:w-6/12 mb-8">
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-bold leading-tight">
                        Слідкуй в <span class="text-yellow-400">соцмережах</span>
                    </p>
                    <p class="text-lg sm:text-xl text-slate-600 mt-4">
                        Мій шлях — це не лише перемога в суді. Це приклад для тисяч. Приєднуйтесь у соцмережах.
                    </p>
                </div>

                <div class="flex flex-wrap justify-center gap-6 sm:gap-8 my-8 lg:my-10">
                    <div class="flex flex-col items-center">
                        <a href="https://www.instagram.com/serafim_ngu">
                            <img src="{{ asset('img/instagram.svg') }}" alt="">
                        </a>
                    </div>

                    <div class="flex flex-col items-center">
                        <a href="https://tiktok.com/@serafim_ngu">
                            <img src="{{ asset('img/tiktok.svg') }}" alt="">
                        </a>
                    </div>

                    <div class="flex flex-col items-center">
                        <a href="#">
                            <img src="{{ asset('img/facebook.svg') }}" alt="">
                        </a>
                    </div>

                    <div class="flex flex-col items-center">
                        <a href="https://youtube.com/@serafim_ngu">
                            <img src="{{ asset('img/youtube.svg') }}" alt="">
                        </a>
                    </div>

                    <div class="flex flex-col items-center">
                        <a href="#">
                            <img src="{{ asset('img/telegram.svg') }}" alt="">
                        </a>
                    </div>
                </div>

                <div class="w-full flex justify-end">
                    <div
                        class="border border-blue-400 rounded-xl px-6 sm:px-10 py-3 font-bold w-full sm:w-10/12 lg:w-6/12 text-center text-base sm:text-lg">
                        Тут — підтримка, дієві поради і спільнота, що не здається...
                    </div>
                </div>
            </div> -->

    <script>

        function smoothScrollToElement(targetId, offset = 80) {
            const targetElement = document.getElementById(targetId);
            if (!targetElement) return;

            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - offset;

            window.scrollTo({
                top: offsetPosition,
                behavior: "smooth",
            });
        }

        document.addEventListener("DOMContentLoaded", function () {
            const navItems = document.querySelectorAll(".nav-item");

            navItems.forEach((item) => {
                item.addEventListener("click", function () {
                    const targetId = this.getAttribute("data-target");

                    // Використовуємо основний метод
                    smoothScrollToElement(targetId, 80);

                    // Або альтернативний метод (закоментований)
                    // smoothScrollAlternative(targetId);
                });
            });
        });
        const radios = document.querySelectorAll('input[name="options"]');

        const top7 = document.getElementById("top7");
        const description = document.getElementById("description");
        const argument1 = document.getElementById("argument-1");
        const argument2 = document.getElementById("argument-2");
        const argument3 = document.getElementById("argument-3");
        const argument4 = document.getElementById("argument-4");
        const argument5 = document.getElementById("argument-5");
        const argument6 = document.getElementById("argument-6");
        const argument7 = document.getElementById("argument-7");
        const result = document.getElementById("result");
        const result_desc = document.getElementById("result_desc");
        const price = document.getElementById("price");
        const productHref = document.getElementById("productHref");
        const productImage = document.getElementById("productImage");

        radios.forEach((radio) => {
            if (radio.value === "citizen") {
                radio.checked = true;

                radio.closest("div").classList.add("bg-yellow-400");
                radio.closest("div").classList.remove("bg-blue-400");
                radio.nextElementSibling.classList.remove("text-white");
                radio.nextElementSibling.classList.add("text-black");

                // Встановлюємо початкові значення для citizen
                const initialPrice = radio.dataset.price;
                const initialProductId = radio.dataset.productId;
                const initialImageUrl = radio.dataset.imageUrl;

                if (price) price.textContent = initialPrice;
                if (productHref) productHref.href = "product/" + initialProductId;
                if (productImage) productImage.src = initialImageUrl;

                console.log("Citizen selected by default");
            }

            radio.addEventListener("change", () => {
                const parentDiv = radio.closest("div");
                const label = radio.nextElementSibling;

                // Скидання стилів для всіх радіокнопок
                radios.forEach((r) => {
                    const rParentDiv = r.closest("div");
                    const rLabel = r.nextElementSibling;
                    if (rParentDiv) {
                        rParentDiv.classList.remove("bg-yellow-400");
                        rParentDiv.classList.add("bg-blue-400");
                    }
                    if (rLabel) {
                        rLabel.classList.remove("text-black");
                        rLabel.classList.add("text-white");
                    }
                });

                // Оновлення тексту та стилів для вибраної радіокнопки
                if (radio.checked) {
                    if (parentDiv) {
                        parentDiv.classList.add("bg-yellow-400");
                        parentDiv.classList.remove("bg-blue-400");
                    }
                    if (label) {
                        label.classList.add("text-black");
                        label.classList.remove("text-white");
                    }

                    const currentPrice = radio.dataset.price;
                    const productId = radio.dataset.productId;
                    const imageUrl = radio.dataset.imageUrl;

                    // Оновлення ціни
                    if (price) price.textContent = currentPrice;

                    // Оновлення href для посилання
                    if (productHref) {
                        productHref.href = "product/" + productId;
                    }

                    // Оновлення картинки
                    if (productImage) {
                        productImage.src = imageUrl;
                    }

                    // Зміна тексту залежно від вибраної опції
                    switch (radio.value) {
                        case "citizen":
                            if (top7) top7.textContent = "ТОП-7 Переваг знання законів та прав людини";
                            if (description) description.textContent = "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:";
                            if (argument1) argument1.textContent = "Правовий захист у будь-якій ситуації";
                            if (argument2) argument2.textContent = "Гарантована свобода та недоторканність";
                            if (argument3) argument3.textContent = "Можливість законного самозахисту";
                            if (argument4) argument4.textContent = "Контроль над державою, а не навпаки";
                            if (argument5) argument5.textContent = "Неможливість маніпуляцій";
                            if (argument6) argument6.textContent = "Готовність до міжнародного захисту";
                            if (argument7) argument7.textContent = "Повага і впевненість";
                            if (result) result.textContent = "Результат: ";
                            if (result_desc) result_desc.textContent = "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.";
                            break;
                        case "military":
                            top7.textContent =
                                "ТОП-7 переваг знання Конституції України, Статутів ЗСУ, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців";
                            description.textContent =
                                "Знання Конституції України, Статутів Збройних Сил України, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців є важливим не лише для виконання службових обов’язків, але й для захисту власних прав, підвищення професійної компетентності та ефективного виконання завдань у складних умовах. Ці знання допомагають військовослужбовцям діяти законно, свідомо та впевнено. Ось сім ключових переваг:";
                            argument1.textContent =
                                "Законність у дії — виконуєш завдання, чітко розуміючи межі дозволеного й уникаючи кримінальної відповідальності.";
                            argument2.textContent =
                                "Право відхилити злочинний наказ — аргументовано відмовляєшся від розпоряджень, що суперечать Статутам або МГП.";
                            argument3.textContent =
                                "Гарантовані виплати й пільги — знаєш процедури оформлення компенсацій, доплат і соціальних пакетів.";
                            argument4.textContent =
                                "Правильне застосування зброї — знання Закону «Про оборону України» допомагає визначити правовий режим воєнного стану, законні підстави відкриття вогню та мінімізувати юридичні й репутаційні ризики.";
                            argument5.textContent =
                                "Коректне діловодство — грамотно складаєш рапорти, скарги та заяви, знижуючи шанс дисциплінарних стягнень.";
                            argument6.textContent =
                                "Сильна командна дисципліна — правова впевненість підсилює згуртованість і бойовий дух підрозділу.";
                            argument7.textContent =
                                "Повага й авторитет — обізнаність у своїх правах і обов’язках зміцнює репутацію серед побратимів та суспільства.";
                            result.textContent = "Результат: ";
                            result_desc.textContent =
                                "ти — захищений і обізнаний військовослужбовець, що впевнено відстоює свої права та професійно виконує обов’язки.";
                            break;
                        case "policeman":
                            top7.textContent =
                                "ТОП-7 переваг підготовленого поліцейського";
                            description.textContent =
                                "Глибоке опанування цих законів надає поліцейському потужний юридичний щит, що водночас зміцнює впевненість у кожному правомірному рішенні, мінімізує ризик дисциплінарних помилок і відкриває ширші кар’єрні горизонти — від підвищення в званні до участі в спеціалізованих підрозділах та міжнародних місіях.";
                            argument1.textContent =
                                "Юридична бездоганність — ухвалює рішення, що легко витримують будь-який судовий контроль.";
                            argument2.textContent =
                                "Захист прав людини — дотримується процедур і не допускає порушень при затриманнях та слідчих діях.";
                            argument3.textContent =
                                "Довіра суспільства — професійно пояснює права й свої кроки, зміцнюючи партнерство з громадою.";
                            argument4.textContent =
                                "Антикорупційна стійкість — уникає конфлікту інтересів і законно відмовляється від небажаних «подяк».";
                            argument5.textContent =
                                "Швидке розслідування — чітко планує слідчі дії та оформлює докази, скорочуючи шлях до вироку.";
                            argument6.textContent =
                                "Кар’єрні перспективи — правова ерудиція відкриває двері до спецпідрозділів, викладання й міжнародних місій.";
                            argument7.textContent =
                                "Стійкість у кризах — впевнено діє під тиском, захищаючи себе й підрозділ від правових помилок.";
                            result.textContent = "Результат: ";
                            result_desc.textContent =
                                "Результат: ти — захищений і обізнаний поліцейський, що впевнено відстоює свої права та професійно виконує обов’язки.";
                            break;
                        case "lawyer":
                            top7.textContent =
                                "ТОП-7 Переваг знання законів та прав людини";
                            description.textContent =
                                "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:";
                            argument1.textContent =
                                "Правовий захист у будь-якій ситуації";
                            argument2.textContent =
                                "Гарантована свобода та недоторканність";
                            argument3.textContent = "Можливість законного самозахисту";
                            argument4.textContent =
                                "Контроль над державою, а не навпаки";
                            argument5.textContent = "Неможливість маніпуляцій";
                            argument6.textContent =
                                "Готовність до міжнародного захисту";
                            argument7.textContent = "Повага і впевненість";
                            result.textContent = "Результат: ";
                            result_desc.textContent =
                                "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.";
                            break;
                    }
                }
            });
        });

        radios.forEach((radio) => {
            radio.addEventListener("change", async () => {
                // Збираємо всі вибрані checkbox
                const selectedOptions = Array.from(
                    document.querySelectorAll('input[name="options"]:checked')
                ).map((cb) => cb.value);

                try {
                    const response = await fetch(`/product/${radio.value}`, {
                        method: "POST",
                        headers: { "Content-Type": "application/json" },
                        body: JSON.stringify({ options: selectedOptions }),
                    });
                    const result = await response.json();
                    console.log("Відповідь від бекенду:", result);
                } catch (error) {
                    console.error("Помилка:", error);
                }
            });
        });

        const buttons = document.querySelectorAll(".slide_button");

        // Налаштовуємо MutationObserver для відстеження змін атрибутів
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                const button = mutation.target; // Елемент, у якого змінився атрибут
                if (button.getAttribute("aria-current") === "true") {
                    button.style.backgroundColor = "yellow"; // Змінюємо колір на жовтий
                } else {
                    button.style.backgroundColor = "blue"; // Повертаємо синій, якщо атрибут не "true"
                }
            });
        });

        // Налаштовуємо параметри observer
        const observerConfig = {
            attributes: true, // Відстежуємо зміни атрибутів
            attributeFilter: ["aria-current"], // Відстежуємо лише атрибут aria-current
        };

        // Застосовуємо observer до кожної кнопки та встановлюємо початковий колір
        buttons.forEach((button) => {
            button.style.backgroundColor = "blue"; // Встановлюємо початковий синій колір
            observer.observe(button, observerConfig); // Починаємо відстежувати зміни
        });

    </script>

@endsection
@section('footer')
    @include('components.footer')
@endsection


