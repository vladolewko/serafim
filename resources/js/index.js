import "flowbite/dist/flowbite.min.js";

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

const checkboxes = document.querySelectorAll('input[type="checkbox"]');

checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", function () {
        // Знаходимо батьківський div з класом checkbox-div
        const parentDiv = this.closest(".checkbox-div");
        // Знаходимо відповідний label
        const label = document.querySelector(`label[for="${this.id}"]`);

        if (this.checked) {
            // Додаємо класи при включенні
            if (parentDiv) {
                parentDiv.classList.add("bg-yellow-400");
            }
            if (label) {
                label.classList.add("text-black");
                label.classList.remove("text-white");
            }
        } else {
            // Видаляємо класи при виключенні
            if (parentDiv) {
                parentDiv.classList.remove("bg-yellow-400");
                parentDiv.classList.add("bg-blue-400");
            }
            if (label) {
                label.classList.add("text-white");
                label.classList.remove("text-black");
            }
        }
    });
});

checkboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", async () => {
        // Збираємо всі вибрані checkbox
        const selectedOptions = Array.from(
            document.querySelectorAll('input[name="options"]:checked')
        ).map((cb) => cb.value);

        try {
            const response = await fetch("/product", {
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
