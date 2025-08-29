(self["webpackChunk"] = self["webpackChunk"] || []).push([["app"],{

/***/ "./assets/app.js":
/*!***********************!*\
  !*** ./assets/app.js ***!
  \***********************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _styles_app_scss__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./styles/app.scss */ "./assets/styles/app.scss");
/* harmony import */ var _script_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./script.js */ "./assets/script.js");
/* harmony import */ var _script_js__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_script_js__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _battle_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./battle.js */ "./assets/battle.js");
/* harmony import */ var _battle_js__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_battle_js__WEBPACK_IMPORTED_MODULE_2__);
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */




console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// Character image modal (delegated)
window.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('characterImageModal');
  var modalImg = document.getElementById('characterImageModalImg');
  var modalClose = document.getElementById('characterImageModalClose');
  function openImageModal(src) {
    var alt = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
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
  document.addEventListener('click', function (e) {
    var item = e.target.closest('.character-item');
    if (!item) return;
    var imgSrc = item.getAttribute('data-image');
    var imgEl = item.querySelector('.character-avatar');
    var alt = imgEl ? imgEl.alt || '' : '';
    if (imgSrc) {
      openImageModal(imgSrc, alt);
    }
  });
  if (modalClose) modalClose.addEventListener('click', closeImageModal);
  if (modal) modal.addEventListener('click', function (e) {
    if (e.target === modal) closeImageModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeImageModal();
  });
});

/***/ }),

/***/ "./assets/battle.js":
/*!**************************!*\
  !*** ./assets/battle.js ***!
  \**************************/
/***/ ((__unused_webpack_module, __unused_webpack_exports, __webpack_require__) => {

function _slicedToArray(r, e) { return _arrayWithHoles(r) || _iterableToArrayLimit(r, e) || _unsupportedIterableToArray(r, e) || _nonIterableRest(); }
function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _iterableToArrayLimit(r, l) { var t = null == r ? null : "undefined" != typeof Symbol && r[Symbol.iterator] || r["@@iterator"]; if (null != t) { var e, n, i, u, a = [], f = !0, o = !1; try { if (i = (t = t.call(r)).next, 0 === l) { if (Object(t) !== t) return; f = !1; } else for (; !(f = (e = i.call(t)).done) && (a.push(e.value), a.length !== l); f = !0); } catch (r) { o = !0, n = r; } finally { try { if (!f && null != t["return"] && (u = t["return"](), Object(u) !== u)) return; } finally { if (o) throw n; } } return a; } }
function _arrayWithHoles(r) { if (Array.isArray(r)) return r; }
function _toConsumableArray(r) { return _arrayWithoutHoles(r) || _iterableToArray(r) || _unsupportedIterableToArray(r) || _nonIterableSpread(); }
function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }
function _unsupportedIterableToArray(r, a) { if (r) { if ("string" == typeof r) return _arrayLikeToArray(r, a); var t = {}.toString.call(r).slice(8, -1); return "Object" === t && r.constructor && (t = r.constructor.name), "Map" === t || "Set" === t ? Array.from(r) : "Arguments" === t || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(t) ? _arrayLikeToArray(r, a) : void 0; } }
function _iterableToArray(r) { if ("undefined" != typeof Symbol && null != r[Symbol.iterator] || null != r["@@iterator"]) return Array.from(r); }
function _arrayWithoutHoles(r) { if (Array.isArray(r)) return _arrayLikeToArray(r); }
function _arrayLikeToArray(r, a) { (null == a || a > r.length) && (a = r.length); for (var e = 0, n = Array(a); e < a; e++) n[e] = r[e]; return n; }
__webpack_require__(/*! core-js/modules/es.symbol.js */ "./node_modules/core-js/modules/es.symbol.js");
__webpack_require__(/*! core-js/modules/es.symbol.description.js */ "./node_modules/core-js/modules/es.symbol.description.js");
__webpack_require__(/*! core-js/modules/es.symbol.iterator.js */ "./node_modules/core-js/modules/es.symbol.iterator.js");
__webpack_require__(/*! core-js/modules/es.error.cause.js */ "./node_modules/core-js/modules/es.error.cause.js");
__webpack_require__(/*! core-js/modules/es.error.to-string.js */ "./node_modules/core-js/modules/es.error.to-string.js");
__webpack_require__(/*! core-js/modules/es.array.concat.js */ "./node_modules/core-js/modules/es.array.concat.js");
__webpack_require__(/*! core-js/modules/es.array.find.js */ "./node_modules/core-js/modules/es.array.find.js");
__webpack_require__(/*! core-js/modules/es.array.for-each.js */ "./node_modules/core-js/modules/es.array.for-each.js");
__webpack_require__(/*! core-js/modules/es.array.from.js */ "./node_modules/core-js/modules/es.array.from.js");
__webpack_require__(/*! core-js/modules/es.array.is-array.js */ "./node_modules/core-js/modules/es.array.is-array.js");
__webpack_require__(/*! core-js/modules/es.array.iterator.js */ "./node_modules/core-js/modules/es.array.iterator.js");
__webpack_require__(/*! core-js/modules/es.array.push.js */ "./node_modules/core-js/modules/es.array.push.js");
__webpack_require__(/*! core-js/modules/es.array.slice.js */ "./node_modules/core-js/modules/es.array.slice.js");
__webpack_require__(/*! core-js/modules/es.date.to-string.js */ "./node_modules/core-js/modules/es.date.to-string.js");
__webpack_require__(/*! core-js/modules/es.function.name.js */ "./node_modules/core-js/modules/es.function.name.js");
__webpack_require__(/*! core-js/modules/es.object.entries.js */ "./node_modules/core-js/modules/es.object.entries.js");
__webpack_require__(/*! core-js/modules/es.object.keys.js */ "./node_modules/core-js/modules/es.object.keys.js");
__webpack_require__(/*! core-js/modules/es.object.to-string.js */ "./node_modules/core-js/modules/es.object.to-string.js");
__webpack_require__(/*! core-js/modules/es.parse-int.js */ "./node_modules/core-js/modules/es.parse-int.js");
__webpack_require__(/*! core-js/modules/es.regexp.exec.js */ "./node_modules/core-js/modules/es.regexp.exec.js");
__webpack_require__(/*! core-js/modules/es.regexp.test.js */ "./node_modules/core-js/modules/es.regexp.test.js");
__webpack_require__(/*! core-js/modules/es.regexp.to-string.js */ "./node_modules/core-js/modules/es.regexp.to-string.js");
__webpack_require__(/*! core-js/modules/es.string.iterator.js */ "./node_modules/core-js/modules/es.string.iterator.js");
__webpack_require__(/*! core-js/modules/esnext.iterator.constructor.js */ "./node_modules/core-js/modules/esnext.iterator.constructor.js");
__webpack_require__(/*! core-js/modules/esnext.iterator.find.js */ "./node_modules/core-js/modules/esnext.iterator.find.js");
__webpack_require__(/*! core-js/modules/esnext.iterator.for-each.js */ "./node_modules/core-js/modules/esnext.iterator.for-each.js");
__webpack_require__(/*! core-js/modules/web.dom-collections.for-each.js */ "./node_modules/core-js/modules/web.dom-collections.for-each.js");
__webpack_require__(/*! core-js/modules/web.dom-collections.iterator.js */ "./node_modules/core-js/modules/web.dom-collections.iterator.js");
__webpack_require__(/*! core-js/modules/web.timers.js */ "./node_modules/core-js/modules/web.timers.js");
// ==========================================================================
// BATTLE ANIMATION SCRIPT - WARRIOR ARENA TAVERN
// ==========================================================================

// Global variables for battle animation
var playTimeouts = [];
var isPaused = false;
var currentFrameIdx = 0;
var currentActionIdx = 0;
var currentFrame = null;
var currentActions = [];
var currentEvents = [];
var processNextActionRef = null;
var setup = null;
var frames = null;
var unitMap = null;
var unitOwners = null;
var unitMaxHp = {};

// Initialize battle data from DOM
function initBattleData() {
  var setupElement = document.getElementById('battle-setup-data');
  var framesElement = document.getElementById('battle-frames-data');
  var unitMapElement = document.getElementById('battle-unit-map-data');
  var unitOwnersElement = document.getElementById('battle-unit-owners-data');
  if (setupElement) setup = JSON.parse(setupElement.textContent);
  if (framesElement) frames = JSON.parse(framesElement.textContent);
  if (unitMapElement) unitMap = JSON.parse(unitMapElement.textContent);
  if (unitOwnersElement) unitOwners = JSON.parse(unitOwnersElement.textContent);

  // Build a map of unit max HP for clamping heals
  if (setup) {
    (setup.left || []).forEach(function (u) {
      return unitMaxHp[u.id] = u.maxHp;
    });
    (setup.right || []).forEach(function (u) {
      return unitMaxHp[u.id] = u.maxHp;
    });
  }
}

// Fonction pour déterminer le rôle d'un personnage
function getCharacterRole(characterId) {
  if (!setup) return 'dps'; // valeur par défaut

  // Chercher dans les équipes left et right
  var allCharacters = [].concat(_toConsumableArray(setup.left || []), _toConsumableArray(setup.right || []));
  var character = allCharacters.find(function (_char) {
    return _char.id === characterId;
  });
  if (!character || !character.character) return 'dps';
  var charData = character.character;

  // Logique pour déterminer le rôle basée sur les stats du personnage
  // Tank : constitution élevée et/ou faible attack
  // Heal : magie élevée et/ou compétences de soin
  // DPS : attack élevée

  if (charData.constitution >= 70 || charData.constitution > charData.attack && charData.constitution > 50) {
    return 'tank';
  } else if (charData.magie >= 60 || charData.magie > charData.attack && charData.magie > 40) {
    return 'heal';
  } else {
    return 'dps';
  }
}
function clearAllTimeouts() {
  playTimeouts.forEach(function (id) {
    return clearTimeout(id);
  });
  playTimeouts = [];
}
function resetVisuals() {
  document.querySelectorAll('.avatar-container').forEach(function (el) {
    el.classList.remove('glow-action', 'acting-tank', 'acting-dps', 'acting-heal', 'targeted', 'heal');
  });
}
function resetTemporaryVisuals() {
  // Like resetVisuals but keep dead class
  document.querySelectorAll('.avatar-container').forEach(function (el) {
    el.classList.remove('glow-action', 'acting-tank', 'acting-dps', 'acting-heal', 'targeted', 'heal');
  });
}
function syncHpSnapshot(hpByUnit) {
  Object.entries(hpByUnit || {}).forEach(function (_ref) {
    var _ref2 = _slicedToArray(_ref, 2),
      uid = _ref2[0],
      hp = _ref2[1];
    var el = document.getElementById('hp-' + uid);
    if (el) el.textContent = hp;
    var hpBarEl = document.getElementById('hp-bar-' + uid);
    if (hpBarEl) {
      var maxHp = unitMaxHp[uid] || 9999;
      var hpPercent = Math.max(0, hp / maxHp * 100);
      hpBarEl.style.width = hpPercent + '%';

      // Ajouter classe low-hp si en dessous de 30%
      if (hpPercent < 30) {
        hpBarEl.classList.add('low-hp');
      } else {
        hpBarEl.classList.remove('low-hp');
      }
    }
    var box = document.getElementById('char-' + uid);
    if (box) {
      if (hp <= 0) box.classList.add('dead');else box.classList.remove('dead');
    }
  });
}
function playFrame(idx) {
  if (!frames || idx >= frames.length) {
    // finished
    // Show winner from server result
    var battleWinnerElement = document.getElementById('battle-winner');
    if (battleWinnerElement) {
      var winnerData = battleWinnerElement.dataset.winner;
      var opponentName = battleWinnerElement.dataset.opponentName;
      var winnerText = '';
      if (winnerData === 'left') {
        winnerText = 'Victoire : Votre équipe !';
      } else if (winnerData === 'right') {
        winnerText = 'Victoire : ' + opponentName;
      } else {
        winnerText = 'Match nul !';
      }
      battleWinnerElement.textContent = winnerText;
    }
    var logDiv = document.getElementById('battle-log');
    if (logDiv) {
      var p = document.createElement('div');
      p.textContent = 'Fin du combat.';
      logDiv.appendChild(p);
    }
    return;
  }
  resetTemporaryVisuals();
  currentFrameIdx = idx;
  resetVisuals();
  var frame = frames[idx];
  currentFrame = frame;

  // Update round counter if present
  if (typeof frame.round !== 'undefined') {
    var rc = document.getElementById('round-counter');
    if (rc) rc.textContent = 'Round: ' + frame.round;
  }

  // If frame contains roundEvents (global AoE from round), render them first
  if (frame.roundEvents && frame.roundEvents.length) {
    var _logDiv = document.getElementById('battle-log');
    if (_logDiv) {
      frame.roundEvents.forEach(function (ev) {
        var p = document.createElement('div');
        p.textContent = ev;
        _logDiv.appendChild(p);
      });
      _logDiv.scrollTop = _logDiv.scrollHeight;
    }
  }

  // local hp map to incrementally update UI
  var localHp = {};
  Object.keys(unitMap || {}).forEach(function (uid) {
    var el = document.getElementById('hp-' + uid);
    localHp[uid] = el ? parseInt(el.textContent || '0', 10) : frame.hpByUnit && frame.hpByUnit[uid] ? frame.hpByUnit[uid] : 0;
  });
  currentActions = frame.actions || [];
  currentEvents = frame.events || [];
  currentActionIdx = 0;
  function processNextAction() {
    if (isPaused) return; // paused, do nothing

    // If there are no actions, just sync snapshot and advance after 1s
    if (!currentActions || currentActions.length === 0) {
      if (currentEvents.length) {
        var _logDiv2 = document.getElementById('battle-log');
        if (_logDiv2) {
          currentEvents.forEach(function (ev) {
            var p = document.createElement('div');
            p.textContent = ev;
            _logDiv2.appendChild(p);
          });
          _logDiv2.scrollTop = _logDiv2.scrollHeight;
        }
      }
      syncHpSnapshot(frame.hpByUnit);
      var t = setTimeout(function () {
        return playFrame(currentFrameIdx + 1);
      }, 1000);
      playTimeouts.push(t);
      return;
    }
    if (currentActionIdx >= currentActions.length) {
      // end of actions for this frame: sync snapshot then go to next frame after 1s
      setTimeout(function () {
        syncHpSnapshot(frame.hpByUnit);
        if (!isPaused) {
          var _t = setTimeout(function () {
            return playFrame(currentFrameIdx + 1);
          }, 1000);
          playTimeouts.push(_t);
        }
      }, 300);
      return;
    }
    var action = currentActions[currentActionIdx];

    // perform this action immediately
    var actorEl = document.getElementById('char-' + action.actorId);
    var targetEl = action.targetId ? document.getElementById('char-' + action.targetId) : null;
    if (actorEl) {
      // Déterminer le rôle du personnage qui agit
      var actorRole = getCharacterRole(action.actorId);
      actorEl.classList.add('acting-' + actorRole);
    }
    if (targetEl) targetEl.classList.add('targeted');
    if (action.kind === 'HEAL') {
      if (actorEl) actorEl.classList.add('heal');
      if (targetEl) targetEl.classList.add('heal');
    }

    // update HP display incrementally
    if (action.kind === 'ATTACK') {
      var tid = action.targetId;
      if (typeof localHp[tid] !== 'undefined') {
        localHp[tid] = Math.max(0, localHp[tid] - action.amount);
        var hpEl = document.getElementById('hp-' + tid);
        if (hpEl) hpEl.textContent = localHp[tid];
        var hpBarEl = document.getElementById('hp-bar-' + tid);
        if (hpBarEl) {
          var maxHp = unitMaxHp[tid] || 9999;
          var hpPercent = Math.max(0, localHp[tid] / maxHp * 100);
          hpBarEl.style.width = hpPercent + '%';

          // Ajouter classe low-hp si en dessous de 30%
          if (hpPercent < 30) {
            hpBarEl.classList.add('low-hp');
          } else {
            hpBarEl.classList.remove('low-hp');
          }
        }
        var box = document.getElementById('char-' + tid);
        if (box && localHp[tid] <= 0) box.classList.add('dead');
      }
    } else if (action.kind === 'HEAL') {
      var _tid = action.targetId;
      if (typeof localHp[_tid] !== 'undefined') {
        var _maxHp = unitMaxHp[_tid] || 9999;
        localHp[_tid] = Math.min(_maxHp, localHp[_tid] + action.amount);
        var _hpEl = document.getElementById('hp-' + _tid);
        if (_hpEl) _hpEl.textContent = localHp[_tid];
        var _hpBarEl = document.getElementById('hp-bar-' + _tid);
        if (_hpBarEl) {
          var _hpPercent = Math.max(0, localHp[_tid] / _maxHp * 100);
          _hpBarEl.style.width = _hpPercent + '%';

          // Ajouter classe low-hp si en dessous de 30%
          if (_hpPercent < 30) {
            _hpBarEl.classList.add('low-hp');
          } else {
            _hpBarEl.classList.remove('low-hp');
          }
        }
        var _box = document.getElementById('char-' + _tid);
        if (_box && localHp[_tid] > 0) _box.classList.remove('dead');
      }
    }

    // append corresponding event if available
    var logDiv = document.getElementById('battle-log');
    if (logDiv && currentEvents[currentActionIdx]) {
      var _p = document.createElement('div');
      _p.textContent = currentEvents[currentActionIdx];
      logDiv.appendChild(_p);
      logDiv.scrollTop = logDiv.scrollHeight;
    }

    // schedule cleanup for this action
    var cleanup = setTimeout(function () {
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
    var nextTimer = setTimeout(function () {
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
document.addEventListener('DOMContentLoaded', function () {
  // Only initialize if we're on the battle page
  if (document.getElementById('play-battle')) {
    initBattleData();
    document.getElementById('play-battle').addEventListener('click', function () {
      // Stop any previous run
      clearAllTimeouts();
      resetVisuals();
      var logDiv = document.getElementById('battle-log');
      if (logDiv) logDiv.innerHTML = '';
      var winnerDiv = document.getElementById('battle-winner');
      if (winnerDiv) winnerDiv.textContent = '';
      isPaused = false;
      processNextActionRef = null;
      if (!frames || frames.length === 0) {
        var _logDiv3 = document.getElementById('battle-log');
        if (_logDiv3) {
          var p = document.createElement('div');
          p.textContent = 'No frames available.';
          _logDiv3.appendChild(p);
        }
        return;
      }
      playFrame(0);
    });
    document.getElementById('pause-battle').addEventListener('click', function () {
      isPaused = !isPaused;
      this.textContent = isPaused ? 'Reprendre' : 'Pause';
      if (!isPaused) {
        // resume: continue processing current frame/action
        if (currentFrameIdx < (frames ? frames.length : 0)) {
          // clear any lingering timeouts and continue
          clearAllTimeouts();
          // resume action processing at currentActionIdx
          // small delay to allow visuals to settle
          setTimeout(function () {
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

/***/ }),

/***/ "./assets/script.js":
/*!**************************!*\
  !*** ./assets/script.js ***!
  \**************************/
/***/ (() => {

// ==========================================================================
// SCRIPTS - WARRIOR ARENA TAVERNE
// ==========================================================================

// Fichier vidé - plus de menu burger

console.log('� Scripts chargés sans menu burger');

// Burger menu toggle
var burger = document.querySelector(".burger");
var navLinks = document.querySelector(".nav-links");
burger.addEventListener("click", function () {
  navLinks.classList.toggle("open");

  // Animation simple du burger → croix
  burger.classList.toggle("active");
});

// Character image modal
window.addEventListener('DOMContentLoaded', function () {
  var modal = document.getElementById('characterImageModal');
  var modalImg = document.getElementById('characterImageModalImg');
  var modalClose = document.getElementById('characterImageModalClose');
  function openImageModal(src) {
    var alt = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';
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
  document.addEventListener('click', function (e) {
    var target = e.target;
    if (target && target.classList && target.classList.contains('character-avatar')) {
      // if it's an <img>
      var src = target.tagName === 'IMG' ? target.src : null;
      var alt = target.alt || '';
      if (src) openImageModal(src, alt);
    }
  });
  if (modalClose) modalClose.addEventListener('click', closeImageModal);
  if (modal) modal.addEventListener('click', function (e) {
    if (e.target === modal) closeImageModal();
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeImageModal();
  });
});

/***/ }),

/***/ "./assets/styles/app.scss":
/*!********************************!*\
  !*** ./assets/styles/app.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

"use strict";
__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ })

},
/******/ __webpack_require__ => { // webpackRuntimeModules
/******/ var __webpack_exec__ = (moduleId) => (__webpack_require__(__webpack_require__.s = moduleId))
/******/ __webpack_require__.O(0, ["vendors-node_modules_core-js_modules_es_array_concat_js-node_modules_core-js_modules_es_array-09c948"], () => (__webpack_exec__("./assets/app.js")));
/******/ var __webpack_exports__ = __webpack_require__.O();
/******/ }
]);
//# sourceMappingURL=data:application/json;charset=utf-8;base64,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LGdCQUFnQixDQUFDLFNBQVMsRUFBRSxVQUFDaUIsQ0FBQyxFQUFLO0lBQ3hDLElBQUlBLENBQUMsQ0FBQ1EsR0FBRyxLQUFLLFFBQVEsRUFBRVYsZUFBZSxDQUFDLENBQUM7RUFDN0MsQ0FBQyxDQUFDO0FBQ04sQ0FBQyxDQUFDLEM7Ozs7Ozs7Ozs7OztBQzdERiIsInNvdXJjZXMiOlsid2VicGFjazovLy8uL2Fzc2V0cy9hcHAuanMiLCJ3ZWJwYWNrOi8vLy4vYXNzZXRzL2JhdHRsZS5qcyIsIndlYnBhY2s6Ly8vLi9hc3NldHMvc2NyaXB0LmpzIiwid2VicGFjazovLy8uL2Fzc2V0cy9zdHlsZXMvYXBwLnNjc3MiXSwic291cmNlc0NvbnRlbnQiOlsiLypcclxuICogV2VsY29tZSB0byB5b3VyIGFwcCdzIG1haW4gSmF2YVNjcmlwdCBmaWxlIVxyXG4gKlxyXG4gKiBUaGlzIGZpbGUgd2lsbCBiZSBpbmNsdWRlZCBvbnRvIHRoZSBwYWdlIHZpYSB0aGUgaW1wb3J0bWFwKCkgVHdpZyBmdW5jdGlvbixcclxuICogd2hpY2ggc2hvdWxkIGFscmVhZHkgYmUgaW4geW91ciBiYXNlLmh0bWwudHdpZy5cclxuICovXHJcblxyXG5pbXBvcnQgJy4vc3R5bGVzL2FwcC5zY3NzJztcclxuaW1wb3J0ICcuL3NjcmlwdC5qcyc7XHJcbmltcG9ydCAnLi9iYXR0bGUuanMnO1xyXG5cclxuY29uc29sZS5sb2coJ1RoaXMgbG9nIGNvbWVzIGZyb20gYXNzZXRzL2FwcC5qcyAtIHdlbGNvbWUgdG8gQXNzZXRNYXBwZXIhIPCfjoknKTtcclxuXHJcbi8vIENoYXJhY3RlciBpbWFnZSBtb2RhbCAoZGVsZWdhdGVkKVxyXG53aW5kb3cuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsICgpID0+IHtcclxuICAgIGNvbnN0IG1vZGFsID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NoYXJhY3RlckltYWdlTW9kYWwnKTtcclxuICAgIGNvbnN0IG1vZGFsSW1nID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NoYXJhY3RlckltYWdlTW9kYWxJbWcnKTtcclxuICAgIGNvbnN0IG1vZGFsQ2xvc2UgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2hhcmFjdGVySW1hZ2VNb2RhbENsb3NlJyk7XHJcblxyXG4gICAgZnVuY3Rpb24gb3BlbkltYWdlTW9kYWwoc3JjLCBhbHQgPSAnJykge1xyXG4gICAgICAgIGlmICghbW9kYWwgfHwgIW1vZGFsSW1nKSByZXR1cm47XHJcbiAgICAgICAgbW9kYWxJbWcuc3JjID0gc3JjO1xyXG4gICAgICAgIG1vZGFsSW1nLmFsdCA9IGFsdDtcclxuICAgICAgICBtb2RhbC5jbGFzc0xpc3QuYWRkKCdhY3RpdmUnKTtcclxuICAgICAgICBkb2N1bWVudC5ib2R5LmNsYXNzTGlzdC5hZGQoJ21vZGFsLW9wZW4nKTtcclxuICAgIH1cclxuXHJcbiAgICBmdW5jdGlvbiBjbG9zZUltYWdlTW9kYWwoKSB7XHJcbiAgICAgICAgaWYgKCFtb2RhbCkgcmV0dXJuO1xyXG4gICAgICAgIG1vZGFsLmNsYXNzTGlzdC5yZW1vdmUoJ2FjdGl2ZScpO1xyXG4gICAgICAgIGRvY3VtZW50LmJvZHkuY2xhc3NMaXN0LnJlbW92ZSgnbW9kYWwtb3BlbicpO1xyXG4gICAgICAgIGlmIChtb2RhbEltZykgbW9kYWxJbWcuc3JjID0gJyc7XHJcbiAgICB9XHJcblxyXG4gICAgLy8gQ2xpY2sgb24gdGhlIGVudGlyZSBjYXJkXHJcbiAgICBkb2N1bWVudC5hZGRFdmVudExpc3RlbmVyKCdjbGljaycsIChlKSA9PiB7XHJcbiAgICAgICAgY29uc3QgaXRlbSA9IGUudGFyZ2V0LmNsb3Nlc3QoJy5jaGFyYWN0ZXItaXRlbScpO1xyXG4gICAgICAgIGlmICghaXRlbSkgcmV0dXJuO1xyXG4gICAgICAgIGNvbnN0IGltZ1NyYyA9IGl0ZW0uZ2V0QXR0cmlidXRlKCdkYXRhLWltYWdlJyk7XHJcbiAgICAgICAgY29uc3QgaW1nRWwgPSBpdGVtLnF1ZXJ5U2VsZWN0b3IoJy5jaGFyYWN0ZXItYXZhdGFyJyk7XHJcbiAgICAgICAgY29uc3QgYWx0ID0gaW1nRWwgPyAoaW1nRWwuYWx0IHx8ICcnKSA6ICcnO1xyXG4gICAgICAgIGlmIChpbWdTcmMpIHtcclxuICAgICAgICAgICAgb3BlbkltYWdlTW9kYWwoaW1nU3JjLCBhbHQpO1xyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG5cclxuICAgIGlmIChtb2RhbENsb3NlKSBtb2RhbENsb3NlLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xvc2VJbWFnZU1vZGFsKTtcclxuICAgIGlmIChtb2RhbCkgbW9kYWwuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xyXG4gICAgICAgIGlmIChlLnRhcmdldCA9PT0gbW9kYWwpIGNsb3NlSW1hZ2VNb2RhbCgpO1xyXG4gICAgfSk7XHJcblxyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIChlKSA9PiB7XHJcbiAgICAgICAgaWYgKGUua2V5ID09PSAnRXNjYXBlJykgY2xvc2VJbWFnZU1vZGFsKCk7XHJcbiAgICB9KTtcclxufSk7XHJcblxyXG4iLCIvLyA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxyXG4vLyBCQVRUTEUgQU5JTUFUSU9OIFNDUklQVCAtIFdBUlJJT1IgQVJFTkEgVEFWRVJOXHJcbi8vID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XHJcblxyXG4vLyBHbG9iYWwgdmFyaWFibGVzIGZvciBiYXR0bGUgYW5pbWF0aW9uXHJcbmxldCBwbGF5VGltZW91dHMgPSBbXTtcclxubGV0IGlzUGF1c2VkID0gZmFsc2U7XHJcbmxldCBjdXJyZW50RnJhbWVJZHggPSAwO1xyXG5sZXQgY3VycmVudEFjdGlvbklkeCA9IDA7XHJcbmxldCBjdXJyZW50RnJhbWUgPSBudWxsO1xyXG5sZXQgY3VycmVudEFjdGlvbnMgPSBbXTtcclxubGV0IGN1cnJlbnRFdmVudHMgPSBbXTtcclxubGV0IHByb2Nlc3NOZXh0QWN0aW9uUmVmID0gbnVsbDtcclxubGV0IHNldHVwID0gbnVsbDtcclxubGV0IGZyYW1lcyA9IG51bGw7XHJcbmxldCB1bml0TWFwID0gbnVsbDtcclxubGV0IHVuaXRPd25lcnMgPSBudWxsO1xyXG5sZXQgdW5pdE1heEhwID0ge307XHJcblxyXG4vLyBJbml0aWFsaXplIGJhdHRsZSBkYXRhIGZyb20gRE9NXHJcbmZ1bmN0aW9uIGluaXRCYXR0bGVEYXRhKCkge1xyXG4gICAgY29uc3Qgc2V0dXBFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhdHRsZS1zZXR1cC1kYXRhJyk7XHJcbiAgICBjb25zdCBmcmFtZXNFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhdHRsZS1mcmFtZXMtZGF0YScpO1xyXG4gICAgY29uc3QgdW5pdE1hcEVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYmF0dGxlLXVuaXQtbWFwLWRhdGEnKTtcclxuICAgIGNvbnN0IHVuaXRPd25lcnNFbGVtZW50ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhdHRsZS11bml0LW93bmVycy1kYXRhJyk7XHJcblxyXG4gICAgaWYgKHNldHVwRWxlbWVudCkgc2V0dXAgPSBKU09OLnBhcnNlKHNldHVwRWxlbWVudC50ZXh0Q29udGVudCk7XHJcbiAgICBpZiAoZnJhbWVzRWxlbWVudCkgZnJhbWVzID0gSlNPTi5wYXJzZShmcmFtZXNFbGVtZW50LnRleHRDb250ZW50KTtcclxuICAgIGlmICh1bml0TWFwRWxlbWVudCkgdW5pdE1hcCA9IEpTT04ucGFyc2UodW5pdE1hcEVsZW1lbnQudGV4dENvbnRlbnQpO1xyXG4gICAgaWYgKHVuaXRPd25lcnNFbGVtZW50KSB1bml0T3duZXJzID0gSlNPTi5wYXJzZSh1bml0T3duZXJzRWxlbWVudC50ZXh0Q29udGVudCk7XHJcblxyXG4gICAgLy8gQnVpbGQgYSBtYXAgb2YgdW5pdCBtYXggSFAgZm9yIGNsYW1waW5nIGhlYWxzXHJcbiAgICBpZiAoc2V0dXApIHtcclxuICAgICAgICAoc2V0dXAubGVmdCB8fCBbXSkuZm9yRWFjaCh1ID0+IHVuaXRNYXhIcFt1LmlkXSA9IHUubWF4SHApO1xyXG4gICAgICAgIChzZXR1cC5yaWdodCB8fCBbXSkuZm9yRWFjaCh1ID0+IHVuaXRNYXhIcFt1LmlkXSA9IHUubWF4SHApO1xyXG4gICAgfVxyXG59XHJcblxyXG4vLyBGb25jdGlvbiBwb3VyIGTDqXRlcm1pbmVyIGxlIHLDtGxlIGQndW4gcGVyc29ubmFnZVxyXG5mdW5jdGlvbiBnZXRDaGFyYWN0ZXJSb2xlKGNoYXJhY3RlcklkKSB7XHJcbiAgICBpZiAoIXNldHVwKSByZXR1cm4gJ2Rwcyc7IC8vIHZhbGV1ciBwYXIgZMOpZmF1dFxyXG4gICAgXHJcbiAgICAvLyBDaGVyY2hlciBkYW5zIGxlcyDDqXF1aXBlcyBsZWZ0IGV0IHJpZ2h0XHJcbiAgICBjb25zdCBhbGxDaGFyYWN0ZXJzID0gWy4uLihzZXR1cC5sZWZ0IHx8IFtdKSwgLi4uKHNldHVwLnJpZ2h0IHx8IFtdKV07XHJcbiAgICBjb25zdCBjaGFyYWN0ZXIgPSBhbGxDaGFyYWN0ZXJzLmZpbmQoY2hhciA9PiBjaGFyLmlkID09PSBjaGFyYWN0ZXJJZCk7XHJcbiAgICBcclxuICAgIGlmICghY2hhcmFjdGVyIHx8ICFjaGFyYWN0ZXIuY2hhcmFjdGVyKSByZXR1cm4gJ2Rwcyc7XHJcbiAgICBcclxuICAgIGNvbnN0IGNoYXJEYXRhID0gY2hhcmFjdGVyLmNoYXJhY3RlcjtcclxuICAgIFxyXG4gICAgLy8gTG9naXF1ZSBwb3VyIGTDqXRlcm1pbmVyIGxlIHLDtGxlIGJhc8OpZSBzdXIgbGVzIHN0YXRzIGR1IHBlcnNvbm5hZ2VcclxuICAgIC8vIFRhbmsgOiBjb25zdGl0dXRpb24gw6lsZXbDqWUgZXQvb3UgZmFpYmxlIGF0dGFja1xyXG4gICAgLy8gSGVhbCA6IG1hZ2llIMOpbGV2w6llIGV0L291IGNvbXDDqXRlbmNlcyBkZSBzb2luXHJcbiAgICAvLyBEUFMgOiBhdHRhY2sgw6lsZXbDqWVcclxuICAgIFxyXG4gICAgaWYgKGNoYXJEYXRhLmNvbnN0aXR1dGlvbiA+PSA3MCB8fCAoY2hhckRhdGEuY29uc3RpdHV0aW9uID4gY2hhckRhdGEuYXR0YWNrICYmIGNoYXJEYXRhLmNvbnN0aXR1dGlvbiA+IDUwKSkge1xyXG4gICAgICAgIHJldHVybiAndGFuayc7XHJcbiAgICB9IGVsc2UgaWYgKGNoYXJEYXRhLm1hZ2llID49IDYwIHx8IChjaGFyRGF0YS5tYWdpZSA+IGNoYXJEYXRhLmF0dGFjayAmJiBjaGFyRGF0YS5tYWdpZSA+IDQwKSkge1xyXG4gICAgICAgIHJldHVybiAnaGVhbCc7XHJcbiAgICB9IGVsc2Uge1xyXG4gICAgICAgIHJldHVybiAnZHBzJztcclxuICAgIH1cclxufVxyXG5cclxuZnVuY3Rpb24gY2xlYXJBbGxUaW1lb3V0cygpIHtcclxuICAgIHBsYXlUaW1lb3V0cy5mb3JFYWNoKGlkID0+IGNsZWFyVGltZW91dChpZCkpO1xyXG4gICAgcGxheVRpbWVvdXRzID0gW107XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHJlc2V0VmlzdWFscygpIHtcclxuICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5hdmF0YXItY29udGFpbmVyJykuZm9yRWFjaChlbCA9PiB7XHJcbiAgICAgICAgZWwuY2xhc3NMaXN0LnJlbW92ZSgnZ2xvdy1hY3Rpb24nLCdhY3RpbmctdGFuaycsJ2FjdGluZy1kcHMnLCdhY3RpbmctaGVhbCcsJ3RhcmdldGVkJywnaGVhbCcpO1xyXG4gICAgfSk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHJlc2V0VGVtcG9yYXJ5VmlzdWFscygpIHtcclxuICAgIC8vIExpa2UgcmVzZXRWaXN1YWxzIGJ1dCBrZWVwIGRlYWQgY2xhc3NcclxuICAgIGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3JBbGwoJy5hdmF0YXItY29udGFpbmVyJykuZm9yRWFjaChlbCA9PiB7XHJcbiAgICAgICAgZWwuY2xhc3NMaXN0LnJlbW92ZSgnZ2xvdy1hY3Rpb24nLCdhY3RpbmctdGFuaycsJ2FjdGluZy1kcHMnLCdhY3RpbmctaGVhbCcsJ3RhcmdldGVkJywnaGVhbCcpO1xyXG4gICAgfSk7XHJcbn1cclxuXHJcbmZ1bmN0aW9uIHN5bmNIcFNuYXBzaG90KGhwQnlVbml0KSB7XHJcbiAgICBPYmplY3QuZW50cmllcyhocEJ5VW5pdCB8fCB7fSkuZm9yRWFjaCgoW3VpZCwgaHBdKSA9PiB7XHJcbiAgICAgICAgY29uc3QgZWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaHAtJyArIHVpZCk7XHJcbiAgICAgICAgaWYgKGVsKSBlbC50ZXh0Q29udGVudCA9IGhwO1xyXG4gICAgICAgIGNvbnN0IGhwQmFyRWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaHAtYmFyLScgKyB1aWQpO1xyXG4gICAgICAgIGlmIChocEJhckVsKSB7XHJcbiAgICAgICAgICAgIGNvbnN0IG1heEhwID0gdW5pdE1heEhwW3VpZF0gfHwgOTk5OTtcclxuICAgICAgICAgICAgY29uc3QgaHBQZXJjZW50ID0gTWF0aC5tYXgoMCwgKGhwIC8gbWF4SHApICogMTAwKTtcclxuICAgICAgICAgICAgaHBCYXJFbC5zdHlsZS53aWR0aCA9IGhwUGVyY2VudCArICclJztcclxuICAgICAgICAgICAgXHJcbiAgICAgICAgICAgIC8vIEFqb3V0ZXIgY2xhc3NlIGxvdy1ocCBzaSBlbiBkZXNzb3VzIGRlIDMwJVxyXG4gICAgICAgICAgICBpZiAoaHBQZXJjZW50IDwgMzApIHtcclxuICAgICAgICAgICAgICAgIGhwQmFyRWwuY2xhc3NMaXN0LmFkZCgnbG93LWhwJyk7XHJcbiAgICAgICAgICAgIH0gZWxzZSB7XHJcbiAgICAgICAgICAgICAgICBocEJhckVsLmNsYXNzTGlzdC5yZW1vdmUoJ2xvdy1ocCcpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfVxyXG4gICAgICAgIGNvbnN0IGJveCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjaGFyLScgKyB1aWQpO1xyXG4gICAgICAgIGlmIChib3gpIHtcclxuICAgICAgICAgICAgaWYgKGhwIDw9IDApIGJveC5jbGFzc0xpc3QuYWRkKCdkZWFkJyk7XHJcbiAgICAgICAgICAgIGVsc2UgYm94LmNsYXNzTGlzdC5yZW1vdmUoJ2RlYWQnKTtcclxuICAgICAgICB9XHJcbiAgICB9KTtcclxufVxyXG5cclxuZnVuY3Rpb24gcGxheUZyYW1lKGlkeCkge1xyXG4gICAgaWYgKCFmcmFtZXMgfHwgaWR4ID49IGZyYW1lcy5sZW5ndGgpIHtcclxuICAgICAgICAvLyBmaW5pc2hlZFxyXG4gICAgICAgIC8vIFNob3cgd2lubmVyIGZyb20gc2VydmVyIHJlc3VsdFxyXG4gICAgICAgIGNvbnN0IGJhdHRsZVdpbm5lckVsZW1lbnQgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYmF0dGxlLXdpbm5lcicpO1xyXG4gICAgICAgIGlmIChiYXR0bGVXaW5uZXJFbGVtZW50KSB7XHJcbiAgICAgICAgICAgIGNvbnN0IHdpbm5lckRhdGEgPSBiYXR0bGVXaW5uZXJFbGVtZW50LmRhdGFzZXQud2lubmVyO1xyXG4gICAgICAgICAgICBjb25zdCBvcHBvbmVudE5hbWUgPSBiYXR0bGVXaW5uZXJFbGVtZW50LmRhdGFzZXQub3Bwb25lbnROYW1lO1xyXG4gICAgICAgICAgICBsZXQgd2lubmVyVGV4dCA9ICcnO1xyXG4gICAgICAgICAgICBpZiAod2lubmVyRGF0YSA9PT0gJ2xlZnQnKSB7XHJcbiAgICAgICAgICAgICAgICB3aW5uZXJUZXh0ID0gJ1ZpY3RvaXJlIDogVm90cmUgw6lxdWlwZSAhJztcclxuICAgICAgICAgICAgfSBlbHNlIGlmICh3aW5uZXJEYXRhID09PSAncmlnaHQnKSB7XHJcbiAgICAgICAgICAgICAgICB3aW5uZXJUZXh0ID0gJ1ZpY3RvaXJlIDogJyArIG9wcG9uZW50TmFtZTtcclxuICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgIHdpbm5lclRleHQgPSAnTWF0Y2ggbnVsICEnO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIGJhdHRsZVdpbm5lckVsZW1lbnQudGV4dENvbnRlbnQgPSB3aW5uZXJUZXh0O1xyXG4gICAgICAgIH1cclxuICAgICAgICBjb25zdCBsb2dEaXYgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYmF0dGxlLWxvZycpO1xyXG4gICAgICAgIGlmIChsb2dEaXYpIHtcclxuICAgICAgICAgICAgY29uc3QgcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgICAgICAgICBwLnRleHRDb250ZW50ID0gJ0ZpbiBkdSBjb21iYXQuJztcclxuICAgICAgICAgICAgbG9nRGl2LmFwcGVuZENoaWxkKHApO1xyXG4gICAgICAgIH1cclxuICAgICAgICByZXR1cm47XHJcbiAgICB9XHJcblxyXG4gICAgcmVzZXRUZW1wb3JhcnlWaXN1YWxzKCk7XHJcbiAgICBjdXJyZW50RnJhbWVJZHggPSBpZHg7XHJcbiAgICByZXNldFZpc3VhbHMoKTtcclxuICAgIGNvbnN0IGZyYW1lID0gZnJhbWVzW2lkeF07XHJcbiAgICBjdXJyZW50RnJhbWUgPSBmcmFtZTtcclxuXHJcbiAgICAvLyBVcGRhdGUgcm91bmQgY291bnRlciBpZiBwcmVzZW50XHJcbiAgICBpZiAodHlwZW9mIGZyYW1lLnJvdW5kICE9PSAndW5kZWZpbmVkJykge1xyXG4gICAgICAgIGNvbnN0IHJjID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3JvdW5kLWNvdW50ZXInKTtcclxuICAgICAgICBpZiAocmMpIHJjLnRleHRDb250ZW50ID0gJ1JvdW5kOiAnICsgZnJhbWUucm91bmQ7XHJcbiAgICB9XHJcblxyXG4gICAgLy8gSWYgZnJhbWUgY29udGFpbnMgcm91bmRFdmVudHMgKGdsb2JhbCBBb0UgZnJvbSByb3VuZCksIHJlbmRlciB0aGVtIGZpcnN0XHJcbiAgICBpZiAoZnJhbWUucm91bmRFdmVudHMgJiYgZnJhbWUucm91bmRFdmVudHMubGVuZ3RoKSB7XHJcbiAgICAgICAgY29uc3QgbG9nRGl2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhdHRsZS1sb2cnKTtcclxuICAgICAgICBpZiAobG9nRGl2KSB7XHJcbiAgICAgICAgICAgIGZyYW1lLnJvdW5kRXZlbnRzLmZvckVhY2goZXYgPT4geyBjb25zdCBwID0gZG9jdW1lbnQuY3JlYXRlRWxlbWVudCgnZGl2Jyk7IHAudGV4dENvbnRlbnQgPSBldjsgbG9nRGl2LmFwcGVuZENoaWxkKHApOyB9KTtcclxuICAgICAgICAgICAgbG9nRGl2LnNjcm9sbFRvcCA9IGxvZ0Rpdi5zY3JvbGxIZWlnaHQ7XHJcbiAgICAgICAgfVxyXG4gICAgfVxyXG5cclxuICAgIC8vIGxvY2FsIGhwIG1hcCB0byBpbmNyZW1lbnRhbGx5IHVwZGF0ZSBVSVxyXG4gICAgY29uc3QgbG9jYWxIcCA9IHt9O1xyXG4gICAgT2JqZWN0LmtleXModW5pdE1hcCB8fCB7fSkuZm9yRWFjaCh1aWQgPT4ge1xyXG4gICAgICAgIGNvbnN0IGVsID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2hwLScgKyB1aWQpO1xyXG4gICAgICAgIGxvY2FsSHBbdWlkXSA9IGVsID8gcGFyc2VJbnQoZWwudGV4dENvbnRlbnQgfHwgJzAnLCAxMCkgOiAoZnJhbWUuaHBCeVVuaXQgJiYgZnJhbWUuaHBCeVVuaXRbdWlkXSA/IGZyYW1lLmhwQnlVbml0W3VpZF0gOiAwKTtcclxuICAgIH0pO1xyXG5cclxuICAgIGN1cnJlbnRBY3Rpb25zID0gZnJhbWUuYWN0aW9ucyB8fCBbXTtcclxuICAgIGN1cnJlbnRFdmVudHMgPSBmcmFtZS5ldmVudHMgfHwgW107XHJcbiAgICBjdXJyZW50QWN0aW9uSWR4ID0gMDtcclxuXHJcbiAgICBmdW5jdGlvbiBwcm9jZXNzTmV4dEFjdGlvbigpIHtcclxuICAgICAgICBpZiAoaXNQYXVzZWQpIHJldHVybjsgLy8gcGF1c2VkLCBkbyBub3RoaW5nXHJcblxyXG4gICAgICAgIC8vIElmIHRoZXJlIGFyZSBubyBhY3Rpb25zLCBqdXN0IHN5bmMgc25hcHNob3QgYW5kIGFkdmFuY2UgYWZ0ZXIgMXNcclxuICAgICAgICBpZiAoIWN1cnJlbnRBY3Rpb25zIHx8IGN1cnJlbnRBY3Rpb25zLmxlbmd0aCA9PT0gMCkge1xyXG4gICAgICAgICAgICBpZiAoY3VycmVudEV2ZW50cy5sZW5ndGgpIHtcclxuICAgICAgICAgICAgICAgIGNvbnN0IGxvZ0RpdiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdiYXR0bGUtbG9nJyk7XHJcbiAgICAgICAgICAgICAgICBpZiAobG9nRGl2KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY3VycmVudEV2ZW50cy5mb3JFYWNoKGV2ID0+IHsgY29uc3QgcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpOyBwLnRleHRDb250ZW50ID0gZXY7IGxvZ0Rpdi5hcHBlbmRDaGlsZChwKTsgfSk7XHJcbiAgICAgICAgICAgICAgICAgICAgbG9nRGl2LnNjcm9sbFRvcCA9IGxvZ0Rpdi5zY3JvbGxIZWlnaHQ7XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgc3luY0hwU25hcHNob3QoZnJhbWUuaHBCeVVuaXQpO1xyXG4gICAgICAgICAgICBjb25zdCB0ID0gc2V0VGltZW91dCgoKSA9PiBwbGF5RnJhbWUoY3VycmVudEZyYW1lSWR4ICsgMSksIDEwMDApO1xyXG4gICAgICAgICAgICBwbGF5VGltZW91dHMucHVzaCh0KTtcclxuICAgICAgICAgICAgcmV0dXJuO1xyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgaWYgKGN1cnJlbnRBY3Rpb25JZHggPj0gY3VycmVudEFjdGlvbnMubGVuZ3RoKSB7XHJcbiAgICAgICAgICAgIC8vIGVuZCBvZiBhY3Rpb25zIGZvciB0aGlzIGZyYW1lOiBzeW5jIHNuYXBzaG90IHRoZW4gZ28gdG8gbmV4dCBmcmFtZSBhZnRlciAxc1xyXG4gICAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcclxuICAgICAgICAgICAgICAgIHN5bmNIcFNuYXBzaG90KGZyYW1lLmhwQnlVbml0KTtcclxuICAgICAgICAgICAgICAgIGlmICghaXNQYXVzZWQpIHtcclxuICAgICAgICAgICAgICAgICAgICBjb25zdCB0ID0gc2V0VGltZW91dCgoKSA9PiBwbGF5RnJhbWUoY3VycmVudEZyYW1lSWR4ICsgMSksIDEwMDApO1xyXG4gICAgICAgICAgICAgICAgICAgIHBsYXlUaW1lb3V0cy5wdXNoKHQpO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9LCAzMDApO1xyXG4gICAgICAgICAgICByZXR1cm47XHJcbiAgICAgICAgfVxyXG5cclxuICAgICAgICBjb25zdCBhY3Rpb24gPSBjdXJyZW50QWN0aW9uc1tjdXJyZW50QWN0aW9uSWR4XTtcclxuXHJcbiAgICAgICAgLy8gcGVyZm9ybSB0aGlzIGFjdGlvbiBpbW1lZGlhdGVseVxyXG4gICAgICAgIGNvbnN0IGFjdG9yRWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2hhci0nICsgYWN0aW9uLmFjdG9ySWQpO1xyXG4gICAgICAgIGNvbnN0IHRhcmdldEVsID0gYWN0aW9uLnRhcmdldElkID8gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2NoYXItJyArIGFjdGlvbi50YXJnZXRJZCkgOiBudWxsO1xyXG4gICAgICAgIGlmIChhY3RvckVsKSB7XHJcbiAgICAgICAgICAgIC8vIETDqXRlcm1pbmVyIGxlIHLDtGxlIGR1IHBlcnNvbm5hZ2UgcXVpIGFnaXRcclxuICAgICAgICAgICAgY29uc3QgYWN0b3JSb2xlID0gZ2V0Q2hhcmFjdGVyUm9sZShhY3Rpb24uYWN0b3JJZCk7XHJcbiAgICAgICAgICAgIGFjdG9yRWwuY2xhc3NMaXN0LmFkZCgnYWN0aW5nLScgKyBhY3RvclJvbGUpO1xyXG4gICAgICAgIH1cclxuICAgICAgICBpZiAodGFyZ2V0RWwpIHRhcmdldEVsLmNsYXNzTGlzdC5hZGQoJ3RhcmdldGVkJyk7XHJcbiAgICAgICAgaWYgKGFjdGlvbi5raW5kID09PSAnSEVBTCcpIHtcclxuICAgICAgICAgICAgaWYgKGFjdG9yRWwpIGFjdG9yRWwuY2xhc3NMaXN0LmFkZCgnaGVhbCcpO1xyXG4gICAgICAgICAgICBpZiAodGFyZ2V0RWwpIHRhcmdldEVsLmNsYXNzTGlzdC5hZGQoJ2hlYWwnKTtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIHVwZGF0ZSBIUCBkaXNwbGF5IGluY3JlbWVudGFsbHlcclxuICAgICAgICBpZiAoYWN0aW9uLmtpbmQgPT09ICdBVFRBQ0snKSB7XHJcbiAgICAgICAgICAgIGNvbnN0IHRpZCA9IGFjdGlvbi50YXJnZXRJZDtcclxuICAgICAgICAgICAgaWYgKHR5cGVvZiBsb2NhbEhwW3RpZF0gIT09ICd1bmRlZmluZWQnKSB7XHJcbiAgICAgICAgICAgICAgICBsb2NhbEhwW3RpZF0gPSBNYXRoLm1heCgwLCBsb2NhbEhwW3RpZF0gLSBhY3Rpb24uYW1vdW50KTtcclxuICAgICAgICAgICAgICAgIGNvbnN0IGhwRWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaHAtJyArIHRpZCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoaHBFbCkgaHBFbC50ZXh0Q29udGVudCA9IGxvY2FsSHBbdGlkXTtcclxuICAgICAgICAgICAgICAgIGNvbnN0IGhwQmFyRWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnaHAtYmFyLScgKyB0aWQpO1xyXG4gICAgICAgICAgICAgICAgaWYgKGhwQmFyRWwpIHtcclxuICAgICAgICAgICAgICAgICAgICBjb25zdCBtYXhIcCA9IHVuaXRNYXhIcFt0aWRdIHx8IDk5OTk7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgaHBQZXJjZW50ID0gTWF0aC5tYXgoMCwgKGxvY2FsSHBbdGlkXSAvIG1heEhwKSAqIDEwMCk7XHJcbiAgICAgICAgICAgICAgICAgICAgaHBCYXJFbC5zdHlsZS53aWR0aCA9IGhwUGVyY2VudCArICclJztcclxuICAgICAgICAgICAgICAgICAgICBcclxuICAgICAgICAgICAgICAgICAgICAvLyBBam91dGVyIGNsYXNzZSBsb3ctaHAgc2kgZW4gZGVzc291cyBkZSAzMCVcclxuICAgICAgICAgICAgICAgICAgICBpZiAoaHBQZXJjZW50IDwgMzApIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaHBCYXJFbC5jbGFzc0xpc3QuYWRkKCdsb3ctaHAnKTtcclxuICAgICAgICAgICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgICAgICAgICBocEJhckVsLmNsYXNzTGlzdC5yZW1vdmUoJ2xvdy1ocCcpO1xyXG4gICAgICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIGNvbnN0IGJveCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjaGFyLScgKyB0aWQpO1xyXG4gICAgICAgICAgICAgICAgaWYgKGJveCAmJiBsb2NhbEhwW3RpZF0gPD0gMCkgYm94LmNsYXNzTGlzdC5hZGQoJ2RlYWQnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH0gZWxzZSBpZiAoYWN0aW9uLmtpbmQgPT09ICdIRUFMJykge1xyXG4gICAgICAgICAgICBjb25zdCB0aWQgPSBhY3Rpb24udGFyZ2V0SWQ7XHJcbiAgICAgICAgICAgIGlmICh0eXBlb2YgbG9jYWxIcFt0aWRdICE9PSAndW5kZWZpbmVkJykge1xyXG4gICAgICAgICAgICAgICAgY29uc3QgbWF4SHAgPSB1bml0TWF4SHBbdGlkXSB8fCA5OTk5O1xyXG4gICAgICAgICAgICAgICAgbG9jYWxIcFt0aWRdID0gTWF0aC5taW4obWF4SHAsIGxvY2FsSHBbdGlkXSArIGFjdGlvbi5hbW91bnQpO1xyXG4gICAgICAgICAgICAgICAgY29uc3QgaHBFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdocC0nICsgdGlkKTtcclxuICAgICAgICAgICAgICAgIGlmIChocEVsKSBocEVsLnRleHRDb250ZW50ID0gbG9jYWxIcFt0aWRdO1xyXG4gICAgICAgICAgICAgICAgY29uc3QgaHBCYXJFbCA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdocC1iYXItJyArIHRpZCk7XHJcbiAgICAgICAgICAgICAgICBpZiAoaHBCYXJFbCkge1xyXG4gICAgICAgICAgICAgICAgICAgIGNvbnN0IGhwUGVyY2VudCA9IE1hdGgubWF4KDAsIChsb2NhbEhwW3RpZF0gLyBtYXhIcCkgKiAxMDApO1xyXG4gICAgICAgICAgICAgICAgICAgIGhwQmFyRWwuc3R5bGUud2lkdGggPSBocFBlcmNlbnQgKyAnJSc7XHJcbiAgICAgICAgICAgICAgICAgICAgXHJcbiAgICAgICAgICAgICAgICAgICAgLy8gQWpvdXRlciBjbGFzc2UgbG93LWhwIHNpIGVuIGRlc3NvdXMgZGUgMzAlXHJcbiAgICAgICAgICAgICAgICAgICAgaWYgKGhwUGVyY2VudCA8IDMwKSB7XHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGhwQmFyRWwuY2xhc3NMaXN0LmFkZCgnbG93LWhwJyk7XHJcbiAgICAgICAgICAgICAgICAgICAgfSBlbHNlIHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgaHBCYXJFbC5jbGFzc0xpc3QucmVtb3ZlKCdsb3ctaHAnKTtcclxuICAgICAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICB9XHJcbiAgICAgICAgICAgICAgICBjb25zdCBib3ggPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2hhci0nICsgdGlkKTtcclxuICAgICAgICAgICAgICAgIGlmIChib3ggJiYgbG9jYWxIcFt0aWRdID4gMCkgYm94LmNsYXNzTGlzdC5yZW1vdmUoJ2RlYWQnKTtcclxuICAgICAgICAgICAgfVxyXG4gICAgICAgIH1cclxuXHJcbiAgICAgICAgLy8gYXBwZW5kIGNvcnJlc3BvbmRpbmcgZXZlbnQgaWYgYXZhaWxhYmxlXHJcbiAgICAgICAgY29uc3QgbG9nRGl2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhdHRsZS1sb2cnKTtcclxuICAgICAgICBpZiAobG9nRGl2ICYmIGN1cnJlbnRFdmVudHNbY3VycmVudEFjdGlvbklkeF0pIHtcclxuICAgICAgICAgICAgY29uc3QgcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgICAgICAgICBwLnRleHRDb250ZW50ID0gY3VycmVudEV2ZW50c1tjdXJyZW50QWN0aW9uSWR4XTtcclxuICAgICAgICAgICAgbG9nRGl2LmFwcGVuZENoaWxkKHApO1xyXG4gICAgICAgICAgICBsb2dEaXYuc2Nyb2xsVG9wID0gbG9nRGl2LnNjcm9sbEhlaWdodDtcclxuICAgICAgICB9XHJcblxyXG4gICAgICAgIC8vIHNjaGVkdWxlIGNsZWFudXAgZm9yIHRoaXMgYWN0aW9uXHJcbiAgICAgICAgY29uc3QgY2xlYW51cCA9IHNldFRpbWVvdXQoKCkgPT4ge1xyXG4gICAgICAgICAgICBpZiAoYWN0b3JFbCkge1xyXG4gICAgICAgICAgICAgICAgLy8gTmV0dG95ZXIgdG91dGVzIGxlcyBjbGFzc2VzIGQnYWN0aW9uIHBvc3NpYmxlc1xyXG4gICAgICAgICAgICAgICAgYWN0b3JFbC5jbGFzc0xpc3QucmVtb3ZlKCdhY3RpbmctdGFuaycsICdhY3RpbmctZHBzJywgJ2FjdGluZy1oZWFsJyk7XHJcbiAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgaWYgKHRhcmdldEVsKSB0YXJnZXRFbC5jbGFzc0xpc3QucmVtb3ZlKCd0YXJnZXRlZCcpO1xyXG4gICAgICAgICAgICBpZiAoYWN0b3JFbCkgYWN0b3JFbC5jbGFzc0xpc3QucmVtb3ZlKCdoZWFsJyk7XHJcbiAgICAgICAgICAgIGlmICh0YXJnZXRFbCkgdGFyZ2V0RWwuY2xhc3NMaXN0LnJlbW92ZSgnaGVhbCcpO1xyXG4gICAgICAgIH0sIDgwMCk7IC8vIEF1Z21lbnTDqSBsw6lnw6hyZW1lbnQgcG91ciBsYWlzc2VyIGxlIHRlbXBzIGF1eCBhbmltYXRpb25zIGRlIHNlIHRlcm1pbmVyXHJcbiAgICAgICAgcGxheVRpbWVvdXRzLnB1c2goY2xlYW51cCk7XHJcblxyXG4gICAgICAgIC8vIHNjaGVkdWxlIG5leHQgYWN0aW9uIGFmdGVyIDFzIChpZiBub3QgcGF1c2VkKVxyXG4gICAgICAgIGNvbnN0IG5leHRUaW1lciA9IHNldFRpbWVvdXQoKCkgPT4ge1xyXG4gICAgICAgICAgICBjdXJyZW50QWN0aW9uSWR4Kys7XHJcbiAgICAgICAgICAgIHByb2Nlc3NOZXh0QWN0aW9uKCk7XHJcbiAgICAgICAgfSwgMTAwMCk7XHJcbiAgICAgICAgcGxheVRpbWVvdXRzLnB1c2gobmV4dFRpbWVyKTtcclxuICAgIH1cclxuXHJcbiAgICAvLyBTdGFydCBwcm9jZXNzaW5nIGFjdGlvbnMgZm9yIHRoaXMgZnJhbWVcclxuICAgIHByb2Nlc3NOZXh0QWN0aW9uUmVmID0gcHJvY2Vzc05leHRBY3Rpb247XHJcbiAgICBwcm9jZXNzTmV4dEFjdGlvbigpO1xyXG59XHJcblxyXG4vLyBJbml0aWFsaXplIGJhdHRsZSB3aGVuIERPTSBpcyBsb2FkZWRcclxuZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignRE9NQ29udGVudExvYWRlZCcsIGZ1bmN0aW9uKCkge1xyXG4gICAgLy8gT25seSBpbml0aWFsaXplIGlmIHdlJ3JlIG9uIHRoZSBiYXR0bGUgcGFnZVxyXG4gICAgaWYgKGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdwbGF5LWJhdHRsZScpKSB7XHJcbiAgICAgICAgaW5pdEJhdHRsZURhdGEoKTtcclxuXHJcbiAgICAgICAgZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ3BsYXktYmF0dGxlJykuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgLy8gU3RvcCBhbnkgcHJldmlvdXMgcnVuXHJcbiAgICAgICAgICAgIGNsZWFyQWxsVGltZW91dHMoKTtcclxuICAgICAgICAgICAgcmVzZXRWaXN1YWxzKCk7XHJcbiAgICAgICAgICAgIGNvbnN0IGxvZ0RpdiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdiYXR0bGUtbG9nJyk7XHJcbiAgICAgICAgICAgIGlmIChsb2dEaXYpIGxvZ0Rpdi5pbm5lckhUTUwgPSAnJztcclxuICAgICAgICAgICAgY29uc3Qgd2lubmVyRGl2ID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQoJ2JhdHRsZS13aW5uZXInKTtcclxuICAgICAgICAgICAgaWYgKHdpbm5lckRpdikgd2lubmVyRGl2LnRleHRDb250ZW50ID0gJyc7XHJcbiAgICAgICAgICAgIGlzUGF1c2VkID0gZmFsc2U7XHJcbiAgICAgICAgICAgIHByb2Nlc3NOZXh0QWN0aW9uUmVmID0gbnVsbDtcclxuXHJcbiAgICAgICAgICAgIGlmICghZnJhbWVzIHx8IGZyYW1lcy5sZW5ndGggPT09IDApIHtcclxuICAgICAgICAgICAgICAgIGNvbnN0IGxvZ0RpdiA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdiYXR0bGUtbG9nJyk7XHJcbiAgICAgICAgICAgICAgICBpZiAobG9nRGl2KSB7XHJcbiAgICAgICAgICAgICAgICAgICAgY29uc3QgcCA9IGRvY3VtZW50LmNyZWF0ZUVsZW1lbnQoJ2RpdicpO1xyXG4gICAgICAgICAgICAgICAgICAgIHAudGV4dENvbnRlbnQgPSAnTm8gZnJhbWVzIGF2YWlsYWJsZS4nO1xyXG4gICAgICAgICAgICAgICAgICAgIGxvZ0Rpdi5hcHBlbmRDaGlsZChwKTtcclxuICAgICAgICAgICAgICAgIH1cclxuICAgICAgICAgICAgICAgIHJldHVybjtcclxuICAgICAgICAgICAgfVxyXG5cclxuICAgICAgICAgICAgcGxheUZyYW1lKDApO1xyXG4gICAgICAgIH0pO1xyXG5cclxuICAgICAgICBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgncGF1c2UtYmF0dGxlJykuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCBmdW5jdGlvbigpIHtcclxuICAgICAgICAgICAgaXNQYXVzZWQgPSAhaXNQYXVzZWQ7XHJcbiAgICAgICAgICAgIHRoaXMudGV4dENvbnRlbnQgPSBpc1BhdXNlZCA/ICdSZXByZW5kcmUnIDogJ1BhdXNlJztcclxuICAgICAgICAgICAgaWYgKCFpc1BhdXNlZCkge1xyXG4gICAgICAgICAgICAgICAgLy8gcmVzdW1lOiBjb250aW51ZSBwcm9jZXNzaW5nIGN1cnJlbnQgZnJhbWUvYWN0aW9uXHJcbiAgICAgICAgICAgICAgICBpZiAoY3VycmVudEZyYW1lSWR4IDwgKGZyYW1lcyA/IGZyYW1lcy5sZW5ndGggOiAwKSkge1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIGNsZWFyIGFueSBsaW5nZXJpbmcgdGltZW91dHMgYW5kIGNvbnRpbnVlXHJcbiAgICAgICAgICAgICAgICAgICAgY2xlYXJBbGxUaW1lb3V0cygpO1xyXG4gICAgICAgICAgICAgICAgICAgIC8vIHJlc3VtZSBhY3Rpb24gcHJvY2Vzc2luZyBhdCBjdXJyZW50QWN0aW9uSWR4XHJcbiAgICAgICAgICAgICAgICAgICAgLy8gc21hbGwgZGVsYXkgdG8gYWxsb3cgdmlzdWFscyB0byBzZXR0bGVcclxuICAgICAgICAgICAgICAgICAgICBzZXRUaW1lb3V0KCgpID0+IHtcclxuICAgICAgICAgICAgICAgICAgICAgICAgLy8gY29udGludWUgZnJvbSB0aGUgc3RvcmVkIHByb2Nlc3NOZXh0QWN0aW9uUmVmIHdoaWNoIHByZXNlcnZlcyBpbmRpY2VzXHJcbiAgICAgICAgICAgICAgICAgICAgICAgIGlmIChwcm9jZXNzTmV4dEFjdGlvblJlZikgcHJvY2Vzc05leHRBY3Rpb25SZWYoKTtcclxuICAgICAgICAgICAgICAgICAgICB9LCAxMjApO1xyXG4gICAgICAgICAgICAgICAgfVxyXG4gICAgICAgICAgICB9IGVsc2Uge1xyXG4gICAgICAgICAgICAgICAgLy8gcGF1c2VkOiBzdG9wIHNjaGVkdWxpbmcgZnV0dXJlIHRpbWVyc1xyXG4gICAgICAgICAgICAgICAgY2xlYXJBbGxUaW1lb3V0cygpO1xyXG4gICAgICAgICAgICB9XHJcbiAgICAgICAgfSk7XHJcbiAgICB9XHJcbn0pO1xyXG4iLCIvLyA9PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PVxyXG4vLyBTQ1JJUFRTIC0gV0FSUklPUiBBUkVOQSBUQVZFUk5FXHJcbi8vID09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09PT09XHJcblxyXG4vLyBGaWNoaWVyIHZpZMOpIC0gcGx1cyBkZSBtZW51IGJ1cmdlclxyXG5cclxuY29uc29sZS5sb2coJ++/vSBTY3JpcHRzIGNoYXJnw6lzIHNhbnMgbWVudSBidXJnZXInKTtcclxuXHJcbi8vIEJ1cmdlciBtZW51IHRvZ2dsZVxyXG5jb25zdCBidXJnZXIgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLmJ1cmdlclwiKTtcclxuY29uc3QgbmF2TGlua3MgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKFwiLm5hdi1saW5rc1wiKTtcclxuXHJcbmJ1cmdlci5hZGRFdmVudExpc3RlbmVyKFwiY2xpY2tcIiwgKCkgPT4ge1xyXG4gICAgbmF2TGlua3MuY2xhc3NMaXN0LnRvZ2dsZShcIm9wZW5cIik7XHJcblxyXG4gICAgLy8gQW5pbWF0aW9uIHNpbXBsZSBkdSBidXJnZXIg4oaSIGNyb2l4XHJcbiAgICBidXJnZXIuY2xhc3NMaXN0LnRvZ2dsZShcImFjdGl2ZVwiKTtcclxufSk7XHJcblxyXG5cclxuLy8gQ2hhcmFjdGVyIGltYWdlIG1vZGFsXHJcbndpbmRvdy5hZGRFdmVudExpc3RlbmVyKCdET01Db250ZW50TG9hZGVkJywgKCkgPT4ge1xyXG4gICAgY29uc3QgbW9kYWwgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2hhcmFjdGVySW1hZ2VNb2RhbCcpO1xyXG4gICAgY29uc3QgbW9kYWxJbWcgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnY2hhcmFjdGVySW1hZ2VNb2RhbEltZycpO1xyXG4gICAgY29uc3QgbW9kYWxDbG9zZSA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdjaGFyYWN0ZXJJbWFnZU1vZGFsQ2xvc2UnKTtcclxuXHJcbiAgICBmdW5jdGlvbiBvcGVuSW1hZ2VNb2RhbChzcmMsIGFsdCA9ICcnKSB7XHJcbiAgICAgICAgaWYgKCFtb2RhbCB8fCAhbW9kYWxJbWcpIHJldHVybjtcclxuICAgICAgICBtb2RhbEltZy5zcmMgPSBzcmM7XHJcbiAgICAgICAgbW9kYWxJbWcuYWx0ID0gYWx0O1xyXG4gICAgICAgIG1vZGFsLmNsYXNzTGlzdC5hZGQoJ2FjdGl2ZScpO1xyXG4gICAgICAgIGRvY3VtZW50LmJvZHkuY2xhc3NMaXN0LmFkZCgnbW9kYWwtb3BlbicpO1xyXG4gICAgfVxyXG5cclxuICAgIGZ1bmN0aW9uIGNsb3NlSW1hZ2VNb2RhbCgpIHtcclxuICAgICAgICBpZiAoIW1vZGFsKSByZXR1cm47XHJcbiAgICAgICAgbW9kYWwuY2xhc3NMaXN0LnJlbW92ZSgnYWN0aXZlJyk7XHJcbiAgICAgICAgZG9jdW1lbnQuYm9keS5jbGFzc0xpc3QucmVtb3ZlKCdtb2RhbC1vcGVuJyk7XHJcbiAgICAgICAgLy8gY2xlYXIgc3JjIHRvIHN0b3AgbGFyZ2UgaW1hZ2VzXHJcbiAgICAgICAgaWYgKG1vZGFsSW1nKSBtb2RhbEltZy5zcmMgPSAnJztcclxuICAgIH1cclxuXHJcbiAgICAvLyBEZWxlZ2F0ZSBjbGljayBvbiBjaGFyYWN0ZXIgYXZhdGFyc1xyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xyXG4gICAgICAgIGNvbnN0IHRhcmdldCA9IGUudGFyZ2V0O1xyXG4gICAgICAgIGlmICh0YXJnZXQgJiYgdGFyZ2V0LmNsYXNzTGlzdCAmJiB0YXJnZXQuY2xhc3NMaXN0LmNvbnRhaW5zKCdjaGFyYWN0ZXItYXZhdGFyJykpIHtcclxuICAgICAgICAgICAgLy8gaWYgaXQncyBhbiA8aW1nPlxyXG4gICAgICAgICAgICBjb25zdCBzcmMgPSB0YXJnZXQudGFnTmFtZSA9PT0gJ0lNRycgPyB0YXJnZXQuc3JjIDogbnVsbDtcclxuICAgICAgICAgICAgY29uc3QgYWx0ID0gdGFyZ2V0LmFsdCB8fCAnJztcclxuICAgICAgICAgICAgaWYgKHNyYykgb3BlbkltYWdlTW9kYWwoc3JjLCBhbHQpO1xyXG4gICAgICAgIH1cclxuICAgIH0pO1xyXG5cclxuICAgIGlmIChtb2RhbENsb3NlKSBtb2RhbENsb3NlLmFkZEV2ZW50TGlzdGVuZXIoJ2NsaWNrJywgY2xvc2VJbWFnZU1vZGFsKTtcclxuICAgIGlmIChtb2RhbCkgbW9kYWwuYWRkRXZlbnRMaXN0ZW5lcignY2xpY2snLCAoZSkgPT4ge1xyXG4gICAgICAgIGlmIChlLnRhcmdldCA9PT0gbW9kYWwpIGNsb3NlSW1hZ2VNb2RhbCgpO1xyXG4gICAgfSk7XHJcblxyXG4gICAgZG9jdW1lbnQuYWRkRXZlbnRMaXN0ZW5lcigna2V5ZG93bicsIChlKSA9PiB7XHJcbiAgICAgICAgaWYgKGUua2V5ID09PSAnRXNjYXBlJykgY2xvc2VJbWFnZU1vZGFsKCk7XHJcbiAgICB9KTtcclxufSk7XHJcbiIsIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpblxuZXhwb3J0IHt9OyJdLCJuYW1lcyI6WyJjb25zb2xlIiwibG9nIiwid2luZG93IiwiYWRkRXZlbnRMaXN0ZW5lciIsIm1vZGFsIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsIm1vZGFsSW1nIiwibW9kYWxDbG9zZSIsIm9wZW5JbWFnZU1vZGFsIiwic3JjIiwiYWx0IiwiYXJndW1lbnRzIiwibGVuZ3RoIiwidW5kZWZpbmVkIiwiY2xhc3NMaXN0IiwiYWRkIiwiYm9keSIsImNsb3NlSW1hZ2VNb2RhbCIsInJlbW92ZSIsImUiLCJpdGVtIiwidGFyZ2V0IiwiY2xvc2VzdCIsImltZ1NyYyIsImdldEF0dHJpYnV0ZSIsImltZ0VsIiwicXVlcnlTZWxlY3RvciIsImtleSIsInBsYXlUaW1lb3V0cyIsImlzUGF1c2VkIiwiY3VycmVudEZyYW1lSWR4IiwiY3VycmVudEFjdGlvbklkeCIsImN1cnJlbnRGcmFtZSIsImN1cnJlbnRBY3Rpb25zIiwiY3VycmVudEV2ZW50cyIsInByb2Nlc3NOZXh0QWN0aW9uUmVmIiwic2V0dXAiLCJmcmFtZXMiLCJ1bml0TWFwIiwidW5pdE93bmVycyIsInVuaXRNYXhIcCIsImluaXRCYXR0bGVEYXRhIiwic2V0dXBFbGVtZW50IiwiZnJhbWVzRWxlbWVudCIsInVuaXRNYXBFbGVtZW50IiwidW5pdE93bmVyc0VsZW1lbnQiLCJKU09OIiwicGFyc2UiLCJ0ZXh0Q29udGVudCIsImxlZnQiLCJmb3JFYWNoIiwidSIsImlkIiwibWF4SHAiLCJyaWdodCIsImdldENoYXJhY3RlclJvbGUiLCJjaGFyYWN0ZXJJZCIsImFsbENoYXJhY3RlcnMiLCJjb25jYXQiLCJfdG9Db25zdW1hYmxlQXJyYXkiLCJjaGFyYWN0ZXIiLCJmaW5kIiwiY2hhciIsImNoYXJEYXRhIiwiY29uc3RpdHV0aW9uIiwiYXR0YWNrIiwibWFnaWUiLCJjbGVhckFsbFRpbWVvdXRzIiwiY2xlYXJUaW1lb3V0IiwicmVzZXRWaXN1YWxzIiwicXVlcnlTZWxlY3RvckFsbCIsImVsIiwicmVzZXRUZW1wb3JhcnlWaXN1YWxzIiwic3luY0hwU25hcHNob3QiLCJocEJ5VW5pdCIsIk9iamVjdCIsImVudHJpZXMiLCJfcmVmIiwiX3JlZjIiLCJfc2xpY2VkVG9BcnJheSIsInVpZCIsImhwIiwiaHBCYXJFbCIsImhwUGVyY2VudCIsIk1hdGgiLCJtYXgiLCJzdHlsZSIsIndpZHRoIiwiYm94IiwicGxheUZyYW1lIiwiaWR4IiwiYmF0dGxlV2lubmVyRWxlbWVudCIsIndpbm5lckRhdGEiLCJkYXRhc2V0Iiwid2lubmVyIiwib3Bwb25lbnROYW1lIiwid2lubmVyVGV4dCIsImxvZ0RpdiIsInAiLCJjcmVhdGVFbGVtZW50IiwiYXBwZW5kQ2hpbGQiLCJmcmFtZSIsInJvdW5kIiwicmMiLCJyb3VuZEV2ZW50cyIsImV2Iiwic2Nyb2xsVG9wIiwic2Nyb2xsSGVpZ2h0IiwibG9jYWxIcCIsImtleXMiLCJwYXJzZUludCIsImFjdGlvbnMiLCJldmVudHMiLCJwcm9jZXNzTmV4dEFjdGlvbiIsInQiLCJzZXRUaW1lb3V0IiwicHVzaCIsImFjdGlvbiIsImFjdG9yRWwiLCJhY3RvcklkIiwidGFyZ2V0RWwiLCJ0YXJnZXRJZCIsImFjdG9yUm9sZSIsImtpbmQiLCJ0aWQiLCJhbW91bnQiLCJocEVsIiwibWluIiwiY2xlYW51cCIsIm5leHRUaW1lciIsImlubmVySFRNTCIsIndpbm5lckRpdiIsImJ1cmdlciIsIm5hdkxpbmtzIiwidG9nZ2xlIiwiY29udGFpbnMiLCJ0YWdOYW1lIl0sInNvdXJjZVJvb3QiOiIifQ==