@extends('layouts.site')


@section('content')
<div id="ttn-form">
        <!-- Статус налаштувань -->
        <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd;">
            <h3>Статус налаштувань</h3>
            <button type="button" id="check-status-btn">Перевірити статус</button>
            <div id="status-info" style="margin-top: 10px;"></div>
        </div>

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