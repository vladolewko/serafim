<!-- <div> @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <label for="name">Назва:</label>
        <input type="text" id="name" name="name" required>
        <label for="description">Опис:</label>
        <input type="text" id="description" name="description" required>
        <label for="price">ціна:</label>
        <input type="text" id="price" name="price" required>
        <label for="books_quantity">Кількість книг:</label>
        <input type="number" id="books_quantity" name="books_quantity" required>
        <label for="weight">вага:</label>
        <input type="number" id="weight" name="weight" required>
        <label for="dimension">розмір:</label>
        <input type="text" id="dimension" name="dimension" required>
        <label for="content">Вміст:</label>
        <input type="text" id="content" name="content" required>
        <label for="for_whom">Для кого призначено:</label>
        <input type="text" id="for_whom" name="for_whom" required>
        <label for="appointment">призначення комплект:</label>
        <input type="text" id="appointment" name="appointment" required>
        <label for="image">Зображення:</label>
        <input type="file" id="image" name="product_image" required>

        <button type="submit">Create Product</button>
    </form>
</div> -->




<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Serafim</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sofia+Sans+Condensed:ital,wght@0,1..1000;1,1..1000&display=swap" rel="stylesheet">


    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/index.js'])
    @endif
</head>
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
                            Розмір
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="dimension"
                               name="dimension"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200 placeholder-gray-400"
                               placeholder="наприклад: 20x15x5 см">
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
                               placeholder="Опишіть вміст комплекту">
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
                               placeholder="наприклад: дітей 5-8 років">
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
                               placeholder="наприклад: навчання, розвиток">
                    </div>

                    <!-- Зображення -->
                    <div class="lg:col-span-2">
                        <label for="image" class="block text-sm font-semibold text-gray-700 mb-2">
                            Зображення продукту
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Завантажити файл</span>
                                        <input id="image" name="product_image" type="file" class="sr-only" required accept="image/*">
                                    </label>
                                    <p class="pl-1">або перетягніть сюди</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF до 10MB</p>
                            </div>
                        </div>
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

    <script>
        // File upload preview
        const fileInput = document.getElementById('image');
        fileInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileName = file.name;
                const fileSize = (file.size / 1024 / 1024).toFixed(2);

                // Update the upload area text
                const uploadArea = fileInput.closest('.border-dashed');
                const textArea = uploadArea.querySelector('.space-y-1');
                textArea.innerHTML = `
                    <svg class="mx-auto h-12 w-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="text-sm text-gray-600">
                        <p class="font-medium text-green-600">${fileName}</p>
                        <p class="text-xs text-gray-500">${fileSize} MB</p>
                    </div>
                `;
                uploadArea.classList.remove('border-gray-300');
                uploadArea.classList.add('border-green-300', 'bg-green-50');
            }
        });

        // Form validation feedback
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input[required]');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                if (this.value.trim() === '') {
                    this.classList.add('border-red-300', 'ring-red-500');
                    this.classList.remove('border-gray-300');
                } else {
                    this.classList.remove('border-red-300', 'ring-red-500');
                    this.classList.add('border-green-300');
                }
            });

            input.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('border-red-300', 'ring-red-500');
                    this.classList.add('border-green-300');
                }
            });
        });
    </script>
</div>





