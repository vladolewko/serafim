@extends('layouts.admin')
@section('content')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">
        <!-- Success Alert -->
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm hidden" id="success-alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">Успіх!</span> Операція виконана успішно.
            </div>
        </div>

        <!-- Error Alert -->
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg shadow-sm hidden" id="error-alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <span class="font-medium">Помилка!</span> Щось пішло не так.
            </div>
        </div>

        <!-- Main Form Card -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
                <h1 class="text-2xl font-bold text-white">Створити новий продукт</h1>
                <p class="text-blue-100 mt-1">Заповніть всі поля для створення продукту</p>
            </div>

            <!-- Form -->
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Назва -->
                    <div class="lg:col-span-2">
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                            Назва продукту
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="name"
                               name="name"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="Введіть назву продукту">
                    </div>

                    <!-- Опис -->
                    <div class="lg:col-span-2">
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                            Опис
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="description"
                               name="description"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="Введіть опис продукту">
                    </div>

                    <!-- Ціна -->
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Ціна
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text"
                                   id="price"
                                   name="price"
                                   required
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 text-sm">₴</span>
                            </div>
                        </div>
                    </div>

                    <!-- Кількість книг -->
                    <div>
                        <label for="books_quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                            Кількість книг
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="number"
                               id="books_quantity"
                               name="books_quantity"
                               required
                               min="1"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="Введіть кількість">
                    </div>

                    <!-- Вага -->
                    <div>
                        <label for="weight" class="block text-sm font-semibold text-gray-700 mb-2">
                            Вага
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="number"
                                   id="weight"
                                   name="weight"
                                   required
                                   step="0.01"
                                   min="0"
                                   class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                                   placeholder="0.00">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                <span class="text-gray-500 text-sm">кг</span>
                            </div>
                        </div>
                    </div>


                    <!-- Розмір -->
                    <div>
                        <label for="dimension" class="block text-sm font-semibold text-gray-700 mb-2">
                            Розмір(довжина, ширина та висота в сантиметрах)
                            <span class="text-red-500">*</span>
                        </label>

                        <input type="text"
                               id="length"
                               name="length"
                               required
                               class="w-full px-4 mb-2 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="наприклад: 20">
                        <input type="text"
                               id="height"
                               name="height"
                               required
                               class="w-full px-4 mb-2 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="наприклад: 20">
                        <input type="text"
                               id="width"
                               name="width"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="наприклад: 20">
                    </div>

                    <!-- Вміст -->
                    <div class="lg:col-span-2">
                        <label for="content" class="block text-sm font-semibold text-gray-700 mb-2">
                            Вміст
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="content"
                               name="content"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="наприклад: елемент1|елемент2|елемент3|">
                    </div>

                    <!-- Для кого призначено -->
                    <div>
                        <label for="for_whom" class="block text-sm font-semibold text-gray-700 mb-2">
                            Для кого призначено
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="for_whom"
                               name="for_whom"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="наприклад: призначення1|призначення2|призначення3|">
                    </div>

                    <!-- Призначення комплекту -->
                    <div>
                        <label for="appointment" class="block text-sm font-semibold text-gray-700 mb-2">
                            Призначення комплекту
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="appointment"
                               name="appointment"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="наприклад: для навчання та розвитку">
                    </div>
                    <!-- категорія -->
                    <div class="lg:col-span-2">
                        <label for="applying" class="block text-sm font-semibold text-gray-700 mb-2">
                            категорія
                            <span class="text-red-500">*</span>
                        </label>
                        <select  id="applying"
                                 name="applying"
                                 required
                                 class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400">
                            <option value="">Виберіть категорію</option>
                            @foreach($applyings as $name => $applying)
                                <option value="{{ $name }}">{{ $applying }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Зображення -->
                    <div class="lg:col-span-2">
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                            Зображення продукту
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="image" name="product_image" accept="image/*" class="hidden">
                        <label for="image" class="flex items-center w-full px-4 py-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-colors">
                            <span id="file-name" class="text-black text-left">Файл не вибрано</span>
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 font-medium">
                            Скасувати
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-600 to-indigo-700 text-white rounded-lg hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            <span class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Створити продукт
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


<script defer>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('image');
    const fileNameSpan = document.getElementById('file-name');

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            fileNameSpan.textContent = file.name; // Відображаємо назву файлу
            fileNameSpan.classList.remove('text-gray-500'); // Знімаємо сірий колір, якщо був
            fileNameSpan.classList.add('text-black'); // Додаємо чорний колір
        } else {
            fileNameSpan.textContent = 'Файл не вибрано';
            fileNameSpan.classList.add('text-gray-500');
        }
    });
});
</script>



