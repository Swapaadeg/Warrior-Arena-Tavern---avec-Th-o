// ==========================================================================
// BATTLE ANIMATION SCRIPT - WARRIOR ARENA TAVERN
// ==========================================================================

// Global variables for battle animation
let playTimeouts = [];
let isPaused = false;
let currentFrameIdx = 0;
let currentActionIdx = 0;
let currentFrame = null;
let currentActions = [];
let currentEvents = [];
let processNextActionRef = null;
let setup = null;
let frames = null;
let unitMap = null;
let unitOwners = null;
let unitMaxHp = {};

// Initialize battle data from DOM
function initBattleData() {
    const setupElement = document.getElementById('battle-setup-data');
    const framesElement = document.getElementById('battle-frames-data');
    const unitMapElement = document.getElementById('battle-unit-map-data');
    const unitOwnersElement = document.getElementById('battle-unit-owners-data');

    if (setupElement) setup = JSON.parse(setupElement.textContent);
    if (framesElement) frames = JSON.parse(framesElement.textContent);
    if (unitMapElement) unitMap = JSON.parse(unitMapElement.textContent);
    if (unitOwnersElement) unitOwners = JSON.parse(unitOwnersElement.textContent);

    // Build a map of unit max HP for clamping heals
    if (setup) {
        (setup.left || []).forEach(u => unitMaxHp[u.id] = u.maxHp);
        (setup.right || []).forEach(u => unitMaxHp[u.id] = u.maxHp);
    }
}

// Fonction pour déterminer le rôle d'un personnage
function getCharacterRole(characterId) {
    if (!setup) return 'dps'; // valeur par défaut
    
    // Chercher dans les équipes left et right
    const allCharacters = [...(setup.left || []), ...(setup.right || [])];
    const character = allCharacters.find(char => char.id === characterId);
    
    if (!character || !character.character) return 'dps';
    
    const charData = character.character;
    
    // Logique pour déterminer le rôle basée sur les stats du personnage
    // Tank : constitution élevée et/ou faible attack
    // Heal : magie élevée et/ou compétences de soin
    // DPS : attack élevée
    
    if (charData.constitution >= 70 || (charData.constitution > charData.attack && charData.constitution > 50)) {
        return 'tank';
    } else if (charData.magie >= 60 || (charData.magie > charData.attack && charData.magie > 40)) {
        return 'heal';
    } else {
        return 'dps';
    }
}

function clearAllTimeouts() {
    playTimeouts.forEach(id => clearTimeout(id));
    playTimeouts = [];
}

function resetVisuals() {
    document.querySelectorAll('.avatar-container').forEach(el => {
        el.classList.remove('glow-action','acting-tank','acting-dps','acting-heal','targeted','heal');
    });
}

function resetTemporaryVisuals() {
    // Like resetVisuals but keep dead class
    document.querySelectorAll('.avatar-container').forEach(el => {
        el.classList.remove('glow-action','acting-tank','acting-dps','acting-heal','targeted','heal');
    });
}

function syncHpSnapshot(hpByUnit) {
    Object.entries(hpByUnit || {}).forEach(([uid, hp]) => {
        const el = document.getElementById('hp-' + uid);
        if (el) el.textContent = hp;
        const hpBarEl = document.getElementById('hp-bar-' + uid);
        if (hpBarEl) {
            const maxHp = unitMaxHp[uid] || 9999;
            const hpPercent = Math.max(0, (hp / maxHp) * 100);
            hpBarEl.style.width = hpPercent + '%';
            
            // Ajouter classe low-hp si en dessous de 30%
            if (hpPercent < 30) {
                hpBarEl.classList.add('low-hp');
            } else {
                hpBarEl.classList.remove('low-hp');
            }
        }
        const box = document.getElementById('char-' + uid);
        if (box) {
            if (hp <= 0) box.classList.add('dead');
            else box.classList.remove('dead');
        }
    });
}

function playFrame(idx) {
    if (!frames || idx >= frames.length) {
        // finished
        // Show winner from server result
        const battleWinnerElement = document.getElementById('battle-winner');
        if (battleWinnerElement) {
            const winnerData = battleWinnerElement.dataset.winner;
            const opponentName = battleWinnerElement.dataset.opponentName;
            let winnerText = '';
            if (winnerData === 'left') {
                winnerText = 'Victoire : Votre équipe !';
            } else if (winnerData === 'right') {
                winnerText = 'Victoire : ' + opponentName;
            } else {
                winnerText = 'Match nul !';
            }
            battleWinnerElement.textContent = winnerText;
        }
        const logDiv = document.getElementById('battle-log');
        if (logDiv) {
            const p = document.createElement('div');
            p.textContent = 'Fin du combat.';
            logDiv.appendChild(p);
        }
        return;
    }

    resetTemporaryVisuals();
    currentFrameIdx = idx;
    resetVisuals();
    const frame = frames[idx];
    currentFrame = frame;

    // Update round counter if present
    if (typeof frame.round !== 'undefined') {
        const rc = document.getElementById('round-counter');
        if (rc) rc.textContent = 'Round: ' + frame.round;
    }

    // If frame contains roundEvents (global AoE from round), render them first
    if (frame.roundEvents && frame.roundEvents.length) {
        const logDiv = document.getElementById('battle-log');
        if (logDiv) {
            frame.roundEvents.forEach(ev => { const p = document.createElement('div'); p.textContent = ev; logDiv.appendChild(p); });
            logDiv.scrollTop = logDiv.scrollHeight;
        }
    }

    // local hp map to incrementally update UI
    const localHp = {};
    Object.keys(unitMap || {}).forEach(uid => {
        const el = document.getElementById('hp-' + uid);
        localHp[uid] = el ? parseInt(el.textContent || '0', 10) : (frame.hpByUnit && frame.hpByUnit[uid] ? frame.hpByUnit[uid] : 0);
    });

    currentActions = frame.actions || [];
    currentEvents = frame.events || [];
    currentActionIdx = 0;

    function processNextAction() {
        if (isPaused) return; // paused, do nothing

        // If there are no actions, just sync snapshot and advance after 1s
        if (!currentActions || currentActions.length === 0) {
            if (currentEvents.length) {
                const logDiv = document.getElementById('battle-log');
                if (logDiv) {
                    currentEvents.forEach(ev => { const p = document.createElement('div'); p.textContent = ev; logDiv.appendChild(p); });
                    logDiv.scrollTop = logDiv.scrollHeight;
                }
            }
            syncHpSnapshot(frame.hpByUnit);
            const t = setTimeout(() => playFrame(currentFrameIdx + 1), 1000);
            playTimeouts.push(t);
            return;
        }

        if (currentActionIdx >= currentActions.length) {
            // end of actions for this frame: sync snapshot then go to next frame after 1s
            setTimeout(() => {
                syncHpSnapshot(frame.hpByUnit);
                if (!isPaused) {
                    const t = setTimeout(() => playFrame(currentFrameIdx + 1), 1000);
                    playTimeouts.push(t);
                }
            }, 300);
            return;
        }

        const action = currentActions[currentActionIdx];

        // perform this action immediately
        const actorEl = document.getElementById('char-' + action.actorId);
        const targetEl = action.targetId ? document.getElementById('char-' + action.targetId) : null;
        if (actorEl) {
            // Déterminer le rôle du personnage qui agit
            const actorRole = getCharacterRole(action.actorId);
            actorEl.classList.add('acting-' + actorRole);
        }
        if (targetEl) targetEl.classList.add('targeted');
        if (action.kind === 'HEAL') {
            if (actorEl) actorEl.classList.add('heal');
            if (targetEl) targetEl.classList.add('heal');
        }

        // update HP display incrementally
        if (action.kind === 'ATTACK') {
            const tid = action.targetId;
            if (typeof localHp[tid] !== 'undefined') {
                localHp[tid] = Math.max(0, localHp[tid] - action.amount);
                const hpEl = document.getElementById('hp-' + tid);
                if (hpEl) hpEl.textContent = localHp[tid];
                const hpBarEl = document.getElementById('hp-bar-' + tid);
                if (hpBarEl) {
                    const maxHp = unitMaxHp[tid] || 9999;
                    const hpPercent = Math.max(0, (localHp[tid] / maxHp) * 100);
                    hpBarEl.style.width = hpPercent + '%';
                    
                    // Ajouter classe low-hp si en dessous de 30%
                    if (hpPercent < 30) {
                        hpBarEl.classList.add('low-hp');
                    } else {
                        hpBarEl.classList.remove('low-hp');
                    }
                }
                const box = document.getElementById('char-' + tid);
                if (box && localHp[tid] <= 0) box.classList.add('dead');
            }
        } else if (action.kind === 'HEAL') {
            const tid = action.targetId;
            if (typeof localHp[tid] !== 'undefined') {
                const maxHp = unitMaxHp[tid] || 9999;
                localHp[tid] = Math.min(maxHp, localHp[tid] + action.amount);
                const hpEl = document.getElementById('hp-' + tid);
                if (hpEl) hpEl.textContent = localHp[tid];
                const hpBarEl = document.getElementById('hp-bar-' + tid);
                if (hpBarEl) {
                    const hpPercent = Math.max(0, (localHp[tid] / maxHp) * 100);
                    hpBarEl.style.width = hpPercent + '%';
                    
                    // Ajouter classe low-hp si en dessous de 30%
                    if (hpPercent < 30) {
                        hpBarEl.classList.add('low-hp');
                    } else {
                        hpBarEl.classList.remove('low-hp');
                    }
                }
                const box = document.getElementById('char-' + tid);
                if (box && localHp[tid] > 0) box.classList.remove('dead');
            }
        }

        // append corresponding event if available
        const logDiv = document.getElementById('battle-log');
        if (logDiv && currentEvents[currentActionIdx]) {
            const p = document.createElement('div');
            p.textContent = currentEvents[currentActionIdx];
            logDiv.appendChild(p);
            logDiv.scrollTop = logDiv.scrollHeight;
        }

        // schedule cleanup for this action
        const cleanup = setTimeout(() => {
            if (actorEl) {
                // Nettoyer toutes les classes d'action possibles
                actorEl.classList.remove('acting-tank', 'acting-dps', 'acting-heal');
            }
            if (targetEl) targetEl.classList.remove('targeted');
            if (actorEl) actorEl.classList.remove('heal');
            if (targetEl) targetEl.classList.remove('heal');
        }, 800); // Augmenté légèrement pour laisser le temps aux animations de se terminer
        playTimeouts.push(cleanup);

        // schedule next action after 1s (if not paused)
        const nextTimer = setTimeout(() => {
            currentActionIdx++;
            processNextAction();
        }, 1000);
        playTimeouts.push(nextTimer);
    }

    // Start processing actions for this frame
    processNextActionRef = processNextAction;
    processNextAction();
}

// Initialize battle when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Only initialize if we're on the battle page
    if (document.getElementById('play-battle')) {
        initBattleData();

        document.getElementById('play-battle').addEventListener('click', function() {
            // Stop any previous run
            clearAllTimeouts();
            resetVisuals();
            const logDiv = document.getElementById('battle-log');
            if (logDiv) logDiv.innerHTML = '';
            const winnerDiv = document.getElementById('battle-winner');
            if (winnerDiv) winnerDiv.textContent = '';
            isPaused = false;
            processNextActionRef = null;

            if (!frames || frames.length === 0) {
                const logDiv = document.getElementById('battle-log');
                if (logDiv) {
                    const p = document.createElement('div');
                    p.textContent = 'No frames available.';
                    logDiv.appendChild(p);
                }
                return;
            }

            playFrame(0);
        });

        document.getElementById('pause-battle').addEventListener('click', function() {
            isPaused = !isPaused;
            this.textContent = isPaused ? 'Reprendre' : 'Pause';
            if (!isPaused) {
                // resume: continue processing current frame/action
                if (currentFrameIdx < (frames ? frames.length : 0)) {
                    // clear any lingering timeouts and continue
                    clearAllTimeouts();
                    // resume action processing at currentActionIdx
                    // small delay to allow visuals to settle
                    setTimeout(() => {
                        // continue from the stored processNextActionRef which preserves indices
                        if (processNextActionRef) processNextActionRef();
                    }, 120);
                }
            } else {
                // paused: stop scheduling future timers
                clearAllTimeouts();
            }
        });
    }
});
