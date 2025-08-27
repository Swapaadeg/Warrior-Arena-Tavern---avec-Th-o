// /assets/matchmaking.js
(function () {
  const cfgEl = document.getElementById('mm-config');
  if (!cfgEl) return;

  const joinUrl   = cfgEl.dataset.joinUrl;
  const cancelUrl = cfgEl.dataset.cancelUrl;
  const readyTpl  = cfgEl.dataset.readyUrlTemplate;
  const statusTpl = cfgEl.dataset.statusUrlTemplate;
  const launchTpl = cfgEl.dataset.launchUrlTemplate;
  const battleAction = cfgEl.dataset.battleAction;

  const waitingEl = document.getElementById('mm-waiting');
  const statusEl  = document.getElementById('mm-status');
  const playBtn   = document.getElementById('play-suggested');
  const cancelBtn = document.getElementById('cancel-mm');
  const oppInput  = document.getElementById('opponentId');
  const form      = document.getElementById('battle-form');

  // Lobby
  const lobbySection      = document.getElementById('lobby-section');
  const searchingSection  = document.getElementById('searching-section');
  const opponentNameEl    = document.getElementById('opponent-name');
  const readyStatusEl     = document.getElementById('ready-status');
  const readyBtn          = document.getElementById('ready-btn');
  const launchBtn         = document.getElementById('launch-btn');
  const cancelMatchBtn    = document.getElementById('cancel-match');

  let stopped = false;
  let currentMatchId = null;
  let youReady = false;
  let oppReady = false;
  let youClickedLaunch = false;
  let oppClickedLaunch = false;
  let statusInterval = null;

function urlFromTpl(tpl, id) {
  return tpl.replace(/\/0(\/|$)/, '/' + id + '$1');
}


  function showLobby(matchData) {
    searchingSection && (searchingSection.style.display = 'none');
    lobbySection && (lobbySection.style.display = 'block');
    if (opponentNameEl) opponentNameEl.textContent = matchData.opponent.username;
    currentMatchId = matchData.matchId;
    tickStatus(); // immediate
    statusInterval = setInterval(tickStatus, 2000);
  }

  function hideLobby() {
    lobbySection && (lobbySection.style.display = 'none');
    searchingSection && (searchingSection.style.display = 'block');
    if (statusInterval) { clearInterval(statusInterval); statusInterval = null; }
  }

  function updateReadyUI() {
    if (!readyStatusEl) return;

    // Ready section
    if (youReady && oppReady) {
      readyStatusEl.textContent = 'Both players are ready.';
      readyBtn && (readyBtn.style.display = 'none');
      // Launch section
      if (youClickedLaunch && oppClickedLaunch) {
        launchBtn && (launchBtn.disabled = true);
        readyStatusEl.textContent = 'Launching…';
      } else if (youClickedLaunch && !oppClickedLaunch) {
        launchBtn && (launchBtn.disabled = true);
        readyStatusEl.textContent = 'You clicked launch. Waiting for opponent…';
      } else if (!youClickedLaunch && oppClickedLaunch) {
        launchBtn && (launchBtn.disabled = false);
        readyStatusEl.textContent = 'Opponent clicked launch. Click "Launch Battle" to start.';
      } else {
        launchBtn && (launchBtn.disabled = false);
      }
      launchBtn && (launchBtn.style.display = 'block');
    } else if (youReady && !oppReady) {
      readyStatusEl.textContent = 'You are ready. Waiting for opponent…';
      readyBtn && (readyBtn.style.display = 'none');
      launchBtn && (launchBtn.style.display = 'none');
    } else if (!youReady && oppReady) {
      readyStatusEl.textContent = 'Opponent is ready. Confirm you are ready.';
      readyBtn && (readyBtn.style.display = 'block');
      launchBtn && (launchBtn.style.display = 'none');
    } else {
      readyStatusEl.textContent = 'Waiting for both players to be ready…';
      readyBtn && (readyBtn.style.display = 'block');
      launchBtn && (launchBtn.style.display = 'none');
    }
  }

  async function tickStatus() {
    if (!currentMatchId) return;
    try {
      const res = await fetch(url(statusTpl, currentMatchId), { headers:{'X-Requested-With':'XMLHttpRequest'}, credentials:'same-origin' });
      const data = await res.json();
      if (!data.success) return;

      youReady = !!data.youReady;
      oppReady = !!data.opponentReady;
      youClickedLaunch = !!data.youClickedLaunch;
      oppClickedLaunch = !!data.opponentClickedLaunch;

      if (data.state === 'launched' && data.opponentId) {
        // Backend confirmed both clicked launch
        oppInput && (oppInput.value = data.opponentId);
        form && form.submit();
        return;
      }
      updateReadyUI();
    } catch (e) {
      // silent
    }
  }

  async function pollJoin() {
    if (stopped || currentMatchId) return;
    try {
      const res = await fetch(joinUrl, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, credentials:'same-origin' });
      const data = await res.json();

      if (data.matched === true) {
        showLobby(data);
        return;
      }

      if (data.suggest_random_user) {
        waitingEl && (waitingEl.textContent = 'random');
        statusEl  && (statusEl.textContent = 'Aucun joueur connecté. Adversaire aléatoire disponible.');
        oppInput && (oppInput.value = data.suggest_random_user);
        playBtn  && (playBtn.style.display = '');
      } else {
        waitingEl && (waitingEl.textContent = 'searching…');
        statusEl  && (statusEl.textContent = "Recherche d'adversaire en cours…");
        playBtn  && (playBtn.style.display = 'none');
      }
    } catch (e) {
      statusEl && (statusEl.textContent = 'Erreur réseau, nouvelle tentative…');
    } finally {
      if (!stopped && !currentMatchId) setTimeout(pollJoin, 3000);
    }
  }

  // UI handlers
  cancelBtn && cancelBtn.addEventListener('click', async () => {
    stopped = true;
    try { await fetch(cancelUrl, { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, credentials:'same-origin' }); }
    finally {
      statusEl && (statusEl.textContent = 'Recherche annulée.');
      waitingEl && (waitingEl.textContent = '');
      playBtn  && (playBtn.style.display = 'none');
    }
  });

  playBtn && playBtn.addEventListener('click', () => { form && form.submit(); });

  readyBtn && readyBtn.addEventListener('click', async () => {
    if (!currentMatchId) return;
    try {
      const res = await fetch(url(readyTpl, currentMatchId), { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, credentials:'same-origin' });
      const data = await res.json();
      if (data.success) { youReady = true; updateReadyUI(); }
    } catch {}
  });

  launchBtn && launchBtn.addEventListener('click', async () => {
    if (!currentMatchId) return;
    launchBtn.disabled = true;
    try {
      const res = await fetch(url(launchTpl, currentMatchId), { method:'POST', headers:{'X-Requested-With':'XMLHttpRequest'}, credentials:'same-origin' });
      const data = await res.json();
      if (data.success === true && data.state === 'launched' && data.opponentId) {
        oppInput && (oppInput.value = data.opponentId);
        form && form.submit();
        return;
      }
      // Partial: you clicked launch, waiting for opponent
      youClickedLaunch = true;
      updateReadyUI();
    } catch {
      launchBtn.disabled = false;
    }
  });

  cancelMatchBtn && cancelMatchBtn.addEventListener('click', () => {
    hideLobby();
    currentMatchId = null;
    youReady = false; oppReady = false;
    youClickedLaunch = false; oppClickedLaunch = false;
    stopped = false;
    statusEl && (statusEl.textContent = 'Match annulé. Recherche...');
    waitingEl && (waitingEl.textContent = 'searching…');
    setTimeout(pollJoin, 1000);
  });

  // Start
  pollJoin();
})();
