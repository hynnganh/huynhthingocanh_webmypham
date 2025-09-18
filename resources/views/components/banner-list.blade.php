<div class="relative w-full h-[500px]">
        <!-- @foreach ($banner_list as $banner)
            <div class="absolute inset-0 transition-all duration-1000 ease-in-out slide-show opacity-0">
                <img src="{{ asset('assets/images/banner/'.$banner->image) }}" alt="{{ $banner->name }}" class="w-full h-full object-cover" />
            </div>
        @endforeach -->

        <div class="absolute inset-0 transition-all duration-1000 ease-in-out slide-show opacity-0">
                <img src="http://localhost/assets/img/banner3.jpg"  class="w-full h-full object-cover" />
            </div>
            <div class="absolute inset-0 transition-all duration-1000 ease-in-out slide-show opacity-0">
                <img src="http://localhost/assets/img/skc.jpg"  class="w-full h-full object-cover" />
            </div>
    <!-- Navigation Arrows -->
    <button id="prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 text-black text-4xl z-10 bg-opacity-50 hover:bg-opacity-80 transition duration-300">
        &#60;
    </button>
    <button id="next" class="absolute top-1/2 right-4 transform -translate-y-1/2 text-black text-4xl z-10 bg-opacity-50 hover:bg-opacity-80 transition duration-300">
        &#62;
    </button>
</div>

<script>
    let currentIndex = 0;
    const slides = document.querySelectorAll('.slide-show');
    const prevButton = document.getElementById('prev');
    const nextButton = document.getElementById('next');

    function changeSlide() {
        // Hide the current slide
        slides[currentIndex].classList.add('opacity-0');
        // Move to the next slide
        currentIndex = (currentIndex + 1) % slides.length;
        // Show the next slide
        slides[currentIndex].classList.remove('opacity-0');
    }

    // Change slide when clicking on the next button
    nextButton.addEventListener('click', function() {
        // Hide the current slide
        slides[currentIndex].classList.add('opacity-0');
        // Move to the next slide
        currentIndex = (currentIndex + 1) % slides.length;
        // Show the next slide
        slides[currentIndex].classList.remove('opacity-0');
    });

    // Change slide when clicking on the previous button
    prevButton.addEventListener('click', function() {
        // Hide the current slide
        slides[currentIndex].classList.add('opacity-0');
        // Move to the previous slide
        currentIndex = (currentIndex - 1 + slides.length) % slides.length;
        // Show the previous slide
        slides[currentIndex].classList.remove('opacity-0');
    });

    // Initial setup to show the first image
    slides[currentIndex].classList.remove('opacity-0');

    // Set interval to change slides every 3 seconds
    setInterval(changeSlide, 5000);
</script>