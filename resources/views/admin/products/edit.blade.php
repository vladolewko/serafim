@extends('layouts.admin')
@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-lg mb-8 p-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Редагування товару</h1>
            <p class="text-lg text-gray-600">{{ $product->name }}</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Main Form -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <form action="{{ route('admin.products.update') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-200">
                @csrf
                @method('PATCH')
                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <!-- Form Header -->
                <div class="px-6 py-4 bg-gray-50">
                    <h2 class="text-xl font-semibold text-gray-800">Інформація про товар</h2>
                </div>

                <!-- Form Fields -->
                <div class="px-6 py-6 space-y-6">
                    <!-- Name Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Назва</label>
                            <p class="text-sm text-gray-500">Введіть назву товару</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="text" id="name" name="name" value="{{ $product->name }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @if($errors->has('name'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('name') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Description Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Опис</label>
                            <p class="text-sm text-gray-500">Короткий опис товару</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="text" id="description" name="description" value="{{ $product->description }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @if($errors->has('description'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('description') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Price Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Ціна</label>
                            <p class="text-sm text-gray-500">Вартість товару</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="relative">
                                <input type="text" id="price" name="price" value="{{ $product->price }}" required
                                       class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">₴</span>
                                </div>
                            </div>
                            @if($errors->has('price'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('price') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Books Quantity Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="books_quantity" class="block text-sm font-medium text-gray-700 mb-2">Кількість книг</label>
                            <p class="text-sm text-gray-500">Число книг в комплекті</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="number" id="books_quantity" name="books_quantity" value="{{ $product->books_quantity }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @if($errors->has('books_quantity'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('books_quantity') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Weight Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">Вага</label>
                            <p class="text-sm text-gray-500">Вага товару в грамах</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="relative">
                                <input type="number" id="weight" name="weight" value="{{ $product->weight }}" required
                                       class="block w-full pr-12 pl-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">г</span>
                                </div>
                            </div>
                            @if($errors->has('weight'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('weight') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Dimension Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="dimension" class="block text-sm font-medium text-gray-700 mb-2">Розміри</label>
                            <p class="text-sm text-gray-500">Габарити товару</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="text" id="dimension" name="dimension" value="{{ $product->dimension }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @if($errors->has('dimension'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('dimension') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Content Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Вміст</label>
                            <p class="text-sm text-gray-500">Детальний опис змісту</p>
                        </div>
                        <div class="md:col-span-2">
                            <textarea id="content" name="content" required rows="4"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $product->content }}</textarea>
                            @if($errors->has('content'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('content') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- For Whom Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="for_whom" class="block text-sm font-medium text-gray-700 mb-2">Для кого призначено</label>
                            <p class="text-sm text-gray-500">Цільова аудitorія</p>
                        </div>
                        <div class="md:col-span-2">
                            <textarea id="for_whom" name="for_whom" required rows="3"
                                      class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $product->for_whom }}</textarea>
                            @if($errors->has('for_whom'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('for_whom') }}</p>
                            @endif
                        </div>
                    </div>

                    <!-- Appointment Field -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="appointment" class="block text-sm font-medium text-gray-700 mb-2">Призначення комплекту</label>
                            <p class="text-sm text-gray-500">Для яких цілей призначений</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="text" id="appointment" name="appointment" value="{{ $product->appointment }}" required
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            @if($errors->has('appointment'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('appointment') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="applying" class="block text-sm font-medium text-gray-700 mb-2">Категорія</label>
                            <p class="text-sm text-gray-500">виберіть категорію</p>
                        </div>
                        <div class="md:col-span-2">
                            <select id="applying" name="applying" required
                                    class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @foreach($applyings as $name => $applying)

                                    <option value="{{ $name }}"
                                        {{ $product->applying?->value === $name ? 'selected' : '' }}>
                                        {{ $applying }}
                                    </option>
                                @endforeach
                            </select>
                            @if($errors->has('applying'))
                                <p class="mt-2 text-sm text-red-600">{{ $errors->first('applying') }}</p>
                            @endif
                        </div>
                    </div>


                    <!-- Image Upload Field - ВИПРАВЛЕНА ВЕРСІЯ -->
                    <!-- <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="product_image" class="block text-sm font-medium text-gray-700 mb-2">Зображення</label>
                            <p class="text-sm text-gray-500">Завантажте нове зображення</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="space-y-4"> -->
                                <!-- Виправлена секція завантаження -->
                                <!-- <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition duration-200" id="upload-area">
                                    <div class="space-y-1 text-center">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <div class="flex text-sm text-gray-600"> -->
                                            <!-- <label for="product_image" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500"> -->
                                                <!-- <span>Завантажити файл</span> -->
                                                <!-- ГОЛОВНЕ ВИПРАВЛЕННЯ: правильний id та name -->
                                                <!-- <input id="product_image" name="product_image" type="file" class="sr-only" accept="image/*"> -->
                                            <!-- </label>
                                            <p class="pl-1">або перетягніть сюди</p>
                                        </div>
                                        <p class="text-xs text-gray-500">PNG, JPG, GIF до 5MB</p>
                                    </div>
                                </div> -->

                                <!--@if($errors->has('product_image'))
                                    <p class="text-sm text-red-600">{{ $errors->first('product_image') }}</p>
                                @endif

                                @if($product->getMedia('product_images')->isNotEmpty())
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Поточне зображення:</p>
                                        <div class="relative inline-block">
                                            @if($product->getMedia('product_images')->isNotEmpty())
                                                @php
                                                    $imageUrl = $product->getFirstMediaUrl('product_images');
                                                    $imageUrl = str_replace('http://110.172.148.57:8000', 'https://serafym.info', $imageUrl);
                                                @endphp
                                                <img class="w-32 h-32" src="{{ $imageUrl }}" alt="{{ $product->title }}">
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div> -->

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Зображення</label>
                            <p class="text-sm text-gray-500">Завантажте нове зображення</p>
                        </div>
                        <div class="md:col-span-2">
                            <div class="space-y-4">
                                <input type="file" id="product_image" name="product_image" accept="image/*" class="hidden">
                                <label for="product_image" class="flex items-center w-full px-4 py-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-colors">
                                    <span id="file-name" class="text-black text-left">Файл не вибрано</span>
                                </label>
                                @if($errors->has('product_image'))
                                    <p class="text-sm text-red-600">{{ $errors->first('product_image') }}</p>
                                @endif
                                @if($product->getMedia('product_images')->isNotEmpty())
                                    <div class="mt-4">
                                        <p class="text-sm font-medium text-gray-700 mb-2">Поточне зображення:</p>
                                        <div class="relative inline-block">
                                            @php
                                                $imageUrl = $product->getFirstMediaUrl('product_images');
                                                $imageUrl = str_replace('http://110.172.148.57:8000', 'https://serafym.info', $imageUrl);
                                            @endphp
                                            <img class="w-32 h-32" src="{{ $imageUrl }}" alt="{{ $product->title }}">
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    </div>



                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 flex justify-between items-center">
                    <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Оновити товар
                    </button>
                </div>
            </form>



            <!-- Delete Form -->
        <div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-red-400">
            <div class="px-6 py-4 bg-red-50">
                <h3 class="text-lg font-medium text-red-800">Небезпечна зона</h3>
                <p class="text-sm text-red-600 mt-1">Після видалення товар неможливо буде відновити</p>
            </div>
            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                  onsubmit="return confirm('Ви впевнені, що хочете видалити цей товар?');"
                  class="px-6 py-4">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Видалити товар
                </button>
            </form>
        </div>
        </div>
    </div>
</div>

@endsection


<script defer>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('product_image');
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
