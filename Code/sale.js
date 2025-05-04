document.addEventListener('DOMContentLoaded', () => {
  const salaSelect  = document.getElementById('sala');
  const form        = document.querySelector('.search-form');
  const grid        = document.querySelector('.seats-grid');
  const salaWrapper = document.getElementById('sala-wrapper');

  form.addEventListener('submit', e => {
    e.preventDefault();
    const id = salaSelect.value;
    if (!id) {
      alert('Seleziona una sala valida.');
      return;
    }

    fetch(`get_sala_data.php?id=${id}`)
      .then(res => {
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json();
      })
      .then(data => {
        renderGrid(data);
        salaWrapper.style.display = 'block';
      })
      .catch(err => {
        console.error('Errore nel caricamento sala:', err);
        alert('Errore nel caricamento della sala.');
      });
  });

  function renderGrid(room) {
    grid.innerHTML = '';
    grid.style.display = 'grid';
    grid.style.gridTemplateColumns = `repeat(${room.numPostiPerFila}, 1fr)`;
    grid.style.gap = '5px';

    for (let row = 0; row < room.numFile; row++) {
      for (let col = 0; col < room.numPostiPerFila; col++) {
        const seat = document.createElement('div');
        seat.className = 'seat available';
        seat.textContent = `${String.fromCharCode(65 + row)}${col + 1}`;
        grid.appendChild(seat);
      }
    }
  }
});
