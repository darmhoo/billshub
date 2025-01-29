<x-filament-panels::page>
    <div class="flex flex-col gap-5 my-3">
        @foreach ($faqs as $faq)
            <hr class="w-full lg:mt-10 md:mt-12 md:mb-8 my-8" />

            <div class="w-full md:px-6">
                <div id="mainHeading" class="flex justify-between items-center w-full">
                    <div class="">
                        <p
                            class="flex justify-center items-center dark:text-white font-medium text-base leading-6 md:leading-4 text-gray-800">
                            <span
                                class="lg:mr-6 mr-4 dark:text-white lg:text-2xl md:text-xl text-lg leading-6 md:leading-5 lg:leading-4 font-semibold text-gray-800">Q{{$faq->id}}</span>
                            {{$faq['question']}}
                        </p>
                    </div>
                    <button aria-label="toggler"
                        class="focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-800" data-menu>
                        <img class="transform dark:hidden "
                            src="https://tuk-cdn.s3.amazonaws.com/can-uploader/faq-8-svg2.svg" alt="toggler">
                        <img class="transform dark:block hidden "
                            src="https://tuk-cdn.s3.amazonaws.com/can-uploader/faq-8-svg2dark.svg" alt="toggler">
                    </button>
                </div>
                <div id="menu" class="hidden mt-6 w-full">
                    <p class="text-base leading-6 text-gray-600 dark:text-gray-300 font-normal">{!! $faq['answer'] !!}</p>
                </div>
            </div>

        @endforeach

    </div>



    <script>
        let elements = document.querySelectorAll("[data-menu]");
        for (let i = 0; i < elements.length; i++) {
            let main = elements[i];

            main.addEventListener("click", function () {
                let element = main.parentElement.parentElement;
                let indicators = main.querySelectorAll("img");
                let child = element.querySelector("#menu");
                let h = element.querySelector("#mainHeading>div>p");

                h.classList.toggle("font-semibold");
                child.classList.toggle("hidden");
                // console.log(indicators[0]);
                indicators[0].classList.toggle("rotate-180");
            });
        }
    </script>

</x-filament-panels::page>