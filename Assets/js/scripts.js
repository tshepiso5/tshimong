const bottomNav = document.querySelector('.bottom-nav');
let lastScrollY = window.scrollY;

window.addEventListener('scroll', () => {
    const currentScrollY = window.scrollY;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    // 1. Hide if scrolling down OR if we've hit the bottom of the page
    if (currentScrollY > lastScrollY || (currentScrollY + windowHeight) >= documentHeight) {
        bottomNav.classList.add('nav-hidden');
    } 
    // 2. Show if scrolling up
    else {
        bottomNav.classList.remove('nav-hidden');
    }

    // Update the last scroll position
    lastScrollY = currentScrollY;
});