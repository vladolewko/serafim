<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформлення замовлення</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="w-full">
        <!-- Navigation -->
        <nav class="mb-6">
            <ul class="flex flex-wrap text-lg sm:text-xl text-slate-600 items-center gap-2 sm:gap-4">
                <li class="hover:text-slate-800 cursor-pointer"><a href="#" onclick="return false">головна</a></li>
                <li class="hover:text-slate-800 cursor-pointer"><a href="#" onclick="return false">замовлення</a></li>
                <li class="text-yellow-400 text-xl sm:text-2xl font-semibold">оформлення</li>
            </ul>
        </nav>

        <!-- Debug Panel -->
        <div id="debug-panel" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden">
            <strong class="font-bold">Debug Info:</strong>
            <span class="block sm:inline" id="debug-message"></span>
        </div>

        <!-- Page Title -->
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-8 lg:mb-16">Оформлення замовлення</h1>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row justify-between w-full gap-6 lg:gap-8">

            <!-- Left Column - Forms -->
            <div class="flex flex-col w-full lg:w-7/12 xl:w-6/12 space-y-6 lg:space-y-10">

                <!-- Delivery Section -->
                <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg" id="ttn-form">
                    <div class="w-11/12 mx-auto py-4 sm:py-6 md:py-8">
                        <h2 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-semibold mb-3 sm:mb-4 md:mb-6 text-center sm:text-left">Доставка</h2>

                        <!-- Search form -->
                        <div class="grid grid-cols-1 text-black mx-auto gap-3 sm:gap-4" id="search-section">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <input
                                    id="city-search"
                                    class="w-full sm:flex-1 rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200"
                                    type="text"
                                    placeholder="Введіть місто"
                                    required>
                                <button
                                    id="search-btn"
                                    type="button"
                                    class="w-full sm:w-auto bg-yellow-400 text-black px-4 sm:px-6 py-3 sm:py-4 rounded-lg hover:bg-yellow-500 active:bg-yellow-600 transition-colors duration-200 font-medium text-sm sm:text-base lg:text-lg whitespace-nowrap">
                                    Знайти
                                </button>
                            </div>
                        </div>

                        <!-- Settlement selection -->
                        <div class="grid grid-cols-1 text-black mx-auto gap-3 sm:gap-4 mt-4 hidden" id="settlement-section">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <select
                                    id="settlement-select"
                                    class="w-full sm:flex-1 rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200"
                                    required>
                                    <option value="" disabled selected>Місто</option>
                                </select>
                                <button
                                    id="choose-settlement-btn"
                                    type="button"
                                    class="w-full sm:w-auto bg-yellow-400 text-black px-4 sm:px-6 py-3 sm:py-4 rounded-lg hover:bg-yellow-500 active:bg-yellow-600 transition-colors duration-200 font-medium text-sm sm:text-base lg:text-lg whitespace-nowrap">
                                    Обрати
                                </button>
                            </div>
                        </div>

                        <!-- Warehouse selection -->
                        <div class="grid grid-cols-1 text-black mx-auto gap-3 sm:gap-4 mt-4 hidden" id="warehouse-section">
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                                <select
                                    id="warehouse-select"
                                    class="w-full sm:flex-1 md:max-w-none rounded-lg p-3 sm:p-4 border-none text-sm sm:text-base lg:text-lg focus:ring-2 focus:ring-yellow-400 outline-none transition-all duration-200"
                                    required>
                                    <option value="" disabled selected>Відділення або поштомат</option>
                                </select>
                                <button
                                    id="set-warehouse-btn"
                                    type="button"
                                    class="w-full sm:w-auto bg-yellow-400 text-black px-4 sm:px-6 py-3 sm:py-4 rounded-lg hover:bg-yellow-500 active:bg-yellow-600 transition-colors duration-200 font-medium text-sm sm:text-base lg:text-lg whitespace-nowrap">
                                    Обрати
                                </button>
                            </div>
                        </div>

                        <!-- Loading indicator -->
                        <div class="text-center mt-4 hidden" id="loading">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-white"></div>
                            <p class="mt-2 text-white">Завантаження...</p>
                        </div>
                    </div>
                </div>

                <!-- Main order form (hidden initially) -->
                <div id="order-form-section" class="hidden">
                    <form id="unified-order-form">
                        <!-- Contact Information -->
                        <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg">
                            <div class="w-11/12 mx-auto py-6">
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Контактні данні</h2>
                                <div class="grid grid-cols-1 sm:grid-cols-2 text-black mx-auto gap-3">
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="+380" name="phone" value="+380" type="tel" required>
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="Електронна пошта" name="email" type="email" required>
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="Ім'я" name="name" type="text" required>
                                    <input
                                        class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                        placeholder="Прізвище" name="surname" type="text" required>
                                </div>
                            </div>
                        </div>

                        <!-- Payment -->
                        <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg mt-6">
                            <div class="w-11/12 mx-auto py-6">
                                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Оплата</h2>
                                <div class="grid grid-cols-1 text-black mx-auto gap-3">
                                    <div class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors">
                                        <input id="radio-3"
                                               class="w-4 h-4 cursor-pointer text-yellow-400 bg-white border-yellow-400"
                                               name="payment" type="radio" value="card" required>
                                        <label class="cursor-pointer text-black text-sm sm:text-base font-medium flex-1"
                                               for="radio-3">
                                            Карткою на сайті
                                        </label>
                                    </div>

                                    <div class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors">
                                        <input id="radio-4"
                                               class="w-4 h-4 cursor-pointer text-yellow-400 bg-white border-yellow-400"
                                               name="payment" type="radio" value="cash" required>
                                        <label class="cursor-pointer text-black text-sm sm:text-base font-medium flex-1"
                                               for="radio-4">
                                            При отриманні
                                        </label>
                                    </div>
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
                            <span class="text-slate-600 text-sm sm:text-base">{{ session('cart')['quantity'] }} товар(и) на суму</span>
                            <span class="font-semibold text-sm sm:text-base">{{ session('cart')['total'] }} грн</span>
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
                        <span class="font-bold text-xl sm:text-2xl lg:text-3xl" id="total-amount">{{ isset($deliveryCost) ? session('cart')['total'] + $deliveryCost : session('cart')['total']  }} грн</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" form="unified-order-form" id="submit-btn"
                            class="w-full bg-gray-400 text-xl sm:text-2xl lg:text-3xl text-white font-bold py-3 sm:py-4 rounded-lg text-center">
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

<script>
    // Зберігаємо дані адреси
    let addressData = {
        search: '',
        settlement: '',
        warehouse: ''
    };

    // Зберігаємо дані для селектів
    let settlementsData = [];
    let warehousesData = [];

    // Функція для показу/приховування завантаження
    function toggleLoading(show) {
        const loading = document.getElementById('loading');
        if (show) {
            loading.classList.remove('hidden');
        } else {
            loading.classList.add('hidden');
        }
    }

    // Функція для показу debug інформації
    function showDebug(message, isError = true) {
        const debugPanel = document.getElementById('debug-panel');
        const debugMessage = document.getElementById('debug-message');

        debugMessage.textContent = message;
        debugPanel.classList.remove('hidden');

        if (!isError) {
            debugPanel.className = 'bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4';
        } else {
            debugPanel.className = 'bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4';
        }

        console.log('Debug:', message);
    }

    // Функція для реальних AJAX запитів
    async function makeAjaxRequest(url, data) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        showDebug(`Відправляємо запит на: ${url}`, false);
        showDebug(`CSRF Token: ${csrfToken ? 'знайдений' : 'НЕ ЗНАЙДЕНИЙ'}`, csrfToken ? false : true);
        showDebug(`Дані запиту: ${JSON.stringify(data)}`, false);

        const requestOptions = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        };

        // Додаємо CSRF токен тільки якщо він є
        if (csrfToken) {
            requestOptions.headers['X-CSRF-TOKEN'] = csrfToken;
        }

        try {
            const response = await fetch(url, requestOptions);

            showDebug(`Статус відповіді: ${response.status} ${response.statusText}`, false);

            // Спробуємо отримати текст відповіді для відладки
            const responseText = await response.text();
            showDebug(`Текст відповіді: ${responseText.substring(0, 200)}...`, false);

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}, response: ${responseText}`);
            }

            // Парсимо JSON
            let jsonData;
            try {
                jsonData = JSON.parse(responseText);
            } catch (parseError) {
                throw new Error(`JSON parse error: ${parseError.message}, response: ${responseText}`);
            }

            showDebug(`Успішна відповідь: ${JSON.stringify(jsonData)}`, false);
            return jsonData;

        } catch (error) {
            showDebug(`Помилка запиту: ${error.message}`, true);
            throw error;
        }
    }

    // Пошук населених пунктів
    document.getElementById('search-btn').addEventListener('click', async function() {
        const searchInput = document.getElementById('city-search');
        const searchValue = searchInput.value.trim();

        if (!searchValue) {
            showDebug('Порожнє поле пошуку', true);
            alert('Введіть назву міста');
            return;
        }

        showDebug(`Початок пошуку для: "${searchValue}"`, false);
        toggleLoading(true);

        try {
            const response = await makeAjaxRequest('{{ route('orders.searchSettlement') }}', { search: searchValue });

            if (!response.success) {
                showDebug(`Сервер повернув помилку: ${response.error || 'невідома помилка'}`, true);
                alert(response.error || 'Помилка пошуку населених пунктів');
                return;
            }

            addressData.search = searchValue;
            settlementsData = response.settlements;

            showDebug(`Знайдено населених пунктів: ${response.settlements ? response.settlements.length : 0}`, false);

            // Заповнюємо селект населених пунктів
            const settlementSelect = document.getElementById('settlement-select');
            settlementSelect.innerHTML = '<option value="" disabled selected>Місто</option>';

            if (response.settlements && response.settlements.length > 0) {
                response.settlements.forEach(settlement => {
                    const option = document.createElement('option');
                    option.value = settlement.Ref;
                    option.textContent = settlement.Present;
                    settlementSelect.appendChild(option);
                });

                // Показуємо секцію вибору населеного пункту
                document.getElementById('settlement-section').classList.remove('hidden');
                showDebug('Секція вибору населеного пункту показана', false);
            } else {
                showDebug('Немає населених пунктів у відповіді', true);
            }

        } catch (error) {
            showDebug(`Виняток під час пошуку: ${error.message}`, true);
            alert('Помилка пошуку населених пунктів: ' + error.message);
        } finally {
            toggleLoading(false);
        }
    });

    // Вибір населеного пункту
    document.getElementById('choose-settlement-btn').addEventListener('click', async function() {
        const settlementSelect = document.getElementById('settlement-select');
        const settlementValue = settlementSelect.value;

        if (!settlementValue) {
            showDebug('Не обрано населений пункт', true);
            alert('Оберіть населений пункт');
            return;
        }

        showDebug(`Обрано населений пункт: ${settlementValue}`, false);
        toggleLoading(true);

        try {
            const response = await makeAjaxRequest('{{ route('orders.chooseSettlement') }}', { settlement: settlementValue });

            if (!response.success) {
                showDebug(`Помилка отримання відділень: ${response.error || 'невідома помилка'}`, true);
                alert(response.error || 'Помилка отримання відділень');
                return;
            }

            addressData.settlement = settlementValue;
            warehousesData = response.warehouses;

            showDebug(`Знайдено відділень: ${response.warehouses ? response.warehouses.length : 0}`, false);

            // Заповнюємо селект відділень
            const warehouseSelect = document.getElementById('warehouse-select');
            warehouseSelect.innerHTML = '<option value="" disabled selected>Відділення або поштомат</option>';

            if (response.warehouses && response.warehouses.length > 0) {
                response.warehouses.forEach(warehouse => {
                    const option = document.createElement('option');
                    option.value = warehouse.Ref;
                    option.textContent = warehouse.Description;
                    warehouseSelect.appendChild(option);
                });

                // Показуємо секцію вибору відділення
                document.getElementById('warehouse-section').classList.remove('hidden');
                showDebug('Секція вибору відділення показана', false);
            }

        } catch (error) {
            showDebug(`Виняток під час отримання відділень: ${error.message}`, true);
            alert('Помилка отримання відділень: ' + error.message);
        } finally {
            toggleLoading(false);
        }
    });

    // Вибір відділення
    document.getElementById('set-warehouse-btn').addEventListener('click', async function() {
        const warehouseSelect = document.getElementById('warehouse-select');
        const warehouseValue = warehouseSelect.value;

        if (!warehouseValue) {
            showDebug('Не обрано відділення', true);
            alert('Оберіть відділення');
            return;
        }

        showDebug(`Обрано відділення: ${warehouseValue}`, false);
        toggleLoading(true);

        try {
            const response = await makeAjaxRequest('{{ route('orders.setWarehouse') }}', { warehouse: warehouseValue });

            if (!response.success) {
                showDebug('Помилка розрахунку вартості доставки', true);
                alert('Помилка розрахунку вартості доставки');
                return;
            }

            addressData.warehouse = warehouseValue;

            // Оновлюємо вартість доставки
            const deliveryCost = response.deliveryCost;
            const productCosts = response.productCosts;
            document.getElementById('delivery-cost').textContent = deliveryCost + ' грн';

            // Оновлюємо загальну суму (1500 + вартість доставки)
            const total = productCosts + deliveryCost;
            document.getElementById('total-amount').textContent = total + ' грн';

            // Показуємо форму замовлення
            document.getElementById('order-form-section').classList.remove('hidden');

            // Активуємо кнопку підтвердження
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.classList.remove('bg-gray-400');
            submitBtn.classList.add('bg-blue-400', 'hover:bg-blue-500', 'active:bg-blue-600', 'transition-colors', 'focus:ring-4', 'focus:ring-blue-300', 'outline-none');
            submitBtn.textContent = 'Підтвердити замовлення';

            showDebug(`Вартість доставки: ${deliveryCost} грн, загальна сума: ${total} грн`, false);

        } catch (error) {
            showDebug(`Виняток під час розрахунку доставки: ${error.message}`, true);
            alert('Помилка розрахунку вартості доставки: ' + error.message);
        } finally {
            toggleLoading(false);
        }
    });

    // Обробка відправки замовлення
    document.getElementById('unified-order-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        if (!addressData.warehouse) {
            showDebug('Спроба відправки без вибору адреси', true);
            alert('Спочатку оберіть адресу доставки');
            return;
        }

        const formData = new FormData(this);
        formData.append('settlement', addressData.settlement);
        formData.append('warehouse', addressData.warehouse);

        const paymentMethod = formData.get('payment');
        showDebug(`Відправка замовлення з оплатою: ${paymentMethod}`, false);

        toggleLoading(true);

        try {
            if (paymentMethod === 'card') {
                await handleCardPayment(formData);
            } else {
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
                    showDebug(`Замовлення створено успішно. ТТН: ${data.ttn_number}`, false);
                    alert('Успіх!\n' + data.message + '\nНомер ТТН: ' + data.ttn_number);
                    // Перенаправлення на головну сторінку
                    window.location.href = '{{ route('home') }}';
                } else {
                    showDebug(`Помилка створення замовлення: ${data.message}`, true);
                    alert('Помилка: ' + data.message);
                }
            }
        } catch (error) {
            showDebug(`Виняток під час створення замовлення: ${error.message}`, true);
            alert('Виникла помилка при створенні замовлення: ' + error.message);
        } finally {
            toggleLoading(false);
        }
    });

    async function handleCardPayment(formData) {
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

            if (data.success && data.payment_type === 'card') {
                showDebug('Підготовка до оплати карткою', false);

                // Створюємо форму WayForPay
                const wayForPayForm = document.createElement('form');
                wayForPayForm.method = 'POST';
                wayForPayForm.action = 'https://secure.wayforpay.com/pay';
                wayForPayForm.acceptCharset = 'utf-8';
                wayForPayForm.style.display = 'none';

                const wayForPayData = data.wayforpay_data;

                if (!wayForPayData.merchantAccount) {
                    showDebug('Відсутній merchantAccount у відповіді', true);
                    alert('Помилка конфігурації платіжної системи');
                    return;
                }

                for (const [key, value] of Object.entries(wayForPayData)) {
                    if (Array.isArray(value)) {
                        value.forEach((item, index) => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = key + '[]';
                            input.value = item;
                            wayForPayForm.appendChild(input);
                        });
                    } else {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = key;
                        input.value = value;
                        wayForPayForm.appendChild(input);
                    }
                }

                document.body.appendChild(wayForPayForm);
                wayForPayForm.submit();
            } else {
                showDebug(`Помилка підготовки оплати: ${data.message || 'невідома помилка'}`, true);
                alert('Помилка підготовки оплати: ' + (data.message || 'Невідома помилка'));
            }
        } catch (error) {
            showDebug(`Виняток під час підготовки оплати: ${error.message}`, true);
            alert('Помилка підготовки оплати: ' + error.message);
        }
    }

    // Початкова перевірка на наявність CSRF токену
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            showDebug('УВАГА: CSRF токен не знайдено в мета-тегах! Це може бути причиною помилок.', true);
        } else {
            showDebug('CSRF токен знайдено успішно', false);
        }
    });
</script>
</body>
</html>
