
document.addEventListener('DOMContentLoaded', function() {
    toggle();
});

function toggle() {
    // Select the element with the class 'navbar-toggler'
    var toggler = document.getElementsByClassName('navbar-toggler')[0];
    
    // Check if the element exists before attempting to click
    if (toggler) {
        toggler.click();
    }
}


    // Carousel functionality
    const carousel = document.getElementById('hero-carousel');
    const indicators = document.querySelectorAll('#carousel-indicators span');
    const slides = document.querySelectorAll('.hero-slide');
    const prev = document.getElementById('prev');
    const next = document.getElementById('next');
    let currentSlide = 0;

    function updateCarousel() {
        carousel.style.transform = `translateX(-${currentSlide * 100}%)`;
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentSlide);
        });
    }

    next.addEventListener('click', () => {
        currentSlide = (currentSlide + 1) % slides.length;
        updateCarousel();
    });

    prev.addEventListener('click', () => {
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        updateCarousel();
    });

    indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
            currentSlide = index;
            updateCarousel();
        });
    });


    const hamburger = document.querySelector('.hamburger');
const navLinks = document.querySelector('.nav-links');

hamburger.addEventListener('click', () => {
navLinks.classList.toggle('show');
hamburger.classList.toggle('active');
});



// Detect when the feature boxes come into view and add animation class
window.addEventListener('scroll', function() {
const elements = document.querySelectorAll('.animated-box');
const scrollTop = window.scrollY + window.innerHeight;

elements.forEach(function(element) {
    if (element.offsetTop < scrollTop) {
        element.classList.add('visible');
    }
});
});




// for the buttons in registration section


function registerAsGigWorker(){
   
    setTimeout(() => {
        document.getElementById("gig").click()
    }, 1000);
}
function registerAsIndustry(){
   
 
        document.getElementById("industry").click()
   
}
function openLoginModal(){
   
 
        document.getElementById("gigloginaction").click()
   
}