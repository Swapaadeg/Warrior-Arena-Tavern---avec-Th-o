// ==========================================================================
// SCRIPTS - WARRIOR ARENA TAVERNE
// ==========================================================================

// Fichier vidé - plus de menu burger

console.log('� Scripts chargés sans menu burger');

// Burger menu toggle
const burger = document.querySelector(".burger");
const navLinks = document.querySelector(".nav-links");

burger.addEventListener("click", () => {
    navLinks.classList.toggle("open");

    // Animation simple du burger → croix
    burger.classList.toggle("active");
});
