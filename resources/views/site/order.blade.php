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
                    <a href="{{ route('product.show', $cart['productId']) }}">замовлення</a>
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
                                             class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 hidden">
                                            <div
                                                class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                        </div>
                                        <div
                                            class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
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
                                             class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 hidden">
                                            <div
                                                class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                        </div>
                                        <div
                                            class="absolute right-3 sm:right-4 top-1/2 transform -translate-y-1/2 pointer-events-none">
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
                                <span id="policy"
                                    class="underline hover:text-slate-800 cursor-pointer serafym-modal-trigger">політики конфіденційності</span>
                                та
                                <span id="terms" class="underline hover:text-slate-800 cursor-pointer serafym-modal-trigger">угоди користувача</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ORDER_CONFIG = {
            routes: {
                searchSettlement: '{{ route("orders.searchSettlement") }}',
                chooseSettlement: '{{ route("orders.chooseSettlement") }}',
                setWarehouse: '{{ route("orders.setWarehouse") }}',
                createOrder: '{{ route("orders.createOrder") }}',
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
                    'settlement-input', 'settlement-dropdown', 'settlement-search',
                    'settlement-options', 'settlement-loader', 'warehouse-input',
                    'warehouse-dropdown', 'warehouse-search', 'warehouse-options',
                    'warehouse-loader', 'selected-address', 'address-text',
                    'change-address', 'unified-order-form', 'order-form-section',
                    'submit-btn', 'delivery-cost', 'total-amount'
                ];

                return ids.reduce((els, id) => {
                    const key = this.toCamelCase(id);
                    els[key] = document.getElementById(id);
                    return els;
                }, {});
            }

            toCamelCase(str) {
                return str.replace(/-([a-z])/g, (_, letter) => letter.toUpperCase());
            }

            initState() {
                return {
                    address: {
                        search: '',
                        settlement: '',
                        settlementName: '',
                        warehouse: '',
                        warehouseName: ''
                    },
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

                if (value.startsWith('0')) {
                    value = '380' + value.substring(1);
                } else if (value.length > 0 && !value.startsWith('380')) {
                    value = '380' + value;
                }

                value = value.substring(0, 12);

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

                const existingError = field.parentNode.querySelector('.field-error');
                if (existingError) existingError.remove();

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

                    if (!response.success) {
                        throw new Error(response.error || 'Не вдалося знайти населені пункти');
                    }

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

                    if (!response.success) {
                        throw new Error(response.error || 'Не вдалося завантажити відділення');
                    }

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

                    if (!response.success) {
                        throw new Error('Не вдалося розрахувати вартість доставки');
                    }

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

                this.elements.warehouseInput.disabled = false;
                this.elements.warehouseInput.placeholder = 'Оберіть відділення або поштомат...';
                this.elements.warehouseInput.classList.remove('bg-gray-50');
                this.elements.warehouseInput.classList.add('bg-white');
            }

            disableWarehouseSelect() {
                if (!this.elements.warehouseInput) return;

                this.elements.warehouseInput.disabled = true;
                this.elements.warehouseInput.placeholder = 'Спочатку оберіть місто';
                this.elements.warehouseInput.value = '';
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
                this.state.address = {
                    search: '',
                    settlement: '',
                    settlementName: '',
                    warehouse: '',
                    warehouseName: ''
                };
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

                this.safeUpdateElement('deliveryCost', '0 грн');
                this.safeUpdateElement('totalAmount', '0 грн');
            }

            // UI helper methods
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
                const response = await this.makeFormRequest(ORDER_CONFIG.routes.createOrder, formData);

                if (response.success) {
                    this.showOrderSuccessPopup('Замовлення успішно створено!');
                } else {
                    throw new Error(response.message || 'Помилка створення замовлення');
                }
            }

            async handleCardPayment(formData) {
                this.showInfoMessage('Підготовка до оплати...');

                const response = await this.makeFormRequest(ORDER_CONFIG.routes.createOrder, formData);

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
                    this.showOrderSuccessPopup('Оплата успішна!', 'Ваше замовлення буде оброблено найближчим часом.');
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
                            this.showOrderSuccessPopup('Замовлення успішно створено!');
                        } else {
                            this.showOrderSuccessPopup('Оплата успішна!', 'Замовлення обробляється.');
                        }
                    } else {
                        this.showOrderSuccessPopup('Оплата успішна!', 'Замовлення обробляється.');
                    }
                } catch (error) {
                    this.showOrderSuccessPopup('Оплата успішна!', 'Замовлення обробляється.');
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
                toast.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full ${colors[type]}`;
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

            showOrderSuccessPopup(title, message = 'Ваше замовлення буде оброблено найближчим часом.') {
                const popup = document.createElement('div');
                popup.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
                popup.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4 text-center">
                <div class="text-green-500 text-6xl mb-4">✓</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">${title}</h3>
                <p class="text-gray-600 mb-4">${message}</p>
                <button
                    id="goToHomeBtn"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg transition-colors focus:ring-4 focus:ring-blue-300"
                >
                    Перейти на головну
                </button>
            </div>
        `;

                document.body.appendChild(popup);

                const goToHome = () => {
                    popup.remove();
                    this.resetForm();
                    window.location.href = ORDER_CONFIG.routes.home;
                };

                popup.querySelector('#goToHomeBtn').addEventListener('click', goToHome);
                popup.addEventListener('click', (e) => {
                    if (e.target === popup) goToHome();
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






        // Альтернативний скрипт для модальних вікон
// Використовує делегування подій та унікальні класи

(function() {
    'use strict';

    // Унікальні класи для уникнення конфліктів
    const MODAL_CLASSES = {
        trigger: 'serafym-modal-trigger',
        modal: 'serafym-modal',
        close: 'serafym-modal-close',
        content: 'serafym-modal-content',
        header: 'serafym-modal-header',
        show: 'serafym-modal-show'
    };

    // Функція для відкриття модального вікна
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal && modal.classList.contains(MODAL_CLASSES.modal)) {
            modal.classList.add(MODAL_CLASSES.show);
            document.body.style.overflow = 'hidden';

            // Встановлюємо фокус на модальне вікно для accessibility
            modal.setAttribute('tabindex', '-1');
            modal.focus();
        }
    }

    // Функція для закриття модального вікна
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        if (modal && modal.classList.contains(MODAL_CLASSES.modal)) {
            modal.classList.remove(MODAL_CLASSES.show);
            document.body.style.overflow = 'auto';

            // Прибираємо фокус
            modal.removeAttribute('tabindex');
        }
    }

    // Функція для закриття всіх відкритих модальних вікон
    function closeAllModals() {
        const openModals = document.querySelectorAll(`.${MODAL_CLASSES.modal}.${MODAL_CLASSES.show}`);
        openModals.forEach(modal => {
            closeModal(modal.id);
        });
    }

    // Універсальний обробник кліків через делегування
    document.addEventListener('click', function(e) {
        // Кнопки відкриття модального вікна
        if (e.target.classList.contains(MODAL_CLASSES.trigger)) {
            e.preventDefault();
            const triggerId = e.target.id;
            let modalId = '';

            // Визначаємо яке модальне вікно відкрити
            switch(triggerId) {
                case 'terms':
                    modalId = 'termsModal';
                    break;
                case 'policy':
                    modalId = 'policyModal';
                    break;
                default:
                    // Можна додати data-target атрибут для гнучкості
                    modalId = e.target.getAttribute('data-target');
            }

            if (modalId) {
                openModal(modalId);
            }
            return;
        }

        // Кнопки закриття модального вікна
        if (e.target.classList.contains(MODAL_CLASSES.close)) {
            e.preventDefault();
            const modalId = e.target.getAttribute('data-modal');
            if (modalId) {
                closeModal(modalId);
            } else {
                // Якщо немає data-modal, шукаємо найближче модальне вікно
                const modal = e.target.closest(`.${MODAL_CLASSES.modal}`);
                if (modal) {
                    closeModal(modal.id);
                }
            }
            return;
        }

        // Клік по фону модального вікна
        if (e.target.classList.contains(MODAL_CLASSES.modal)) {
            closeModal(e.target.id);
            return;
        }
    });

    // Обробка клавіатури
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAllModals();
        }
    });

    // Обробка анімації скролу в модальному вікні
    document.addEventListener('scroll', function(e) {
        if (e.target.classList.contains(MODAL_CLASSES.content)) {
            const scrolled = e.target.scrollTop;
            const header = e.target.querySelector(`.${MODAL_CLASSES.header}`);

            if (header) {
                if (scrolled > 10) {
                    header.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
                } else {
                    header.style.boxShadow = 'none';
                }
            }
        }
    }, true); // true для capture phase, щоб ловити всі scroll події

    // Додаткові методи для зовнішнього використання
    window.SerafymModal = {
        open: openModal,
        close: closeModal,
        closeAll: closeAllModals
    };

    // Debug функції (можна видалити в продакшені)
    if (window.console && window.console.log) {
        console.log('Serafym Modal System initialized');

        // Перевірка наявності елементів
        const triggers = document.querySelectorAll(`.${MODAL_CLASSES.trigger}`);
        const modals = document.querySelectorAll(`.${MODAL_CLASSES.modal}`);
        const closeButtons = document.querySelectorAll(`.${MODAL_CLASSES.close}`);

        console.log(`Found ${triggers.length} triggers, ${modals.length} modals, ${closeButtons.length} close buttons`);
    }

})();


    </script>


<style>
    /* Оновлені стилі для модальних вікон */
.serafym-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.serafym-modal.serafym-modal-show {
    display: block;
    opacity: 1;
    visibility: visible;
}

.serafym-modal-content {
    background-color: #fefefe;
    margin: 5% auto;
    padding: 0;
    border: none;
    width: 90%;
    max-width: 800px;
    max-height: 85vh;
    border-radius: 8px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    transform: scale(0.9);
    transition: transform 0.3s ease;
}

.serafym-modal.serafym-modal-show .serafym-modal-content {
    transform: scale(1);
}

.serafym-modal-header {
    background-color: #f8f9fa;
    padding: 20px;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: sticky;
    top: 0;
    z-index: 10;
    transition: box-shadow 0.3s ease;
}

.serafym-modal-header h2 {
    margin: 0;
    font-size: 1.5rem;
    color: #333;
}

.serafym-modal-close {
    background: none;
    border: none;
    font-size: 24px;
    font-weight: bold;
    color: #aaa;
    cursor: pointer;
    padding: 5px 10px;
    border-radius: 4px;
    transition: all 0.3s ease;
    line-height: 1;
}

.serafym-modal-close:hover,
.serafym-modal-close:focus {
    color: #333;
    background-color: #e9ecef;
    transform: scale(1.1);
}

.serafym-modal-body {
    padding: 20px;
    max-height: 60vh;
    overflow-y: auto;
    line-height: 1.6;
}

.serafym-modal-body h3 {
    color: #333;
    margin-top: 25px;
    margin-bottom: 15px;
    font-size: 1.2rem;
}

.serafym-modal-body ul {
    margin: 10px 0;
    padding-left: 20px;
}

.serafym-modal-body li {
    margin: 5px 0;
}

.serafym-modal-body p {
    margin: 10px 0;
    color: #555;
}

/* Стилі для кнопок-тригерів */
.serafym-modal-trigger {
    background: none;
    border: none;

    text-decoration: underline;
    cursor: pointer;
    padding: 0;
    font-size: inherit;
    transition: color 0.3s ease;
}

.serafym-modal-trigger:hover,
.serafym-modal-trigger:focus {
    color: #0056b3;
}

/* Адаптивність */
@media (max-width: 768px) {
    .serafym-modal-content {
        width: 95%;
        margin: 2% auto;
        max-height: 95vh;
    }

    .serafym-modal-header {
        padding: 15px;
    }

    .serafym-modal-body {
        padding: 15px;
        max-height: 70vh;
    }
}

/* Анімації появи */
@keyframes serafymModalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.serafym-modal.serafym-modal-show {
    animation: serafymModalFadeIn 0.3s ease;
}
</style>

<div id="termsModal" class="serafym-modal">
    <div class="serafym-modal-content">
        <div class="serafym-modal-header">
            <h2 class="modal-title">Terms of Use</h2>
            <button class="serafym-modal-close" data-modal="termsModal">&times;</button>
        </div>
        <div class="serafym-modal-body">
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
<div id="policyModal" class="serafym-modal">
    <div class="serafym-modal-content">
        <div class="serafym-modal-header">
            <h2 class="modal-title">Privacy Policy</h2>
            <button class="serafym-modal-close" data-modal="policyModal">&times;</button>
        </div>
        <div class="serafym-modal-body">
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


