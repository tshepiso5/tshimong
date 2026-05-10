
<nav class="bottom-nav sticky-bottom bg-body-tertiary">
    <a href="news.php" class="nav-item">
        <i class="fa-solid fa-feed"></i>
        <span>News</span>
    </a>
    <a href="office.php" class="nav-item">
        <i class="fa-solid fa-building"></i>
        <span>Office</span>
    </a>
    <a href="wallet.php" class="nav-item">
        <i class="fa-solid fa-wallet"></i>
        <span>Wallet</span>
    </a>
    <a href="work.php" class="nav-item">
        <i class="fa-solid fa-briefcase"></i>
        <span>Work</span>
    </a>
</nav>

<style>
    /* 1. Override Bootstrap's sticky-bottom if necessary and set transition */
    .bottom-nav {
        position: fixed !important; /* Force fixed to ensure it stays at bottom */
        bottom: 0;
        left: 0;
        right: 0;
        transition: transform 0.3s ease-in-out;
        z-index: 1050; /* Higher than most elements */
    }

    /* 2. The class that hides the bar */
    .nav-hidden {
        transform: translateY(200%);
    }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const bottomNav = document.querySelector('.bottom-nav');
    let lastScrollY = window.scrollY;

    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;

        // Tolerance: prevent hide/show on tiny 5px movements
        if (Math.abs(currentScrollY - lastScrollY) < 5) return;

        // Hide logic: scrolling down OR reached bottom
        if (currentScrollY > lastScrollY || (currentScrollY + windowHeight) >= documentHeight - 10) {
            bottomNav.classList.add('nav-hidden');
        } 
        // Show logic: scrolling up
        else {
            bottomNav.classList.remove('nav-hidden');
        }

        lastScrollY = currentScrollY;
    });
});
</script>