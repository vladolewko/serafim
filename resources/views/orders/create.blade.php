@extends('layouts.site')


@section('content')
<div id="ttn-form">
        <!-- Форма пошуку міста -->
        <form action="{{ route('orders.searchSettlement') }}" method="POST">
            @csrf
            <div>
                <label>Пошук міста:</label>
                <input type="text" name="search" placeholder="Введіть місто"
                       value="{{ isset($addressData['search']) ? $addressData['search'] : '' }}" required>
                <button type="submit">Знайти</button>
            </div>
        </form>

        @if(isset($error))
            <div style="color: red; margin: 10px 0;">{{ $error }}</div>
        @endif

        <!-- Вибір населеного пункту -->
        @if(isset($settlements) && count($settlements) > 0)
            <form action="{{ route('orders.chooseSettlement') }}" method="POST">
                @csrf
                <div>
                    <label>Населений пункт:</label>
                    <select name="settlement" required>
                        <option value="">Оберіть населений пункт</option>
                        @foreach($settlements as $settlement)
                            <option value="{{ $settlement['Ref'] }}"
                                    {{ (isset($addressData['settlement']) && $addressData['settlement'] === $settlement['Ref']) ? 'selected' : '' }}>
                                {{ $settlement['Present'] }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Обрати</button>
                </div>
            </form>
        @endif

        <!-- Вибір відділення -->
        @if(isset($warehouses) && count($warehouses) > 0)
            <form action="{{ route('orders.setWarehouse') }}" method="POST">
                @csrf
                <div>
                    <label>Відділення:</label>
                    <select name="warehouse" required>
                        <option value="">Оберіть відділення</option>
                        @foreach($warehouses as $warehouse)
                            <option value="{{ $warehouse['Ref'] }}"
                                    {{ (isset($addressData['warehouse']) && $addressData['warehouse'] === $warehouse['Ref']) ? 'selected' : '' }}>
                                {{ $warehouse['Description'] }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit">Обрати</button>
                </div>
            </form>

            <!-- Форма створення ТТН (показується тільки після вибору відділення) -->
            @if(isset($addressData['warehouse']))
                <form action="{{ route('orders.createCounterparty') }}" method="POST" id="create-ttn-form">
                    @csrf
                    <h3>Дані отримувача</h3>
                    <div>
                        <input type="text" name="name" placeholder="Ім'я" required>
                    </div>
                    <div>
                        <input type="text" name="surname" placeholder="Прізвище" required>
                    </div>
                    <div>
                        <input type="text" name="middlename" placeholder="По батькові" required>
                    </div>
                    <div>
                        <input type="text" name="phone" placeholder="Телефон (380xxxxxxxxx)" required>
                    </div>

                    <button type="submit">Створити ТТН</button>
                </form>
            @endif
        @endif
    </div>




<main class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <div class="w-full">
        <!-- Navigation -->
        <nav class="mb-6">
            <ul class="flex flex-wrap text-lg sm:text-xl text-slate-600 items-center gap-2 sm:gap-4">
                <li class="hover:text-slate-800 cursor-pointer">головна</li>
                <li class="hover:text-slate-800 cursor-pointer">замовлення</li>
                <li class="text-yellow-400 text-xl sm:text-2xl font-semibold">оформлення</li>
            </ul>
        </nav>

        <!-- Page Title -->
        <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mb-8 lg:mb-16">Оформлення замовлення</h1>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row justify-between w-full gap-6 lg:gap-8">

            <!-- Left Column - Forms -->
            <div class="flex flex-col w-full lg:w-7/12 xl:w-6/12 space-y-6 lg:space-y-10">

                <!-- Contact Information -->
                <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg">
                    <div class="w-11/12 mx-auto py-6">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Контактні данні</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 text-black mx-auto gap-3">
                            <input class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                    placeholder="+380" value="+380" type="tel">
                            <input class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                    placeholder="Електронна пошта" type="email">
                            <input class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                    placeholder="Ім'я" type="text">
                            <input class="rounded-lg p-3 border-none placeholder-slate-600 text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none"
                                    placeholder="Прізвище" type="text">
                        </div>
                    </div>
                </div>

                <!-- Delivery -->
                <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg">
                    <div class="w-11/12 mx-auto py-6">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Доставка</h2>

                        <!-- Radio buttons -->
                        <div class="flex flex-col sm:flex-row gap-2 mb-6">
                            <div class="flex items-center gap-2 bg-yellow-400 rounded-lg px-2 py-2">
                                <input id="radio-1" class="w-4 h-4 cursor-pointer rounded-full text-blue-400 bg-white" name="delivery" type="radio" value="1">
                                <label class="cursor-pointer text-black text-xs sm:text-sm font-medium" for="radio-1">Кур’єр “нової пошти”</label>
                            </div>

                            <div class="flex items-center gap-2 bg-yellow-400 rounded-lg px-2 py-2 flex-1">
                                <input checked id="radio-2" class="w-4 h-4 cursor-pointer rounded-full text-blue-400 bg-white" name="delivery" type="radio" value="2">
                                <label class="cursor-pointer text-black text-xs text-xs sm:text-sm font-medium" for="radio-2">Відділення/поштомат “нової пошти”</label>
                            </div>

                        </div>

                        <!-- Dropdowns -->
                        <div class="grid grid-cols-1 text-black mx-auto gap-3">
                            <select class="rounded-lg p-3 border-none text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none">
                                <option value="" disabled selected>Місто</option>
                                <option value="kyiv">Київ</option>
                                <option value="lviv">Львів</option>
                                <option value="kharkiv">Харків</option>
                            </select>
                            <select class="rounded-lg p-3 border-none text-sm sm:text-base focus:ring-2 focus:ring-yellow-400 outline-none">
                                <option value="" disabled selected>Відділення або поштомат</option>
                                <option value="1">Відділення №1</option>
                                <option value="2">Відділення №2</option>
                                <option value="3">Поштомат №1</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Payment -->
                <div class="bg-blue-400 text-white rounded-lg w-full shadow-lg">
                    <div class="w-11/12 mx-auto py-6">
                        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-semibold mb-4">Оплата</h2>
                        <div class="grid grid-cols-1 text-black mx-auto gap-3">
                            <div class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors">
                                <input id="radio-3" class="w-4 h-4 cursor-pointer text-yellow-400 bg-white border-yellow-400"
                                        name="payment" type="radio" value="1">
                                <label class="cursor-pointer text-black text-sm sm:text-base font-medium flex-1" for="radio-3">
                                    Карткою на сайті
                                </label>
                            </div>

                            <div class="flex items-center gap-3 bg-white rounded-lg px-3 py-3 hover:bg-gray-50 transition-colors">
                                <input id="radio-4" class="w-4 h-4 cursor-pointer text-yellow-400 bg-white border-yellow-400"
                                        name="payment" type="radio" value="2">
                                <label class="cursor-pointer text-black text-sm sm:text-base font-medium flex-1" for="radio-4">
                                    При отриманні
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Summary -->
            <div class="bg-yellow-400 text-black rounded-lg w-full lg:w-5/12 h-fit shadow-lg sticky top-6">
                <div class="w-10/12 mx-auto py-6">
                    <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-6">Разом</h2>

                    <!-- Order Details -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 text-sm sm:text-base">Один товар на суму</span>
                            <span class="font-semibold text-sm sm:text-base">600 грн</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-600 text-sm sm:text-base">Вартість доставки</span>
                            <span class="font-semibold text-sm sm:text-base">60 грн</span>
                        </div>
                    </div>

                    <!-- Divider -->
                    <hr class="border-slate-800 border-[1.5px] rounded-lg my-6">

                    <!-- Total -->
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-lg sm:text-xl font-semibold">До сплати</span>
                        <span class="font-bold text-xl sm:text-2xl lg:text-3xl">660 грн</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-blue-400 hover:bg-blue-500 active:bg-blue-600 text-xl sm:text-2xl lg:text-3xl text-white font-bold py-3 sm:py-4 rounded-lg transition-colors focus:ring-4 focus:ring-blue-300 outline-none">
                        Підтвердити замовлення
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

        // Обробка створення ТТН
        const createTtnForm = document.getElementById('create-ttn-form');
        if (createTtnForm) {
            createTtnForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('{{ route("orders.createCounterparty") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('ТТН створено успішно! Номер: ' + data.ttn_number);
                        window.location.href = '{{ route("home") }}';
                    } else {
                        alert('Помилка створення ТТН: ' + (data.message || 'Невідома помилка'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Виникла помилка при створенні ТТН');
                });
            });
        }
    </script>

    @endsection
