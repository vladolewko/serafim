<div class="w-full bg-blue-400 py-12 lg:py-20 text-white">
    <div class="w-full px-2 sm:px-6">
        <div class="flex flex-col lg:flex-row gap-8">
            <div class="w-full lg:w-5/12 flex gap-3 justify-between">
                <div
                    class="rounded-xl inline-block h-min bg-gradient-to-t from-blue-300 to-white overflow-hidden">
                    <img class=" w-[800px]" src="{{ asset('img/serafim.png') }}" alt="serafim">
                </div>
                <div class="flex flex-col gap-4 justify-between text_desc">
                    <p class="text-2xl sm:text-4xl font-bold about_author">
                        Про <span class="text-yellow-400">автора</span>
                    </p>
                    <div class="my-3 italic text-sm sm:text-2xl w-5/6 war_text">
                    "Війна — це не лише передова. Це ще й боротьба за права, за людську гідність і проти
                    беззаконня,
                    яке, на жаль, існує навіть у формі."
                    </div>
                    <div class="div_name">
                        <p class="text-xs sm:text-lg name">Моренець: <span
                                class="font-bold">Євгеній Борисович</span></p>
                        <p class="text-xs sm:text-lg name">Позивний: <span class="font-bold">Серафим</span></p>
                    </div>
                </div>
            </div>
            <div class="flex flex-col w-full lg:w-5/12 ">

                <div class="flex flex-col font-bold text-base/[1px] sm:text-2xl leading-relaxed gap-4 description">
                    <p>Правозахисник. Доброволець. Військовослужбовець Національної гвардії України.</p>
                    <p>Засновник serafim.info та автор комплектів знань, представлених на цьому сайті.</p>
                    <p>Від початку ініціативи Міністерства внутрішніх справ України «Гвардія наступу» —
                        долучився добровольцем.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media screen and (min-width: 1024px) {
        .text_desc{
            width: 91.6667%;
        }
        .about_author{
            font-size: 3rem;
        }
        .war_text{
            font-size: 2.5rem;
            line-height: 3rem;
        }
        .div_name{;
            display:flex;
            flex-direction: column;
            gap: 1rem;
        }
        .name {
            font-size: 2rem;
        }
        .description{
            margin-top: 2rem;
            font-size: 2rem;
            line-height: 2.5rem;
            gap:3rem
        }
    }
</style>
