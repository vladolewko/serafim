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

            <!-- Knowledge Pack Selection Mobile -->
            <!-- <div class="lg:hidden w-full px-4 sm:px-6 lg:w-4/6 lg:mx-auto my-16 lg:my-72" id="knowledge_pack">
                <div class="flex flex-col lg:flex-row items-start lg:items-center gap-4 mb-8">
                    <div>
                        <p class="text-2xl sm:text-3xl lg:text-4xl font-bold">
                            Обери свій <span class="text-yellow-400"> комплект знань!</span>
                        </p>
                        <p class="text-lg lg:text-xl text-slate-600 mt-2">
                            Замовляй свій набір та будь захищеним знанями...
                        </p>
                    </div>
                    <div class="hidden lg:block ml-6">
                        <div class="w-8 h-8 bg-yellow-400 rounded transform rotate-90"></div>
                    </div>
                </div> -->
                <!-- @if($products->isNotEmpty()) -->
                    <!-- Knowledge Pack Cards -->
                    <!-- <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                        @foreach($products as $product)
                            <div class="rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-0.5">
                                <div class="bg-white flex flex-col items-center rounded-xl p-4">
                                    <div class="h-32 w-32 lg:h-48 lg:w-48 rounded-xl bg-gray-200 mb-4 overflow-hidden">
                                        @if($product->getMedia('product_images')->isNotEmpty())
                                            @php
                                                $imageUrl = $product->getFirstMediaUrl('product_images');
                                                $imageUrl = str_replace('http://110.172.148.57:8000', 'https://serafym.info', $imageUrl);
                                            @endphp
                                            <img class="w-full h-full" src="{{ $imageUrl }}" alt="{{ $product->title }}">
                                        @endif
                                    </div>
                                    <p class="text-lg lg:text-2xl font-bold text-center mb-3">{{ $product->name }}</p>
                                    <p class="text-2xl lg:text-4xl text-center mb-4 font-bold">{{ $product->price }}
                                        грн</p>
                                    <button
                                        class="bg-yellow-400 px-6 py-2 lg:px-8 lg:py-3 rounded-lg text-black text-lg lg:text-xl font-semibold">
                                        Переглянути
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div> -->

            <!-- Knowledge Pack Selection Desktop -->
            <!-- @if($productsChunks->isNotEmpty())
                <div class="hidden lg:block mx-auto my-72 section lg:w-5/6 xl:w-4/6" id="knowledge_pack">
                    <div class="flex">
                        <div>
                            <p class="text-4xl font-bold">Обери свій <span
                                    class="text-yellow-400"> комплект знань!</span></p>
                            <p class="text-xl text-slate-600">Замовляй свій набір та будь захищеним знанями...</p>
                        </div>
                        <img class="mt-3 mx-10" src="{{ asset('img/arrow_90.svg') }}" alt="arrow_90">
                    </div> -->
                    <!-- Carousel knowledge pack -->
                    <!-- <div id="indicators-carousel" class="relative w-full mb-20" data-carousel="static"> -->
                        <!-- Carousel wrapper -->

                        <!-- <div class="relative h-56 overflow-hidden rounded-lg md:h-[500px] w-full"> -->
                            <!-- Item 1 -->
                            <!-- @foreach ($productsChunks as $perPage)

                                <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                                    <div class="flex lg:gap-10 xl:gap-20 mt-10 justify-center ">
                                        @foreach($perPage as $product)
                                            <div
                                                class="lg:w-3/12 xl:w-1/5 h-full rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px] ">

                                                <div class="bg-white flex flex-col items-center rounded-xl"> -->
                                                    <!-- posible img -->
                                                    <!-- <div
                                                        class="h-[212px] w-[212px] rounded-xl bg-gray-200 m-3 slef-center overflow-hidden">
                                                        @if($product->getMedia('product_images')->isNotEmpty())
                                                            @php
                                                                $imageUrl = $product->getFirstMediaUrl('product_images');
                                                                $imageUrl = str_replace('http://110.172.148.57:8000', 'https://serafym.info', $imageUrl);
                                                            @endphp
                                                            <img class="w-full h-full" src="{{ $imageUrl }}" alt="{{ $product->title }}">
                                                        @endif
                                                    </div>

                                                    <p class="text-2xl/6 font-bold text-center">{{$product->name}}</p>
                                                    <p class="text-4xl text-center my-4">{{$product->price}} грн</p>
                                                    <a class="flex items-center bg-yellow-400 px-8 m-3 rounded-lg inline-block align-middle h-10 text-black text-xl hover:bg-yellow-500 transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                                       href="{{ route('product.show', $product->id) }}">перейти</a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div> -->
                        <!-- Slider indicators -->
                        <!-- <div
                            class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-0 w-full justify-center">
                            @foreach ($productsChunks as $index => $perPage)
                                <button
                                    type="button"
                                    class="h-[1px] rounded-full slide_button {{ $index === 0 ? 'bg-gray-800' : 'bg-gray-400' }}"
                                    style="width: {{ 90 / count($productsChunks) }}%"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                    aria-label="Slide {{ $index + 1 }}"
                                    data-carousel-slide-to="{{ $index }}"
                                ></button>
                            @endforeach

                        </div> -->
                        <!-- Slider controls -->
                        <!-- <button type="button"
                                class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                data-carousel-prev>
                            <span class="inline-flex items-center justify-center">
                                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180"
                                     src="{{ asset('img/button_carousel_left.svg') }}" alt="button_carousel_left">
                                <span class="sr-only">Previous</span>
                            </span>
                        </button>
                        <button type="button"
                                class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none"
                                data-carousel-next>
                            <span class="inline-flex items-center justify-center group-focus:outline-none">
                                <img class="w-8 h-8 text-white dark:text-gray-800 rtl:rotate-180"
                                     src="{{ asset('img/button_carousel_right.svg') }}" alt="button_carousel_right">
                                <span class="sr-only">Next</span>
                            </span>
                        </button>
                    </div>
                </div>
            @endif -->




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
                productIdInput.value = '{{ $product->id }}';

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
