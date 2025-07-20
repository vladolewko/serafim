<div class="lg:w-5/6 xl:w-4/6 mx-auto section">
    <div class="flex">
        <div class="text-center mx-auto">
            <p class="text-2xl font-bold">Обери свій <span class="text-yellow-400"> курс відеоуроків</span>
            </p>
            <p class="text-xs font-bold text-slate-500 w-5/6 mx-auto">Замовляй свій курс та будь захищеним знаннями...</p>
        </div>
        <!-- <img class="mt-3 mx-10" src="{{ asset('img/arrow_90.svg') }}" alt="arrow_90"> -->
    </div>
    <!-- Carousel knowledge pack -->

    <div id="indicators-carousel" class="relative w-full mb-20" data-carousel="static">
        <!-- Carousel wrapper -->

        <div class="relative overflow-hidden rounded-lg h-[550px] w-full">

            <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                    <div class="flex justify-center mt-10">

                        <div class="w-8/12 md:w-4/12 lg:w-3/12 xl:w-1/5 h-full rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px]">
                            <div class="relative bg-white flex flex-col items-center rounded-xl">
                                    <div class="absolute right-0 bg-gray-400 rounded-full">
                                        <img class="rounded-full w-[75px] h-[75px]"
                                             src="{{ asset('img/sticker.png') }}" alt="">
                                    </div>

                                <!-- posible img -->
                                <div class="relative h-[212px] w-[212px] rounded-xl bg-gray-200 m-3 slef-center overflow-hidden">
                                    <img class="absolute  w-[212px] mx-auto" src="{{ asset('img/upakovka-online-kurs.jpg') }}" alt="">
                                </div>

                                <p class="text-xl/6 font-semibold text-center w-5/6">Комплект літератури для юридичного та правового виховання військовослужбовця "9В1"</p>
                                <p class="text-3xl font-bold text-center my-4" style="font-weight: 700">355 грн</p>
                                <a class="flex items-center justify-center bg-yellow-400 w-11/12 text-center m-2 rounded-lg inline-block align-middle h-10 text-black font-bold text-xl hover:bg-yellow-500 transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                     href="https://nadiya.serafym.info">переглянути</a>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                <div class="flex justify-center mt-10">
                    <div class="w-8/12 md:w-4/12 lg:w-3/12 xl:w-1/5 h-full rounded-xl bg-gray-200 p-[2px]">
                        <div class="bg-white flex flex-col items-center rounded-xl">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 m-3 slef-center overflow-hidden">
                            </div>

                            <p class="text-xl/6 font-semibold text-center w-4/6">В РОЗРОБЦІ</p>
                            <p class="text-3xl font-bold text-center my-4" style="font-weight: 700">0 грн</p>
                            <a class="flex items-center justify-center bg-gray-400 w-11/12 text-center m-2 rounded-lg inline-block align-middle h-10 text-black font-bold text-xl shadow-md cursor-default"
                            >переглянути</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                <div class="flex justify-center mt-10">
                    <div class="w-8/12 md:w-4/12 lg:w-3/12 xl:w-1/5 h-full rounded-xl bg-gray-200 p-[2px]">
                        <div class="bg-white flex flex-col items-center rounded-xl">
                            <!-- posible img -->
                            <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 m-3 slef-center overflow-hidden">
                            </div>

                            <p class="text-xl/6 font-semibold text-center w-4/6">В РОЗРОБЦІ</p>
                            <p class="text-3xl font-bold text-center my-4" style="font-weight: 700">0 грн</p>
                            <a class="flex items-center justify-center bg-gray-400 w-11/12 text-center m-2 rounded-lg inline-block align-middle h-10 text-black font-bold text-xl shadow-md cursor-default"
                            >переглянути</a>
                        </div>
                    </div>
                </div>
            </div>

            @foreach ($banners as $index => $banner)
                <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                    <div class="flex justify-center mt-10">
                        <div class="w-8/12 md:w-4/12 lg:w-3/12 xl:w-1/5 h-full rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px]">
                            <div class="relative bg-white flex flex-col items-center rounded-xl">
                                @if($banner->price == 0)
                                    <div class="absolute right-0 bg-gray-400 rounded-full">
                                        <img class="rounded-full w-[75px] h-[75px]"
                                             src="{{ asset('img/sticker.png') }}" alt="">
                                    </div>
                                @endif
                                <!-- posible img -->
                                <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 m-3 slef-center overflow-hidden">
                                    @if($banner->getMedia('banner_images')->isNotEmpty())
                                        <img class="w-full h-full" src="{{ $banner->getFirstMediaUrl('banner_images') }}"
                                             alt="{{ $banner->title }}">
                                    @endif
                                </div>

                                <p class="text-xl/6 font-semibold text-center w-4/6">{{$banner->title}}</p>
                                <p class="text-3xl font-bold text-center my-4" style="font-weight: 700">{{$banner->price}} грн</p>
                                <a class="flex items-center justify-center bg-yellow-400 w-11/12 text-center m-2 rounded-lg inline-block align-middle h-10 text-black font-bold text-xl hover:bg-yellow-500 transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                   href="{{ $banner->reference }}">переглянути</a>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach


            <!-- Створюємо слайд для кожного продукту окремо -->
            @foreach ($products as $index => $product)
                <div class="hidden duration-500 ease-in-out bg-white" data-carousel-item>
                    <div class="flex justify-center mt-10">
                        <div class="w-8/12 md:w-4/12 lg:w-3/12 xl:w-1/5 h-full rounded-xl bg-gradient-to-t from-yellow-400 to-blue-500 p-[2px]">
                            <div class="bg-white flex flex-col items-center rounded-xl">
                                <!-- posible img -->

                                <div class="h-[212px] w-[212px] rounded-xl bg-gray-200 m-3 slef-center overflow-hidden">
                                    @if($product->getMedia('product_images')->isNotEmpty())
                                        <img class="w-full h-full" src="{{ $product->getFirstMediaUrl('product_images') }}"
                                                alt="{{ $product->title }}">
                                    @endif

                                </div>

                                <p class="text-base/6 font-semibold text-center w-4/6">{{$product->name}}</p>
                                <p class="text-3xl font-bold text-center my-4" style="font-weight: 700">{{$product->price}} грн</p>
                                <a class="flex items-center justify-center bg-yellow-400 w-11/12 text-center m-2 rounded-lg inline-block align-middle h-10 text-black font-bold text-xl hover:bg-yellow-500 transition duration-300 transform hover:scale-105 shadow-md hover:shadow-lg"
                                    href="{{ route('product.show', $product->id) }}">переглянути</a>
                            </div>
                        </div>
                    </div>
                </div>

            @endforeach









        </div>

        <!-- Slider indicators -->
        <div class="absolute z-30 flex -translate-x-1/2 space-x-0 rtl:space-x-reverse left-1/2 bottom-0 w-full justify-center">
            @foreach ($products as $index => $product)
                <button
                    type="button"
                    class="h-[1px] rounded-full slide_button {{ $index === 0 ? 'bg-gray-800' : 'bg-gray-400' }}"
                    style="width: {{ 90 / count($products) }}%"
                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                    aria-label="Slide {{ $index + 1 }}"
                    data-carousel-slide-to="{{ $index }}"
                ></button>
            @endforeach
        </div>

        <!-- Slider controls -->
        <button type="button"
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
