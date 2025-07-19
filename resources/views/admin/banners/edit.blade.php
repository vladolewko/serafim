@extends('layouts.admin')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-lg shadow-lg mb-8 p-6">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Редагування банера</h1>
                <p class="text-lg text-gray-600">{{ $banner->title }}</p>
            </div>

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
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
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                      clip-rule="evenodd"/>
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
                <form action="{{ route('admin.banners.update') }}" method="POST" enctype="multipart/form-data"
                      class="divide-y divide-gray-200">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="banner_id" value="{{ $banner->id }}">

                    <!-- Form Header -->
                    <div class="px-6 py-4 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-800">Інформація про банер</h2>
                    </div>

                    <!-- Form Fields -->
                    <div class="px-6 py-6 space-y-6">
                        <!-- Name Field -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Назва</label>
                                <p class="text-sm text-gray-500">Введіть назву банера</p>
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" id="title" name="title" value="{{ $banner->title }}" required
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @if($errors->has('title'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('title') }}</p>
                                @endif
                            </div>
                        </div>

                        <!-- Price Field -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Ціна</label>
                                <p class="text-sm text-gray-500">Вартість банера</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="relative">
                                    <input type="text" id="price" name="price" value="{{ $banner->price }}" required
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


                        <!-- Content Field -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="content"
                                       class="block text-sm font-medium text-gray-700 mb-2">Посилання</label>
                                <p class="text-sm text-gray-500">Посилання на банер</p>
                            </div>
                            <div class="md:col-span-2">
                                <input type="text" id="reference" name="reference" value="{{ $banner->reference }}"
                                       required
                                       class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                @if($errors->has('reference'))
                                    <p class="mt-2 text-sm text-red-600">{{ $errors->first('reference') }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="md:col-span-1">
                                <label for="banner_image"
                                       class="block text-sm font-medium text-gray-700 mb-2">Зображення</label>
                                <p class="text-sm text-gray-500">Завантажте нове зображення</p>
                            </div>
                            <div class="md:col-span-2">
                                <div class="space-y-4">
                                    <input type="file" id="banner_image" name="banner_image" accept="image/*"
                                           class="hidden">
                                    <label for="banner_image"
                                           class="flex items-center w-full px-4 py-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-colors">
                                        <span id="file-name" class="text-black text-left">Файл не вибрано</span>
                                    </label>
                                    @if($errors->has('banner_image'))
                                        <p class="text-sm text-red-600">{{ $errors->first('banner_image') }}</p>
                                    @endif
                                    @if($banner->getMedia('banner_images')->isNotEmpty())
                                        <div class="mt-4">
                                            <p class="text-sm font-medium text-gray-700 mb-2">Поточне зображення:</p>
                                            <div class="relative inline-block">
                                                @php
                                                    $imageUrl = $banner->getFirstMediaUrl('banner_images');
                                                    $imageUrl = str_replace('http://110.172.148.57:8000', 'https://serafym.info', $imageUrl);
                                                @endphp
                                                <img class="w-32 h-32" src="{{ $imageUrl }}"
                                                     alt="{{ $banner->title }}">
                                            </div>
                                        </div>
                                    @endif
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
                    Оновити банер
                </button>
            </div>
            </form>


            <!-- Delete Form -->
            <div class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-red-400">
                <div class="px-6 py-4 bg-red-50">
                    <h3 class="text-lg font-medium text-red-800">Небезпечна зона</h3>
                    <p class="text-sm text-red-600 mt-1">Після видалення банер неможливо буде відновити</p>
                </div>
                <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST"
                      onsubmit="return confirm('Ви впевнені, що хочете видалити цей банер?');"
                      class="px-6 py-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Видалити банер
                    </button>
                </form>
            </div>
        </div>
    </div>

@endsection


<script defer>
    document.addEventListener('DOMContentLoaded', function () {
        const fileInput = document.getElementById('banner_image');
        const fileNameSpan = document.getElementById('file-name');

        fileInput.addEventListener('change', function (e) {
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
