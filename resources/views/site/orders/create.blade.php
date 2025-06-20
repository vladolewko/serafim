@extends('layouts.site')


@section('content')

    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="w-full">
            <!-- Navigation -->
            <nav class="mb-6">
                <ul class="flex flex-wrap text-lg sm:text-xl text-slate-600 items-center gap-2 sm:gap-4">
                    <li class="hover:text-slate-800 cursor-pointer">
                        <a href="{{ route('home') }}">головна</a>
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

                            {{--                        <!-- City Search -->--}}
                            {{--                        <div class="mb-4">--}}
                            {{--                            <div class="relative">--}}
                            {{--                                <input--}}
                            {{--                                    id="city-search"--}}
                            {{--                                    class="w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black"--}}
                            {{--                                    type="text"--}}
                            {{--                                    placeholder="Введіть місто для пошуку..."--}}
                            {{--                                    autocomplete="off">--}}
                            {{--                                <div id="search-loader" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">--}}
                            {{--                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>--}}
                            {{--                                </div>--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}

                            {{--                        <!-- Settlement Selection -->--}}
                            {{--                        <div id="settlement-section" class="mb-4 hidden">--}}
                            {{--                            <div class="relative">--}}
                            {{--                                <select--}}
                            {{--                                    id="settlement-select"--}}
                            {{--                                    class="w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black appearance-none cursor-pointer"--}}
                            {{--                                    disabled>--}}
                            {{--                                    <option value="" selected>Оберіть місто...</option>--}}
                            {{--                                </select>--}}
                            {{--                                <div id="settlement-loader" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">--}}
                            {{--                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>--}}
                            {{--                                </div>--}}
                            {{--                                <!-- Custom arrow -->--}}
                            {{--                                <!-- <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">--}}
                            {{--                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
                            {{--                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                </div> -->--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}

                            {{--                        <!-- Warehouse Selection -->--}}
                            {{--                        <div id="warehouse-section" class="mb-4 hidden">--}}
                            {{--                            <div class="relative">--}}
                            {{--                                <select--}}
                            {{--                                    id="warehouse-select"--}}
                            {{--                                    class="w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black appearance-none cursor-pointer"--}}
                            {{--                                    disabled>--}}
                            {{--                                    <option value="" selected>Оберіть відділення або поштомат...</option>--}}
                            {{--                                </select>--}}
                            {{--                                <div id="warehouse-loader" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">--}}
                            {{--                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>--}}
                            {{--                                </div>--}}
                            {{--                                <!-- Custom arrow -->--}}
                            {{--                                <!-- <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none">--}}
                            {{--                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
                            {{--                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>--}}
                            {{--                                    </svg>--}}
                            {{--                                </div> -->--}}
                            {{--                            </div>--}}
                            {{--                        </div>--}}

                            <!-- Settlement Selection with Search -->
                            <div class="mb-4">
                                <div class="searchable-select">
                                    <div class="relative">
                                        <input
                                            id="settlement-input"
                                            class="select-input w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black bg-white cursor-pointer"
                                            type="text"
                                            placeholder="Оберіть місто..."
                                            autocomplete="off"
                                            readonly>
                                        <div id="settlement-loader"
                                             class="absolute right-10 top-1/2 transform -translate-y-1/2 hidden">
                                            <div
                                                class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                        </div>
                                        <div
                                            class="absolute right-10 top-1/2 transform -translate-y-1/2 pointer-events-none">
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
                                            class="select-input w-full rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200 text-black bg-gray-50 cursor-pointer"
                                            type="text"
                                            placeholder="Спочатку оберіть місто"
                                            autocomplete="off"
                                            readonly
                                            disabled>
                                        <div id="warehouse-loader"
                                             class="absolute right-10 top-1/2 transform -translate-y-1/2 hidden">
                                            <div
                                                class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-500"></div>
                                        </div>
                                        <div
                                            class="absolute right-10 top-1/2 transform -translate-y-1/2 pointer-events-none">
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
                            <style>
                                /* Searchable Select Styles */
                                .searchable-select {
                                    position: relative;
                                }

                                .select-input {
                                    cursor: pointer;
                                }

                                .select-input:disabled {
                                    cursor: not-allowed;
                                    opacity: 0.7;
                                }

                                .select-dropdown {
                                    position: absolute;
                                    top: 100%;
                                    left: 0;
                                    right: 0;
                                    background: white;
                                    border: 1px solid #d1d5db;
                                    border-radius: 0.5rem;
                                    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
                                    z-index: 1000;
                                    margin-top: 4px;
                                }

                                .search-input {
                                    border: none;
                                    outline: none;
                                    width: 100%;
                                    padding: 0.75rem 1rem;
                                    border-bottom: 1px solid #e5e7eb;
                                    font-size: 0.875rem;
                                    background: #f9fafb;
                                    color: black;
                                }

                                .search-input:focus {
                                    background: white;
                                    border-bottom-color: #fbbf24;
                                }

                                .options-container {
                                    max-height: 200px;
                                    overflow-y: auto;
                                }

                                .select-option {
                                    padding: 0.75rem 1rem;
                                    cursor: pointer;
                                    border-bottom: 1px solid #f3f4f6;
                                    transition: background-color 0.15s ease-in-out;
                                    font-size: 0.875rem;
                                    color: black;

                                }

                                .select-option:hover {
                                    background-color: #f9fafb;
                                }

                                .select-option:last-child {
                                    border-bottom: none;
                                }

                                .select-option.highlighted {
                                    background-color: #fef3c7;
                                }

                                .no-results {
                                    padding: 1.5rem 1rem;
                                    text-align: center;
                                    color: #6b7280;
                                    font-style: italic;
                                    font-size: 0.875rem;
                                }

                                /* Custom scrollbar for options */
                                .options-container::-webkit-scrollbar {
                                    width: 6px;
                                }

                                .options-container::-webkit-scrollbar-track {
                                    background: #f1f1f1;
                                    border-radius: 10px;
                                }

                                .options-container::-webkit-scrollbar-thumb {
                                    background: #c1c1c1;
                                    border-radius: 10px;
                                }

                                .options-container::-webkit-scrollbar-thumb:hover {
                                    background: #a8a8a8;
                                }

                                /* Animation for dropdown */
                                .select-dropdown {
                                    animation: slideDown 0.2s ease-out;
                                    transform-origin: top;
                                }

                                @keyframes slideDown {
                                    from {
                                        opacity: 0;
                                        transform: translateY(-10px) scaleY(0.95);
                                    }
                                    to {
                                        opacity: 1;
                                        transform: translateY(0) scaleY(1);
                                    }
                                }

                                /* Responsive adjustments */
                                @media (max-width: 640px) {
                                    .select-dropdown {
                                        position: fixed;
                                        left: 1rem;
                                        right: 1rem;
                                        top: auto;
                                        bottom: 1rem;
                                        max-height: 50vh;
                                    }

                                    .options-container {
                                        max-height: calc(50vh - 4rem);
                                    }
                                }
                            </style>

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
        class OrderFormManager {
            constructor() {
                this.elements = {
                    // Searchable select elements
                    settlementInput: document.getElementById('settlement-input'),
                    settlementDropdown: document.getElementById('settlement-dropdown'),
                    settlementSearch: document.getElementById('settlement-search'),
                    settlementOptions: document.getElementById('settlement-options'),
                    settlementLoader: document.getElementById('settlement-loader'),

                    warehouseInput: document.getElementById('warehouse-input'),
                    warehouseDropdown: document.getElementById('warehouse-dropdown'),
                    warehouseSearch: document.getElementById('warehouse-search'),
                    warehouseOptions: document.getElementById('warehouse-options'),
                    warehouseLoader: document.getElementById('warehouse-loader'),

                    // Address confirmation elements
                    selectedAddress: document.getElementById('selected-address'),
                    addressText: document.getElementById('address-text'),
                    changeAddress: document.getElementById('change-address'),

                    // Form elements
                    unifiedOrderForm: document.getElementById('unified-order-form'),
                    orderFormSection: document.getElementById('order-form-section'),
                    submitBtn: document.getElementById('submit-btn'),

                    // Cost elements
                    deliveryCost: document.getElementById('delivery-cost'),
                    totalAmount: document.getElementById('total-amount')
                };

                this.addressData = {
                    search: '',
                    settlement: '',
                    settlementName: '',
                    warehouse: '',
                    warehouseName: ''
                };

                this.settlementsData = [];
                this.warehousesData = [];
                this.filteredSettlements = [];
                this.filteredWarehouses = [];
                this.searchTimeout = null;

                this.init();
            }

            init() {
                this.attachEventListeners();
                this.initializeFormState();
            }

            initializeFormState() {
                // Hide order form initially
                this.hideOrderForm();
                // Disable warehouse selection initially
                this.disableWarehouseSelect();
            }

            attachEventListeners() {
                // Settlement dropdown events
                this.elements.settlementInput?.addEventListener('click', () => {
                    this.toggleDropdown('settlement');
                });

                this.elements.settlementSearch?.addEventListener('input', (e) => {
                    clearTimeout(this.searchTimeout);
                    const value = e.target.value.trim();

                    if (value.length >= 2) {
                        this.searchTimeout = setTimeout(() => this.searchSettlements(value), 500);
                    } else {
                        this.filteredSettlements = [];
                        this.renderOptions('settlement', []);
                    }
                });

                // Warehouse dropdown events
                this.elements.warehouseInput?.addEventListener('click', () => {
                    if (!this.elements.warehouseInput.disabled) {
                        this.toggleDropdown('warehouse');
                    }
                });

                this.elements.warehouseSearch?.addEventListener('input', (e) => {
                    const value = e.target.value.trim();
                    this.filterWarehouses(value);
                });

                // Close dropdowns when clicking outside
                document.addEventListener('click', (e) => {
                    if (!e.target.closest('.searchable-select')) {
                        this.closeAllDropdowns();
                    }
                });

                // Other handlers
                this.elements.changeAddress?.addEventListener('click', () => this.resetAddressSelection());
                this.elements.unifiedOrderForm?.addEventListener('submit', (e) => {
                    e.preventDefault();
                    this.submitOrder();
                });
            }

            toggleDropdown(type) {
                const dropdown = this.elements[`${type}Dropdown`];
                if (!dropdown) return;

                const isOpen = !dropdown.classList.contains('hidden');

                // Close all dropdowns first
                this.closeAllDropdowns();

                if (!isOpen) {
                    dropdown.classList.remove('hidden');
                    const searchInput = this.elements[`${type}Search`];
                    if (searchInput) {
                        searchInput.focus();
                        searchInput.value = '';
                    }

                    if (type === 'settlement') {
                        if (this.elements.settlementSearch) {
                            this.elements.settlementSearch.placeholder = 'Введіть назву міста (мін. 2 символи)...';
                        }
                    } else if (type === 'warehouse') {
                        this.filterWarehouses('');
                    }
                }
            }

            closeAllDropdowns() {
                this.elements.settlementDropdown?.classList.add('hidden');
                this.elements.warehouseDropdown?.classList.add('hidden');
            }

            async searchSettlements(searchValue) {
                if (!searchValue.trim()) return;

                this.showLoader('settlement');

                try {
                    const response = await this.makeRequest('{{ route('orders.searchSettlement') }}', {
                        search: searchValue
                    });

                    if (!response.success) throw new Error(response.error || 'Не вдалося знайти населені пункти');

                    this.addressData.search = searchValue;
                    this.settlementsData = response.settlements || [];
                    this.filteredSettlements = this.settlementsData;

                    if (this.settlementsData.length === 0) {
                        this.showInfoMessage('Населені пункти не знайдено. Спробуйте інший запит.');
                        this.renderOptions('settlement', []);
                        return;
                    }

                    this.renderOptions('settlement', this.filteredSettlements);

                } catch (error) {
                    this.showErrorMessage(error.message);
                    this.renderOptions('settlement', []);
                } finally {
                    this.hideLoader('settlement');
                }
            }

            filterWarehouses(searchValue) {
                if (!this.warehousesData.length) return;

                this.filteredWarehouses = searchValue.trim()
                    ? this.warehousesData.filter(warehouse =>
                        warehouse.Description.toLowerCase().includes(searchValue.toLowerCase())
                    )
                    : this.warehousesData;

                this.renderOptions('warehouse', this.filteredWarehouses);
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
                    div.addEventListener('click', () => {
                        this.selectOption(type, option);
                    });
                    container.appendChild(div);
                });
            }

            async selectOption(type, option) {
                if (type === 'settlement') {
                    await this.chooseSettlement(option.Ref, option.Present);
                } else if (type === 'warehouse') {
                    await this.setWarehouse(option.Ref, option.Description);
                }

                this.closeAllDropdowns();
            }

            async chooseSettlement(settlementRef, settlementName) {
                this.showLoader('settlement');

                try {
                    const response = await this.makeRequest('{{ route('orders.chooseSettlement') }}', {
                        settlement: settlementRef
                    });

                    if (!response.success) throw new Error(response.error || 'Не вдалося завантажити відділення');

                    this.addressData.settlement = settlementRef;
                    this.addressData.settlementName = settlementName;

                    if (this.elements.settlementInput) {
                        this.elements.settlementInput.value = settlementName;
                    }

                    this.warehousesData = response.warehouses || [];
                    this.filteredWarehouses = this.warehousesData;

                    if (this.warehousesData.length === 0) {
                        this.showInfoMessage('У цьому місті немає доступних відділень');
                        this.disableWarehouseSelect();
                        return;
                    }

                    this.enableWarehouseSelect();

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
                    const response = await this.makeRequest('{{ route('orders.setWarehouse') }}', {
                        warehouse: warehouseRef
                    });

                    if (!response.success) throw new Error('Не вдалося розрахувати вартість доставки');

                    this.addressData.warehouse = warehouseRef;
                    this.addressData.warehouseName = warehouseName;

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
                this.elements.warehouseInput.classList.add('bg-gray-50');
                this.elements.warehouseInput.classList.remove('bg-white');
                this.elements.warehouseInput.value = '';
            }

            showAddressConfirmation() {
                if (this.addressData.settlementName && this.addressData.warehouseName) {
                    const addressText = `${this.addressData.settlementName}, ${this.addressData.warehouseName}`;

                    if (this.elements.addressText) {
                        this.elements.addressText.textContent = addressText;
                    }

                    if (this.elements.selectedAddress) {
                        this.elements.selectedAddress.classList.remove('hidden');
                    }
                }
            }

            resetAddressSelection() {
                this.addressData = {
                    search: '',
                    settlement: '',
                    settlementName: '',
                    warehouse: '',
                    warehouseName: ''
                };

                this.settlementsData = [];
                this.warehousesData = [];
                this.filteredSettlements = [];
                this.filteredWarehouses = [];

                if (this.elements.settlementInput) {
                    this.elements.settlementInput.value = '';
                }

                if (this.elements.warehouseInput) {
                    this.elements.warehouseInput.value = '';
                }

                this.disableWarehouseSelect();

                if (this.elements.selectedAddress) {
                    this.elements.selectedAddress.classList.add('hidden');
                }

                this.closeAllDropdowns();
                this.hideOrderForm();
                this.toggleSubmitButton(false);

                // Reset delivery costs
                this.safeUpdateElement('deliveryCost', '0 грн');
                this.safeUpdateElement('totalAmount', '0 грн');
            }

            showLoader(type) {
                const loader = this.elements[`${type}Loader`];
                if (loader) {
                    loader.classList.remove('hidden');
                }
            }

            hideLoader(type) {
                const loader = this.elements[`${type}Loader`];
                if (loader) {
                    loader.classList.add('hidden');
                }
            }

            updateDeliveryInfo(response) {
                const deliveryCost = response.deliveryCost || 0;
                const productCosts = response.productCosts || 0;

                this.safeUpdateElement('deliveryCost', `${deliveryCost} грн`);
                this.safeUpdateElement('totalAmount', `${productCosts + deliveryCost} грн`);
            }

            showOrderForm() {
                if (this.elements.orderFormSection) {
                    this.elements.orderFormSection.classList.remove('hidden');
                }
            }

            hideOrderForm() {
                if (this.elements.orderFormSection) {
                    this.elements.orderFormSection.classList.add('hidden');
                }
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

            formatPhoneNumber(input) {
                if (!input) return;

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

            // Safe element update method
            safeUpdateElement(elementKey, content) {
                const element = this.elements[elementKey];
                if (element) {
                    element.textContent = content;
                } else {
                    console.warn(`Element ${elementKey} not found in DOM`);
                }
            }

            showToast(message, type = 'info') {
                const colors = {
                    success: 'bg-green-500 text-white',
                    error: 'bg-red-500 text-white',
                    info: 'bg-blue-500 text-white'
                };
                const icons = {success: '✓', error: '✕', info: 'ℹ'};

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

            showSuccessMessage(msg) {
                this.showToast(msg, 'success');
            }

            showErrorMessage(msg) {
                this.showToast(msg, 'error');
            }

            showInfoMessage(msg) {
                this.showToast(msg, 'info');
            }

            showSuccessToastWithRedirect(title, message, redirectUrl, delay = 3000) {
                const toast = document.createElement('div');
                toast.className = 'fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50';
                toast.innerHTML = `
            <div class="bg-white rounded-lg p-6 max-w-md mx-4 text-center">
                <div class="text-green-500 text-6xl mb-4">✓</div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">${title}</h3>
                <p class="text-gray-600 mb-4">${message}</p>
                <div class="text-sm text-gray-500">
                    Перенаправлення через <span id="countdown">${delay / 1000}</span> секунд...
                </div>
            </div>
        `;

                document.body.appendChild(toast);

                let seconds = delay / 1000;
                const countdownEl = toast.querySelector('#countdown');
                const interval = setInterval(() => {
                    seconds--;
                    if (countdownEl) countdownEl.textContent = seconds;
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

            validateForm() {
                const form = this.elements.unifiedOrderForm;
                if (!form) return false;

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

                if (this.elements.submitBtn) {
                    this.elements.submitBtn.disabled = true;
                    this.elements.submitBtn.textContent = 'Створення замовлення...';
                }

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
                try {
                    this.showInfoMessage('Підготовка до оплати...');

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
                        this.initWayForPay(data.wayforpay_data);
                    } else {
                        throw new Error(data.message || 'Помилка підготовки оплати');
                    }
                } catch (error) {
                    this.showErrorMessage(error.message || 'Помилка при підготовці оплати');
                }
            }

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
                try {
                    this.showSuccessMessage('Оплата успішна! Ваше замовлення обробляється.');
                    this.resetForm();

                    setTimeout(() => {
                        window.location.href = '{{ route('home') }}';
                    }, 2000);
                } catch (error) {
                    this.showSuccessMessage('Оплата пройшла успішно! Ваше замовлення буде оброблено найближчим часом.');
                    this.resetForm();
                }
            }

            handlePaymentError(response) {
                const errorMessage = response?.reason
                    ? `Помилка оплати: ${response.reason}`
                    : 'Оплата не пройшла. Спробуйте ще раз або оберіть інший спосіб оплати.';

                this.showErrorMessage(errorMessage);
            }

            resetForm() {
                const form = this.elements.unifiedOrderForm;
                if (form) {
                    form.reset();
                }
                this.resetAddressSelection();
            }
        }

        // Initialize when DOM is loaded
        document.addEventListener('DOMContentLoaded', function () {
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





<!--

// <<<<<<< HEAD
//         const main = document.querySelector('main');
//         main?.insertBefore(errorDiv, main.firstChild);
//     }
// });
// </script> -->


 <!-- @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/index.js', 'resources/js/order.js']) -->



<!-- <script>
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

-->
