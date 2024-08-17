"use sctict";

window.addEventListener('DOMContentLoaded', function(e){
    // Navbar
    window.addEventListener('scroll', function(e){
        if(window.scrollY > 50) {
            document.querySelector('.navbar').classList.add('sticky-navbar');
            document.querySelector('.navbar').classList.remove('bg-transparent');
        } else {
            document.querySelector('.navbar').classList.remove('sticky-navbar');
            document.querySelector('.navbar').classList.add('bg-transparent');
        }
    });
    
});

// Select all elements with the data attribute "data-observe"
const targets = document.querySelectorAll('[data-aos]');

// Create an IntersectionObserver instance
const observer = new IntersectionObserver((entries, observer) => {
    // Loop through the entry list
    entries.forEach(entry => {
        // Check if the target element intersects with the viewport
        if (entry.isIntersecting) {
            // If it does, log a message and unobserve the target element
            console.log(entry.target.classList.add('aos-animate'));
            observer.unobserve(entry.target);
        }
    });
});

// Start observing the target elements
targets.forEach(target => observer.observe(target));

window.addEventListener('click', function(e){
    if(!e.target.classList.contains('navbar-collapse')) {
        document.querySelector('.navbar-collapse').classList.remove('show');
    }
})
