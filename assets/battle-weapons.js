// Système de drag & drop pour les armes dans les batailles
class BattleWeaponSystem {
    constructor() {
        this.equippedWeapons = new Map(); // characterId -> weaponData
        this.init();
    }

    init() {
        this.setupDragAndDrop();
        this.setupCharacterHover();
    }

    setupDragAndDrop() {
        // Gérer le drag des armes
        document.querySelectorAll('.weapon-card').forEach(weapon => {
            weapon.addEventListener('dragstart', this.handleDragStart.bind(this));
            weapon.addEventListener('dragend', this.handleDragEnd.bind(this));
        });

        // Gérer le drop sur les personnages
        document.querySelectorAll('.character-target').forEach(character => {
            character.addEventListener('dragover', this.handleDragOver.bind(this));
            character.addEventListener('dragenter', this.handleDragEnter.bind(this));
            character.addEventListener('dragleave', this.handleDragLeave.bind(this));
            character.addEventListener('drop', this.handleDrop.bind(this));
        });
    }

    setupCharacterHover() {
        document.querySelectorAll('.character-target').forEach(character => {
            character.addEventListener('mouseenter', this.showCharacterStats.bind(this));
            character.addEventListener('mouseleave', this.hideCharacterStats.bind(this));
        });
    }

    handleDragStart(e) {
        const weapon = e.target.closest('.weapon-card');
        weapon.classList.add('dragging');
        
        // Stocker les données de l'arme
        e.dataTransfer.setData('weapon-id', weapon.dataset.weaponId);
        e.dataTransfer.setData('weapon-name', weapon.dataset.weaponName);
        e.dataTransfer.setData('weapon-power', weapon.dataset.weaponPower);
        e.dataTransfer.setData('weapon-defense', weapon.dataset.weaponDefense);
        e.dataTransfer.effectAllowed = 'move';
    }

    handleDragEnd(e) {
        e.target.closest('.weapon-card').classList.remove('dragging');
    }

    handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
    }

    handleDragEnter(e) {
        e.preventDefault();
        const character = e.target.closest('.character-target');
        if (character) {
            character.classList.add('drag-over');
        }
    }

    handleDragLeave(e) {
        const character = e.target.closest('.character-target');
        if (character && !character.contains(e.relatedTarget)) {
            character.classList.remove('drag-over');
        }
    }

    handleDrop(e) {
        e.preventDefault();
        const character = e.target.closest('.character-target');
        character.classList.remove('drag-over');

        // Récupérer les données de l'arme
        const weaponData = {
            id: e.dataTransfer.getData('weapon-id'),
            name: e.dataTransfer.getData('weapon-name'),
            power: parseInt(e.dataTransfer.getData('weapon-power')),
            defense: parseInt(e.dataTransfer.getData('weapon-defense'))
        };

        // Récupérer les données du personnage
        const characterData = {
            id: character.dataset.characterId,
            name: character.dataset.characterName,
            power: parseInt(character.dataset.power),
            defense: parseInt(character.dataset.defense),
            hp: parseInt(character.dataset.hp)
        };

        this.equipWeapon(characterData, weaponData, character);
    }

    equipWeapon(character, weapon, characterElement) {
        // Sauvegarder l'équipement
        this.equippedWeapons.set(character.id, weapon);

        // Mettre à jour l'affichage du personnage
        characterElement.classList.add('equipped');
        
        // Ajouter un indicateur d'arme équipée
        this.addWeaponIndicator(characterElement, weapon);

        // Calculer et afficher les nouvelles stats
        const newStats = {
            power: character.power + weapon.power,
            defense: character.defense + weapon.defense,
            hp: character.hp // HP ne change pas
        };

        // Mettre à jour les data attributes pour le hover
        characterElement.dataset.modifiedPower = newStats.power;
        characterElement.dataset.modifiedDefense = newStats.defense;
        characterElement.dataset.equippedWeapon = weapon.name;

        // Notification
        this.showNotification(`${weapon.name} équipé sur ${character.name}!`);

        // Masquer l'arme utilisée
        this.hideUsedWeapon(weapon.id);
    }

    addWeaponIndicator(characterElement, weapon) {
        // Supprimer l'ancien indicateur s'il existe
        const oldIndicator = characterElement.parentElement.querySelector('.weapon-equipped-indicator');
        if (oldIndicator) {
            oldIndicator.remove();
        }

        // Créer le nouvel indicateur
        const indicator = document.createElement('div');
        indicator.className = 'weapon-equipped-indicator';
        indicator.innerHTML = '⚔️';
        indicator.title = `Équipé: ${weapon.name}`;
        
        // Positionner l'indicateur
        const container = characterElement.parentElement;
        container.style.position = 'relative';
        container.appendChild(indicator);
    }

    hideUsedWeapon(weaponId) {
        const weaponCard = document.querySelector(`[data-weapon-id="${weaponId}"]`);
        if (weaponCard) {
            weaponCard.style.opacity = '0.3';
            weaponCard.style.pointerEvents = 'none';
            weaponCard.draggable = false;
        }
    }

    showCharacterStats(e) {
        const character = e.target.closest('.character-target');
        const characterData = {
            name: character.dataset.characterName,
            power: parseInt(character.dataset.power),
            defense: parseInt(character.dataset.defense),
            hp: parseInt(character.dataset.hp)
        };

        let statsHtml = `
            <h6>${characterData.name}</h6>
            <div>❤️ PV: ${characterData.hp}</div>
            <div>⚔️ Puissance: ${characterData.power}</div>
            <div>🛡️ Défense: ${characterData.defense}</div>
        `;

        // Si une arme est équipée, montrer les stats modifiées
        if (character.dataset.modifiedPower) {
            const modifiedPower = parseInt(character.dataset.modifiedPower);
            const modifiedDefense = parseInt(character.dataset.modifiedDefense);
            const weaponName = character.dataset.equippedWeapon;

            statsHtml += `
                <hr>
                <small><strong>Avec ${weaponName}:</strong></small>
                <div>⚔️ Puissance: <span class="stat-change positive">${modifiedPower} (+${modifiedPower - characterData.power})</span></div>
                <div>🛡️ Défense: <span class="stat-change positive">${modifiedDefense} (+${modifiedDefense - characterData.defense})</span></div>
            `;
        }

        this.showStatsComparison(statsHtml);
    }

    hideCharacterStats(e) {
        this.hideStatsComparison();
    }

    showStatsComparison(html) {
        let overlay = document.querySelector('.stats-comparison');
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'stats-comparison';
            document.body.appendChild(overlay);
        }
        overlay.innerHTML = html;
        overlay.style.display = 'block';
    }

    hideStatsComparison() {
        const overlay = document.querySelector('.stats-comparison');
        if (overlay) {
            overlay.style.display = 'none';
        }
    }

    showNotification(message) {
        // Créer une notification temporaire
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            z-index: 1001;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Méthode pour récupérer les équipements actuels (pour le combat)
    getEquippedWeapons() {
        return this.equippedWeapons;
    }
}

// Initialiser le système quand la page est chargée
document.addEventListener('DOMContentLoaded', () => {
    window.battleWeaponSystem = new BattleWeaponSystem();
});


