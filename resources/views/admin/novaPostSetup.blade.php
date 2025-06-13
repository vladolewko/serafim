 <!-- @extends('layouts.site')


@section('content')
 <div style="margin-bottom: 20px; padding: 10px; border: 1px solid #ddd;">
            <h3>Статус налаштувань</h3>
            <button type="button" id="check-status-btn">Перевірити статус</button>
            <div id="status-info" style="margin-top: 10px;"></div>
        </div>


        <!-- Форма налаштування відправника (додаткова) -->
       <!-- <div style="margin-top: 40px; border-top: 1px solid #ccc; padding-top: 20px;">
            <h3>Налаштування відправника (одноразово)</h3>
            <form id="setup-sender-form">
                @csrf
                <div>
                    <input type="text" name="name" placeholder="Ім'я відправника" required>
                </div>
                <div>
                    <input type="text" name="surname" placeholder="Прізвище відправника" required>
                </div>
                <div>
                    <input type="text" name="phone" placeholder="Телефон відправника" required>
                </div>
                <div>
                    <input type="text" name="city" placeholder="Місто відправника" required>
                </div>

                <button type="submit">Налаштувати відправника</button>
            </form>
        </div>
        <script>

         // Перевірка статусу налаштувань
        document.getElementById('check-status-btn').addEventListener('click', function() {
            fetch('{{ route("orders.checkStatus") }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                let statusHtml = '<div>';

                // API статус
                if (data.api_key.success) {
                    statusHtml += '<p style="color: green;">✓ API ключ працює</p>';
                } else {
                    statusHtml += '<p style="color: red;">✗ Проблема з API ключем: ' + data.api_key.message + '</p>';
                }

                // Статус відправника
                if (data.sender_setup.is_configured) {
                    statusHtml += '<p style="color: green;">✓ Відправник налаштований</p>';
                    statusHtml += '<details><summary>Деталі</summary><pre>' + JSON.stringify(data.sender_setup.existing, null, 2) + '</pre></details>';
                } else {
                    statusHtml += '<p style="color: red;">✗ Відправник не налаштований</p>';
                    statusHtml += '<p>Відсутні: ' + data.sender_setup.missing.join(', ') + '</p>';
                }

                statusHtml += '</div>';
                document.getElementById('status-info').innerHTML = statusHtml;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('status-info').innerHTML = '<p style="color: red;">Помилка перевірки статусу</p>';
            });
        });

        // Обробка налаштування відправника
        document.getElementById('setup-sender-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route("orders.setupSender") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Відправник налаштований успішно!');
                    this.reset();
                } else {
                    alert('Помилка: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Виникла помилка при налаштуванні відправника');
            });
        });
         </script>
@endsection -->



<!-- Не можу підключити tailwind -->
@extends('layouts.site')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-purple-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Заголовок сторінки -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Налаштування системи</h1>
            <p class="text-lg text-gray-600">Конфігурація відправника та перевірка статусу API</p>
        </div>

        <!-- Карточка перевірки статусу -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Статус налаштувань
                </h3>
            </div>

            <div class="p-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <p class="text-gray-600">Перевірте поточний стан системи та налаштувань</p>
                    <button type="button" id="check-status-btn"
                            class="inline-flex items-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Перевірити статус
                    </button>
                </div>

                <div id="status-info" class="mt-6"></div>
            </div>
        </div>

        <!-- Форма налаштування відправника -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-teal-600 px-6 py-4">
                <h3 class="text-xl font-semibold text-white flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Налаштування відправника
                </h3>
                <p class="text-green-100 mt-1">Одноразове налаштування інформації про відправника</p>
            </div>

            <div class="p-6">
                <form id="setup-sender-form" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Ім'я відправника</label>
                            <input type="text" name="name" placeholder="Введіть ім'я" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 placeholder-gray-400">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Прізвище відправника</label>
                            <input type="text" name="surname" placeholder="Введіть прізвище" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 placeholder-gray-400">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Телефон відправника</label>
                            <input type="text" name="phone" placeholder="+380 XX XXX XX XX" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 placeholder-gray-400">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700">Місто відправника</label>
                            <input type="text" name="city" placeholder="Введіть місто" required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 placeholder-gray-400">
                        </div>
                    </div>

                    <div class="flex justify-end pt-4">
                        <button type="submit"
                                class="inline-flex items-center px-8 py-3 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Налаштувати відправника
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Перевірка статусу налаштувань
document.getElementById('check-status-btn').addEventListener('click', function() {
    // Показуємо індикатор завантаження
    const button = this;
    const originalText = button.innerHTML;
    button.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Перевіряємо...';
    button.disabled = true;

    fetch('{{ route("orders.checkStatus") }}', {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        let statusHtml = '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">';

        // API статус
        if (data.api_key.success) {
            statusHtml += `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium text-green-800">API ключ працює</span>
                    </div>
                </div>
            `;
        } else {
            statusHtml += `
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        <div>
                            <span class="font-medium text-red-800">Проблема з API ключем</span>
                            <p class="text-red-600 text-sm mt-1">${data.api_key.message}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        // Статус відправника
        if (data.sender_setup.is_configured) {
            statusHtml += `
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center mb-2">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="font-medium text-green-800">Відправник налаштований</span>
                    </div>
                    <details class="mt-2">
                        <summary class="cursor-pointer text-sm text-green-700 hover:text-green-800">Переглянути деталі</summary>
                        <pre class="mt-2 text-xs bg-green-100 p-2 rounded border overflow-x-auto">${JSON.stringify(data.sender_setup.existing, null, 2)}</pre>
                    </details>
                </div>
            `;
        } else {
            statusHtml += `
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-yellow-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <span class="font-medium text-yellow-800">Відправник не налаштований</span>
                            <p class="text-yellow-700 text-sm mt-1">Відсутні поля: ${data.sender_setup.missing.join(', ')}</p>
                        </div>
                    </div>
                </div>
            `;
        }

        statusHtml += '</div>';
        document.getElementById('status-info').innerHTML = statusHtml;
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('status-info').innerHTML = `
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span class="font-medium text-red-800">Помилка перевірки статусу</span>
                </div>
            </div>
        `;
    })
    .finally(() => {
        // Відновлюємо кнопку
        button.innerHTML = originalText;
        button.disabled = false;
    });
});

// Обробка налаштування відправника
document.getElementById('setup-sender-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<svg class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Налаштовуємо...';
    submitButton.disabled = true;

    const formData = new FormData(this);

    fetch('{{ route("orders.setupSender") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Показуємо успішне повідомлення
            const successMessage = document.createElement('div');
            successMessage.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            successMessage.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Відправник налаштований успішно!
                </div>
            `;
            document.body.appendChild(successMessage);

            setTimeout(() => {
                successMessage.remove();
            }, 3000);

            this.reset();
        } else {
            // Показуємо помилку
            const errorMessage = document.createElement('div');
            errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            errorMessage.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Помилка: ${data.message}
                </div>
            `;
            document.body.appendChild(errorMessage);

            setTimeout(() => {
                errorMessage.remove();
            }, 5000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorMessage = document.createElement('div');
        errorMessage.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        errorMessage.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Виникла помилка при налаштуванні відправника
            </div>
        `;
        document.body.appendChild(errorMessage);

        setTimeout(() => {
            errorMessage.remove();
        }, 5000);
    })
    .finally(() => {
        // Відновлюємо кнопку
        submitButton.innerHTML = originalText;
        submitBook.disabled = false;
    });
});
</script>

@endsection
