@extends('layouts.site')

@section('header')
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Navigation -->
        <nav class="mb-6">
            <ul class="flex flex-wrap text-lg sm:text-xl text-slate-600 items-center justify-center sm:justify-start gap-2 sm:gap-4">
                <li class="hover:text-slate-800 cursor-pointer">
                    <a href="{{ route('home') }}">головна</a>
                </li>
                <li class="hover:text-slate-800 cursor-pointer">
                    <a href="{{ route('product.show', $cart['product']->id) }}">замовлення</a>
                </li>
                <li class="text-yellow-400 text-xl sm:text-2xl font-semibold">оформлення</li>
            </ul>
        </nav>
    </div>
@endsection

@section('content')

    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="w-full">

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

                            <!-- Settlement Selection with Search -->
                            <div class="mb-4">
                                <div class="searchable-select">
                                    <div class="relative">
                                        <input
                                            id="settlement-input"
                                            class="tracking-normal select-input w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black bg-white cursor-pointer"
                                            type="text"
                                            placeholder="Оберіть місто..."
                                            autocomplete="off"
                                            readonly>
                                        <div id="settlement-loader"
                                             class="absolute right-6 sm:right-10 top-1/2 transform -translate-y-1/2 hidden">
                                            <div
                                                class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                        </div>
                                        <div
                                            class="absolute right-6 sm:right-10 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div id="settlement-dropdown" class="select-dropdown hidden">
                                        <input
                                            id="settlement-search"
                                            class="search-input"
                                            type="text"
                                            placeholder="Введіть назву міста (мін. 2 символи)..."
                                            autocomplete="off">
                                        <div id="settlement-options" class="options-container"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Warehouse Selection with Search -->
                            <div class="mb-4">
                                <div class="searchable-select">
                                    <div class="relative">
                                        <input
                                            id="warehouse-input"
                                            class="tracking-normal select-input w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black bg-gray-50 cursor-pointer"
                                            type="text"
                                            placeholder="Спочатку оберіть місто"
                                            autocomplete="off"
                                            readonly
                                            disabled>
                                        <div id="warehouse-loader"
                                             class="absolute right-6 sm:right-10 top-1/2 transform -translate-y-1/2 hidden">
                                            <div
                                                class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                        </div>
                                        <div
                                            class="absolute right-6 sm:right-10 top-1/2 transform -translate-y-1/2 pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </div>
                                    </div>

                                    <div id="warehouse-dropdown" class="select-dropdown hidden">
                                        <input
                                            id="warehouse-search"
                                            class="search-input"
                                            type="text"
                                            placeholder="Пошук відділення або поштомату..."
                                            autocomplete="off">
                                        <div id="warehouse-options" class="options-container"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Selected Address Display -->
                            <div id="selected-address" class="hidden bg-green-100 text-green-800 p-3 rounded-lg">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                         viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <div>
                                        <div class="font-semibold">Обрана адреса:</div>
                                        <div id="address-text" class="text-sm"></div>
                                        <button id="change-address"
                                                class="text-blue-600 hover:text-blue-800 text-sm underline mt-1">
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
                                        <label
                                            class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors cursor-pointer">
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

                                        <label
                                            class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors cursor-pointer">
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
                                <span
                                    class="underline hover:text-slate-800 cursor-pointer">політики конфіденційності</span>
                                та
                                <span class="underline hover:text-slate-800 cursor-pointer">угоди користувача</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Конфігурація роутів - винеси це в окремий об'єкт для легкого відокремлення
        const ORDER_CONFIG = {
            routes: {
                searchSettlement: '{{ route("orders.searchSettlement") }}',
                chooseSettlement: '{{ route("orders.chooseSettlement") }}',
                setWarehouse: '{{ route("orders.setWarehouse") }}',
                createCounterparty: '{{ route("orders.createCounterparty") }}',
                orderStatus: '/api/orders/status/',
                home: '{{ route("home") }}'
            },
            constants: {
                MIN_SEARCH_LENGTH: 2,
                SEARCH_DELAY: 500,
                REDIRECT_DELAY: 3000,
                STATUS_CHECK_DELAY: 2000
            }
        };

        class OrderFormManager {
            constructor() {
                this.elements = this.initElements();
                this.state = this.initState();
                this.searchTimeout = null;

                this.init();
            }

            initElements() {
                const ids = [
                    'settlement-input', 'settlement-dropdown', 'settlement-search', 'settlement-options', 'settlement-loader',
                    'warehouse-input', 'warehouse-dropdown', 'warehouse-search', 'warehouse-options', 'warehouse-loader',
                    'selected-address', 'address-text', 'change-address',
                    'unified-order-form', 'order-form-section', 'submit-btn',
                    'delivery-cost', 'total-amount'
                ];

                return ids.reduce((els, id) => {
                    const key = id.replace(/-([a-z])/g, (_, letter) => letter.toUpperCase());
                    els[key] = document.getElementById(id);
                    return els;
                }, {});
            }

            initState() {
                return {
                    address: { search: '', settlement: '', settlementName: '', warehouse: '', warehouseName: '' },
                    settlements: [],
                    warehouses: [],
                    filteredSettlements: [],
                    filteredWarehouses: []
                };
            }

            init() {
                this.attachEventListeners();
                this.initializeFormState();
            }

            initializeFormState() {
                this.hideOrderForm();
                this.disableWarehouseSelect();
            }

            attachEventListeners() {
                // Settlement events
                this.elements.settlementInput?.addEventListener('click', () => this.toggleDropdown('settlement'));
                this.elements.settlementSearch?.addEventListener('input', (e) => this.handleSearchInput(e, 'settlement'));

                // Warehouse events
                this.elements.warehouseInput?.addEventListener('click', () => {
                    if (!this.elements.warehouseInput.disabled) this.toggleDropdown('warehouse');
                });
                this.elements.warehouseSearch?.addEventListener('input', (e) => this.handleSearchInput(e, 'warehouse'));

                // Phone formatting
                const phoneInput = this.elements.unifiedOrderForm?.querySelector('input[name="phone"]');
                if (phoneInput) {
                    phoneInput.addEventListener('input', (e) => this.formatPhoneNumber(e.target));
                    phoneInput.addEventListener('blur', (e) => this.validatePhone(e.target));
                }

                // Other events
                document.addEventListener('click', this.handleOutsideClick.bind(this));
                this.elements.changeAddress?.addEventListener('click', () => this.resetAddressSelection());
                this.elements.unifiedOrderForm?.addEventListener('submit', (e) => this.handleFormSubmit(e));
            }

            handleSearchInput(e, type) {
                const value = e.target.value.trim();

                if (type === 'settlement') {
                    clearTimeout(this.searchTimeout);
                    if (value.length >= ORDER_CONFIG.constants.MIN_SEARCH_LENGTH) {
                        this.searchTimeout = setTimeout(() => this.searchSettlements(value), ORDER_CONFIG.constants.SEARCH_DELAY);
                    } else {
                        this.renderOptions('settlement', []);
                    }
                } else if (type === 'warehouse') {
                    this.filterWarehouses(value);
                }
            }

            handleOutsideClick(e) {
                if (!e.target.closest('.searchable-select')) {
                    this.closeAllDropdowns();
                }
            }

            handleFormSubmit(e) {
                e.preventDefault();
                this.submitOrder();
            }

            // Phone validation and formatting
            formatPhoneNumber(input) {
                let value = input.value.replace(/\D/g, '');

                // Автоматично додаємо 380 якщо номер починається з 0 або порожній
                if (value.startsWith('0')) {
                    value = '380' + value.substring(1);
                } else if (value.length > 0 && !value.startsWith('380')) {
                    value = '380' + value;
                }

                // Обмежуємо довжину
                value = value.substring(0, 12);

                // Форматуємо
                if (value.length > 0) {
                    let formatted = '+380';
                    if (value.length > 3) formatted += value.substring(3, 5);
                    if (value.length > 5) formatted += value.substring(5, 8);
                    if (value.length > 8) formatted += value.substring(8, 10);
                    if (value.length > 10) formatted += value.substring(10, 12);

                    input.value = formatted;
                }
            }

            validatePhone(input) {
                const value = input.value.replace(/\D/g, '');
                const isValid = value.length === 12 && value.startsWith('380');

                this.toggleFieldError(input, !isValid, 'Введіть коректний український номер телефону');
                return isValid;
            }

            validateEmail(input) {
                const isValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(input.value);
                this.toggleFieldError(input, !isValid, 'Введіть коректну електронну адресу');
                return isValid;
            }

            toggleFieldError(field, hasError, message = '') {
                field.classList.toggle('border-red-500', hasError);
                field.classList.toggle('border-2', hasError);

                // Видаляємо попереднє повідомлення про помилку
                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) existingError.remove();

                // Додаємо нове повідомлення про помилку
                if (hasError && message) {
                    const errorEl = document.createElement('div');
                    errorEl.className = 'field-error text-red-500 text-sm mt-1';
                    errorEl.textContent = message;
                    field.parentNode.appendChild(errorEl);
                }
            }

            // Dropdown management
            toggleDropdown(type) {
                const dropdown = this.elements[`${type}Dropdown`];
                if (!dropdown) return;

                const isOpen = !dropdown.classList.contains('hidden');
                this.closeAllDropdowns();

                if (!isOpen) {
                    dropdown.classList.remove('hidden');
                    const searchInput = this.elements[`${type}Search`];
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.value = '';
                        if (type === 'settlement') {
                            searchInput.placeholder = 'Введіть назву міста (мін. 2 символи)...';
                        }
                    }
                    if (type === 'warehouse') this.filterWarehouses('');
                }
            }

            closeAllDropdowns() {
                ['settlement', 'warehouse'].forEach(type => {
                    this.elements[`${type}Dropdown`]?.classList.add('hidden');
                });
            }

            // Search and selection
            async searchSettlements(searchValue) {
                if (!searchValue.trim()) return;

                this.showLoader('settlement');
                try {
                    const response = await this.makeRequest(ORDER_CONFIG.routes.searchSettlement, { search: searchValue });

                    if (!response.success) throw new Error(response.error || 'Не вдалося знайти населені пункти');

                    this.state.address.search = searchValue;
                    this.state.settlements = response.settlements || [];
                    this.state.filteredSettlements = this.state.settlements;

                    if (this.state.settlements.length === 0) {
                        this.showInfoMessage('Населені пункти не знайдено. Спробуйте інший запит.');
                    }

                    this.renderOptions('settlement', this.state.filteredSettlements);
                } catch (error) {
                    this.showErrorMessage(error.message);
                    this.renderOptions('settlement', []);
                } finally {
                    this.hideLoader('settlement');
                }
            }

            filterWarehouses(searchValue) {
                if (!this.state.warehouses.length) return;

                this.state.filteredWarehouses = searchValue.trim()
                    ? this.state.warehouses.filter(warehouse =>
                        warehouse.Description.toLowerCase().includes(searchValue.toLowerCase())
                    )
                    : this.state.warehouses;

                this.renderOptions('warehouse', this.state.filteredWarehouses);
            }

            renderOptions(type, options) {
                const container = this.elements[`${type}Options`];
                if (!container) return;

                container.innerHTML = '';

                if (options.length === 0) {
                    const noResults = type === 'settlement'
                        ? 'Введіть назву міста для пошуку...'
                        : 'Нічого не знайдено';
                    container.innerHTML = `<div class="no-results">${noResults}</div>`;
                    return;
                }

                options.forEach(option => {
                    const div = document.createElement('div');
                    div.className = 'select-option';
                    div.textContent = type === 'warehouse' ? option.Description : option.Present;
                    div.addEventListener('click', () => this.selectOption(type, option));
                    container.appendChild(div);
                });
            }

            async selectOption(type, option) {
                this.closeAllDropdowns();

                if (type === 'settlement') {
                    await this.chooseSettlement(option.Ref, option.Present);
                } else if (type === 'warehouse') {
                    await this.setWarehouse(option.Ref, option.Description);
                }
            }

            async chooseSettlement(settlementRef, settlementName) {
                this.showLoader('settlement');

                try {
                    const response = await this.makeRequest(ORDER_CONFIG.routes.chooseSettlement, { settlement: settlementRef });

                    if (!response.success) throw new Error(response.error || 'Не вдалося завантажити відділення');

                    this.state.address.settlement = settlementRef;
                    this.state.address.settlementName = settlementName;

                    if (this.elements.settlementInput) {
                        this.elements.settlementInput.value = settlementName;
                    }

                    this.state.warehouses = response.warehouses || [];
                    this.state.filteredWarehouses = this.state.warehouses;

                    if (this.state.warehouses.length === 0) {
                        this.showInfoMessage('У цьому місті немає доступних відділень');
                        this.disableWarehouseSelect();
                    } else {
                        this.enableWarehouseSelect();
                    }
                } catch (error) {
                    this.showErrorMessage(error.message);
                    this.disableWarehouseSelect();
                } finally {
                    this.hideLoader('settlement');
                }
            }

            async setWarehouse(warehouseRef, warehouseName) {
                this.showLoader('warehouse');

                try {
                    const response = await this.makeRequest(ORDER_CONFIG.routes.setWarehouse, { warehouse: warehouseRef });

                    if (!response.success) throw new Error('Не вдалося розрахувати вартість доставки');

                    this.state.address.warehouse = warehouseRef;
                    this.state.address.warehouseName = warehouseName;

                    if (this.elements.warehouseInput) {
                        this.elements.warehouseInput.value = warehouseName;
                    }

                    this.updateDeliveryInfo(response);
                    this.showAddressConfirmation();
                    this.showOrderForm();
                    this.toggleSubmitButton(true);
                    this.showSuccessMessage('Адресу доставки обрано успішно');
                } catch (error) {
                    this.showErrorMessage(error.message);
                    this.hideOrderForm();
                } finally {
                    this.hideLoader('warehouse');
                }
            }

            // UI State Management
            enableWarehouseSelect() {
                if (!this.elements.warehouseInput) return;

                Object.assign(this.elements.warehouseInput, {
                    disabled: false,
                    placeholder: 'Оберіть відділення або поштомат...'
                });

                this.elements.warehouseInput.classList.remove('bg-gray-50');
                this.elements.warehouseInput.classList.add('bg-white');
            }

            disableWarehouseSelect() {
                if (!this.elements.warehouseInput) return;

                Object.assign(this.elements.warehouseInput, {
                    disabled: true,
                    placeholder: 'Спочатку оберіть місто',
                    value: ''
                });

                this.elements.warehouseInput.classList.add('bg-gray-50');
                this.elements.warehouseInput.classList.remove('bg-white');
            }

            showAddressConfirmation() {
                const { settlementName, warehouseName } = this.state.address;
                if (settlementName && warehouseName) {
                    const addressText = `${settlementName}, ${warehouseName}`;

                    if (this.elements.addressText) {
                        this.elements.addressText.textContent = addressText;
                    }
                    if (this.elements.selectedAddress) {
                        this.elements.selectedAddress.classList.remove('hidden');
                    }
                }
            }

            resetAddressSelection() {
                this.state.address = { search: '', settlement: '', settlementName: '', warehouse: '', warehouseName: '' };
                this.state.settlements = [];
                this.state.warehouses = [];
                this.state.filteredSettlements = [];
                this.state.filteredWarehouses = [];

                ['settlementInput', 'warehouseInput'].forEach(input => {
                    if (this.elements[input]) this.elements[input].value = '';
                });

                this.disableWarehouseSelect();
                this.elements.selectedAddress?.classList.add('hidden');
                this.closeAllDropdowns();
                this.hideOrderForm();
                this.toggleSubmitButton(false);

                // Reset delivery costs
                this.safeUpdateElement('deliveryCost', '0 грн');
                this.safeUpdateElement('totalAmount', '0 грн');
            }

            // Loaders and UI updates
            showLoader(type) {
                this.elements[`${type}Loader`]?.classList.remove('hidden');
            }

            hideLoader(type) {
                this.elements[`${type}Loader`]?.classList.add('hidden');
            }

            updateDeliveryInfo(response) {
                const deliveryCost = response.deliveryCost || 0;
                const productCosts = response.productCosts || 0;

                this.safeUpdateElement('deliveryCost', `${deliveryCost} грн`);
                this.safeUpdateElement('totalAmount', `${productCosts + deliveryCost} грн`);
            }

            showOrderForm() {
                this.elements.orderFormSection?.classList.remove('hidden');
            }

            hideOrderForm() {
                this.elements.orderFormSection?.classList.add('hidden');
                this.toggleSubmitButton(false);
            }

            toggleSubmitButton(active) {
                const btn = this.elements.submitBtn;
                if (!btn) return;

                btn.disabled = !active;

                if (active) {
                    btn.className = btn.className.replace('bg-gray-400', 'bg-blue-400 hover:bg-blue-500 active:bg-blue-600 transition-colors focus:ring-4 focus:ring-blue-300');
                    btn.textContent = 'Підтвердити замовлення';
                } else {
                    btn.className = btn.className.replace(/bg-blue-\d+.*?focus:ring-blue-300/g, 'bg-gray-400');
                    btn.textContent = 'Оберіть адресу доставки';
                }
            }

            safeUpdateElement(elementKey, content) {
                const element = this.elements[elementKey];
                if (element) {
                    element.textContent = content;
                } else {
                    console.warn(`Element ${elementKey} not found in DOM`);
                }
            }

            // Form validation and submission
            validateForm() {
                const form = this.elements.unifiedOrderForm;
                if (!form) return false;

                let isValid = true;
                const errors = [];

                // Required fields
                const requiredFields = form.querySelectorAll('[required]');
                requiredFields.forEach(field => {
                    const hasValue = field.value.trim();
                    this.toggleFieldError(field, !hasValue, `Поле "${field.placeholder || field.name}" обов'язкове`);
                    if (!hasValue) {
                        isValid = false;
                        errors.push(`Поле "${field.placeholder || field.name}" обов'язкове`);
                    }
                });

                // Phone validation
                const phoneInput = form.querySelector('input[name="phone"]');
                if (phoneInput && !this.validatePhone(phoneInput)) {
                    isValid = false;
                }

                // Email validation
                const emailInput = form.querySelector('input[name="email"]');
                if (emailInput && emailInput.value && !this.validateEmail(emailInput)) {
                    isValid = false;
                }

                if (!isValid && errors.length > 0) {
                    this.showErrorMessage(errors[0]);
                }

                return isValid;
            }

            async submitOrder() {
                if (!this.state.address.warehouse) {
                    this.showErrorMessage('Оберіть адресу доставки перед оформленням замовлення');
                    return;
                }

                if (!this.validateForm()) return;

                this.setSubmitButtonLoading(true);

                const formData = new FormData(this.elements.unifiedOrderForm);
                formData.append('settlement', this.state.address.settlement);
                formData.append('warehouse', this.state.address.warehouse);

                try {
                    if (formData.get('payment') === 'card') {
                        await this.handleCardPayment(formData);
                    } else {
                        await this.handleCashPayment(formData);
                    }
                } catch (error) {
                    this.showErrorMessage(error.message || 'Виникла помилка при створенні замовлення');
                } finally {
                    this.setSubmitButtonLoading(false);
                }
            }

            setSubmitButtonLoading(loading) {
                if (!this.elements.submitBtn) return;

                this.elements.submitBtn.disabled = loading;
                this.elements.submitBtn.textContent = loading ? 'Створення замовлення...' : 'Підтвердити замовлення';
            }

            async handleCashPayment(formData) {
                const response = await this.makeFormRequest(ORDER_CONFIG.routes.createCounterparty, formData);

                if (response.success) {
                    // Замість showSuccessToastWithRedirect використовуємо новий метод
                    this.showOrderSuccessPopup(
                        'Замовлення успішно створено!',
                        `ТТН: ${response.ttn_number}`,
                        response.ttn_number
                    );
                } else {
                    throw new Error(response.message || 'Помилка створення замовлення');
                }
            }

            async handleCardPayment(formData) {
                this.showInfoMessage('Підготовка до оплати...');

                const response = await this.makeFormRequest(ORDER_CONFIG.routes.createCounterparty, formData);

                if (response.success && response.payment_type === 'card') {
                    this.initWayForPay(response.wayforpay_data);
                } else {
                    throw new Error(response.message || 'Помилка підготовки оплати');
                }
            }

            // Payment handling
            initWayForPay(config) {
                if (!window.Wayforpay) {
                    const script = document.createElement('script');
                    script.src = 'https://secure.wayforpay.com/server/pay-widget.js';
                    script.onload = () => this.runWayForPay(config);
                    script.onerror = () => this.showErrorMessage('Помилка завантаження платіжного віджета');
                    document.head.appendChild(script);
                } else {
                    this.runWayForPay(config);
                }
            }

            runWayForPay(config) {
                const wayforpay = new Wayforpay();
                wayforpay.run(
                    config,
                    (response) => this.handlePaymentSuccess(response),
                    (response) => this.handlePaymentError(response)
                );
            }

            async handlePaymentSuccess(response) {
                this.showSuccessMessage('Оплата успішна! Ваше замовлення створюється...');

                try {
                    await this.checkOrderStatus(response.orderReference);
                } catch (error) {
                    console.error('Error after payment:', error);
                    this.showOrderSuccessPopup(
                        'Оплата успішна!',
                        'Ваше замовлення буде оброблено найближчим часом.'
                    );
                }
            }

            async checkOrderStatus(orderReference) {
                await new Promise(resolve => setTimeout(resolve, ORDER_CONFIG.constants.STATUS_CHECK_DELAY));

                try {
                    const response = await fetch(`${ORDER_CONFIG.routes.orderStatus}${orderReference}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success && data.order) {
                            this.showOrderSuccessPopup(
                                'Замовлення успішно створено!',
                                data.order.ttn_number ? `ТТН: ${data.order.ttn_number}` : 'Замовлення обробляється.',
                                data.order.ttn_number
                            );
                        } else {
                            this.showOrderSuccessPopup(
                                'Оплата успішна!',
                                'Замовлення обробляється.'
                            );
                        }
                    } else {
                        this.showOrderSuccessPopup(
                            'Оплата успішна!',
                            'Замовлення обробляється.'
                        );
                    }
                } catch (error) {
                    console.error('Error checking order status:', error);
                    this.showOrderSuccessPopup(
                        'Оплата успішна!',
                        'Замовлення обробляється.'
                    );
                }
            }

            handlePaymentError(response) {
                const errorMessage = response?.reason
                    ? `Помилка оплати: ${response.reason}`
                    : 'Оплата не пройшла. Спробуйте ще раз або оберіть інший спосіб оплати.';
                this.showErrorMessage(errorMessage);
            }


            resetForm() {
                this.elements.unifiedOrderForm?.reset();
                this.resetAddressSelection();
            }

            // HTTP utilities
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

                    return await this.handleResponse(response);
                } catch (error) {
                    if (error instanceof TypeError && error.message.includes('fetch')) {
                        throw new Error('Проблеми з підключенням до інтернету');
                    }
                    throw error;
                }
            }

            async makeFormRequest(url, formData) {
                if (!this.checkCSRFToken()) throw new Error('CSRF токен відсутній');

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                try {
                    const response = await fetch(url, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    return await this.handleResponse(response);
                } catch (error) {
                    if (error instanceof TypeError) {
                        throw new Error('Проблеми з підключенням. Перевірте інтернет');
                    }
                    throw error;
                }
            }

            async handleResponse(response) {
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
            }

            // Toast notifications
            showToast(message, type = 'info') {
                const colors = {
                    success: 'bg-green-500 text-white',
                    error: 'bg-red-500 text-white',
                    info: 'bg-blue-500 text-white'
                };
                const icons = { success: '✓', error: '✕', info: 'ℹ' };

                const toast = document.createElement('div');
                toast.className = `fixed top-4 lg:right-4 right-1/2 w-5/6 lg:w-auto z-50 p-4 rounded-lg shadow-lg transition-all duration-300 lg:translate-x-full  ${colors[type]}`;
                toast.innerHTML = `
            <div class="flex items-center justify-center lg:justify-none gap-3">
                <span class="text-lg font-bold">${icons[type]}</span>
                <span>${message}</span>
                <button class="ml-2 text-xl font-bold opacity-70 hover:opacity-100" onclick="this.parentElement.parentElement.remove()">×</button>
            </div>
        `;

                document.body.appendChild(toast);
                if(window.screen.width <= 1024) {
                    setTimeout(() => toast.classList.add('md:translate-x-1/2'), 100);
                }
                else if(window.screen.width > 1024) {
                    setTimeout(() => toast.classList.remove('lg:translate-x-full'), 100);
                }


                setTimeout(() => {
                    if(window.screen.width <= 1024) {
                        toast.classList.remove('md:translate-x-1/2');
                    }else if(window.screen.width > 1024) {
                        toast.classList.add('lg:translate-x-full');
                    }


                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            }

            showSuccessMessage(msg) { this.showToast(msg, 'success'); }
            showErrorMessage(msg) { this.showToast(msg, 'error'); }
            showInfoMessage(msg) { this.showToast(msg, 'info'); }

            showOrderSuccessPopup(title, message, ttnNumber = null) {
                const popup = document.createElement('div');
                popup.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';

                const ttnInfo = ttnNumber ? `
        <div class="bg-gray-50 rounded-lg p-4 mb-4">
            <p class="text-sm text-gray-600 mb-2">Номер ТТН для відстеження:</p>
            <p class="text-lg font-mono font-bold text-blue-600">${ttnNumber}</p>
        </div>
    ` : '';

                popup.innerHTML = `
        <div class="bg-white rounded-lg p-6 max-w-md mx-4 text-center">
            <div class="text-green-500 text-6xl mb-4">✓</div>
            <h3 class="text-xl font-bold text-gray-800 mb-2">${title}</h3>
            <p class="text-gray-600 mb-4">${message}</p>
            ${ttnInfo}
            <button
                id="goToHomeBtn"
                class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-colors focus:ring-4 focus:ring-blue-300"
            >
                Перейти на головну
            </button>
        </div>
    `;

                document.body.appendChild(popup);

                // Обробник кнопки
                popup.querySelector('#goToHomeBtn').addEventListener('click', () => {
                    popup.remove();
                    this.resetForm();
                    window.location.href = ORDER_CONFIG.routes.home;
                });

                // Закриття по кліку на backdrop
                popup.addEventListener('click', (e) => {
                    if (e.target === popup) {
                        popup.remove();
                        this.resetForm();
                        window.location.href = ORDER_CONFIG.routes.home;
                    }
                });
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            try {
                window.orderFormManager = new OrderFormManager();
                console.log('OrderFormManager initialized successfully');
            } catch (error) {
                console.error('Failed to initialize OrderFormManager:', error);

                const errorDiv = document.createElement('div');
                errorDiv.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
                errorDiv.innerHTML = `
            <strong class="font-bold">Помилка ініціалізації:</strong>
            <span class="block sm:inline">Сторінка не може працювати правильно. Будь ласка, оновіть сторінку.</span>
        `;

                const main = document.querySelector('main') || document.body;
                main.insertBefore(errorDiv, main.firstChild);
            }
        });
    </script>
@endsection


