<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Помилка оплати</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
<div class="bg-white rounded-lg shadow-xl p-8 max-w-md w-full mx-4">
    <!-- Error Icon -->
    <div class="text-center mb-6">
        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
            <i class="fas fa-times text-red-600 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-900">Помилка оплати</h1>
    </div>

    <!-- Error Message -->
    <div class="text-center mb-6">
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <p class="text-red-700">
                {{ $error ?? 'На жаль, під час оплати виникла помилка. Спробуйте ще раз або оберіть інший спосіб оплати.' }}
            </p>
        </div>
    </div>

    <!-- Possible Reasons -->
    <div class="mb-6">
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <h3 class="font-semibold text-yellow-800 mb-2">Можливі причини:</h3>
            <ul class="text-sm text-yellow-700 space-y-1">
                <li>• Недостатньо коштів на картці</li>
                <li>• Картка заблокована або прострочена</li>
                <li>• Неправильно введені дані картки</li>
                <li>• Тимчасові технічні проблеми</li>
                <li>• Операцію було скасовано</li>
            </ul>
        </div>
    </div>

    <!-- What to do -->
    <div class="mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h3 class="font-semibold text-blue-800 mb-2">Що робити далі?</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Перевірте дані вашої картки</li>
                <li>• Переконайтеся, що у вас достатньо коштів</li>
                <li>• Спробуйте повторити оплату</li>
                <li>• Або оберіть оплату при отриманні</li>
            </ul>
        </div>
    </div>

    <!-- Actions -->
    <div class="flex flex-col space-y-3">
        <button onclick="history.back()"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Спробувати ще раз
        </button>

        <a href="{{ route('home') }}"
           class="w-full bg-gray-600 hover:bg-gray-700 text-white font-medium py-3 px-4 rounded-lg text-center transition-colors">
            <i class="fas fa-home mr-2"></i>
            Повернутися на головну
        </a>
    </div>

    <!-- Contact Info -->
    <div class="mt-6 pt-6 border-t border-gray-200 text-center">
        <p class="text-sm text-gray-500 mb-2">
            Проблема не вирішується?
        </p>
        <div class="flex flex-col space-y-2">
            <a href="tel:+380123456789"
               class="text-blue-600 hover:text-blue-700 text-sm flex items-center justify-center">
                <i class="fas fa-phone mr-2"></i>
                +38 (012) 345-67-89
            </a>
            <a href="mailto:support@example.com"
               class="text-blue-600 hover:text-blue-700 text-sm flex items-center justify-center">
                <i class="fas fa-envelope mr-2"></i>
                support@example.com
            </a>
        </div>
    </div>

    <!-- Additional Help -->
    <div class="mt-4 text-center">
        <p class="text-xs text-gray-400">
            Наша служба підтримки працює цілодобово та готова допомогти вам з будь-якими питаннями
        </p>
    </div>
</div>

<script>
    // Автоматично приховуємо повідомлення через деякий час
    setTimeout(function() {
        const errorBg = document.querySelector('.bg-red-50');
        if (errorBg) {
            errorBg.style.transition = 'opacity 0.5s ease';
            errorBg.style.opacity = '0.7';
        }
    }, 10000);
</script>
</body>
</html>
