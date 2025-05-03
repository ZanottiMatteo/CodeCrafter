document.addEventListener('DOMContentLoaded', () => {
  const salaSelect = document.getElementById('sala');
  const form       = document.querySelector('.search-form');
  const grid       = document.querySelector('.seats-grid');
  let rooms = [];

  // 1) prendi via AJAX tutte le sale dal PHP
  fetch('get_sala_data.php')
    .then(res => {
      if (!res.ok) throw new Error(`HTTP ${res.status}`);
      return res.json();
    })
    .then(data => {
      rooms = data;
      populateDropdown();
    })
    .catch(err => {
      console.error('Errore caricamento dati sale:', err);
      alert('Impossibile caricare le sale. Riprova più tardi.');
    });

  // 2) ricostruisci le <option> in modo pulito
  function populateDropdown() {
    // resetta: mantieni solo la prima placeholder
    salaSelect.innerHTML = '<option value="">Seleziona una sala</option>';
    rooms.forEach(r => {
      const opt = document.createElement('option');
      opt.value = r.id;                                   // es. "1"
      opt.textContent = `Sala ${r.id} – ${r.name}`;      // es. "Sala 1 – Tradizionale"
      salaSelect.appendChild(opt);
    });
  }

  // 3) al submit, verifica e disegna
  form.addEventListener('submit', e => {
    e.preventDefault();
    const id = parseInt(salaSelect.value, 10);
    if (!id) {
      alert('Per favore seleziona una sala dal menu.');
      return;
    }
    const room = rooms.find(r => r.id === id);
    if (!room) {
      alert('Sala selezionata non valida.');
      return;
    }
    renderGrid(room);
  });

  // 4) disegna la griglia in base a numFile e numPostiPerFila
  function renderGrid(room) {
    grid.innerHTML = '';
    for (let row = 0; row < room.numFile; row++) {
      const rowEl = document.createElement('div');
      rowEl.className = 'seat-row';
      for (let col = 0; col < room.numPostiPerFila; col++) {
        const seatDiv = document.createElement('div');
        seatDiv.className = 'seat available';
        seatDiv.textContent = `${String.fromCharCode(65 + row)}${col + 1}`;
        rowEl.appendChild(seatDiv);
      }
      grid.appendChild(rowEl);
    }
  }
});
