class OrderFormManager {
    constructor(routes) {
        this.routes = routes; // Отримуємо маршрути як параметр
        this.addressData = {
            search: "",
            settlement: "",
            warehouse: "",
            settlementName: "",
            warehouseName: "",
        };
        this.settlementsData = [];
        this.warehousesData = [];
        this.searchTimeout = null;

        this.initializeElements();
        this.attachEventListeners();
        this.checkCSRFToken();
    }

    initializeElements() {
        const ids = [
            "city-search",
            "settlement-select",
            "warehouse-select",
            "settlement-section",
            "warehouse-section",
            "order-form-section",
            "selected-address",
            "address-text",
            "change-address",
            "submit-btn",
            "unified-order-form",
            "delivery-cost",
            "total-amount",
            "search-loader",
            "settlement-loader",
            "warehouse-loader",
        ];

        this.elements = {};
        ids.forEach((id) => {
            this.elements[id.replace(/-([a-z])/g, (g) => g[1].toUpperCase())] =
                document.getElementById(id);
        });
    }

    attachEventListeners() {
        // City search with debounce
        this.elements.citySearch.addEventListener("input", (e) => {
            clearTimeout(this.searchTimeout);
            const value = e.target.value.trim();

            if (value.length >= 2) {
                this.searchTimeout = setTimeout(
                    () => this.searchSettlements(value),
                    500
                );
            } else {
                this.toggleSection("settlement", false);
            }
        });

        // Selection handlers
        this.elements.settlementSelect.addEventListener(
            "change",
            (e) => e.target.value && this.chooseSettlement(e.target.value)
        );

        this.elements.warehouseSelect.addEventListener(
            "change",
            (e) => e.target.value && this.setWarehouse(e.target.value)
        );

        // Other handlers
        this.elements.changeAddress.addEventListener("click", () =>
            this.resetAddressSelection()
        );
        this.elements.unifiedOrderForm.addEventListener("submit", (e) => {
            e.preventDefault();
            this.submitOrder();
        });

        // Phone formatting
        const phoneInput = this.elements.unifiedOrderForm.querySelector(
            'input[name="phone"]'
        );
        phoneInput?.addEventListener("input", (e) =>
            this.formatPhoneNumber(e.target)
        );
    }

    formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, "");

        if (!value.startsWith("380")) {
            if (value.startsWith("0")) value = "380" + value.substring(1);
            else if (value.length > 0) value = "380" + value;
        }

        value = value.substring(0, 12);

        let formatted = "+380";
        if (value.length > 3) formatted += " " + value.substring(3, 5);
        if (value.length > 5) formatted += " " + value.substring(5, 8);
        if (value.length > 8) formatted += " " + value.substring(8, 10);
        if (value.length > 10) formatted += " " + value.substring(10, 12);

        input.value = formatted;
    }

    showToast(message, type = "info") {
        const colors = {
            success: "bg-green-500 text-white",
            error: "bg-red-500 text-white",
            info: "bg-blue-500 text-white",
        };
        const icons = { success: "✓", error: "✕", info: "ℹ" };

        const toast = document.createElement("div");
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 translate-x-full ${colors[type]}`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="text-lg font-bold">${icons[type]}</span>
                <span>${message}</span>
                <button class="ml-2 text-xl font-bold opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        document.body.appendChild(toast);
        setTimeout(() => toast.classList.remove("translate-x-full"), 100);
        setTimeout(() => {
            toast.classList.add("translate-x-full");
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    showSuccessMessage(msg) {
        this.showToast(msg, "success");
    }
    showErrorMessage(msg) {
        this.showToast(msg, "error");
    }
    showInfoMessage(msg) {
        this.showToast(msg, "info");
    }

    showSuccessToastWithRedirect(title, message, redirectUrl, delay = 3000) {
        const toast = document.createElement("div");
        toast.className =
            "fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50";
        toast.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4 text-center">
                <div class="text-green-500 text-6xl mb-4">✓</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">${title}</h3>
                <p class="text-gray-600 mb-4">${message}</p>
                <div class="text-sm text-gray-500">
                    Перенаправлення через <span id="countdown">${
                        delay / 1000
                    }</span> секунд...
                </div>
            </div>
        `;

        document.body.appendChild(toast);

        let seconds = delay / 1000;
        const countdownEl = toast.querySelector("#countdown");
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) clearInterval(interval);
        }, 1000);

        setTimeout(() => (window.location.href = redirectUrl), delay);
    }

    checkCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            this.showErrorMessage(
                "Помилка безпеки. Будь ласка, оновіть сторінку."
            );
            return false;
        }
        return true;
    }

    async makeRequest(url, data) {
        if (!this.checkCSRFToken()) throw new Error("CSRF токен відсутній");

        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify(data),
            });

            if (!response.ok) {
                let errorMessage = "Виникла помилка на сервері";
                try {
                    const errorData = await response.json();
                    errorMessage =
                        errorData.message || errorData.error || errorMessage;
                } catch (e) {
                    const messages = {
                        404: "Сервіс тимчасово недоступний",
                        500: "Внутрішня помилка сервера",
                        422: "Некоректні дані",
                    };
                    errorMessage = messages[response.status] || errorMessage;
                }
                throw new Error(errorMessage);
            }

            return await response.json();
        } catch (error) {
            if (error instanceof TypeError && error.message.includes("fetch")) {
                throw new Error("Проблеми з підключенням до інтернету");
            }
            throw error;
        }
    }

    showLoader(type, show = true) {
        const loader = this.elements[`${type}Loader`];
        loader?.classList.toggle("hidden", !show);
    }

    toggleSection(type, show) {
        const section = this.elements[`${type}Section`];
        const select = this.elements[`${type}Select`];

        section?.classList.toggle("hidden", !show);
        if (select) select.disabled = !show;

        if (type === "settlement" && !show)
            this.toggleSection("warehouse", false);
        if (type === "warehouse" && !show) this.hideOrderForm();
    }

    async searchSettlements(searchValue) {
        if (!searchValue.trim()) return;

        this.showLoader("search");

        try {
            const response = await this.makeRequest(
                this.routes.searchSettlement,
                {
                    search: searchValue,
                }
            );

            if (!response.success)
                throw new Error(
                    response.error || "Не вдалося знайти населені пункти"
                );

            this.addressData.search = searchValue;
            this.settlementsData = response.settlements || [];

            if (this.settlementsData.length === 0) {
                this.showInfoMessage(
                    "Населені пункти не знайдено. Спробуйте інший запит."
                );
                this.toggleSection("settlement", false);
                return;
            }

            this.populateSelect(
                "settlement",
                this.settlementsData,
                "Оберіть місто..."
            );
            this.toggleSection("settlement", true);
        } catch (error) {
            this.showErrorMessage(error.message);
            this.toggleSection("settlement", false);
        } finally {
            this.showLoader("search", false);
        }
    }

    populateSelect(
        type,
        data,
        placeholder,
        valueKey = "Ref",
        textKey = "Present"
    ) {
        const select = this.elements[`${type}Select`];
        select.innerHTML = `<option value="">${placeholder}</option>`;

        data.forEach((item) => {
            const option = document.createElement("option");
            option.value = item[valueKey];
            option.textContent =
                type === "warehouse" ? item.Description : item[textKey];
            select.appendChild(option);
        });

        select.disabled = false;
    }

    async chooseSettlement(settlementRef) {
        this.showLoader("settlement");

        try {
            const response = await this.makeRequest(
                this.routes.chooseSettlement,
                {
                    settlement: settlementRef,
                }
            );

            if (!response.success)
                throw new Error(
                    response.error || "Не вдалося завантажити відділення"
                );

            this.addressData.settlement = settlementRef;
            this.addressData.settlementName =
                this.elements.settlementSelect.selectedOptions[0]
                    ?.textContent || "";
            this.warehousesData = response.warehouses || [];

            if (this.warehousesData.length === 0) {
                this.showInfoMessage("У цьому місті немає доступних відділень");
                this.toggleSection("warehouse", false);
                return;
            }

            this.populateSelect(
                "warehouse",
                this.warehousesData,
                "Оберіть відділення або поштомат..."
            );
            this.toggleSection("warehouse", true);
        } catch (error) {
            this.showErrorMessage(error.message);
            this.toggleSection("warehouse", false);
        } finally {
            this.showLoader("settlement", false);
        }
    }

    async setWarehouse(warehouseRef) {
        this.showLoader("warehouse");

        try {
            const response = await this.makeRequest(this.routes.setWarehouse, {
                warehouse: warehouseRef,
            });

            if (!response.success)
                throw new Error("Не вдалося розрахувати вартість доставки");

            this.addressData.warehouse = warehouseRef;
            this.addressData.warehouseName =
                this.elements.warehouseSelect.selectedOptions[0]?.textContent ||
                "";

            this.updateDeliveryInfo(response);
            this.showAddressConfirmation();
            this.showOrderForm();
            this.toggleSubmitButton(true);

            this.showSuccessMessage("Адресу доставки обрано успішно");
        } catch (error) {
            this.showErrorMessage(error.message);
            this.hideOrderForm();
        } finally {
            this.showLoader("warehouse", false);
        }
    }

    updateDeliveryInfo(response) {
        const deliveryCost = response.deliveryCost || 0;
        const productCosts = response.productCosts || 0;

        this.elements.deliveryCost.textContent = `${deliveryCost} грн`;
        this.elements.totalAmount.textContent = `${
            productCosts + deliveryCost
        } грн`;
    }

    showAddressConfirmation() {
        this.elements.addressText.textContent = `${this.addressData.settlementName}, ${this.addressData.warehouseName}`;
        this.elements.selectedAddress.classList.remove("hidden");
    }

    showOrderForm() {
        this.elements.orderFormSection.classList.remove("hidden");
    }

    hideOrderForm() {
        this.elements.orderFormSection.classList.add("hidden");
        this.toggleSubmitButton(false);
    }

    toggleSubmitButton(active) {
        const btn = this.elements.submitBtn;
        btn.disabled = !active;

        if (active) {
            btn.className = btn.className.replace(
                "bg-gray-400",
                "bg-blue-400 hover:bg-blue-500 active:bg-blue-600 transition-colors focus:ring-4 focus:ring-blue-300"
            );
            btn.textContent = "Підтвердити замовлення";
        } else {
            btn.className = btn.className.replace(
                /bg-blue-\d+.*?focus:ring-blue-300/g,
                "bg-gray-400"
            );
            btn.textContent = "Оберіть адресу доставки";
        }
    }

    resetAddressSelection() {
        this.addressData = {
            search: "",
            settlement: "",
            warehouse: "",
            settlementName: "",
            warehouseName: "",
        };

        this.elements.citySearch.value = "";
        this.elements.settlementSelect.innerHTML =
            '<option value="">Оберіть місто...</option>';
        this.elements.warehouseSelect.innerHTML =
            '<option value="">Оберіть відділення або поштомат...</option>';

        this.toggleSection("settlement", false);
        this.toggleSection("warehouse", false);
        this.hideOrderForm();
        this.elements.selectedAddress.classList.add("hidden");

        this.elements.deliveryCost.textContent = "- грн";
        this.elements.totalAmount.textContent = "0 грн";

        this.elements.citySearch.focus();
    }

    validateForm() {
        const form = this.elements.unifiedOrderForm;
        const requiredFields = form.querySelectorAll("[required]");
        let isValid = true;
        let errorMessages = [];

        requiredFields.forEach((field) => {
            const hasValue = field.value.trim();
            field.classList.toggle("border-red-500", !hasValue);
            field.classList.toggle("border-2", !hasValue);

            if (!hasValue) {
                isValid = false;
                errorMessages.push(
                    `Поле "${
                        field.placeholder || field.name
                    }" обов'язкове для заповнення`
                );
            }
        });

        // Phone validation
        const phoneInput = form.querySelector('input[name="phone"]');
        if (phoneInput) {
            const phoneValue = phoneInput.value.replace(/\D/g, "");
            const isValidPhone =
                phoneValue.length === 12 && phoneValue.startsWith("380");
            phoneInput.classList.toggle("border-red-500", !isValidPhone);
            phoneInput.classList.toggle("border-2", !isValidPhone);

            if (!isValidPhone) {
                isValid = false;
                errorMessages.push(
                    "Некоректний номер телефону. Введіть український номер"
                );
            }
        }

        // Email validation
        const emailInput = form.querySelector('input[name="email"]');
        if (emailInput) {
            const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(
                emailInput.value
            );
            emailInput.classList.toggle("border-red-500", !isValidEmail);
            emailInput.classList.toggle("border-2", !isValidEmail);

            if (!isValidEmail) {
                isValid = false;
                errorMessages.push("Введіть коректну електронну адресу");
            }
        }

        if (!isValid && errorMessages.length > 0) {
            this.showErrorMessage(errorMessages[0]);
        }

        return isValid;
    }

    async submitOrder() {
        if (!this.addressData.warehouse) {
            this.showErrorMessage(
                "Оберіть адресу доставки перед оформленням замовлення"
            );
            return;
        }

        if (!this.validateForm()) return;

        this.elements.submitBtn.disabled = true;
        this.elements.submitBtn.textContent = "Створення замовлення...";

        const formData = new FormData(this.elements.unifiedOrderForm);
        formData.append("settlement", this.addressData.settlement);
        formData.append("warehouse", this.addressData.warehouse);

        try {
            if (formData.get("payment") === "card") {
                await this.handleCardPayment(formData);
            } else {
                await this.handleCashPayment(formData);
            }
        } catch (error) {
            this.showErrorMessage(
                error.message || "Виникла помилка при створенні замовлення"
            );
        } finally {
            this.toggleSubmitButton(true);
        }
    }

    async handleCashPayment(formData) {
        try {
            const response = await fetch(this.routes.createCounterparty, {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN":
                        document
                            .querySelector('meta[name="csrf-token"]')
                            ?.getAttribute("content") || "",
                    Accept: "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccessToastWithRedirect(
                    "Замовлення успішно створено!",
                    `ТТН: ${data.ttn_number}`,
                    this.routes.home
                );
            } else {
                throw new Error(data.message || "Помилка створення замовлення");
            }
        } catch (error) {
            if (error instanceof TypeError) {
                throw new Error("Проблеми з підключенням. Перевірте інтернет");
            }
            throw error;
        }
    }

    async handleCardPayment(formData) {
        const response = await fetch(this.routes.createCounterparty, {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN":
                    document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content") || "",
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        const data = await response.json();

        if (data.success && data.payment_type === "card") {
            this.createWayForPayForm(data.wayforpay_data);
        } else {
            throw new Error(data.message || "Помилка підготовки оплати");
        }
    }

    createWayForPayForm(wayForPayData) {
        const form = document.createElement("form");
        form.method = "POST";
        form.action = "https://secure.wayforpay.com/pay";
        form.acceptCharset = "utf-8";
        form.style.display = "none";

        Object.entries(wayForPayData).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach((item) => {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.name = `${key}[]`;
                    input.value = item;
                    form.appendChild(input);
                });
            } else {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
        });

        document.body.appendChild(form);
        this.showInfoMessage("Перенаправлення на платіжну систему...");
        form.submit();
        setTimeout(() => form.remove(), 1000);
    }
}

window.OrderFormManager = OrderFormManager;
