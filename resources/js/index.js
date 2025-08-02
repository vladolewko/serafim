document.addEventListener("DOMContentLoaded", function () {
    // Обробка всіх кліків з data-target
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

    // Обробка якорів з URL
    const hash = window.location.hash;
    if (hash) {
        setTimeout(() => {
            const targetElement = document.getElementById(hash.substring(1));
            if (targetElement) {
                const elementPosition =
                    targetElement.getBoundingClientRect().top;
                const offsetPosition =
                    elementPosition + window.pageYOffset - 80;
                window.scrollTo({
                    top: offsetPosition,
                    behavior: "smooth",
                });
            }
        }, 100);
    }
});

// Оголошуємо змінні на початку файлу
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
const onSaleSoonSticker = document.getElementById("onSaleSoonSticker");

// Функція для показу/приховання стікера
function toggleOnSaleSoonSticker(isOnSaleSoon) {
    if (onSaleSoonSticker) {
        if (isOnSaleSoon === '1' || isOnSaleSoon === 1) {
            onSaleSoonSticker.classList.remove("hidden");
        } else {
            onSaleSoonSticker.classList.add("hidden");
        }
    }
}

// Функція для оновлення контенту залежно від вибраної опції
function updateContent(radioValue) {
    switch (radioValue) {
        case "citizen":
            if (top7)
                top7.textContent = "ТОП-7 Переваг знання законів та прав людини";
            if (description)
                description.textContent = "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:";
            if (argument1)
                argument1.textContent = "Правовий захист у будь-якій ситуації";
            if (argument2)
                argument2.textContent = "Гарантована свобода та недоторканність";
            if (argument3)
                argument3.textContent = "Можливість законного самозахисту";
            if (argument4)
                argument4.textContent = "Контроль над державою, а не навпаки";
            if (argument5)
                argument5.textContent = "Неможливість маніпуляцій";
            if (argument6)
                argument6.textContent = "Готовність до міжнародного захисту";
            if (argument7)
                argument7.textContent = "Повага і впевненість";
            if (result) result.textContent = "Результат: ";
            if (result_desc)
                result_desc.textContent = "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.";
            break;
        case "military":
            if (top7)
                top7.textContent = "ТОП-7 переваг знання Конституції України, Статутів ЗСУ, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців";
            if (description)
                description.textContent = "Знання Конституції України, Статутів Збройних Сил України, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців є важливим не лише для виконання службових обов'язків, але й для захисту власних прав, підвищення професійної компетентності та ефективного виконання завдань у складних умовах. Ці знання допомагають військовослужбовцям діяти законно, свідомо та впевнено. Ось сім ключових переваг:";
            if (argument1)
                argument1.textContent = "Законність у дії — виконуєш завдання, чітко розуміючи межі дозволеного й уникаючи кримінальної відповідальності.";
            if (argument2)
                argument2.textContent = "Право відхилити злочинний наказ — аргументовано відмовляєшся від розпоряджень, що суперечать Статутам або МГП.";
            if (argument3)
                argument3.textContent = "Гарантовані виплати й пільги — знаєш процедури оформлення компенсацій, доплат і соціальних пакетів.";
            if (argument4)
                argument4.textContent = "Правильне застосування зброї — знання Закону «Про оборону України» допомагає визначити правовий режим воєнного стану, законні підстави відкриття вогню та мінімізувати юридичні й репутаційні ризики.";
            if (argument5)
                argument5.textContent = "Коректне діловодство — грамотно складаєш рапорти, скарги та заяви, знижуючи шанс дисциплінарних стягнень.";
            if (argument6)
                argument6.textContent = "Сильна командна дисципліна — правова впевненість підсилює згуртованість і бойовий дух підрозділу.";
            if (argument7)
                argument7.textContent = "Повага й авторитет — обізнаність у своїх правах і обов'язках зміцнює репутацію серед побратимів та суспільства.";
            if (result) result.textContent = "Результат: ";
            if (result_desc)
                result_desc.textContent = "ти — захищений і обізнаний військовослужбовець, що впевнено відстоює свої права та професійно виконує обов'язки.";
            break;
        case "policeman":
            if (top7)
                top7.textContent = "ТОП-7 переваг підготовленого поліцейського";
            if (description)
                description.textContent = "Глибоке опанування цих законів надає поліцейському потужний юридичний щит, що водночас зміцнює впевненість у кожному правомірному рішенні, мінімізує ризик дисциплінарних помилок і відкриває ширші кар'єрні горизонти — від підвищення в званні до участі в спеціалізованих підрозділах та міжнародних місіях.";
            if (argument1)
                argument1.textContent = "Юридична бездоганність — ухвалює рішення, що легко витримують будь-який судовий контроль.";
            if (argument2)
                argument2.textContent = "Захист прав людини — дотримується процедур і не допускає порушень при затриманнях та слідчих діях.";
            if (argument3)
                argument3.textContent = "Довіра суспільства — професійно пояснює права й свої кроки, зміцнюючи партнерство з громадою.";
            if (argument4)
                argument4.textContent = "Антикорупційна стійкість — уникає конфлікту інтересів і законно відмовляється від небажаних «подяк».";
            if (argument5)
                argument5.textContent = "Швидке розслідування — чітко планує слідчі дії та оформлює докази, скорочуючи шлях до вироку.";
            if (argument6)
                argument6.textContent = "Кар'єрні перспективи — правова ерудиція відкриває двері до спецпідрозділів, викладання й міжнародних місій.";
            if (argument7)
                argument7.textContent = "Стійкість у кризах — впевнено діє під тиском, захищаючи себе й підрозділ від правових помилок.";
            if (result) result.textContent = "Результат: ";
            if (result_desc)
                result_desc.textContent = "Результат: ти — захищений і обізнаний поліцейський, що впевнено відстоює свої права та професійно виконує обов'язки.";
            break;
        case "lawyer":
            if (top7)
                top7.textContent = "ТОП-7 Переваг знання законів та прав людини";
            if (description)
                description.textContent = "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:";
            if (argument1)
                argument1.textContent = "Правовий захист у будь-якій ситуації";
            if (argument2)
                argument2.textContent = "Гарантована свобода та недоторканність";
            if (argument3)
                argument3.textContent = "Можливість законного самозахисту";
            if (argument4)
                argument4.textContent = "Контроль над державою, а не навпаки";
            if (argument5)
                argument5.textContent = "Неможливість маніпуляцій";
            if (argument6)
                argument6.textContent = "Готовність до міжнародного захисту";
            if (argument7)
                argument7.textContent = "Повага і впевненість";
            if (result) result.textContent = "Результат: ";
            if (result_desc)
                result_desc.textContent = "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.";
            break;
    }
}

// Ініціалізація при завантаженні сторінки
document.addEventListener("DOMContentLoaded", function() {
    // Знаходимо перший вибраний радіо
    const checkedRadio = document.querySelector('input[name="options"]:checked');

    if (checkedRadio) {
        const initialOnSaleSoon = checkedRadio.dataset.onSaleSoon;

        // Ініціалізуємо стікер
        toggleOnSaleSoonSticker(initialOnSaleSoon);

        // Оновлюємо контент
        updateContent(checkedRadio.value);

        console.log("Initial option selected:", checkedRadio.value, "On sale soon:", initialOnSaleSoon);
    }
});

radios.forEach((radio) => {
    radio.addEventListener("change", () => {
        const parentDiv = radio.closest("div");
        const label = radio.nextElementSibling;

        // Скидання стилів для всіх радіокнопок
        radios.forEach((r) => {
            const rParentDiv = r.closest("div");
            const rLabel = r.nextElementSibling;

            r.classList.remove("bg-blue-400");
            r.classList.add("bg-white");
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
            radio.classList.add("bg-blue-400");
            radio.classList.remove("bg-white");

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
            const onSaleSoon = radio.dataset.onSaleSoon;

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

            // Показуємо/приховуємо стікер залежно від властивості on_sale_soon
            toggleOnSaleSoonSticker(onSaleSoon);

            // Оновлюємо контент залежно від вибраної опції
            updateContent(radio.value);
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
        const button = mutation.target;
        if (button.getAttribute("aria-current") === "true") {
            button.style.backgroundColor = "yellow";
        } else {
            button.style.backgroundColor = "blue";
        }
    });
});

// Налаштовуємо параметри observer
const observerConfig = {
    attributes: true,
    attributeFilter: ["aria-current"],
};

// Застосовуємо observer до кожної кнопки та встановлюємо початковий колір
buttons.forEach((button) => {
    button.style.backgroundColor = "blue";
    observer.observe(button, observerConfig);
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
