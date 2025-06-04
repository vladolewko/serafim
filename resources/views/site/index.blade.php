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

<div class="flex my-24 w-4/6 mx-auto justify-between">
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
            <div class="flex items-center gap-3 bg-yellow-400 rounded-lg px-2 py-2">
                <input checked id="checkbox-1" class="w-4 h-4 cursor-pointer rounded-md text-blue-600 bg-white" type="checkbox">
                <label class="cursor-pointer" for="checkbox-1">Громадянину</label>
            </div>

            <div class="flex items-center gap-3 bg-blue-400 rounded-lg px-2 py-2">
                <input id="checkbox-2" class="w-4 h-4 cursor-pointer rounded-md text-blue-600 bg-white" type="checkbox">
                <label class="cursor-pointer text-white" for="checkbox-2">Військовослужбовцю</label>
            </div>

            <div class="flex items-center gap-3 bg-blue-400 rounded-lg px-2 py-2">
                <input id="checkbox-3" class="w-4 h-4 cursor-pointer rounded-md text-blue-600 bg-white" type="checkbox">
                <label class="cursor-pointer text-white" for="checkbox-3">Поліцейському</label>
            </div>

            <div class="flex items-center gap-3 bg-blue-400 rounded-lg px-2 py-2">
                <input id="checkbox-4" class="w-4 h-4 cursor-pointer rounded-md text-blue-600 bg-white" type="checkbox">
                <label class="cursor-pointer text-white" for="checkbox-4">Юристу</label>
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

<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
@endsection
