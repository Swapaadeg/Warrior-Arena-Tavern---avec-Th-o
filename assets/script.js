// ==========================================================================
// WARRIOR ARENA TAVERNE - Menu Burger JavaScript
// ==========================================================================

document.addEventListener('DOMContentLoaded', function() {
    
    // Variables pour le menu burger
    const burger = document.querySelector('.burger');
    const navLinks = document.querySelector('.nav-links');
    const authNav = document.querySelector('.auth-nav');
    const body = document.body;
    
    // Debug - vérifier que les éléments sont trouvés
    console.log('🔍 Debug burger:', burger);
    console.log('🔍 Debug navLinks:', navLinks);
    console.log('🔍 Debug authNav:', authNav);
    
    // Variables pour l'état du menu
    let isMenuOpen = false;
    
    // Fonction pour ouvrir le menu
    function openMenu() {
        isMenuOpen = true;
        
        if (navLinks) navLinks.classList.add('active');
        if (authNav) authNav.classList.add('active');
        if (burger) burger.classList.add('active');
        
        body.style.overflow = 'hidden'; // Empêche le scroll
        
        console.log('✅ Menu ouvert');
    }
    
    // Fonction pour fermer le menu
    function closeMenu() {
        isMenuOpen = false;
        
        if (navLinks) navLinks.classList.remove('active');
        if (authNav) authNav.classList.remove('active');
        if (burger) burger.classList.remove('active');
        
        body.style.overflow = ''; // Restaure le scroll
        
        console.log('❌ Menu fermé');
    }
    
    // Event listener pour le bouton burger
    if (burger) {
        burger.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Clic sur burger, état actuel:', isMenuOpen);
            
            if (isMenuOpen) {
                closeMenu();
            } else {
                openMenu();
            }
        });
    }
    
    // Fermer le menu si on clique en dehors
    document.addEventListener('click', function(e) {
        if (isMenuOpen && navLinks && authNav && burger &&
            !navLinks.contains(e.target) && 
            !authNav.contains(e.target) && 
            !burger.contains(e.target)) {
            closeMenu();
        }
    });
    
    // Fermer le menu avec la touche Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && isMenuOpen) {
            closeMenu();
        }
    });
    
    // Fermer le menu quand on clique sur un lien
    if (navLinks && authNav) {
        const menuLinks = document.querySelectorAll('.nav-links a, .auth-nav a');
        menuLinks.forEach(link => {
            link.addEventListener('click', function() {
                setTimeout(closeMenu, 100);
            });
        });
    }
    
    // Gestion du resize de fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768 && isMenuOpen) {
            closeMenu();
        }
    });
    
    console.log('🏰 Menu burger initialized');
    
    // Test final - afficher l'état de tous les éléments
    if (!burger) console.error('❌ Burger button not found');
    if (!navLinks) console.error('❌ Nav links not found'); 
    if (!authNav) console.error('❌ Auth nav not found');
    if (burger && navLinks && authNav) console.log('✅ All elements found, burger should work');
});