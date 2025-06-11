@extends('layouts.site')

@section('content')
    <main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="w-full">
            <!-- Navigation -->
            <nav class="mb-6">
                <ul class="flex flex-wrap text-lg sm:text-xl text-slate-600 items-center gap-2 sm:gap-4">
                    <li class="hover:text-slate-800 cursor-pointer"><a href="{{ route('home') }}">головна</a></li>
                    <li class="hover:text-slate-800 cursor-pointer"><a href="{{ route('product.show', session('cart')['product']->id) }}">замовлення</a></li>
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
                    <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg" id="ttn-form">
                        <div class="w-11/12 mx-auto py-6">
                            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Доставка</h2>

                            <!-- Окремі форми для вибору адреси -->
                            <div class="grid grid-cols-1 text-black mx-auto gap-3">
                                @if(!isset($settlements))
                                    <form action="{{ route('orders.searchSettlement') }}" method="POST">
                                        @csrf
                                        <div class="flex gap-2">
                                            <input
                                                class="rounded-lg p-3 border-none text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none flex-1"
                                                type="text" name="search" placeholder="Введіть місто"
                                                value="{{ isset($addressData['search']) ? $addressData['search'] : '' }}"
                                                required>
                                            <button type="submit" class="bg-yellow-400 text-black px-4 py-3 rounded-lg hover:bg-yellow-500 transition-colors">Знайти</button>
                                        </div>
                                    </form>
                                @elseif(isset($settlements) && count($settlements) > 0)
                                    <form action="{{ route('orders.chooseSettlement') }}" method="POST">
                                        @csrf
                                        <div class="flex gap-2">
                                            <select
                                                class="rounded-lg p-3 border-none text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none flex-1"
                                                name="settlement" required>
                                                <option value="" {{ !isset($addressData['settlement']) ? 'disabled selected' : '' }}>
                                                    Місто
                                                </option>
                                                @foreach($settlements as $settlement)
                                                    <option value="{{ $settlement['Ref'] }}"
                                                        {{ (isset($addressData['settlement']) && $addressData['settlement'] === $settlement['Ref']) ? 'selected' : '' }}>
                                                        {{ $settlement['Present'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-yellow-400 text-black px-4 py-3 rounded-lg hover:bg-yellow-500 transition-colors">Обрати</button>
                                        </div>
                                    </form>
                                @endif

                                @if(isset($warehouses) && count($warehouses) > 0)
                                    <form action="{{ route('orders.setWarehouse') }}" method="POST">
                                        @csrf
                                        <div class="flex gap-2">
                                            <select name="warehouse"
                                                    class="max-w-[300px] rounded-lg p-3 border-none text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none flex-1"
                                                    required>
                                                <option value="" {{ !isset($addressData['warehouse']) ? 'disabled selected' : '' }}>
                                                    Відділення або поштомат
                                                </option>
                                                @foreach($warehouses as $warehouse)
                                                    <option value="{{ $warehouse['Ref'] }}"
                                                        {{ (isset($addressData['warehouse']) && $addressData['warehouse'] === $warehouse['Ref']) ? 'selected' : '' }}>
                                                        {{ $warehouse['Description'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="bg-yellow-400 text-black px-4 py-3 rounded-lg hover:bg-yellow-500 transition-colors">Обрати</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Головна форма замовлення (показується тільки після вибору відділення) -->
                    @if(isset($addressData['warehouse']))
                        <form action="{{ route('orders.createCounterparty') }}" method="POST" id="unified-order-form">
                            @csrf

                            <!-- Приховані поля для адресних даних -->
                            <input type="hidden" name="settlement" value="{{ $addressData['settlement'] }}">
                            <input type="hidden" name="warehouse" value="{{ $addressData['warehouse'] }}">

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
                    @endif
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
                                <span class="font-semibold text-sm sm:text-base">{{ $deliveryCost ?? '-' }} грн</span>
                            </div>
                        </div>

                        <!-- Divider -->
                        <hr class="border-slate-800 border-[1.5px] rounded-lg my-6">

                        <!-- Total -->
                        <div class="flex justify-between items-center mb-8">
                            <span class="text-lg sm:text-xl font-semibold">До сплати</span>
                            <span class="font-bold text-xl sm:text-2xl lg:text-3xl">{{ isset($deliveryCost) ? session('cart')['total'] + $deliveryCost : session('cart')['total'] }} грн</span>
                        </div>

                        <!-- Submit Button (показується тільки після вибору відділення) -->
                        @if(isset($addressData['warehouse']))
                            <button type="submit" form="unified-order-form"
                                    class="w-full bg-blue-400 hover:bg-blue-500 active:bg-blue-600 text-xl sm:text-2xl lg:text-3xl text-white font-bold py-3 sm:py-4 rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 outline-none">
                                Підтвердити замовлення
                            </button>
                        @else
                            <div class="w-full bg-gray-400 text-xl sm:text-2xl lg:text-3xl text-white font-bold py-3 sm:py-4 rounded-lg text-center">
                                Оберіть адресу доставки
                            </div>
                        @endif

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
        </div>
    </main>

    <script>
        const unifiedOrderForm = document.getElementById('unified-order-form');
        if (unifiedOrderForm) {
            unifiedOrderForm.addEventListener('submit', function (e) {
                e.preventDefault();

                const formData = new FormData(this);

                // Якщо вибрано оплату карткою, відправляємо на WayForPay
                const paymentMethod = formData.get('payment');
                if (paymentMethod === 'card') {
                    // Тут можна додати логіку для WayForPay
                    // Наприклад, створити динамічну форму для WayForPay
                    handleCardPayment(formData);
                    return;
                }

                // Для оплати при отриманні відправляємо звичайний запит
                fetch('{{ route("orders.createCounterparty") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.text();
                    })
                    .then(text => {
                        console.log('Raw response:', text);

                        try {
                            const data = JSON.parse(text);
                            console.log('Parsed data:', data);

                            if (data.success) {
                                let message = data.message;
                                if (typeof message === 'string' && message.includes('\\u')) {
                                    message = message.replace(/\\u[\dA-F]{4}/gi, function (match) {
                                        return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
                                    });
                                }

                                alert('Успіх!\n' + message + '\nНомер ТТН: ' + data.ttn_number);
                                window.location.href = '{{ route("home") }}';
                            } else {
                                let errorMessage = data.message || 'Невідома помилка';
                                if (typeof errorMessage === 'string' && errorMessage.includes('\\u')) {
                                    errorMessage = errorMessage.replace(/\\u[\dA-F]{4}/gi, function (match) {
                                        return String.fromCharCode(parseInt(match.replace(/\\u/g, ''), 16));
                                    });
                                }
                                alert('Помилка: ' + errorMessage);
                            }
                        } catch (parseError) {
                            console.error('JSON parse error:', parseError);
                            alert('Помилка обробки відповіді сервера');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Виникла помилка при створенні ТТН');
                    });
            });
        }


        function handleCardPayment(formData) {
            // Спочатку відправляємо запит на сервер для підготовки оплати
            fetch('{{ route("orders.createCounterparty") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('HTTP error! status: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Server response:', data); // Для дебагу

                    if (data.success && data.payment_type === 'card') {
                        // Створюємо форму WayForPay з даними від сервера
                        const wayForPayForm = document.createElement('form');
                        wayForPayForm.method = 'POST';
                        wayForPayForm.action = 'https://secure.wayforpay.com/pay';
                        wayForPayForm.acceptCharset = 'utf-8';
                        wayForPayForm.style.display = 'none';

                        // Додаємо поля з даних сервера
                        const wayForPayData = data.wayforpay_data;

                        // Перевіряємо чи є merchantAccount
                        if (!wayForPayData.merchantAccount) {
                            alert('Помилка конфігурації платіжної системи');
                            return;
                        }

                        for (const [key, value] of Object.entries(wayForPayData)) {
                            if (Array.isArray(value)) {
                                // Для масивів створюємо окремі поля
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
                        console.log('Submitting WayForPay form'); // Для дебагу
                        wayForPayForm.submit();
                    } else {
                        alert('Помилка підготовки оплати: ' + (data.message || 'Невідома помилка'));
                    }
                })
                .catch(error => {
                    console.error('Payment preparation error:', error);
                    alert('Помилка підготовки оплати');
                });
        }
    </script>
@endsection

