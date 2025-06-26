@extends('layouts.site')

@section('header')
    <div class="w-full sm:px-6 lg:px-8 sm:w-5/6 mx-auto">

    <!-- Breadcrumb navigation -->
    <div class="flex items-center gap-4 mt-4 sm:mt-8">
        <a class="text-slate-600 hover:text-slate-800 transition-colors text-xl"
           href="{{ route('home') }}">головна</a>
        <p class="text-yellow-400 text-xl sm:text-2xl font-medium">замовлення</p>
    </div>
    </div>
@endsection
@section('content')
            <div class="w-full px-4 sm:px-6 lg:px-8 sm:w-5/6 mx-auto">

                <!-- Main title -->
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold mt-4 mb-6 sm:mb-8">Замовити комплект</h1>

                <!-- Main content grid -->
                <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 lg:justify-between">
                    <!-- Left column - Image and delivery info -->
                    <div class="w-full lg:w-6/12">
                        <!-- Product image placeholder -->
                        <div class="bg-gray-200 w-full h-64 sm:h-80 lg:h-[548px] rounded-lg mb-4 overflow-hidden">
                            @if($product->getMedia('product_images')->isNotEmpty())
                                @php
                                    $imageUrl = $product->getFirstMediaUrl('product_images');
                                    $imageUrl = str_replace('http://110.172.148.57:8000', 'https://serafym.info', $imageUrl);
                                @endphp
                                <img class="w-full h-full" src="{{ $imageUrl }}" alt="{{ $product->title }}">
                            @endif
                        </div>

                        <!-- Delivery and payment info -->
                        <div class="border-2 border-blue-400 rounded-lg p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row lg:flex-col xl:flex-row gap-4 sm:gap-4">
                                <!-- Delivery section -->
                                <div class="">
                                    <div class="flex items-center gap-2 mb-3">
                                        <img src="{{ asset('img/delivery.svg') }}" alt="">
                                        <p class="text-lg sm:text-xl font-medium">доставка</p>
                                    </div>
                                    <ul class="list-disc text-slate-600 ml-6 text-sm sm:text-base space-y-1">
                                        <li>Доставка у відділення нової пошти</li>


                                        {{--<li>Доставка кур'єром нової пошти</li>--}}

                                    </ul>
                                </div>

                                <!-- Vertical divider - hidden on mobile -->
                                <div class="hidden sm:block w-px bg-blue-400"></div>

                                <!-- Payment section -->
                                <div class="">
                                    <div class="flex items-center gap-2 mb-3">
                                        <img src="{{ asset('img/card.svg') }}" alt="">
                                        <p class="text-lg sm:text-xl font-medium">оплата</p>
                                    </div>
                                    <ul class="list-disc text-slate-600 ml-6 text-sm sm:text-base space-y-1">
                                        <li>Накладений платіж</li>
                                        <li>Онлайн оплата на сайті</li>
                                        <li>Безготівковий переказ</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right column - Product details -->
                    <div class="w-full lg:w-5/12">
                        <!-- Product title and availability -->
                        <div class="mb-6">
                            <h2 class="text-2xl sm:text-3xl font-bold mb-2">{{ $product->name }}</h2>
                            <p class="text-green-400 text-sm sm:text-base">в наявності</p>
                        </div>

                        <!-- Product description -->
                        <div class="mb-6">
                            <p class="text-base sm:text-lg lg:text-xl lg:w-[90%] leading-relaxed">
                                {{ $product->description }}
                            </p>
                        </div>

                        <!-- Price -->
                        <div class="mb-6">
                            <p class="text-4xl sm:text-5xl font-bold text-center sm:text-left">
                                <span class="text-yellow-400">{{ $product->price }}</span> грн
                            </p>
                        </div>

                        <!-- Quantity and order button -->
                        <div class="flex flex-col sm:flex-row gap-4 sm:gap-5 mb-8">
                            <!-- Quantity selector -->
                            <div
                                class="border-2 border-yellow-400 rounded-lg px-4 py-2 flex items-center justify-between w-full sm:w-32">
                                <button type="button" id="decreaseBtn"
                                        class="p-1 hover:bg-yellow-50 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 13H5v-2h14v2z"/>
                                    </svg>
                                </button>
                                <span id="quantityDisplay" class="text-xl sm:text-2xl font-medium px-4">1</span>
                                <button type="button" id="increaseBtn"
                                        class="p-1 hover:bg-yellow-50 rounded transition-colors">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/>
                                    </svg>
                                </button>
                            </div>

                            <!-- Order button -->


                            <form class="flex justify-end" id="orderForm" action="{{ route('orders.create') }}"
                                  method="post"
                                  data-product-id="{{ $product->id ?? '' }}">

                                @csrf
                                <button type="submit"
                                        class="bg-yellow-400 hover:bg-yellow-500 px-6 py-3 rounded-lg text-black text-lg sm:text-xl lg:text-2xl font-medium transition-colors flex-1 sm:flex-none">
                                    замовити
                                </button>
                            </form>
                        </div>

                        <!-- Target audience -->
                        <div class="mb-6">
                            <div class="flex items-center gap-2 mb-3">
                                <img src="{{ asset('img/check.svg') }}" alt="">
                                <p class="text-base sm:text-lg font-medium">для кого цей комплект:</p>
                            </div>

                            <div class="flex flex-wrap gap-2 text-sm">

                                @foreach ($product->for_whom as $tag)
                                    <div class="border-2 border-blue-400 rounded-lg py-1 px-3 ">{{ $tag }}</div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Contents -->
                        <div class="mb-8">
                            <div class="flex items-center gap-2 mb-3">
                                <img src="{{ asset('img/check.svg') }}" alt="">
                                <p class="text-base sm:text-lg font-medium">що всередині:</p>
                            </div>

                            <div class="flex flex-wrap gap-2 text-sm">
                                @foreach($product->content as $item)
                                    <div
                                        class="border-2 border-blue-400 rounded-lg py-2 px-3  flex-grow sm:flex-grow-0">
                                        {{$item}}
                                    </div>

                                @endforeach
                            </div>
                        </div>

                        <!-- Specifications -->
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <img src="{{ asset('img/properties.svg') }}" alt="">
                                <p class="text-base sm:text-lg font-medium">характеристики</p>
                            </div>

                            <div class="text-sm text-slate-600 space-y-2">
                                <p>Вага: <span class="text-black font-bold">{{ $product->weight }} кг</span></p>
                                <p>К-сть книг: <span class="text-black font-bold">{{ $product->books_quantity }} шт</span></p>
                                <p>Розміри: <span class="text-black font-bold">{{ $product->dimension }} см</span></p>
                                <p>Призначення: <span class="text-black font-bold">{{ $product->appointment }}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

<div class="lg:hidden py-32" id="knowledge_pack">
    @include('components.chooseknoweledge-mobile')
</div>


<div class="hidden lg:block">
    @include('components.chooseknoweledge')
</div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const decreaseBtn = document.getElementById('decreaseBtn');
            const increaseBtn = document.getElementById('increaseBtn');
            const quantityDisplay = document.getElementById('quantityDisplay');
            const orderForm = document.getElementById('orderForm');
            const productId = orderForm.getAttribute('data-product-id');

            let quantity = 1;
            const minQuantity = 1;
            const maxQuantity = 999;

            function updateQuantity(newQuantity) {
                quantity = Math.max(minQuantity, Math.min(maxQuantity, newQuantity));
                quantityDisplay.textContent = quantity;

                // Оновлюємо стан кнопок
                decreaseBtn.disabled = quantity <= minQuantity;
                increaseBtn.disabled = quantity >= maxQuantity;

                // Додаємо візуальні стилі для заблокованих кнопок
                if (quantity <= minQuantity) {
                    decreaseBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    decreaseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }

                if (quantity >= maxQuantity) {
                    increaseBtn.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    increaseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }

            decreaseBtn.addEventListener('click', function () {
                if (quantity > minQuantity) {
                    updateQuantity(quantity - 1);
                }
            });

            increaseBtn.addEventListener('click', function () {
                if (quantity < maxQuantity) {
                    updateQuantity(quantity + 1);
                }
            });

            // Обробляємо відправку форми
            orderForm.addEventListener('submit', function (e) {
                // Створюємо тимчасові приховані поля
                const quantityInput = document.createElement('input');
                quantityInput.type = 'hidden';
                quantityInput.name = 'quantity';
                quantityInput.value = quantity;

                const productIdInput = document.createElement('input');
                productIdInput.type = 'hidden';
                productIdInput.name = 'productId';
                productIdInput.value = productId;

                // Додаємо поля до форми
                this.appendChild(quantityInput);
                this.appendChild(productIdInput);
            });

            // Ініціалізуємо стан кнопок
            updateQuantity(quantity);
        });
    </script>
@endsection
@section('footer')
    @include('components.footer')

@endsection
