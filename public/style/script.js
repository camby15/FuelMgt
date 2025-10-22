const navbar = document.getElementById('navbar');
const headerHeight = window.innerHeight;

window.addEventListener('scroll', () => {
    if (window.scrollY > headerHeight) {
        navbar.classList.add('scrolled');
    } else {
        navbar.classList.remove('scrolled');
    }
});

const slides = document.querySelectorAll('.slide');
const navButtons = document.querySelectorAll('.nav-btn');
let currentIndex = 0;

function goToSlide(index) {
    // Update slider position
    const slider = document.querySelector('.slider');
    slider.style.transform = `translateX(-${index * 100}%)`;

    // Update active classes
    slides.forEach((slide) => slide.classList.remove('active'));
    navButtons.forEach((btn) => btn.classList.remove('active'));
    slides[index].classList.add('active');
    navButtons[index].classList.add('active');
}

// Add click events to navigation buttons
navButtons.forEach((btn, index) => {
    btn.addEventListener('click', () => {
        currentIndex = index;
        goToSlide(currentIndex);
    });
});

// Auto-slide functionality (optional)
setInterval(() => {
    currentIndex = (currentIndex + 1) % slides.length;
    goToSlide(currentIndex);
}, 5000); // Change slide every 5 seconds

// testimony
const testimonials = document.querySelectorAll('.testimonial');
const prevButton = document.getElementById('prev');
const nextButton = document.getElementById('next');

let newIndex = 0;

function showTestimonial(index) {
    testimonials.forEach((testimonial, i) => {
        testimonial.style.transform = `translateX(${(i - index) * 100}%)`;
    });
}

prevButton.addEventListener('click', () => {
    newIndex = (newIndex - 1 + testimonials.length) % testimonials.length;
    showTestimonial(newIndex);
});

nextButton.addEventListener('click', () => {
    newIndex = (newIndex + 1) % testimonials.length;
    showTestimonial(newIndex);
});

// Initialize
showTestimonial(newIndex);
