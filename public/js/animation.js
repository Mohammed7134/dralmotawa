// Intersection Observer options
const options = {
    root: null, // Use the viewport as the root
    rootMargin: '0px',
    threshold: 0.05, // 20% of the element is visible
};

// Function to handle the intersection
function handleIntersect(entries, observer) {
    entries.forEach((entry) => {
        if (entry.isIntersecting) {
            entry.target.classList.add('animate-on-scroll');
            observer.unobserve(entry.target); // Stop observing once animation is triggered
        }
    });
}

// Initialize the Intersection Observer
const observer = new IntersectionObserver(handleIntersect, options);

// Get all list items with the "animate-on-scroll" class

const applyAnimation = function () {
    const animateOnScrollItems = document.querySelectorAll('.item');

    // Start observing each list item
    const applyAnimation = animateOnScrollItems.forEach((item) => {
        observer.observe(item);
    });
}

applyAnimation();