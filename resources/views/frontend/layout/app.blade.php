<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forma Template - Tailwind</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        dark: '#020C19',
                    },
                    animation: {
                        'float': 'float 3s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': {transform: 'translateY(0)'},
                            '50%': {transform: 'translateY(-10px)'},
                        },
                    },
                }
            }
        }
    </script>

    <style>
        .scroll-reveal {
            opacity: 0;
            transform: translateY(30px);
            transition: all 2s ease-out;
        }

        .scroll-reveal.active {
            opacity: 1;
            transform: translateY(0);
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #testimonial-wrapper {
            transition: all 0.5s ease-in-out;
        }

        #team-slider {
            display: flex;
            transition: transform 0.7s ease-in-out;
        }
    </style>
</head>
<body class="bg-[#020C19] text-white font-sans">

@include("frontend.partials.header")

<!-- Home -->
@yield('content')











<!-- Footer -->
@include("frontend.partials.footer")


<div id="lightbox" class="fixed inset-0 z-50 hidden bg-black/90 flex items-center justify-center p-4">
    <button onclick="closeLightbox()" class="absolute top-8 right-8 text-white text-4xl">&times;</button>
    <img id="lightbox-img" src="" class="max-w-full max-h-full object-contain rounded-lg">
</div>
<script>
    window.addEventListener('scroll', function () {
        let navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {

            navbar.classList.add('shadow-lg');
        } else {

            navbar.classList.remove('shadow-lg');
        }
    });

    // toggle button //
    let menuBtn = document.getElementById('mobile-menu-btn');
    let closeBtn = document.getElementById('close-menu-btn');
    let mobileMenu = document.getElementById('mobile-menu');

    menuBtn.addEventListener('click', () => {
        mobileMenu.classList.remove('hidden');
    });

    closeBtn.addEventListener('click', () => {
        mobileMenu.classList.add('hidden');
    });


    let observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
            }
        });
    }, {threshold: 0.1});

    document.querySelectorAll('.scroll-reveal').forEach((el) => {
        observer.observe(el);
    });


    const countUpObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = parseInt(counter.getAttribute('data-target'));
                const duration = 2000;
                const stepTime = Math.max(Math.floor(duration / target), 15);
                let startTime = null;

                function animate(currentTime) {
                    if (!startTime) startTime = currentTime;
                    const progress = currentTime - startTime;
                    const progressPercentage = Math.min(progress / duration, 1);

                    const currentCount = Math.floor(progressPercentage * target);
                    counter.innerText = currentCount;

                    if (progressPercentage < 1) {
                        requestAnimationFrame(animate);
                    } else {
                        counter.innerText = target;
                    }
                }

                requestAnimationFrame(animate);
                countUpObserver.unobserve(counter);
            }
        });
    }, {threshold: 0.5});

    document.querySelectorAll('.count-up').forEach((el) => {
        countUpObserver.observe(el);
    });


    document.addEventListener("DOMContentLoaded", function () {
        const buttons = document.querySelectorAll(".filter-btn");
        const items = document.querySelectorAll(".portfolio-item");

        buttons.forEach(button => {
            button.addEventListener("click", () => {

                buttons.forEach(btn => {
                    btn.classList.remove("bg-[#208BF4]", "font-semibold");
                    btn.classList.add("bg-[#0A1625]");
                });
                button.classList.add("bg-[#208BF4]", "font-semibold");
                button.classList.remove("bg-[#0A1625]");

                const filterValue = button.getAttribute("data-filter");

                items.forEach(item => {
                    if (filterValue === "all" || item.getAttribute("data-category") === filterValue) {

                        item.classList.remove("hidden");
                        setTimeout(() => {
                            item.style.opacity = "1";
                            item.style.transform = "scale(1)";
                        }, 10);
                    } else {
                        // Card ko hide karo
                        item.style.opacity = "0";
                        item.style.transform = "scale(0.95)";
                        setTimeout(() => {
                            item.classList.add("hidden");
                        }, 300);
                    }
                });
            });
        });
    });

    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');

    document.querySelectorAll('[data-img]').forEach(icon => {
        icon.addEventListener('click', (e) => {
            const imgSrc = e.currentTarget.getAttribute('data-img');
            lightboxImg.src = imgSrc;
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
        });
    });

    function closeLightbox() {
        lightbox.classList.add('hidden');
        lightbox.classList.remove('flex');
    }


    // Testimonials //
    const testimonials = [
        {
            name: "Matt Brandon",
            role: "Freelancer",
            title: "Labore nostrum eos impedit",
            text: "The team is incredible. The team is incredible. The team is incredible. The team is incredible. The team is incredible. The team is incredible.",
            img: "{{ asset('images/matt-brandon.webp') }}"
        },
        {
            name: "Sarah Willson",
            role: "Designer",
            title: "Nemo enim ipsam voluptatem",
            text: "Working with this company was one of the best decisions we made. Everything was delivered on time and exceeded expectations.",
            img: "{{ asset('images/sara-wilsone.webp') }}"
        },
        {
            name: "Jena Karlis",
            role: "Store Owner",
            title: "Impedit dolor facilis nulla",
            text: "Professional team, excellent communication, and amazing quality. Highly recommended for anyone looking for reliable service.",
            img: "{{ asset('images/jena-karlis.webp') }}"
        },
        {
            name: "Saul Goodman",
            role: "Client",
            title: "Sed ut perspiciatis unde omnis",
            text: "Outstanding experience from start to finish. They truly understand customer needs and deliver exceptional work.",
            img: "{{ asset('images/saul-goodman.webp') }}"
        }
    ];

    let currentIndex = 0;
    let autoSlide;

    const wrapper = document.getElementById("testimonial-wrapper");

    function updateTestimonial(index, direction = "next") {

        wrapper.style.opacity = "0";
        wrapper.style.transform =
            direction === "next"
                ? "translateX(-40px)"
                : "translateX(40px)";

        setTimeout(() => {

            const t = testimonials[index];

            document.getElementById("testimonial-title").textContent = t.title;
            document.getElementById("testimonial-text").textContent = t.text;
            document.getElementById("testimonial-name").textContent = t.name;
            document.getElementById("testimonial-role").textContent = t.role;

            document.getElementById("testimonial-img-small").src = t.img;
            document.getElementById("testimonial-img-large").src = t.img;

            wrapper.style.opacity = "1";
            wrapper.style.transform = "translateX(0)";

        }, 300);
    }

    function startAutoSlide() {

        clearInterval(autoSlide);

        autoSlide = setInterval(() => {

            currentIndex++;

            if (currentIndex >= testimonials.length) {
                currentIndex = 0;
            }

            updateTestimonial(currentIndex, "next");

        }, 5000);
    }

    function nextSlide() {

        currentIndex++;

        if (currentIndex >= testimonials.length) {
            currentIndex = 0;
        }

        updateTestimonial(currentIndex, "next");

        startAutoSlide();
    }

    function prevSlide() {

        currentIndex--;

        if (currentIndex < 0) {
            currentIndex = testimonials.length - 1;
        }

        updateTestimonial(currentIndex, "prev");

        startAutoSlide();
    }

    document.addEventListener("DOMContentLoaded", () => {

        updateTestimonial(currentIndex);

        startAutoSlide();

    });


    // Team //
    const slider = document.getElementById('team-slider');

    let autoPlay = setInterval(moveNext, 5000);

    function moveNext() {
        slider.style.transition = "transform 0.7s ease-in-out";
        slider.style.transform = "translateX(-25%)";

        setTimeout(() => {
            slider.style.transition = "none";
            slider.appendChild(slider.firstElementChild);
            slider.style.transform = "translateX(0)";
        }, 700);

        resetInterval();
    }

    function movePrev() {
        slider.insertBefore(slider.lastElementChild, slider.firstElementChild);
        slider.style.transition = "none";
        slider.style.transform = "translateX(-25%)";

        setTimeout(() => {
            slider.style.transition = "transform 0.7s ease-in-out";
            slider.style.transform = "translateX(0)";
        }, 50);

        resetInterval();
    }

    function resetInterval() {
        clearInterval(autoPlay);
        autoPlay = setInterval(moveNext, 5000);
    }


    // Back to Top //

    const backToTopButton = document.getElementById("backToTop");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            backToTopButton.classList.remove("opacity-0", "invisible");
            backToTopButton.classList.add("opacity-100", "visible");
        } else {
            backToTopButton.classList.add("opacity-0", "invisible");
            backToTopButton.classList.remove("opacity-100", "visible");
        }
    });

    backToTopButton.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });
</script>
</body>
</html>
