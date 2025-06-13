<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оплата успішна</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
    <!-- Success Icon -->
    <div class="text-center mb-6">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-green-100 mb-4">
            <i class="fas fa-check text-green-600 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Оплата успішна!</h1>
    </div>

    <!-- Message -->
    <div class="text-center mb-6">
        <p class="text-gray-600 mb-4">
            {{ $message ?? 'Ваше замовлення було успішно оплачено та прийнято в обробку.' }}
        </p>

        @if(isset($ttn_number))
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <h3 class="font-semibold text-blue-800 mb-2">Номер ТТН:</h3>
                <p class="text-blue-700 font-mono text-lg">{{ $ttn_number }}</p>
                <p class="text-sm text-blue-600 mt-2">
                    Збережіть цей номер для відстеження посилки
                </p>
            </div>
        @endif

        @if(isset($amount))
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                <h3 class="font-semibold text-gray-800 mb-1">Сума оплати:</h3>
                <p class="text-gray-700 font-semibold text-xl">{{ $amount }} грн</p>
            </div>
        @endif
    </div>

    <!-- Instructions -->
    <div class="text-center mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="font-semibold text-yellow-800 mb-2">Що далі?</h3>
            <ul class="text-sm text-yellow-700 text-left space-y-1">
                <li>• Ваше замовлення передано в обробку</li>
                <li>• Посилка буде відправлена найближчим часом</li>
                <li>• Ви отримаєте SMS з номером для відстеження</li>
                <li>• Забрати посилку можна у вказаному відділенні</li>
            </ul>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col space-y-3">
        <a href="{{ route('home') }}"
           class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors">
            <i class="fas fa-home mr-2"></i>
            Повернутися на головну
        </a>

        @if(isset($ttn_number))
            <button onclick="copyTTN()"
                    class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors">
                <i class="fas fa-copy mr-2"></i>
                Скопіювати номер ТТН
            </button>
        @endif
    </div>

    <!-- Contact Info -->
    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-500">
            Виникли питання?
            <a href="#" class="text-blue-600 hover:text-blue-700 underline">Зв'яжіться з нами</a>
        </p>
    </div>
</div>

@if(isset($ttn_number))
    <script>
        function copyTTN() {
            const ttnNumber = '{{ $ttn_number }}';
            navigator.clipboard.writeText(ttnNumber).then(function() {
                // Показуємо повідомлення про успішне копіювання
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Скопійовано!';
                button.classList.remove('bg-gray-600', 'hover:bg-gray-700');
                button.classList.add('bg-green-600');

                setTimeout(function() {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600');
                    button.classList.add('bg-gray-600', 'hover:bg-gray-700');
                }, 2000);
            }).catch(function(err) {
                alert('Помилка копіювання: ' + err);
            });
        }
    </script>
@endif
</body>
</html>
