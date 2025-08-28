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


// Character image modal
window.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('characterImageModal');
    const modalImg = document.getElementById('characterImageModalImg');
    const modalClose = document.getElementById('characterImageModalClose');

    function openImageModal(src, alt = '') {
        if (!modal || !modalImg) return;
        modalImg.src = src;
        modalImg.alt = alt;
        modal.classList.add('active');
        document.body.classList.add('modal-open');
    }

    function closeImageModal() {
        if (!modal) return;
        modal.classList.remove('active');
        document.body.classList.remove('modal-open');
        // clear src to stop large images
        if (modalImg) modalImg.src = '';
    }

    // Delegate click on character avatars
    document.addEventListener('click', (e) => {
        const target = e.target;
        if (target && target.classList && target.classList.contains('character-avatar')) {
            // if it's an <img>
            const src = target.tagName === 'IMG' ? target.src : null;
            const alt = target.alt || '';
            if (src) openImageModal(src, alt);
        }
    });

    if (modalClose) modalClose.addEventListener('click', closeImageModal);
    if (modal) modal.addEventListener('click', (e) => {
        if (e.target === modal) closeImageModal();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeImageModal();
    });
});
