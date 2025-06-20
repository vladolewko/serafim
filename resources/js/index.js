import "flowbite/dist/flowbite.min.js";
import "flowbite";
//
// function smoothScrollToElement(targetId, offset = 80) {
//     const targetElement = document.getElementById(targetId);
//     if (!targetElement) return;
//
//     const elementPosition = targetElement.getBoundingClientRect().top;
//     const offsetPosition = elementPosition + window.pageYOffset - offset;
//
//     window.scrollTo({
//         top: offsetPosition,
//         behavior: "smooth",
//     });
// }
//
// document.addEventListener("DOMContentLoaded", function () {
//     const navItems = document.querySelectorAll(".nav-item");
//
//     navItems.forEach((item) => {
//         item.addEventListener("click", function () {
//             const targetId = this.getAttribute("data-target");
//
//             // Використовуємо основний метод
//             smoothScrollToElement(targetId, 80);
//
//             // Або альтернативний метод (закоментований)
//             // smoothScrollAlternative(targetId);
//         });
//     });
// });
// const radios = document.querySelectorAll('input[name="options"]');
//
// const top7 = document.getElementById("top7");
// const description = document.getElementById("description");
// const argument1 = document.getElementById("argument-1");
// const argument2 = document.getElementById("argument-2");
// const argument3 = document.getElementById("argument-3");
// const argument4 = document.getElementById("argument-4");
// const argument5 = document.getElementById("argument-5");
// const argument6 = document.getElementById("argument-6");
// const argument7 = document.getElementById("argument-7");
// const result = document.getElementById("result");
// const result_desc = document.getElementById("result_desc");
// const price = document.getElementById("price");
// const productHref = document.getElementById("productHref");
// const productImage = document.getElementById("productImage");
//
// radios.forEach((radio) => {
//     if (radio.value === "citizen") {
//         radio.checked = true;
//
//         radio.closest("div").classList.add("bg-yellow-400");
//         radio.closest("div").classList.remove("bg-blue-400");
//         radio.nextElementSibling.classList.remove("text-white");
//         radio.nextElementSibling.classList.add("text-black");
//
//         // Встановлюємо початкові значення для citizen
//         const initialPrice = radio.dataset.price;
//         const initialProductId = radio.dataset.productId;
//         const initialImageUrl = radio.dataset.imageUrl;
//
//         if (price) price.textContent = initialPrice;
//         if (productHref) productHref.href = "product/" + initialProductId;
//         if (productImage) productImage.src = initialImageUrl;
//
//         console.log("Citizen selected by default");
//     }
//
//     radio.addEventListener("change", () => {
//         const parentDiv = radio.closest("div");
//         const label = radio.nextElementSibling;
//
//         // Скидання стилів для всіх радіокнопок
//         radios.forEach((r) => {
//             const rParentDiv = r.closest("div");
//             const rLabel = r.nextElementSibling;
//             if (rParentDiv) {
//                 rParentDiv.classList.remove("bg-yellow-400");
//                 rParentDiv.classList.add("bg-blue-400");
//             }
//             if (rLabel) {
//                 rLabel.classList.remove("text-black");
//                 rLabel.classList.add("text-white");
//             }
//         });
//
//         // Оновлення тексту та стилів для вибраної радіокнопки
//         if (radio.checked) {
//             if (parentDiv) {
//                 parentDiv.classList.add("bg-yellow-400");
//                 parentDiv.classList.remove("bg-blue-400");
//             }
//             if (label) {
//                 label.classList.add("text-black");
//                 label.classList.remove("text-white");
//             }
//
//             const currentPrice = radio.dataset.price;
//             const productId = radio.dataset.productId;
//             const imageUrl = radio.dataset.imageUrl;
//
//             // Оновлення ціни
//             if (price) price.textContent = currentPrice;
//
//             // Оновлення href для посилання
//             if (productHref) {
//                 productHref.href = "product/" + productId;
//             }
//
//             // Оновлення картинки
//             if (productImage) {
//                 productImage.src = imageUrl;
//             }
//
//             // Зміна тексту залежно від вибраної опції
//             switch (radio.value) {
//                 case "citizen":
//                     if (top7) top7.textContent = "ТОП-7 Переваг знання законів та прав людини";
//                     if (description) description.textContent = "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:";
//                     if (argument1) argument1.textContent = "Правовий захист у будь-якій ситуації";
//                     if (argument2) argument2.textContent = "Гарантована свобода та недоторканність";
//                     if (argument3) argument3.textContent = "Можливість законного самозахисту";
//                     if (argument4) argument4.textContent = "Контроль над державою, а не навпаки";
//                     if (argument5) argument5.textContent = "Неможливість маніпуляцій";
//                     if (argument6) argument6.textContent = "Готовність до міжнародного захисту";
//                     if (argument7) argument7.textContent = "Повага і впевненість";
//                     if (result) result.textContent = "Результат: ";
//                     if (result_desc) result_desc.textContent = "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.";
//                     break;
//                 case "military":
//                     top7.textContent =
//                         "ТОП-7 переваг знання Конституції України, Статутів ЗСУ, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців";
//                     description.textContent =
//                         "Знання Конституції України, Статутів Збройних Сил України, норм міжнародного гуманітарного права та законів про соціальні гарантії військовослужбовців є важливим не лише для виконання службових обов’язків, але й для захисту власних прав, підвищення професійної компетентності та ефективного виконання завдань у складних умовах. Ці знання допомагають військовослужбовцям діяти законно, свідомо та впевнено. Ось сім ключових переваг:";
//                     argument1.textContent =
//                         "Законність у дії — виконуєш завдання, чітко розуміючи межі дозволеного й уникаючи кримінальної відповідальності.";
//                     argument2.textContent =
//                         "Право відхилити злочинний наказ — аргументовано відмовляєшся від розпоряджень, що суперечать Статутам або МГП.";
//                     argument3.textContent =
//                         "Гарантовані виплати й пільги — знаєш процедури оформлення компенсацій, доплат і соціальних пакетів.";
//                     argument4.textContent =
//                         "Правильне застосування зброї — знання Закону «Про оборону України» допомагає визначити правовий режим воєнного стану, законні підстави відкриття вогню та мінімізувати юридичні й репутаційні ризики.";
//                     argument5.textContent =
//                         "Коректне діловодство — грамотно складаєш рапорти, скарги та заяви, знижуючи шанс дисциплінарних стягнень.";
//                     argument6.textContent =
//                         "Сильна командна дисципліна — правова впевненість підсилює згуртованість і бойовий дух підрозділу.";
//                     argument7.textContent =
//                         "Повага й авторитет — обізнаність у своїх правах і обов’язках зміцнює репутацію серед побратимів та суспільства.";
//                     result.textContent = "Результат: ";
//                     result_desc.textContent =
//                         "ти — захищений і обізнаний військовослужбовець, що впевнено відстоює свої права та професійно виконує обов’язки.";
//                     break;
//                 case "policeman":
//                     top7.textContent =
//                         "ТОП-7 переваг підготовленого поліцейського";
//                     description.textContent =
//                         "Глибоке опанування цих законів надає поліцейському потужний юридичний щит, що водночас зміцнює впевненість у кожному правомірному рішенні, мінімізує ризик дисциплінарних помилок і відкриває ширші кар’єрні горизонти — від підвищення в званні до участі в спеціалізованих підрозділах та міжнародних місіях.";
//                     argument1.textContent =
//                         "Юридична бездоганність — ухвалює рішення, що легко витримують будь-який судовий контроль.";
//                     argument2.textContent =
//                         "Захист прав людини — дотримується процедур і не допускає порушень при затриманнях та слідчих діях.";
//                     argument3.textContent =
//                         "Довіра суспільства — професійно пояснює права й свої кроки, зміцнюючи партнерство з громадою.";
//                     argument4.textContent =
//                         "Антикорупційна стійкість — уникає конфлікту інтересів і законно відмовляється від небажаних «подяк».";
//                     argument5.textContent =
//                         "Швидке розслідування — чітко планує слідчі дії та оформлює докази, скорочуючи шлях до вироку.";
//                     argument6.textContent =
//                         "Кар’єрні перспективи — правова ерудиція відкриває двері до спецпідрозділів, викладання й міжнародних місій.";
//                     argument7.textContent =
//                         "Стійкість у кризах — впевнено діє під тиском, захищаючи себе й підрозділ від правових помилок.";
//                     result.textContent = "Результат: ";
//                     result_desc.textContent =
//                         "Результат: ти — захищений і обізнаний поліцейський, що впевнено відстоює свої права та професійно виконує обов’язки.";
//                     break;
//                 case "lawyer":
//                     top7.textContent =
//                         "ТОП-7 Переваг знання законів та прав людини";
//                     description.textContent =
//                         "Знання Конституції України, законів, прав людини та міжнародного права — це не просто освіченість. Це влада, безпека та свобода у щоденному житті. Ось ключові переваги:";
//                     argument1.textContent =
//                         "Правовий захист у будь-якій ситуації";
//                     argument2.textContent =
//                         "Гарантована свобода та недоторканність";
//                     argument3.textContent = "Можливість законного самозахисту";
//                     argument4.textContent =
//                         "Контроль над державою, а не навпаки";
//                     argument5.textContent = "Неможливість маніпуляцій";
//                     argument6.textContent =
//                         "Готовність до міжнародного захисту";
//                     argument7.textContent = "Повага і впевненість";
//                     result.textContent = "Результат: ";
//                     result_desc.textContent =
//                         "Ти — не безправний. Ти — свідомий громадянин, який знає, як себе захистити.";
//                     break;
//             }
//         }
//     });
// });
//
// radios.forEach((radio) => {
//     radio.addEventListener("change", async () => {
//         // Збираємо всі вибрані checkbox
//         const selectedOptions = Array.from(
//             document.querySelectorAll('input[name="options"]:checked')
//         ).map((cb) => cb.value);
//
//         try {
//             const response = await fetch(`/product/${radio.value}`, {
//                 method: "POST",
//                 headers: { "Content-Type": "application/json" },
//                 body: JSON.stringify({ options: selectedOptions }),
//             });
//             const result = await response.json();
//             console.log("Відповідь від бекенду:", result);
//         } catch (error) {
//             console.error("Помилка:", error);
//         }
//     });
// });
//
// const buttons = document.querySelectorAll(".slide_button");
//
// // Налаштовуємо MutationObserver для відстеження змін атрибутів
// const observer = new MutationObserver((mutations) => {
//     mutations.forEach((mutation) => {
//         const button = mutation.target; // Елемент, у якого змінився атрибут
//         if (button.getAttribute("aria-current") === "true") {
//             button.style.backgroundColor = "yellow"; // Змінюємо колір на жовтий
//         } else {
//             button.style.backgroundColor = "blue"; // Повертаємо синій, якщо атрибут не "true"
//         }
//     });
// });
//
// // Налаштовуємо параметри observer
// const observerConfig = {
//     attributes: true, // Відстежуємо зміни атрибутів
//     attributeFilter: ["aria-current"], // Відстежуємо лише атрибут aria-current
// };
//
// // Застосовуємо observer до кожної кнопки та встановлюємо початковий колір
// buttons.forEach((button) => {
//     button.style.backgroundColor = "blue"; // Встановлюємо початковий синій колір
//     observer.observe(button, observerConfig); // Починаємо відстежувати зміни
// });
