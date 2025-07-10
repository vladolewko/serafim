@extends('layouts.site')
@section('header')
    @include('components.header')
    <hr class="bg-white border-0 h-px mx-auto w-full">
    <div class="w-full bg-blue-400">
        <div class="text-white w-11/12 mx-auto lg:hidden py-2  md:w-3/5">
            <p class="text-left text-sm font-bold flex-1">
                Стаття 68 Конституції України:
            </p>
            <p class="text-[9.72px]/[1] font-bold md:text-xs">
                Кожен зобов'язаний неухильно додержуватися Конституції
                України та законів України, не посягати на права і свободи, честь і гідність інших людей.
            </p>
        </div>
    </div>

@endsection

@section('content')
            <div id="introduction">
            <div class="lg:hidden mb-16"> @include('components.introduction-mobile') </div>

            <div class="hidden lg:block"> @include('components.introduction') </div>
            </div>

            <div class="lg:hidden">
                @include('components.goverment_agencies-mobile')
            </div>
            <div class="hidden lg:block">
                @include('components.goverment_agencies')
            </div>




            <!-- Why Important Section -->
             <div id="why_important"></div>
             @include('components.whyimportant')


            <!-- Knowledge Pack Section -->
             <div id="wt_knowledge_pack">
                @include('components.knowledgepack')
             </div>


            <!-- How to Submit Section Desktop -->
            <div id="how_to_submit"></div>
            <div class="hidden lg:block" >
                @include('components.howtosubmit')
            </div>

            <!-- How to Submit Section Mobile -->
            <div class="lg:hidden my-32">
                @include('components.howtosubmit-mobile')
            </div>

            <!-- Choose Knowledge Pack Selection Desktop -->
             <div id="knowledge_pack"></div>
            <div class="lg:hidden my-32" id="knowledge_pack">
                @include('components.chooseknoweledge-mobile')
            </div>


            <div class="hidden lg:block">
                @include('components.chooseknoweledge')
            </div>

            <!-- About Author Section -->
             <div id="about_author"></div>
            <div class="lg:hidden my-32">
                @include('components.aboutauthor-mobile')
            </div>

            <div class="hidden lg:block">
                @include('components.aboutauthor')
            </div>

            <!-- Why Created Section -->
            <div id="why_created"></div>
            <div class="lg:hidden my-32">
                @include('components.whycreated-mobile')
            </div>

            <div class="hidden lg:block">
                @include('components.whycreated')
            </div>

            <!-- Секція безкоштовної допомоги -->
             <div id="for_free"></div>
            <div class="lg:hidden my-32" >
                @include('components.forfree-mobile')
            </div>

            <div class="hidden lg:block">
                @include('components.forfree')
            </div>


            <!-- Секція соцмереж -->
             <div id="social"></div>
            <div class="lg:hidden my-32" >
                @include('components.socialnetwork-mobile')
            </div>

            <div class="hidden lg:block">
                @include('components.socialnetwork')
            </div>

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



<div id="hidden_introduction_btn" class=" fixed mx-auto lg:left-auto lg:right-[20px] left-0 right-0 w-11/12 lg:w-min bottom-5 flex flex-col md:flex-row lg:flex-col gap-4 md:gap-0 lg:gap-4 z-40">
    <button
        class="w-full bg-yellow-400 px-6 py-1 rounded-lg text-black flex items-center justify-center text-xl lg:text-xl  self-center knowledgePackBtn" data-target="why_important">
        <div class="flex items-center justify-between w-[300px]">
            <img class="" src="{{ asset('img/icon_book.png') }}" alt="">
            <span class="mx-auto ">комплекти знань</span>
        </div>
    </button>

    <a href="https://nadiya.serafym.info" class="bg-white text-black border border-blue-400 rounded-lg w-full px-6 py-1  flex items-center justify-center text-center text-lg lg:text-xl self-center sm:mx-4 knowledgePackBtn">
            <div class="flex items-center justify-between w-[300px]">
                <img src="{{ asset('img/icon_youtube.png') }}" alt="">
                <span class="mx-auto">відеоуроки</span>
        </div>
    </a>
</div>


<div id="termsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Terms of Use</h2>
                <button class="close" data-modal="termsModal">&times;</button>
            </div>
            <div class="modal-body">
                <p>Цей документ є офіційною публічною пропозицією (далі по тексту - Договір, Оферта) про надання інформаційних послуг та продаж товарів через веб-сайт serafym.info, що проводиться приватним підприємцем ФОП Моренець О.М, ФОП Моренець Є.Б, іменованим надалі «Виконавець» (далі по тексту також - Продавець) і споживачем послуг та товарів, що має надалі назву «Замовник» (далі по тексту також - Покупець), який прийняв (акцептував) публічну пропозицію (оферту) про укладення даного Договору на нижче викладених умовах:</p>
                <h3>1. ТЕРМІНИ</h3>
                <p><span style="font-weight:600">1.1.</span> Послуги - комплекс інформаційних, консультаційних та освітніх послуг Виконавця, включаючи надання доступу до відеоуроків, консультацій та інших освітніх матеріалів.</p>
                <p><span style="font-weight:600">1.1.1.</span> Товари - фізичні товари, що реалізуються через Сайт Виконавця, включаючи "комплекти знань" - набори друкованих книг та інші освітні матеріали на фізичних носіях.</p>

                <p><span style="font-weight:600">1.2. </span>Публічна оферта - пропозиція Виконавця (викладена на Сайті Виконавця), адресована необмеженому колу суб'єктів, укласти даний Договір на визначених умовах.</p>
                <p><span style="font-weight:600">1.3. </span>Акцепт - повне, безумовне та беззастережне прийняття Замовником умов Публічної оферти даного Договору.</p>
                <p><span style="font-weight:600">1.4. </span>Замовник - фізична особа, досягнувша 18 років, яка прийняла всі умови цього Договору і уклала цей Договір з Виконавцем на умовах даної оферти.</p>
                <p><span style="font-weight:600">1.5. </span>Виконавець - приватний підприємець ФОП Моренець О.М, ФОП Моренець Є.Б</p>
                <p><span style="font-weight:600">1.6. </span>Сторони - Замовник та Виконавець.</p>
                <p><span style="font-weight:600">1.7. </span>Правила надання відповідних Послуг - умови надання Послуг, які є невід'ємною частиною цього Договору та є єдиним джерелом врегулювання всіх відносин між Замовником та Виконавцем.</p>
                <p><span style="font-weight:600">1.8. </span>Заявка - намір Замовника скористатися послугами Виконавця, виражене в заповненні форми зворотного зв'язку на Сайті Виконавця.</p>
                <p><span style="font-weight:600">1.9. </span>Технології дистанційного надання інформаційних послуг - надання інформаційних послуг дистанційно з використанням мережі Інтернет.</p>
                <p><span style="font-weight:600">1.10.</span> Сайт Виконавця - веб-сторінка в мережі Інтернет за адресою https://serafym.info/, яка є офіційним джерелом інформування Замовника про Виконавця та послуги, що ним надаються.</p>

                <h3>2. ПРЕДМЕТ ДОГОВОРУ ТА ОПЛАТА ПОСЛУГ</h3>
                <p><span style="font-weight:600">2.1.</span> Відповідно до умов цього Договору, Виконавець зобов'язується:</p>
                <ul>
                    <li>надати Замовнику інформаційні послуги через веб-сайт serafym.info</li>
                    <li>продати та доставити Замовнику замовлені Товари Замовник зобов'язується прийняти запитані послуги/товари та оплатити їх згідно з умовами, розміщеними на сайті.</li>
                </ul>

                <p><span style="font-weight:600">2.2.</span> Асортимент Товарів та Послуг включає:</p>

                <ul>
                    <li><span style="font-weight:600">Послуги: </span>відеоуроки, онлайн-консультації, доступ до освітніх матеріалів, інформаційні послуги</li>
                    <li><span style="font-weight:600">Товари: </span>"комплекти знань" (набори друкованих книг), освітні матеріали на фізичних носіях, інші фізичні товари</li>
                </ul>

                <p><span style="font-weight:600">2.3.</span> Вартість і докладний опис Послуг та Товарів опубліковані офіційно на сайті <span style="font-weight:600">https://serafym.info/</span> і є додатками до цієї Оферти.</p>
                <p><span style="font-weight:600">2.4.</span> Цей Договір, а також всі зміни та доповнення до цього Договору є відкритими документами і опубліковані для загального відома на Сайті Виконавця.</p>

                <h3>3. АКЦЕПТ ОФЕРТИ</h3>
                <p><span style="font-weight:600">3.1.</sapn> Укладення договору на надання Послуг проводиться шляхом Акцепту Оферти на викладених в ньому умовах.</p>
                <p><span style="font-weight:600">3.2.</sapn> Акцептом оферти вважається:</p>
                <ul>
                    <li>Використання безкоштовних послуг сайту</li>
                    <li>Оплата Послуг або Товарів і отримання Виконавцем відповідного платіжного документа від Замовника</li>
                    <li>Заповнення форми замовлення на сайті</li>
                    <li>Заповнення форми зворотного зв'язку на сайті</li>
                </ul>

                <p><span style="font-weight:600">3.3.</span> Здійснюючи акцепт цього Договору, Замовник гарантує, що ознайомлений, погоджується, повністю і беззастережно приймає всі умови Договору.</p>
                <p><span style="font-weight:600">3.4.</span> Цей Договір не вимагає скріплення печатками і (або) підписання Сторонами, зберігаючи при цьому повну юридичну силу.</p>

                <h3>4. УМОВИ І ПОРЯДОК НАДАННЯ ПОСЛУГ</h3>

                <p><span style="font-weight:600">4.1.</span> Послуги надаються Замовнику дистанційно через веб-сайт <span style="font-weight:600">https://serafym.info/</span> у вигляді:</p>
                <ul>
                    <li>Відеоуроків та онлайн-курсів</li>
                    <li>Індивідуальних консультацій</li>
                    <li>Інформаційних статей та матеріалів</li>
                    <li>Освітніх матеріалів</li>
                    <li>Інших інформаційних послуг</li>
                </ul>

                <p><span style="font-weight:600">4.1.1. Відеоуроки</span> надаються через Телеграм-бота. Для отримання доступу до відеоуроків Замовник повинен:</p>
                <ul>
                    <li>Мати діючий акаунт у месенджері Telegram</li>
                    <li>Пройти авторизацію в Телеграм-боті згідно з інструкціями</li>
                    <li>Дотримуватися правил користування платформою Telegram</li>
                </ul>

                <p><span style="font-weight:600">4.1.2.</span> Товари продаються та доставляються Замовнику відповідно до умов доставки, зазначених на сайті. Товари включають:</p>
                <ul>
                    <li>"Комплекти знань" - набори друкованих книг</li>
                    <li>Освітні матеріали на фізичних носіях</li>
                    <li>Інші фізичні товари, представлені в каталозі сайту</li>
                </ul>

                <p><span style="font-weight:600">4.2.</span> При намірі скористатися послугами або придбати товари Виконавця Замовник може заповнити і відправити на Сайті Виконавця відповідну форму замовлення або зворотного зв'язку із зазначенням достовірних персональних даних та інформації про доставку (для товарів).</p>
                <p><span style="font-weight:600">4.3.</span> Замовник починає отримувати надавані Виконавцем Послуги після звернення до сайту або внесення оплати. Товари передаються Замовнику після оплати та відповідно до умов доставки.</p>

                <p style="font-weight:600">4.4. Умови доставки товарів:</p>
                <ul>
                    <li>Доставка здійснюється службами кур'єрської доставки або поштою України</li>
                    <li>Вартість доставки зазначається окремо при оформленні замовлення</li>
                    <li>Строки доставки залежать від регіону та способу доставки</li>
                    <li>Ризик випадкового пошкодження або втрати товару переходить на Замовника з моменту передачі товару службі доставки</li>
                </ul>

                <p><span style="font-weight:600">4.5.</span> Послуги вважаються наданими належним чином і в повному обсязі за Договором з моменту отримання Замовником доступу до інформаційних матеріалів. Товари вважаються переданими з моменту їх отримання Замовником або уповноваженою ним особою.</p>
                <p><span style="font-weight:600">4.6.</span> Будь-які матеріали, отримані Замовником через сайт, відеоуроки або у складі фізичних товарів, призначені для приватного некомерційного використання. Замовник не має права копіювати, передавати, перепродувати матеріали або товари без письмового дозволу Виконавця.</p>


                <h3>5. ПРАВА ТА ОБОВ'ЯЗКИ СТОРІН</h3>

                <p style="font-weight:600">5.1. Замовник має право:</p>
                <p><span style="font-weight:600">5.1.1.</span> Отримувати від Виконавця інформацію з питань організації та забезпечення належного надання послуг та доставки товарів.</p>
                <p><span style="font-weight:600">5.1.2.</span> Вимагати належного і своєчасного надання Послуг та доставки Товарів Виконавцем.</p>
                <p><span style="font-weight:600">5.1.3.</span> Звертатися до Виконавця в письмовій формі з усіх питань, пов'язаних з наданням Послуг або продажем Товарів.</p>
                <p><span style="font-weight:600">5.1.4.</span> Відмовитися від Товару до моменту його передачі, а після передачі - протягом 14 днів, якщо Товар не був у використанні та збережений його товарний вигляд (відповідно до Закону України "Про захист прав споживачів").</p>

                <p style="font-weight:600">5.2. Замовник зобов'язується:</p>
                <p><span style="font-weight:600">5.2.1.</span> Вказувати достовірні персональні дані при зверненні до Виконавця.</p>
                <p><span style="font-weight:600">5.2.2.</span> Не використовувати інформацію, отриману від Виконавця способами, здатними привести до нанесення збитку інтересам Виконавця.</p>
                <p><span style="font-weight:600">5.2.3.</span> Не порушувати авторські права Виконавця щодо матеріалів сайту, відеоуроків та друкованих матеріалів.</p>
                <p><span style="font-weight:600">5.2.4.</span> Надавати точну інформацію для доставки товарів та своєчасно їх отримувати.</p>

                <p style="font-weight:600">5.3. Виконавець має право:</p>
                <p><span style="font-weight:600">5.3.1.</span> Включити Замовника в список поштової розсилки для подальшого поширення інформаційних матеріалів.</p>
                <p><span style="font-weight:600">5.3.2.</span> Припинити надання послуг або відмовитися від продажу товарів Замовнику в разі порушення Замовником своїх зобов'язань відповідно до цього Договору.</p>
                <p><span style="font-weight:600">5.3.3.</span> Змінювати вартість Послуг та Товарів, а також інші умови даної публічної Оферти, забезпечуючи при цьому публікацію змінених умов на сайті не менше ніж за 1 день до їх введення в дію.</p>

                <p style="font-weight:600">5.4. Виконавець зобов'язується:</p>
                <p><span style="font-weight:600">5.4.1.</span> Надавати інформаційні послуги та доставляти товари в повному обсязі і в строк згідно з умовами Договору.</p>
                <p><span style="font-weight:600">5.4.2.</span> Інформувати Замовника про всі зміни в умовах надання послуг та продажу товарів.</p>
                <p><span style="font-weight:600">5.4.3.</span> Забезпечувати належну якість товарів та їх збереження до моменту передачі Замовнику.</p>
                <p><span style="font-weight:600">5.4.4.</span> Використовувати всі особисті дані і іншу конфіденційну інформацію про Замовника тільки для надання Послуг та доставки Товарів, не передавати і не розголошувати третім особам.</p>
                <p><span style="font-weight:600">5.4.5. Видача фіскальних документів:</span> Виконавець зобов'язується надавати Замовнику документи, що підтверджують оплату, на свій вибір, одним з наступних способів:</p>

                <ul>
                    <li>Електронний чек через програмний реєстратор розрахункових операцій (ПРРО)</li>
                    <li>QR-код, що надається на дисплей пристрою або надсилається на електронну пошту/телефон</li>
                    <li>Друкований чек при особистому отриманні товару</li>
                    <li>Електронний розрахунковий документ, надісланий на вказану Замовником електронну адресу</li>
                    <li>Інші законодавчо передбачені способи документування розрахунків</li>
                </ul>
                <p>Конкретний спосіб видачі фіскального документа визначається технічними можливостями та обирається Виконавцем відповідно до вимог податкового законодавства України.</p>

                <h3>6. УМОВИ ПОВЕРНЕННЯ КОШТІВ ТА ОБМІНУ ТОВАРІВ</h3>
                <p style="font-weight:600">6.1. Повернення коштів за послуги допускається у наступних випадках:</p>
                <ul>
                    <li>Якщо протягом 7 календарних днів з моменту оплати замовник направить виконавцю заяву з вимогою повернути оплачену суму</li>
                    <li>Внаслідок неможливості надання послуг з технічних причин</li>
                </ul>

                <p style="font-weight:600">6.2. Повернення товарів та послуг здійснюється відповідно до Закону України "Про захист прав споживачів":</p>

                <p style="font-weight:600">Фізичні товари (книги):</p>
                <ul>
                    <li>Замовник має право відмовитися від товару до моменту його передачі</li>
                    <li>Книги та друковані видання належної якості <span style="font-weight:600">НЕ ПІДЛЯГАЮТЬ поверненню</span> після отримання (згідно з Постановою КМУ № 172 від 19.03.1994 та новою Постановою КМУ № 1243 від 01.11.2024)</li>
                    <li>У разі виявлення браку або неналежної якості - повернення здійснюється протягом гарантійного терміну</li>
                </ul>

                <p style="font-weight:600">Відеоуроки (цифрові послуги):</p>
                <ul>
                    <li>Повернення коштів за відеоуроки можливе протягом <span style="font-weight:600">14 днів</span> з моменту надання доступу</li>
                    <li>Умова: доступ до відеоуроків не був активований або використаний</li>
                    <li>Після активації доступу та перегляду матеріалів повернення не здійснюється</li>
                </ul>

                <p style="font-weight:600">Інші послуги:</p>
                <ul>
                    <li>Повернення за консультації та інші послуги - протягом 7 днів з моменту оплати при відсутності фактичного надання послуг</li>
                </ul>

                <p><span style="font-weight:600">6.3.</span> У разі відмови від Послуг або повернення Товарів Замовник направляє Виконавцю заяву на електронну пошту з вимогою повернути сплачену суму.</p>
                <p><span style="font-weight:600">6.4.</span> Повернення грошових коштів здійснюється не пізніше 10 робочих днів з моменту пред'явлення вимоги. Витрати на доставку товару при поверненні несе Замовник.</p>

                <h3>7. ВІДПОВІДАЛЬНІСТЬ СТОРІН</h3>
                <p><span style="font-weight:600">7.1.</span> За невиконання або неналежне виконання зобов'язань за цим Договором Сторони несуть відповідальність відповідно до Договору та законодавства України.</p>
                <p><span style="font-weight:600">7.2.</span> Замовник розуміє, що Виконавець не несе відповідальності за розуміння Замовником інформаційних матеріалів або результати їх застосування. Всі ризики за наслідки застосування отриманої інформації в повній мірі несе Замовник. Виконавець гарантує якість товарів відповідно до законодавства України.</p>
                <p><span style="font-weight:600">7.3.</span> Виконавець звільняється від відповідальності за невиконання Договору в разі технічних збоїв, що знаходяться за межами контролю сайту <span style="font-weight:600">serafym.info</span>, а також за затримки доставки товарів з вини служб доставки.</p>


                <h3>8. ОБСТАВИНИ НЕПЕРЕБОРНОЇ СИЛИ</h3>
                <p><span style="font-weight:600">8.1.</span> Сторони звільняються від відповідальності за невиконання зобов'язань, якщо це стало наслідком обставин непереборної сили: пожежа, стихійне лихо, війна, цивільні хвилювання, прийняття органами влади актів, що перешкоджають виконанню умов Договору.</p>
                <p><span style="font-weight:600">8.2.</span> Сторона, яка потрапила під дії обставин непереборної сили, зобов'язана протягом 3 робочих днів повідомити іншу Сторону про настання таких обставин.</p>

                <h3>9. ІНШІ УМОВИ</h3>
                <p><span style="font-weight:600">9.1.</span> Виконавець залишає за собою право внести зміни в умови Оферти в будь-який момент. Зміни вступають в силу з моменту розміщення на сайті.</p>
                <p><span style="font-weight:600">9.2.</span> Претензійний порядок вирішення спорів обов'язковий. Термін відповіді на претензію - 30 календарних днів.</p>
                <p><span style="font-weight:600">9.3.</span> Спори вирішуються в суді за місцем знаходження Виконавця відповідно до законодавства України.</p>
                <p><span style="font-weight:600">9.4.</span> Цей Договір припиняє свою дію після повного виконання Сторонами своїх зобов'язань.</p>

                <h3>10. РЕКВІЗИТИ ВИКОНАВЦЯ</h3>
                <p style="margin:0;"><span style="font-weight:600">ПІБ: </span>ФОП Моренець О.М.</p>
                <p style="margin:0;"><span style="font-weight:600">Код  </span>ЄДРПОУ/РНОКПП: 3063514340</p>
                <p style="margin:0;"><span style="font-weight:600">Адреса: </span> м.Київ Проспект Степана Бандери, 6</p>
                <p style="margin:0;"><span style="font-weight:600">Телефон: </span> 0638782169</p>
                <p style="margin:0;"><span style="font-weight:600">Email: </span> serafim.nation@gmail.com</p>
                <p style="margin:0;"><span style="font-weight:600">Сайт: </span> https://serafym.info/</p>
            </div>
        </div>
    </div>

    <!-- Модальне вікно для Privacy Policy -->
    <div id="policyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Privacy Policy</h2>
                <button class="close" data-modal="policyModal">&times;</button>
            </div>
            <div class="modal-body">
                <p><strong>serafym.info</strong></p>
                <p><em>Дата останнього оновлення: [дата]</em></p>

                <p>Ця Політика конфіденційності описує, як веб-сайт <strong>serafym.info</strong> (далі - "Сайт", "ми", "нас") збирає, використовує, зберігає та захищає вашу особисту інформацію відповідно до Закону України "Про захист персональних даних" та Регламенту ЄС GDPR.</p>

                <h3>1. ЗАГАЛЬНІ ПОЛОЖЕННЯ</h3>
                <p><strong>1.2.</strong> Використовуючи наш Сайт, ви підтверджуєте свою згоду з цією Політикою конфіденційності.</p>
                <p><strong>1.3.</strong> Ми поважаємо вашу приватність і зобов'язуємося захищати ваші персональні дані.</p>

                <h3>2. ЯКІ ДАНІ МИ ЗБИРАЄМО</h3>

                <p style="font-weight:600">2.1. Дані, що надаються добровільно:</p>
                <ul>
                    <li><strong>Контактна інформація:</strong> ім'я, прізвище, електронна пошта, номер телефону</li>
                    <li><strong>Адреса доставки:</strong> для відправки фізичних товарів</li>
                    <li><strong>Платіжна інформація:</strong> дані для обробки платежів (не зберігаються на наших серверах)</li>
                    <li><strong>Telegram-дані:</strong> username або ID для надання доступу до відеоуроків</li>
                    <li><strong>Повідомлення:</strong> зміст ваших звернень через форми зворотного зв'язку</li>
                </ul>

                <p style="font-weight:600">2.2. Дані, що збираються автоматично:</p>
                <ul>
                    <li><strong>Технічна інформація:</strong> IP-адреса, тип браузера, операційна система</li>
                    <li><strong>Дані про поведінку:</strong> сторінки, які ви відвідуєте, час перебування на сайті</li>
                    <li><strong>Cookies та подібні технології:</strong> для покращення роботи сайту</li>
                    <li><strong>Дані аналітики:</strong> через Google Analytics (анонімізовано)</li>
                </ul>

                <p style="font-weight:600">2.3. Дані з третіх джерел:</p>
                <ul>
                    <li><strong>Платіжні системи:</strong> підтвердження успішної оплати</li>
                    <li><strong>Служби доставки:</strong> статус доставки замовлень</li>
                    <li><strong>Telegram API:</strong> для надання доступу до освітніх матеріалів</li>
                </ul>

                <div class="section-divider"></div>

                <h3>3. ДЛЯ ЧОГО МИ ВИКОРИСТОВУЄМО ВАШІ ДАНІ</h3>

                <p style="font-weight:600">3.1. Надання послуг:</p>
                <ul>
                    <li>Обробка та виконання замовлень</li>
                    <li>Доставка фізичних товарів</li>
                    <li>Надання доступу до відеоуроків через Telegram</li>
                    <li>Проведення консультацій</li>
                    <li>Технічна підтримка</li>
                </ul>

                <p style="font-weight:600">3.2. Комунікації:</p>
                <ul>
                    <li>Відповіді на ваші запити</li>
                    <li>Інформування про статус замовлення</li>
                    <li>Розсилка корисних матеріалів (за згодою)</li>
                    <li>Повідомлення про зміни в послугах</li>
                </ul>

                <p style="font-weight:600">3.3. Покращення сервісу:</p>
                <ul>
                    <li>Аналіз використання сайту</li>
                    <li>Персоналізація контенту</li>
                    <li>Розробка нових продуктів</li>
                    <li>Виправлення технічних помилок</li>
                </ul>

                <p style="font-weight:600">3.4. Правові вимоги:</p>
                <ul>
                    <li>Дотримання податкового законодавства</li>
                    <li>Виконання договірних зобов'язань</li>
                    <li>Захист наших законних інтересів</li>
                </ul>

                <div class="section-divider"></div>

                <h3>4. ПРАВОВІ ПІДСТАВИ ОБРОБКИ</h3>
                <p>Ми обробляємо ваші персональні дані на підставі:</p>
                <ul>
                    <li><strong>4.1. Згоди</strong> - для маркетингових розсилок, використання cookies</li>
                    <li><strong>4.2. Виконання договору</strong> - для надання замовлених послуг/товарів</li>
                    <li><strong>4.3. Законних інтересів</strong> - для аналітики, безпеки, покращення сервісу</li>
                    <li><strong>4.4. Правових вимог</strong> - для податкової звітності, захисту прав</li>
                </ul>

                <h3>5. РОЗКРИТТЯ ДАНИХ ТРЕТІМ ОСОБАМ</h3>

                <p style="font-weight:600">5.1. Ми можемо передавати ваші дані:</p>
                <ul>
                    <li><strong>Платіжним системам</strong> - для обробки платежів</li>
                    <li><strong>Службам доставки</strong> - для відправки товарів</li>
                    <li><strong>Telegram</strong> - для надання доступу до освітніх матеріалів</li>
                    <li><strong>Податковим органам</strong> - відповідно до законодавства</li>
                    <li><strong>Постачальникам IT-послуг</strong> - для технічної підтримки</li>
                </ul>

                <h4>5.2. Ми НЕ продаємо та НЕ передаємо ваші дані:</h4>
                <ul>
                    <li>Рекламним компаніям</li>
                    <li>Брокерам даних</li>
                    <li>Іншим третім особам для їх маркетингових цілей</li>
                </ul>

                <h4>5.3. Всі треті особи зобов'язані:</h4>
                <ul>
                    <li>Забезпечувати належний рівень захисту даних</li>
                    <li>Використовувати дані лише для визначених цілей</li>
                    <li>Дотримуватися принципів конфіденційності</li>
                </ul>

                <div class="section-divider"></div>

                <h3>6. МІЖНАРОДНІ ПЕРЕДАЧІ ДАНИХ</h3>
                <p>6.1.Деякі ваші дані можуть оброблятися за межами України:</p>
                <ul>
                    <li><strong>Google Analytics</strong> (США) - для веб-аналітики</li>
                    <li><strong>Платіжні системи</strong> - для обробки платежів</li>
                    <li><strong>Telegram</strong> (різні юрисдикції) - для надання освітніх послуг</li>
                </ul>
                <p><strong>6.2.</strong> Всі міжнародні передачі здійснюються з належними гарантіями захисту відповідно до міжнародних стандартів.</p>

                <h3>7. ЗБЕРІГАННЯ ДАНИХ</h3>
                <p style="font-weight:600">7.1. Терміни зберігання:</p>
                <ul>
                    <li><strong>Контактні дані</strong> - до відкликання згоди або 3 роки після останньої взаємодії</li>
                    <li><strong>Дані замовлень</strong> - 5 років (відповідно до податкового законодавства)</li>
                    <li><strong>Технічні логи</strong> - 12 місяців</li>
                    <li><strong>Маркетингові дані</strong> - до відкликання згоди</li>
                </ul>
                <p><strong>7.2.</strong> Після закінчення терміну зберігання дані надійно видаляються або анонімізуються.</p>

                <h3>8. БЕЗПЕКА ДАНИХ</h3>

                <p style="font-weight:600">8.1. Технічні заходи:</p>
                <ul>
                    <li>SSL-шифрування для передачі даних</li>
                    <li>Захищені сервери з обмеженим доступом</li>
                    <li>Регулярне оновлення систем безпеки</li>
                    <li>Резервне копіювання з шифруванням</li>
                </ul>

                <p style="font-weight:600">8.2. Організаційні заходи:</p>
                <ul>
                    <li>Доступ до даних лише уповноважених осіб</li>
                    <li>Регулярне навчання персоналу</li>
                    <li>Політики безпеки та конфіденційності</li>
                    <li>Контроль доступу та аудит</li>
                </ul>

                <p style="font-weight:600">8.3. У разі порушення безпеки:</p>
                <ul>
                    <li>Негайне усунення загрози</li>
                    <li>Повідомлення відповідних органів</li>
                    <li>Інформування користувачів (при необхідності)</li>
                </ul>

                <div class="section-divider"></div>

                <h3>9. ВАШІ ПРАВА</h3>
                <p style="font-weight:600">9.1. Ви маєте право:</p>
                <ul>
                    <li><strong>Доступу</strong> - отримати інформацію про обробку ваших даних</li>
                    <li><strong>Виправлення</strong> - виправити неточні або неповні дані</li>
                    <li><strong>Видалення</strong> - вимагати видалення даних у певних випадках</li>
                    <li><strong>Обмеження</strong> - обмежити обробку в певних ситуаціях</li>
                    <li><strong>Портативності</strong> - отримати дані в структурованому форматі</li>
                    <li><strong>Заперечення</strong> - заперечити проти обробки з певних підстав</li>
                    <li><strong>Відкликання згоди</strong> - відкликати згоду в будь-який час</li>
                </ul>

                <p style="font-weight:600">9.2. Для реалізації прав звертайтеся:</p>
                <ul>
                    <li><strong>Email:</strong> [ваш email]</li>
                    <li><strong>Форма на сайті: </strong>[посилання]</li>
                    <li><strong>Поштова адреса: </strong>[ваша адреса]</li>
                </ul>

                <p><strong>Терміни розгляду:</strong> до 30 днів з дати отримання запиту.</p>

                <h3>10. COOKIES ТА ВЕБОРЕЗІННЯ</h3>

                <p style="font-weight:600">10.1. Ми використовуємо cookies для:</p>
                <ul>
                    <li><strong>Необхідних функцій</strong> - робота сайту, безпека, аутентифікація</li>
                    <li><strong>Аналітики</strong> - розуміння поведінки користувачів</li>
                    <li><strong>Маркетингу</strong> - персоналізація контенту (за згодою)</li>
                </ul>

                <p style="font-weight:600">10.2. Типи cookies:</p>
                <ul>
                    <li><strong>Сесійні</strong> - видаляються після закриття браузера</li>
                    <li><strong>Постійні</strong> - зберігаються протягом визначеного періоду</li>
                    <li><strong>Власні</strong> - встановлені нашим сайтом</li>
                    <li><strong>Третіх осіб</strong> - встановлені партнерами (Google Analytics)</li>
                </ul>

                <p style="font-weight:600">10.3. Ви можете:</p>
                <ul>
                    <li>Налаштувати cookies в браузері</li>
                    <li>Відкликати згоду на використання</li>
                    <li>Видалити існуючі cookies</li>
                </ul>

                <h3>11. ДІТИ ТА НЕПОВНОЛІТНІ</h3>
                <ul>
                    <li><strong>11.1.</strong> Наші послуги призначені для осіб віком від 18 років</li>
                    <li><strong>11.2.</strong> Ми свідомо не збираємо дані дітей до 16 років без згоди батьків</li>
                    <li><strong>11.3.</strong> Якщо ми дізнаємося про збір даних дитини без згоди, такі дані будуть негайно видалені</li>
                </ul>

                <h3>12. ЗМІНИ В ПОЛІТИЦІ</h3>
                <p><strong>12.1.</strong> Ми можемо оновлювати цю Політику для відображення змін у наших практиках або законодавстві.</p>

                <p><strong>12.2.</strong> Про суттєві зміни ми повідомимо:</p>
                <ul>
                    <li>Розміщенням оновленої версії на сайті</li>
                    <li>Електронною поштою (зареєстрованим користувачам)</li>
                    <li>Через повідомлення на сайті</li>
                </ul>

                <p><strong>12.3.</strong>Дата останнього оновлення завжди вказується на початку документа.</p>

                <div class="section-divider"></div>

                <h3>13. КОНТАКТНА ІНФОРМАЦІЯ</h3>
                <p>З питань щодо обробки персональних даних та цієї Політики звертайтеся:</p>
                <div class="contact-info">
                    <p style="margin:0"><strong>Контролер даних:</strong> Моренець Є.Б.</p>
                    <p style="margin:0"><strong>Email:</strong> serafim.nation@gmail.com</p>
                    <p style="margin:0"><strong>Телефон:</strong> 0638782169</p>
                    <p style="margin:0"><strong>Сайт:</strong> <a href="https://serafym.info/">https://serafym.info/</a></p>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')
    @include('components.footer')
@endsection


