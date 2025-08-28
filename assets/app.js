/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

import './styles/app.scss';
import './script.js';
import './battle.js';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Character image modal (delegated)
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
        if (modalImg) modalImg.src = '';
    }

    // Click on the entire card
    document.addEventListener('click', (e) => {
        const item = e.target.closest('.character-item');
        if (!item) return;
        const imgSrc = item.getAttribute('data-image');
        const imgEl = item.querySelector('.character-avatar');
        const alt = imgEl ? (imgEl.alt || '') : '';
        if (imgSrc) {
            openImageModal(imgSrc, alt);
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

