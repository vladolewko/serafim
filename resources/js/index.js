document.addEventListener("DOMContentLoaded", function () {
    // Обробка всіх кліків з data-target (залишається без змін)
    document.addEventListener("click", function (e) {
        const targetId = e.target.getAttribute("data-target");
        if (!targetId) return;

        e.preventDefault();

        const targetElement = document.getElementById(targetId);
        if (!targetElement) return;

        const elementPosition = targetElement.getBoundingClientRect().top;
        const offsetPosition = elementPosition + window.pageYOffset - 80;

        window.scrollTo({
            top: offsetPosition,
            behavior: "smooth",
        });
    });

    // Обробка якорів з URL (залишається без змін)
    const hash = window.location.hash;
    if (hash) {
        setTimeout(() => {
            const targetElement = document.getElementById(hash.substring(1));
            if (targetElement) {
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - 80;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth",
                });
            }
        }, 100);
    }

    // Introduction block logic (залишається без змін)
    const introduction_block = document.querySelector("#introduction");
    const hidden_introduction_btn = document.querySelector("#hidden-introduction-btn");

    function checkIntroductionPosition() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        const isAtBottom = scrollTop + windowHeight >= documentHeight - 10;

        if (!introduction_block) return;

        const introduction_block_pos = introduction_block.offsetTop || 0;

        if (
            (introduction_block_pos < 0 ||
                introduction_block_pos > window.innerHeight) &&
            (isAtBottom === false || innerWidth >= 1025)
        ) {
            hidden_introduction_btn?.classList.remove("hidden");
        } else {
            hidden_introduction_btn?.classList.add("hidden");
        }
    }

    checkIntroductionPosition();

    let ticking = false;

    function updatePosition() {
        checkIntroductionPosition();
        ticking = false;
    }

    function requestTick() {
        if (!ticking) {
            requestAnimationFrame(updatePosition);
            ticking = true;
        }
    }

    window.addEventListener("scroll", requestTick, { passive: true });

    // ВИПРАВЛЕНА ЛОГІКА ДЛЯ РАДІОКНОПОК
    const radios = document.querySelectorAll('input[name="options"]');

    // Отримуємо всі елементи одразу
    const elements = {
        top7: document.getElementById("top7"),
        description: document.getElementById("description"),
        argument1: document.getElementById("argument-1"),
        argument2: document.getElementById("argument-2"),
        argument3: document.getElementById("argument-3"),
        argument4: document.getElementById("argument-4"),
        argument5: document.getElementById("argument-5"),
        argument6: document.getElementById("argument-6"),
        argument7: document.getElementById("argument-7"),
        result: document.getElementById("result"),
        result_desc: document.getElementById("result_desc"),
        price: document.getElementById("price"),
        productHref: document.getElementById("productHref"),
        productImage: document.getElementById("productImage"),
        onSaleSoonSticker: document.getElementById("onSaleSoonSticker")
    };

    // Контент для різних типів користувачів
    const contentData = {
        citizen: {
            top7: "ТОП-7 Переваг знання законів та прав людини",
            description: "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:",
            arguments: [
                "Правовий захист у будь-якій ситуації",
                "Гарантована свобода та недоторканність",
                "Можливість законного самозахисту",
                "Контроль над державою, а не навпаки",
                "Неможливість маніпуляцій",
                "Готовність до міжнародного захисту",
                "Повага і впевненість"
            ],
            result: "Результат: ",
            result_desc: "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити."
        },
        military: {
            top7: "ТОП-7 переваг знання Конституції України, Статутів ЗСУ, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців",
            description: "Знання Конституції України, Статутів Збройних Сил України, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців є важливим не лише для виконання службових обов'язків, але й для захисту власних прав, підвищення професійної компетентності та ефективного виконання завдань у складних умовах. Ці знання допомагають військовослужбовцям діяти законно, свідомо та впевнено. Ось сім ключових переваг:",
            arguments: [
                "Законність у дії — виконуєш завдання, чітко розуміючи межі дозволеного й уникаючи кримінальної відповідальності.",
                "Право відхилити злочинний наказ — аргументовано відмовляєшся від розпоряджень, що суперечать Статутам або МГП.",
                "Гарантовані виплати й пільги — знаєш процедури оформлення компенсацій, доплат і соціальних пакетів.",
                "Правильне застосування зброї — знання Закону «Про оборону України» допомагає визначити правовий режим воєнного стану, законні підстави відкриття вогню та мінімізувати юридичні й репутаційні ризики.",
                "Коректне діловодство — грамотно складаєш рапорти, скарги та заяви, знижуючи шанс дисциплінарних стягнень.",
                "Сильна командна дисципліна — правова впевненість підсилює згуртованість і бойовий дух підрозділу.",
                "Повага й авторитет — обізнаність у своїх правах і обов'язках зміцнює репутацію серед побратимів та суспільства."
            ],
            result: "Результат: ",
            result_desc: "ти — захищений і обізнаний військовослужбовець, що впевнено відстоює свої права та професійно виконує обов'язки."
        },
        policeman: {
            top7: "ТОП-7 переваг підготовленого поліцейського",
            description: "Глибоке опанування цих законів надає поліцейському потужний юридичний щит, що водночас зміцнює впевненість у кожному правомірному рішенні, мінімізує ризик дисциплінарних помилок і відкриває ширші кар'єрні горизонти — від підвищення в званні до участі в спеціалізованих підрозділах та міжнародних місіях.",
            arguments: [
                "Юридична бездоганність — ухвалює рішення, що легко витримують будь-який судовий контроль.",
                "Захист прав людини — дотримується процедур і не допускає порушень при затриманнях та слідчих діях.",
                "Довіра суспільства — професійно пояснює права й свої кроки, зміцнюючи партнерство з громадою.",
                "Антикорупційна стійкість — уникає конфлікту інтересів і законно відмовляється від небажаних «подяк».",
                "Швидке розслідування — чітко планує слідчі дії та оформлює докази, скорочуючи шлях до вироку.",
                "Кар'єрні перспективи — правова ерудиція відкриває двері до спецпідрозділів, викладання й міжнародних місій.",
                "Стійкість у кризах — впевнено діє під тиском, захищаючи себе й підрозділ від правових помилок."
            ],
            result: "Результат: ",
            result_desc: "Результат: ти — захищений і обізнаний поліцейський, що впевнено відстоює свої права та професійно виконує обов'язки."
        },
        lawyer: {
            top7: "ТОП-7 Переваг знання законів та прав людини",
            description: "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:",
            arguments: [
                "Правовий захист у будь-якій ситуації",
                "Гарантована свобода та недоторканність",
                "Можливість законного самозахисту",
                "Контроль над державою, а не навпаки",
                "Неможливість маніпуляцій",
                "Готовність до міжнародного захисту",
                "Повага і впевненість"
            ],
            result: "Результат: ",
            result_desc: "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити."
        }
    };

    // Функція для оновлення контенту
    function updateContent(radioValue) {
        const content = contentData[radioValue];
        if (!content) return;

        if (elements.top7) elements.top7.textContent = content.top7;
        if (elements.description) elements.description.textContent = content.description;
        if (elements.result) elements.result.textContent = content.result;
        if (elements.result_desc) elements.result_desc.textContent = content.result_desc;

        // Оновлюємо аргументи
        content.arguments.forEach((arg, index) => {
            const argElement = elements[`argument${index + 1}`];
            if (argElement) argElement.textContent = arg;
        });
    }

    // Функція для показу/приховування стікера
    function toggleOnSaleSoonSticker(isOnSaleSoon) {
        if (elements.onSaleSoonSticker) {
            if (isOnSaleSoon === '1' || isOnSaleSoon === 1) {
                elements.onSaleSoonSticker.classList.remove("hidden");
            } else {
                elements.onSaleSoonSticker.classList.add("hidden");
            }
        }
    }

    // ГОЛОВНА ФУНКЦІЯ - оновлення всього стану
    function updateRadioState() {
        radios.forEach((radio) => {
            const parentDiv = radio.closest(".radio-div");
            const label = radio.nextElementSibling;

            if (radio.checked) {
                // Стилі для вибраної радіокнопки
                radio.classList.remove("bg-white");
                radio.classList.add("bg-blue-400");

                if (parentDiv) {
                    parentDiv.classList.remove("bg-blue-400");
                    parentDiv.classList.add("bg-yellow-400");
                }

                if (label) {
                    label.classList.remove("text-white");
                    label.classList.add("text-black");
                }

                // Оновлюємо дані продукту
                const currentPrice = radio.dataset.price;
                const productId = radio.dataset.productId;
                const imageUrl = radio.dataset.imageUrl;
                const onSaleSoon = radio.dataset.onSaleSoon;

                if (elements.price) elements.price.textContent = currentPrice;
                if (elements.productHref) elements.productHref.href = "product/" + productId;
                if (elements.productImage) elements.productImage.src = imageUrl;

                toggleOnSaleSoonSticker(onSaleSoon);
                updateContent(radio.value);
            } else {
                // Стилі для не вибраних радіокнопок
                radio.classList.remove("bg-blue-400");
                radio.classList.add("bg-white");

                if (parentDiv) {
                    parentDiv.classList.remove("bg-yellow-400");
                    parentDiv.classList.add("bg-blue-400");
                }

                if (label) {
                    label.classList.remove("text-black");
                    label.classList.add("text-white");
                }
            }
        });
    }

    // Ініціалізація при завантаженні
    updateRadioState();

    // Обробник зміни радіокнопок
    radios.forEach((radio) => {
        radio.addEventListener("change", async () => {
            updateRadioState();

            // API запит (опціонально)
            try {
                const selectedOptions = Array.from(
                    document.querySelectorAll('input[name="options"]:checked')
                ).map((cb) => cb.value);

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

    // Обробка кнопок слайдера (залишається без змін)
    const buttons = document.querySelectorAll(".slide_button");

    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            const button = mutation.target;
            if (button.getAttribute("aria-current") === "true") {
                button.style.backgroundColor = "yellow";
            } else {
                button.style.backgroundColor = "blue";
            }
        });
    });

    const observerConfig = {
        attributes: true,
        attributeFilter: ["aria-current"],
    };

    buttons.forEach((button) => {
        button.style.backgroundColor = "blue";
        observer.observe(button, observerConfig);
    });

    // ДОДАТКОВА ПЕРЕВІРКА при поверненні на сторінку
    window.addEventListener('pageshow', function(event) {
        // Затримка для того, щоб браузер встигнув відновити стан форми
        setTimeout(updateRadioState, 50);
    });
});

const modalTriggers = document.querySelectorAll(".modal-trigger");
const modals = document.querySelectorAll(".modal");
const closeButtons = document.querySelectorAll(".close");

// Функція для відкриття модального вікна
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add("show");
        // Блокуємо скрол body
        document.body.style.overflow = "hidden";
    }
}

// Функція для закриття модального вікна
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove("show");
        // Відновлюємо скрол body
        document.body.style.overflow = "auto";
    }
}

// Додаємо обробники подій для кнопок відкриття
modalTriggers.forEach((trigger) => {
    trigger.addEventListener("click", function () {
        console.log("lalal");
        const triggerId = this.id;
        let modalId = "";

        // Визначаємо яке модальне вікно відкрити
        switch (triggerId) {
            case "terms":
                modalId = "termsModal";
                break;
            case "policy":
                modalId = "policyModal";
                break;
            case "policy_order":
                modalId = "policyModal";
                break;
            case "terms_order":
                modalId = "termsModal";
                break;
        }

        if (modalId) {
            openModal(modalId);
        }
    });
});

// Додаємо обробники для кнопок закриття
closeButtons.forEach((button) => {
    button.addEventListener("click", function () {
        const modalId = this.getAttribute("data-modal");
        closeModal(modalId);
    });
});

// Закриття модального вікна при кліку на фон
modals.forEach((modal) => {
    modal.addEventListener("click", function (e) {
        if (e.target === this) {
            closeModal(this.id);
        }
    });
});

// Закриття модального вікна при натисканні Escape
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        modals.forEach((modal) => {
            if (modal.classList.contains("show")) {
                closeModal(modal.id);
            }
        });
    }
});

// Додаємо плавну анімацію при скролі в модальному вікні
document.querySelectorAll(".modal-content").forEach((content) => {
    content.addEventListener("scroll", function () {
        const scrolled = this.scrollTop;
        const header = this.querySelector(".modal-header");

        if (scrolled > 10) {
            header.style.boxShadow = "0 2px 10px rgba(0,0,0,0.1)";
        } else {
            header.style.boxShadow = "none";
        }
    });
});
