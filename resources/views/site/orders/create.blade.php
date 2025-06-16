@extends('layouts.site')


@section('content')
<main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="w-full">
        <!-- Navigation -->
        <nav class="mb-6">
            <ul class="flex flex-wrap text-lg sm:text-xl text-slate-600 items-center gap-2 sm:gap-4">
                <li class="hover:text-slate-800 cursor-pointer">
                    <a href="{{ route('home') }}" onclick="return false">головна</a>
                </li>
                <li class="hover:text-slate-800 cursor-pointer">
                    <a href="{{ route('product.show', session('cart')['product']->id) }}">замовлення</a>
                </li>
                <li class="text-yellow-400 text-xl sm:text-2xl font-semibold">оформлення</li>
            </ul>
        </nav>

        <!-- Page Title -->
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-8 lg:mb-16">Оформлення замовлення</h1>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row justify-between w-full gap-6 lg:gap-8">
            <!-- Left Column - Forms -->
            <div class="flex flex-col w-full lg:w-7/12 xl:w-6/12 space-y-6 lg:space-y-10">
                <!-- Delivery Section -->
                <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg">
                    <div class="w-11/12 mx-auto py-4 sm:py-6 md:py-8">
                        <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-semibold mb-3 sm:mb-4 md:mb-6 text-center sm:text-left">
                            Доставка
                        </h2>

                        <!-- City Search -->
                        <div class="mb-4">
                            <div class="relative">
                                <input
                                    id="city-search"
                                    class="w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black"
                                    type="text"
                                    placeholder="Введіть місто для пошуку..."
                                    autocomplete="off">
                                <div id="search-loader" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Settlement Selection -->
                        <div id="settlement-section" class="mb-4 hidden">
                            <div class="relative">
                                <select
                                    id="settlement-select"
                                    class="w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black appearance-none cursor-pointer"
                                    disabled>
                                    <option value="" selected>Оберіть місто...</option>
                                </select>
                                <div id="settlement-loader" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                </div>
                                <!-- Custom arrow -->
                                <!-- <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div> -->
                            </div>
                        </div>

                        <!-- Warehouse Selection -->
                        <div id="warehouse-section" class="mb-4 hidden">
                            <div class="relative">
                                <select
                                    id="warehouse-select"
                                    class="w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black appearance-none cursor-pointer"
                                    disabled>
                                    <option value="" selected>Оберіть відділення або поштомат...</option>
                                </select>
                                <div id="warehouse-loader" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                </div>
                                <!-- Custom arrow -->
                                <!-- <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div> -->
                            </div>
                        </div>

                        <!-- Selected Address Display -->
                        <div id="selected-address" class="hidden bg-green-100 text-green-800 p-3 rounded-lg">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <div>
                                    <div class="font-semibold">Обрана адреса:</div>
                                    <div id="address-text" class="text-sm"></div>
                                    <button id="change-address" class="text-blue-600 hover:text-blue-800 text-sm underline mt-1">
                                        Змінити адресу
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Order Form -->
                <div id="order-form-section" class="hidden">
                    <form id="unified-order-form">
                        <!-- Contact Information -->
                        <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg mb-6">
                            <div class="w-11/12 mx-auto py-6">
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Контактні дані</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 text-black mx-auto gap-3">
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="+380"
                                        name="phone"
                                        value="+380"
                                        type="tel"
                                        required>
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="Електронна пошта"
                                        name="email"
                                        type="email"
                                        required>
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="Ім'я"
                                        name="name"
                                        type="text"
                                        required>
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="Прізвище"
                                        name="surname"
                                        type="text"
                                        required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg">
                            <div class="w-11/12 mx-auto py-6">
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Оплата</h2>
                                <div class="grid grid-cols-1 text-black mx-auto gap-3">
                                    <label class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors cursor-pointer">
                                        <input
                                            id="payment-card"
                                            class="w-4 h-4 cursor-pointer text-yellow-400 bg-white border-yellow-400"
                                            name="payment"
                                            type="radio"
                                            value="card"
                                            required>
                                        <span class="text-black text-sm sm:text-base font-medium flex-1">
                                            Карткою на сайті
                                        </span>
                                    </label>

                                    <label class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors cursor-pointer">
                                        <input
                                            id="payment-cash"
                                            class="w-4 h-4 cursor-pointer text-yellow-400 bg-white border-yellow-400"
                                            name="payment"
                                            type="radio"
                                            value="cash"
                                            required>
                                        <span class="text-black text-sm sm:text-base font-medium flex-1">
                                            При отриманні
                                        </span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="bg-yellow-400 text-black rounded-lg w-full lg:w-5/12 h-fit shadow-lg sticky top-6">
                <div class="w-10/12 mx-auto py-6">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6">Разом</h2>

                    <!-- Order Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 text-sm sm:text-base">
                                {{ session('cart')['quantity'] }} товар(и) на суму
                            </span>
                            <span class="font-semibold text-sm sm:text-base">
                                {{ session('cart')['total'] }} грн
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 text-sm sm:text-base">Вартість доставки</span>
                            <span class="font-semibold text-sm sm:text-base" id="delivery-cost">- грн</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="border-slate-800 border-[1.5px] rounded-lg my-6">

                    <!-- Total -->
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-lg sm:text-xl font-semibold">До сплати</span>
                        <span class="font-bold text-xl sm:text-2xl lg:text-3xl" id="total-amount">
                            {{ isset($deliveryCost) ? session('cart')['total'] + $deliveryCost : session('cart')['total'] }} грн
                        </span>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        form="unified-order-form"
                        id="submit-btn"
                        disabled
                        class="w-full bg-gray-400 text-xl sm:text-2xl lg:text-3xl text-white font-bold py-3 sm:py-4 rounded-lg text-center transition-all duration-200 disabled:cursor-not-allowed">
                        Оберіть адресу доставки
                    </button>

                    <!-- Terms and Conditions -->
                    <div class="text-center text-xs sm:text-xs text-slate-600 mt-4">
                        <p class="w-11/12 mx-auto leading-relaxed">
                            Натискаючи "підтвердити замовлення", ви приймаєте умови
                            <span class="underline hover:text-slate-800 cursor-pointer">політики конфіденційності</span>
                            та
                            <span class="underline hover:text-slate-800 cursor-pointer">угоди користувача</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- <script>
    class OrderFormManager {
        constructor() {
            this.addressData = {
                search: '',
                settlement: '',
                warehouse: '',
                settlementName: '',
                warehouseName: ''
            };

            this.settlementsData = [];
            this.warehousesData = [];
            this.searchTimeout = null;

            this.initializeElements();
            this.attachEventListeners();
            this.checkCSRFToken();
        }

        initializeElements() {
            this.elements = {
                citySearch: document.getElementById('city-search'),
                settlementSelect: document.getElementById('settlement-select'),
                warehouseSelect: document.getElementById('warehouse-select'),
                settlementSection: document.getElementById('settlement-section'),
                warehouseSection: document.getElementById('warehouse-section'),
                orderFormSection: document.getElementById('order-form-section'),
                selectedAddress: document.getElementById('selected-address'),
                addressText: document.getElementById('address-text'),
                changeAddressBtn: document.getElementById('change-address'),
                submitBtn: document.getElementById('submit-btn'),
                orderForm: document.getElementById('unified-order-form'),
                deliveryCost: document.getElementById('delivery-cost'),
                totalAmount: document.getElementById('total-amount'),
                debugPanel: document.getElementById('debug-panel'),
                debugMessage: document.getElementById('debug-message'),
                searchLoader: document.getElementById('search-loader'),
                settlementLoader: document.getElementById('settlement-loader'),
                warehouseLoader: document.getElementById('warehouse-loader')
            };
        }

        attachEventListeners() {
            // City search with debounce
            this.elements.citySearch.addEventListener('input', (e) => {
                clearTimeout(this.searchTimeout);
                const value = e.target.value.trim();

                if (value.length >= 2) {
                    this.searchTimeout = setTimeout(() => {
                        this.searchSettlements(value);
                    }, 500);
                } else {
                    this.hideSettlementSection();
                }
            });

            // Settlement selection
            this.elements.settlementSelect.addEventListener('change', (e) => {
                if (e.target.value) {
                    this.chooseSettlement(e.target.value);
                }
            });

            // Warehouse selection
            this.elements.warehouseSelect.addEventListener('change', (e) => {
                if (e.target.value) {
                    this.setWarehouse(e.target.value);
                }
            });

            // Change address button
            this.elements.changeAddressBtn.addEventListener('click', () => {
                this.resetAddressSelection();
            });

            // Form submission
            this.elements.orderForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.submitOrder();
            });

            // Phone input formatting
            const phoneInput = this.elements.orderForm.querySelector('input[name="phone"]');
            if (phoneInput) {
                phoneInput.addEventListener('input', (e) => {
                    this.formatPhoneNumber(e.target);
                });
            }
        }

        formatPhoneNumber(input) {
            let value = input.value.replace(/\D/g, '');

            if (!value.startsWith('380')) {
                if (value.startsWith('0')) {
                    value = '380' + value.substring(1);
                } else if (value.length > 0 && !value.startsWith('380')) {
                    value = '380' + value;
                }
            }

            // Limit to Ukrainian phone number length
            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            // Format as +380 XX XXX XX XX
            let formatted = '+380';
            if (value.length > 3) {
                formatted = '+380 ' + value.substring(3, 5);
            }
            if (value.length > 5) {
                formatted += ' ' + value.substring(5, 8);
            }
            if (value.length > 8) {
                formatted += ' ' + value.substring(8, 10);
            }
            if (value.length > 10) {
                formatted += ' ' + value.substring(10, 12);
            }

            input.value = formatted;
        }

        showSuccessMessage(message) {
            this.showToast(message, 'success');
        }

        showErrorMessage(message) {
            this.showToast(message, 'error');
        }

        showInfoMessage(message) {
            this.showToast(message, 'info');
        }

        showToast(message, type = 'info') {
            // Створюємо toast повідомлення
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;

            const colors = {
                success: 'bg-green-500 text-white',
                error: 'bg-red-500 text-white',
                info: 'bg-blue-500 text-white',
                warning: 'bg-yellow-500 text-black'
            };

            toast.classList.add(...colors[type].split(' '));

            const icon = {
                success: '✓',
                error: '✕',
                info: 'ℹ',
                warning: '⚠'
            };

            toast.innerHTML = `
        <div class="flex items-center gap-3">
            <span class="text-lg font-bold">${icon[type]}</span>
            <span>${message}</span>
            <button class="ml-2 text-xl font-bold opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">×</button>
        </div>
    `;

            document.body.appendChild(toast);

            // Анімація появи
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Автоматичне приховування через 5 секунд
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 300);
            }, 5000);
        }

        showSuccessToastWithRedirect(title, message, redirectUrl, delay = 3000) {
            const toast = document.createElement('div');
            toast.className = `fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50`;

            toast.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md mx-4 text-center">
            <div class="text-green-500 text-6xl mb-4">✓</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">${title}</h3>
            <p class="text-gray-600 mb-4">${message}</p>
            <div class="text-sm text-gray-500">
                Перенаправлення через <span id="countdown">${delay/1000}</span> секунд...
            </div>
        </div>
    `;

            document.body.appendChild(toast);

            // Зворотний відлік
            let seconds = delay / 1000;
            const countdownEl = toast.querySelector('#countdown');
            const interval = setInterval(() => {
                seconds--;
                countdownEl.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(interval);
                }
            }, 1000);

            // Перенаправлення
            setTimeout(() => {
                window.location.href = redirectUrl;
            }, delay);
        }

    // 2. Покращений метод checkCSRFToken
        checkCSRFToken() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                this.showErrorMessage('Помилка безпеки. Будь ласка, оновіть сторінку.');
                return false;
            }
            return true;
        }

    // 3. Покращений makeRequest з кращою обробкою помилок
        async makeRequest(url, data) {
            if (!this.checkCSRFToken()) {
                throw new Error('CSRF токен відсутній');
            }

            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            const options = {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            };

            try {
                const response = await fetch(url, options);

                if (!response.ok) {
                    // Спробуємо отримати повідомлення про помилку з відповіді
                    let errorMessage = 'Виникла помилка на сервері';
                    try {
                        const errorData = await response.json();
                        errorMessage = errorData.message || errorData.error || errorMessage;
                    } catch (e) {
                        // Якщо не можемо парсити JSON, використовуємо стандартне повідомлення
                        if (response.status === 404) errorMessage = 'Сервіс тимчасово недоступний';
                        else if (response.status === 500) errorMessage = 'Внутрішня помилка сервера';
                        else if (response.status === 422) errorMessage = 'Некоректні дані';
                    }
                    throw new Error(errorMessage);
                }

                const result = await response.json();
                return result;
            } catch (error) {
                if (error instanceof TypeError && error.message.includes('fetch')) {
                    throw new Error('Проблеми з підключенням до інтернету');
                }
                throw error;
            }
        }

        showLoader(type, show = true) {
            const loader = this.elements[`${type}Loader`];
            if (loader) {
                loader.classList.toggle('hidden', !show);
            }
        }


        async searchSettlements(searchValue) {
            if (!searchValue.trim()) return;

            this.showLoader('search');

            try {
                const url = '{{ route('orders.searchSettlement') }}'; // Замініть на актуальний URL
                const response = await this.makeRequest(url, {
                    search: searchValue
                });

                if (!response.success) {
                    throw new Error(response.error || 'Не вдалося знайти населені пункти');
                }

                this.addressData.search = searchValue;
                this.settlementsData = response.settlements || [];

                if (this.settlementsData.length === 0) {
                    this.showInfoMessage('Населені пункти не знайдено. Спробуйте інший запит.');
                    this.hideSettlementSection();
                    return;
                }

                this.populateSettlementSelect();
                this.showSettlementSection();

            } catch (error) {
                this.showErrorMessage(error.message);
                this.hideSettlementSection();
            } finally {
                this.showLoader('search', false);
            }
        }

        populateSettlementSelect() {
            const select = this.elements.settlementSelect;
            select.innerHTML = '<option value="">Оберіть місто...</option>';

            this.settlementsData.forEach(settlement => {
                const option = document.createElement('option');
                option.value = settlement.Ref;
                option.textContent = settlement.Present;
                select.appendChild(option);
            });

            select.disabled = false;
        }

        showSettlementSection() {
            this.elements.settlementSection.classList.remove('hidden');
            this.hideWarehouseSection();
        }

        hideSettlementSection() {
            this.elements.settlementSection.classList.add('hidden');
            this.elements.settlementSelect.disabled = true;
            this.hideWarehouseSection();
        }

        async chooseSettlement(settlementRef) {
            this.showLoader('settlement');

            try {
                const url = '{{ route('orders.chooseSettlement') }}'; // Замініть на актуальний URL
                const response = await this.makeRequest(url, {
                    settlement: settlementRef
                });

                if (!response.success) {
                    throw new Error(response.error || 'Не вдалося завантажити відділення');
                }

                this.addressData.settlement = settlementRef;
                this.addressData.settlementName = this.elements.settlementSelect.selectedOptions[0]?.textContent || '';
                this.warehousesData = response.warehouses || [];

                if (this.warehousesData.length === 0) {
                    this.showInfoMessage('У цьому місті немає доступних відділень');
                    this.hideWarehouseSection();
                    return;
                }

                this.populateWarehouseSelect();
                this.showWarehouseSection();

            } catch (error) {
                this.showErrorMessage(error.message);
                this.hideWarehouseSection();
            } finally {
                this.showLoader('settlement', false);
            }
        }

        populateWarehouseSelect() {
            const select = this.elements.warehouseSelect;
            select.innerHTML = '<option value="">Оберіть відділення або поштомат...</option>';

            this.warehousesData.forEach(warehouse => {
                const option = document.createElement('option');
                option.value = warehouse.Ref;
                option.textContent = warehouse.Description;
                select.appendChild(option);
            });

            select.disabled = false;
        }

        showWarehouseSection() {
            this.elements.warehouseSection.classList.remove('hidden');
        }

        hideWarehouseSection() {
            this.elements.warehouseSection.classList.add('hidden');
            this.elements.warehouseSelect.disabled = true;
            this.hideOrderForm();
        }

        async setWarehouse(warehouseRef) {
            this.showLoader('warehouse');

            try {
                const url = '{{ route('orders.setWarehouse') }}'; // Замініть на актуальний URL
                const response = await this.makeRequest(url, {
                    warehouse: warehouseRef
                });

                if (!response.success) {
                    throw new Error('Не вдалося розрахувати вартість доставки');
                }

                this.addressData.warehouse = warehouseRef;
                this.addressData.warehouseName = this.elements.warehouseSelect.selectedOptions[0]?.textContent || '';

                this.updateDeliveryInfo(response);
                this.showAddressConfirmation();
                this.showOrderForm();
                this.activateSubmitButton();

                this.showSuccessMessage('Адресу доставки обрано успішно');

            } catch (error) {
                this.showErrorMessage(error.message);
                this.hideOrderForm();
            } finally {
                this.showLoader('warehouse', false);
            }
        }

        updateDeliveryInfo(response) {
            const deliveryCost = response.deliveryCost || 0;
            const productCosts = response.productCosts || 0;
            const total = productCosts + deliveryCost;

            this.elements.deliveryCost.textContent = `${deliveryCost} грн`;
            this.elements.totalAmount.textContent = `${total} грн`;
        }

        showAddressConfirmation() {
            const addressText = `${this.addressData.settlementName}, ${this.addressData.warehouseName}`;
            this.elements.addressText.textContent = addressText;
            this.elements.selectedAddress.classList.remove('hidden');
        }

        showOrderForm() {
            this.elements.orderFormSection.classList.remove('hidden');
        }

        hideOrderForm() {
            this.elements.orderFormSection.classList.add('hidden');
            this.deactivateSubmitButton();
        }

        activateSubmitButton() {
            const btn = this.elements.submitBtn;
            btn.disabled = false;
            btn.classList.remove('bg-gray-400');
            btn.classList.add('bg-blue-400', 'hover:bg-blue-500', 'active:bg-blue-600', 'transition-colors', 'focus:ring-4', 'focus:ring-blue-300');
            btn.textContent = 'Підтвердити замовлення';
        }

        deactivateSubmitButton() {
            const btn = this.elements.submitBtn;
            btn.disabled = true;
            btn.classList.remove('bg-blue-400', 'hover:bg-blue-500', 'active:bg-blue-600', 'transition-colors', 'focus:ring-4', 'focus:ring-blue-300');
            btn.classList.add('bg-gray-400');
            btn.textContent = 'Оберіть адресу доставки';
        }

        resetAddressSelection() {
            // Reset address data
            this.addressData = {
                search: '',
                settlement: '',
                warehouse: '',
                settlementName: '',
                warehouseName: ''
            };

            // Reset form elements
            this.elements.citySearch.value = '';
            this.elements.settlementSelect.innerHTML = '<option value="">Оберіть місто...</option>';
            this.elements.warehouseSelect.innerHTML = '<option value="">Оберіть відділення або поштомат...</option>';

            // Hide sections
            this.hideSettlementSection();
            this.hideWarehouseSection();
            this.hideOrderForm();
            this.elements.selectedAddress.classList.add('hidden');

            // Reset delivery info - you may need to adjust the default total
            this.elements.deliveryCost.textContent = '- грн';
            // Replace with actual cart total from session or variable
            this.elements.totalAmount.textContent = '0 грн'; // Replace with actual value

            // Focus on search
            this.elements.citySearch.focus();
        }

        validateForm() {
            const form = this.elements.orderForm;
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let errorMessages = [];

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500', 'border-2');
                    isValid = false;

                    const fieldName = field.placeholder || field.name;
                    errorMessages.push(`Поле "${fieldName}" обов'язкове для заповнення`);
                } else {
                    field.classList.remove('border-red-500', 'border-2');
                }
            });

            // Валідація телефону
            const phoneInput = form.querySelector('input[name="phone"]');
            if (phoneInput) {
                const phoneValue = phoneInput.value.replace(/\D/g, '');
                if (phoneValue.length !== 12 || !phoneValue.startsWith('380')) {
                    phoneInput.classList.add('border-red-500', 'border-2');
                    isValid = false;
                    errorMessages.push('Некоректний номер телефону. Введіть український номер');
                } else {
                    phoneInput.classList.remove('border-red-500', 'border-2');
                }
            }

            // Валідація email
            const emailInput = form.querySelector('input[name="email"]');
            if (emailInput) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(emailInput.value)) {
                    emailInput.classList.add('border-red-500', 'border-2');
                    isValid = false;
                    errorMessages.push('Введіть коректну електронну адресу');
                } else {
                    emailInput.classList.remove('border-red-500', 'border-2');
                }
            }

            if (!isValid && errorMessages.length > 0) {
                this.showErrorMessage(errorMessages[0]); // Показуємо першу помилку
            }

            return isValid;
        }

        async submitOrder() {
            if (!this.addressData.warehouse) {
                this.showErrorMessage('Оберіть адресу доставки перед оформленням замовлення');
                return;
            }

            if (!this.validateForm()) {
                return; // Помилки вже показані в validateForm
            }

            // Деактивуємо кнопку та показуємо процес
            this.elements.submitBtn.disabled = true;
            this.elements.submitBtn.textContent = 'Створення замовлення...';

            const formData = new FormData(this.elements.orderForm);
            formData.append('settlement', this.addressData.settlement);
            formData.append('warehouse', this.addressData.warehouse);

            const paymentMethod = formData.get('payment');

            try {
                if (paymentMethod === 'card') {
                    await this.handleCardPayment(formData);
                } else {
                    await this.handleCashPayment(formData);
                }
            } catch (error) {
                this.showErrorMessage(error.message || 'Виникла помилка при створенні замовлення');
            } finally {
                this.activateSubmitButton();
            }
        }

        async handleCashPayment(formData) {
            const url = '{{ route('orders.createCounterparty') }}'; // Замініть на актуальний URL

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                const data = await response.json();

                if (data.success) {
                    this.showSuccessToastWithRedirect(
                        'Замовлення успішно створено!',
                        `ТТН: ${data.ttn_number}`,
                        '{{ route('home') }}' // Замініть на актуальний маршрут
                    );
                } else {
                    throw new Error(data.message || 'Помилка створення замовлення');
                }
            } catch (error) {
                if (error instanceof TypeError) {
                    throw new Error('Проблеми з підключенням. Перевірте інтернет');
                }
                throw error;
            }
        }

        async handleCardPayment(formData) {
            const url = '{{ route('orders.createCounterparty') }}'; // Replace with actual URL
            const response = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success && data.payment_type === 'card') {
                this.createWayForPayForm(data.wayforpay_data);
            } else {
                throw new Error(data.message || 'Помилка підготовки оплати');
            }
        }

        createWayForPayForm(wayForPayData) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'https://secure.wayforpay.com/pay';
            form.acceptCharset = 'utf-8';
            form.style.display = 'none';

            for (const [key, value] of Object.entries(wayForPayData)) {
                if (Array.isArray(value)) {
                    value.forEach((item, index) => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = `${key}[]`;
                        input.value = item;
                        form.appendChild(input);
                    });
                } else {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = value;
                    form.appendChild(input);
                }
            }

            document.body.appendChild(form);

            this.showInfoMessage('Перенаправлення на платіжну систему...');

            // Submit form to WayForPay
            form.submit();

            // Clean up
            setTimeout(() => {
                document.body.removeChild(form);
            }, 1000);
        }

        // Helper method to get cart total (you might need to adjust this)
        getCartTotal() {
            // This should return the cart total from your backend or session
            // For now, returning 0 as placeholder
            return 0;
        }

        // Method to handle errors gracefully
        handleError(error, context = '') {
            console.error(`Error in ${context}:`, error);
            this.showDebug(`${context}: ${error.message}`, true);

            // You can add additional error reporting here
            // For example, sending error logs to your backend
        }
    }



    // Initialize the form manager when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        try {
            window.orderFormManager = new OrderFormManager();
        } catch (error) {
            console.error('Failed to initialize OrderFormManager:', error);

            // Show a user-friendly error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            errorDiv.innerHTML = `
            <strong class="font-bold">Помилка ініціалізації:</strong>
            <span class="block sm:inline">Сторінка не може працювати правильно. Будь ласка, оновіть сторінку.</span>
        `;

            const main = document.querySelector('main');
            if (main) {
                main.insertBefore(errorDiv, main.firstChild);
            }
        }
    });
</script> -->














<!-- <script>
class OrderFormManager {
    constructor() {
        this.addressData = { search: '', settlement: '', warehouse: '', settlementName: '', warehouseName: '' };
        this.settlementsData = [];
        this.warehousesData = [];
        this.searchTimeout = null;

        this.initializeElements();
        this.attachEventListeners();
        this.checkCSRFToken();
    }

    initializeElements() {
        const ids = ['city-search', 'settlement-select', 'warehouse-select', 'settlement-section',
                    'warehouse-section', 'order-form-section', 'selected-address', 'address-text',
                    'change-address', 'submit-btn', 'unified-order-form', 'delivery-cost',
                    'total-amount', 'search-loader', 'settlement-loader', 'warehouse-loader'];

        this.elements = {};
        ids.forEach(id => {
            this.elements[id.replace(/-([a-z])/g, (g) => g[1].toUpperCase())] = document.getElementById(id);
        });
    }

    attachEventListeners() {
        // City search with debounce
        this.elements.citySearch.addEventListener('input', (e) => {
            clearTimeout(this.searchTimeout);
            const value = e.target.value.trim();

            if (value.length >= 2) {
                this.searchTimeout = setTimeout(() => this.searchSettlements(value), 500);
            } else {
                this.toggleSection('settlement', false);
            }
        });

        // Selection handlers
        this.elements.settlementSelect.addEventListener('change', (e) =>
            e.target.value && this.chooseSettlement(e.target.value));

        this.elements.warehouseSelect.addEventListener('change', (e) =>
            e.target.value && this.setWarehouse(e.target.value));

        // Other handlers
        this.elements.changeAddress.addEventListener('click', () => this.resetAddressSelection());
        this.elements.unifiedOrderForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.submitOrder();
        });

        // Phone formatting
        const phoneInput = this.elements.unifiedOrderForm.querySelector('input[name="phone"]');
        phoneInput?.addEventListener('input', (e) => this.formatPhoneNumber(e.target));
    }

    formatPhoneNumber(input) {
        let value = input.value.replace(/\D/g, '');

        if (!value.startsWith('380')) {
            if (value.startsWith('0')) value = '380' + value.substring(1);
            else if (value.length > 0) value = '380' + value;
        }

        value = value.substring(0, 12);

        let formatted = '+380';
        if (value.length > 3) formatted += ' ' + value.substring(3, 5);
        if (value.length > 5) formatted += ' ' + value.substring(5, 8);
        if (value.length > 8) formatted += ' ' + value.substring(8, 10);
        if (value.length > 10) formatted += ' ' + value.substring(10, 12);

        input.value = formatted;
    }

    showToast(message, type = 'info') {
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white'
        };
        const icons = { success: '✓', error: '✕', info: 'ℹ' };

        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 translate-x-full ${colors[type]}`;
        toast.innerHTML = `
            <div class="flex items-center gap-3">
                <span class="text-lg font-bold">${icons[type]}</span>
                <span>${message}</span>
                <button class="ml-2 text-xl font-bold opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

        document.body.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            toast.classList.add('translate-x-full');
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    }

    showSuccessMessage(msg) { this.showToast(msg, 'success'); }
    showErrorMessage(msg) { this.showToast(msg, 'error'); }
    showInfoMessage(msg) { this.showToast(msg, 'info'); }

    showSuccessToastWithRedirect(title, message, redirectUrl, delay = 3000) {
        const toast = document.createElement('div');
        toast.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
        toast.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4 text-center">
                <div class="text-green-500 text-6xl mb-4">✓</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">${title}</h3>
                <p class="text-gray-600 mb-4">${message}</p>
                <div class="text-sm text-gray-500">
                    Перенаправлення через <span id="countdown">${delay/1000}</span> секунд...
                </div>
            </div>
        `;

        document.body.appendChild(toast);

        let seconds = delay / 1000;
        const countdownEl = toast.querySelector('#countdown');
        const interval = setInterval(() => {
            seconds--;
            countdownEl.textContent = seconds;
            if (seconds <= 0) clearInterval(interval);
        }, 1000);

        setTimeout(() => window.location.href = redirectUrl, delay);
    }

    checkCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        if (!token) {
            this.showErrorMessage('Помилка безпеки. Будь ласка, оновіть сторінку.');
            return false;
        }
        return true;
    }

    async makeRequest(url, data) {
        if (!this.checkCSRFToken()) throw new Error('CSRF токен відсутній');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                let errorMessage = 'Виникла помилка на сервері';
                try {
                    const errorData = await response.json();
                    errorMessage = errorData.message || errorData.error || errorMessage;
                } catch (e) {
                    const messages = {
                        404: 'Сервіс тимчасово недоступний',
                        500: 'Внутрішня помилка сервера',
                        422: 'Некоректні дані'
                    };
                    errorMessage = messages[response.status] || errorMessage;
                }
                throw new Error(errorMessage);
            }

            return await response.json();
        } catch (error) {
            if (error instanceof TypeError && error.message.includes('fetch')) {
                throw new Error('Проблеми з підключенням до інтернету');
            }
            throw error;
        }
    }

    showLoader(type, show = true) {
        const loader = this.elements[`${type}Loader`];
        loader?.classList.toggle('hidden', !show);
    }

    toggleSection(type, show) {
        const section = this.elements[`${type}Section`];
        const select = this.elements[`${type}Select`];

        section?.classList.toggle('hidden', !show);
        if (select) select.disabled = !show;

        if (type === 'settlement' && !show) this.toggleSection('warehouse', false);
        if (type === 'warehouse' && !show) this.hideOrderForm();
    }

    async searchSettlements(searchValue) {
        if (!searchValue.trim()) return;

        this.showLoader('search');

        try {
            const response = await this.makeRequest('{{ route('orders.searchSettlement') }}', {
                search: searchValue
            });

            if (!response.success) throw new Error(response.error || 'Не вдалося знайти населені пункти');

            this.addressData.search = searchValue;
            this.settlementsData = response.settlements || [];

            if (this.settlementsData.length === 0) {
                this.showInfoMessage('Населені пункти не знайдено. Спробуйте інший запит.');
                this.toggleSection('settlement', false);
                return;
            }

            this.populateSelect('settlement', this.settlementsData, 'Оберіть місто...');
            this.toggleSection('settlement', true);

        } catch (error) {
            this.showErrorMessage(error.message);
            this.toggleSection('settlement', false);
        } finally {
            this.showLoader('search', false);
        }
    }

    populateSelect(type, data, placeholder, valueKey = 'Ref', textKey = 'Present') {
        const select = this.elements[`${type}Select`];
        select.innerHTML = `<option value="">${placeholder}</option>`;

        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = type === 'warehouse' ? item.Description : item[textKey];
            select.appendChild(option);
        });

        select.disabled = false;
    }

    async chooseSettlement(settlementRef) {
        this.showLoader('settlement');

        try {
            const response = await this.makeRequest('{{ route('orders.chooseSettlement') }}', {
                settlement: settlementRef
            });

            if (!response.success) throw new Error(response.error || 'Не вдалося завантажити відділення');

            this.addressData.settlement = settlementRef;
            this.addressData.settlementName = this.elements.settlementSelect.selectedOptions[0]?.textContent || '';
            this.warehousesData = response.warehouses || [];

            if (this.warehousesData.length === 0) {
                this.showInfoMessage('У цьому місті немає доступних відділень');
                this.toggleSection('warehouse', false);
                return;
            }

            this.populateSelect('warehouse', this.warehousesData, 'Оберіть відділення або поштомат...');
            this.toggleSection('warehouse', true);

        } catch (error) {
            this.showErrorMessage(error.message);
            this.toggleSection('warehouse', false);
        } finally {
            this.showLoader('settlement', false);
        }
    }

    async setWarehouse(warehouseRef) {
        this.showLoader('warehouse');

        try {
            const response = await this.makeRequest('{{ route('orders.setWarehouse') }}', {
                warehouse: warehouseRef
            });

            if (!response.success) throw new Error('Не вдалося розрахувати вартість доставки');

            this.addressData.warehouse = warehouseRef;
            this.addressData.warehouseName = this.elements.warehouseSelect.selectedOptions[0]?.textContent || '';

            this.updateDeliveryInfo(response);
            this.showAddressConfirmation();
            this.showOrderForm();
            this.toggleSubmitButton(true);

            this.showSuccessMessage('Адресу доставки обрано успішно');

        } catch (error) {
            this.showErrorMessage(error.message);
            this.hideOrderForm();
        } finally {
            this.showLoader('warehouse', false);
        }
    }

    updateDeliveryInfo(response) {
        const deliveryCost = response.deliveryCost || 0;
        const productCosts = response.productCosts || 0;

        this.elements.deliveryCost.textContent = `${deliveryCost} грн`;
        this.elements.totalAmount.textContent = `${productCosts + deliveryCost} грн`;
    }

    showAddressConfirmation() {
        this.elements.addressText.textContent = `${this.addressData.settlementName}, ${this.addressData.warehouseName}`;
        this.elements.selectedAddress.classList.remove('hidden');
    }

    showOrderForm() {
        this.elements.orderFormSection.classList.remove('hidden');
    }

    hideOrderForm() {
        this.elements.orderFormSection.classList.add('hidden');
        this.toggleSubmitButton(false);
    }

    toggleSubmitButton(active) {
        const btn = this.elements.submitBtn;
        btn.disabled = !active;

        if (active) {
            btn.className = btn.className.replace('bg-gray-400', 'bg-blue-400 hover:bg-blue-500 active:bg-blue-600 transition-colors focus:ring-4 focus:ring-blue-300');
            btn.textContent = 'Підтвердити замовлення';
        } else {
            btn.className = btn.className.replace(/bg-blue-\d+.*?focus:ring-blue-300/g, 'bg-gray-400');
            btn.textContent = 'Оберіть адресу доставки';
        }
    }

    resetAddressSelection() {
        this.addressData = { search: '', settlement: '', warehouse: '', settlementName: '', warehouseName: '' };

        this.elements.citySearch.value = '';
        this.elements.settlementSelect.innerHTML = '<option value="">Оберіть місто...</option>';
        this.elements.warehouseSelect.innerHTML = '<option value="">Оберіть відділення або поштомат...</option>';

        this.toggleSection('settlement', false);
        this.toggleSection('warehouse', false);
        this.hideOrderForm();
        this.elements.selectedAddress.classList.add('hidden');

        this.elements.deliveryCost.textContent = '- грн';
        this.elements.totalAmount.textContent = '0 грн';

        this.elements.citySearch.focus();
    }

    validateForm() {
        const form = this.elements.unifiedOrderForm;
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        let errorMessages = [];

        requiredFields.forEach(field => {
            const hasValue = field.value.trim();
            field.classList.toggle('border-red-500', !hasValue);
            field.classList.toggle('border-2', !hasValue);

            if (!hasValue) {
                isValid = false;
                errorMessages.push(`Поле "${field.placeholder || field.name}" обов'язкове для заповнення`);
            }
        });

        // Phone validation
        const phoneInput = form.querySelector('input[name="phone"]');
        if (phoneInput) {
            const phoneValue = phoneInput.value.replace(/\D/g, '');
            const isValidPhone = phoneValue.length === 12 && phoneValue.startsWith('380');
            phoneInput.classList.toggle('border-red-500', !isValidPhone);
            phoneInput.classList.toggle('border-2', !isValidPhone);

            if (!isValidPhone) {
                isValid = false;
                errorMessages.push('Некоректний номер телефону. Введіть український номер');
            }
        }

        // Email validation
        const emailInput = form.querySelector('input[name="email"]');
        if (emailInput) {
            const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailInput.value);
            emailInput.classList.toggle('border-red-500', !isValidEmail);
            emailInput.classList.toggle('border-2', !isValidEmail);

            if (!isValidEmail) {
                isValid = false;
                errorMessages.push('Введіть коректну електронну адресу');
            }
        }

        if (!isValid && errorMessages.length > 0) {
            this.showErrorMessage(errorMessages[0]);
        }

        return isValid;
    }

    async submitOrder() {
        if (!this.addressData.warehouse) {
            this.showErrorMessage('Оберіть адресу доставки перед оформленням замовлення');
            return;
        }

        if (!this.validateForm()) return;

        this.elements.submitBtn.disabled = true;
        this.elements.submitBtn.textContent = 'Створення замовлення...';

        const formData = new FormData(this.elements.unifiedOrderForm);
        formData.append('settlement', this.addressData.settlement);
        formData.append('warehouse', this.addressData.warehouse);

        try {
            if (formData.get('payment') === 'card') {
                await this.handleCardPayment(formData);
            } else {
                await this.handleCashPayment(formData);
            }
        } catch (error) {
            this.showErrorMessage(error.message || 'Виникла помилка при створенні замовлення');
        } finally {
            this.toggleSubmitButton(true);
        }
    }

    async handleCashPayment(formData) {
        try {
            const response = await fetch('{{ route('orders.createCounterparty') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (data.success) {
                this.showSuccessToastWithRedirect(
                    'Замовлення успішно створено!',
                    `ТТН: ${data.ttn_number}`,
                    '{{ route('home') }}'
                );
            } else {
                throw new Error(data.message || 'Помилка створення замовлення');
            }
        } catch (error) {
            if (error instanceof TypeError) {
                throw new Error('Проблеми з підключенням. Перевірте інтернет');
            }
            throw error;
        }
    }

    async handleCardPayment(formData) {
        const response = await fetch('{{ route('orders.createCounterparty') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success && data.payment_type === 'card') {
            this.createWayForPayForm(data.wayforpay_data);
        } else {
            throw new Error(data.message || 'Помилка підготовки оплати');
        }
    }

    createWayForPayForm(wayForPayData) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'https://secure.wayforpay.com/pay';
        form.acceptCharset = 'utf-8';
        form.style.display = 'none';

        Object.entries(wayForPayData).forEach(([key, value]) => {
            if (Array.isArray(value)) {
                value.forEach(item => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = `${key}[]`;
                    input.value = item;
                    form.appendChild(input);
                });
            } else {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }
        });

        document.body.appendChild(form);
        this.showInfoMessage('Перенаправлення на платіжну систему...');
        form.submit();
        setTimeout(() => form.remove(), 1000);
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    try {
        window.orderFormManager = new OrderFormManager();
    } catch (error) {
        console.error('Failed to initialize OrderFormManager:', error);

        const errorDiv = document.createElement('div');
        errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        errorDiv.innerHTML = `
            <strong class="font-bold">Помилка ініціалізації:</strong>
            <span class="block sm:inline">Сторінка не може працювати правильно. Будь ласка, оновіть сторінку.</span>
        `;

        const main = document.querySelector('main');
        main?.insertBefore(errorDiv, main.firstChild);
    }
});
</script> -->


@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/index.js', 'resources/js/order.js'])

<script>
    // Передаємо маршрути з Laravel у JavaScript
    const routes = {
        searchSettlement: '{{ route('orders.searchSettlement') }}',
        chooseSettlement: '{{ route('orders.chooseSettlement') }}',
        setWarehouse: '{{ route('orders.setWarehouse') }}',
        createCounterparty: '{{ route('orders.createCounterparty') }}',
        home: '{{ route('home') }}'
    };
</script>


{{-- Ініціалізуємо клас після завантаження DOM --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Додаткова перевірка чи завантажився клас
        if (typeof OrderFormManager === 'undefined') {
            console.error('OrderFormManager class not loaded!');
            alert('Помилка завантаження скрипту. Перевірте підключення до інтернету та оновіть сторінку.');
            return;
        }

        try {
            window.orderFormManager = new OrderFormManager(routes);
            console.log('OrderFormManager initialized successfully');
        } catch (error) {
            console.error('Failed to initialize OrderFormManager:', error);

            const errorDiv = document.createElement('div');
            errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
            errorDiv.innerHTML = `
                <strong class="font-bold">Помилка ініціалізації:</strong>
                <span class="block sm:inline">Сторінка не може працювати правильно. Будь ласка, оновіть сторінку.</span>
            `;

            const main = document.querySelector('main');
            main?.insertBefore(errorDiv, main.firstChild);
        }
    });
</script>



@endsection
