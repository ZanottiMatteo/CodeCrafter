document.addEventListener('DOMContentLoaded', () => {
    const grid = document.querySelector('.seats-grid');
    const rows = parseInt(grid.dataset.rows, 10);
    const cols = parseInt(grid.dataset.cols, 10);
    const occupiedSeats = window.SALA_CONFIG.occupiedSeats;
  
    generateSeats(rows, cols, occupiedSeats);
  });
  
  function generateSeats(rows, cols, occupiedSeats) {
    const grid = document.querySelector('.seats-grid');
    grid.innerHTML = '';
  
    for (let row = 0; row < rows; row++) {
      const rowDiv = document.createElement('div');
      rowDiv.className = 'seat-row';
  
      for (let col = 0; col < cols; col++) {
        const seatNumber = `${String.fromCharCode(65 + row)}${col + 1}`;
        const seat = createSeatElement(seatNumber, occupiedSeats);
        rowDiv.appendChild(seat);
      }
  
      grid.appendChild(rowDiv);
    }
  
    // (se vuoi) animazione d’ingresso
    animateElements('.seat', 'bounceIn');
  }
  
  function createSeatElement(seatNumber, occupiedSeats) {
    const seat = document.createElement('div');
    seat.classList.add('seat');
  
    if (occupiedSeats.includes(seatNumber)) {
      seat.classList.add('occupied');
    } else {
      seat.classList.add('available');
      seat.addEventListener('click', () => {
        seat.classList.toggle('selected');
      });
    }
  
    seat.textContent = seatNumber;
    return seat;
  }
  
  function getOccupiedSeats() {
    // se vuoi ricaricare dal server via fetch:
    // return fetch('/api/occupied.php?sala=1').then(r => r.json());
    return window.SALA_CONFIG.occupiedSeats;
  }
  
  function animateElements(selector, animationName) {
    document.querySelectorAll(selector).forEach(el => {
      el.style.opacity = '0';
      el.classList.add('animated', animationName);
      el.addEventListener('animationend', () => {
        el.style.opacity = '';
        el.classList.remove('animated', animationName);
      }, { once: true });
    });
  }
  