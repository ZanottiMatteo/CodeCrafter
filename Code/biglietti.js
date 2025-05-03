document.addEventListener('DOMContentLoaded', function () {

  const SEAT_TYPES = {
    VIP: { price: 18, class: 'vip', seats: ['A1', 'A2', 'A7', 'A8'] },
    STANDARD: { price: 12, class: 'standard' }
  };

  const PROMO_CODES = {
    'CINEMA20': 20,
    'PREMIUM35': 35,
    'FESTA10': 10
  };

  let bookingState = {
    selectedMovie: document.querySelector('.selected-movie h3').textContent,
    selectedTime: null,
    selectedSeats: [],
    totalPrice: 0,
    discountApplied: 0
  };

  const uiElements = {
    steps: document.querySelectorAll('.step'),
    timeSlots: document.querySelectorAll('.time-slot'),
    seatsGrid: document.querySelector('.seats-grid'),
    seatsList: document.querySelector('.selected-seats'),
    totalElement: document.getElementById('total'),
    promoInput: document.querySelector('.promo-section input')
  };

  animateElements('.time-slot', 'fadeIn');

  uiElements.timeSlots.forEach(slot => {
    slot.addEventListener('click', () => {
      uiElements.timeSlots.forEach(t => t.classList.remove('selected'));
      slot.classList.add('selected', 'rubberBand');
      bookingState.selectedTime = slot.getAttribute('data-time');
      generateSeats();
      updateSteps(2);
    });
  });

  function generateSeats() {
    uiElements.seatsGrid.innerHTML = '';
    const rows = 20, cols = 10;
    const occupiedSeats = getOccupiedSeats();

    for (let row = 0; row < rows; row++) {
      const rowDiv = document.createElement('div');
      rowDiv.className = 'seat-row';

      for (let col = 0; col < cols; col++) {
        const seatNumber = `${String.fromCharCode(65 + row)}${col + 1}`;
        const seat = createSeatElement(seatNumber, occupiedSeats);
        rowDiv.appendChild(seat);
      }
      uiElements.seatsGrid.appendChild(rowDiv);
    }
    animateElements('.seat', 'bounceIn');
  }

  function createSeatElement(seatNumber, occupiedSeats) {
    const seat = document.createElement('div');
    const isVIP = SEAT_TYPES.VIP.seats.includes(seatNumber);
    const seatType = isVIP ? SEAT_TYPES.VIP : SEAT_TYPES.STANDARD;

    seat.className = `seat ${seatType.class} ${occupiedSeats.includes(seatNumber) ? 'occupied' : 'available'}`;
    seat.textContent = seatNumber;
    seat.title = `Posto ${seatType.class.toUpperCase()} - €${seatType.price}`;

    if (!seat.classList.contains('occupied')) {
      seat.addEventListener('click', () => toggleSeatSelection(seat, seatNumber, seatType.price));
    }

    return seat;
  }

  function toggleSeatSelection(seat, seatNumber, price) {
    seat.classList.toggle('selected');
    seat.classList.add('wobble');

    if (seat.classList.contains('selected')) {
      bookingState.selectedSeats.push({ number: seatNumber, price: price });
      bookingState.totalPrice += price;
      updateSteps(3);
    } else {
      bookingState.selectedSeats = bookingState.selectedSeats.filter(s => s.number !== seatNumber);
      bookingState.totalPrice -= price;
      if (bookingState.totalPrice === 0) updateSteps(2);
    }

    updateCart();
  }

  function updateCart() {
    uiElements.seatsList.innerHTML = bookingState.selectedSeats
      .map(seat => `<li>Posto ${seat.number} (€${seat.price})</li>`)
      .join('');

    uiElements.totalElement.textContent = (bookingState.totalPrice * (1 - bookingState.discountApplied / 100)).toFixed(2);
  }

  function getOccupiedSeats() {
    return [];
  }

  document.querySelector('.apply-promo').addEventListener('click', () => {
    const promoCode = uiElements.promoInput.value.toUpperCase();
    const discount = PROMO_CODES[promoCode] || 0;

    if (discount > 0) {
      bookingState.discountApplied = discount;
      updateCart();
      showCustomAlert('success', `Sconto ${discount}% applicato!`);
      animateElements('.promo-section', 'heartBeat');
    } else {
      showCustomAlert('error', 'Codice promozionale non valido');
    }
  });

  document.querySelector('.checkout-button').addEventListener('click', () => {
    if (!bookingState.selectedTime || !bookingState.selectedSeats.length) {
      showCustomAlert('warning', 'Completa tutti i passaggi!', 'Seleziona orario e posti');
      return;
    }

    const confirmMessage = `📽️ Film: ${bookingState.selectedMovie}<br>
                            ⏰ Orario: ${bookingState.selectedTime}<br>
                            💺 Posti: ${bookingState.selectedSeats.map(s => s.number).join(', ')}<br>
                            💰 Totale: €${(bookingState.totalPrice * (1 - bookingState.discountApplied / 100)).toFixed(2)}`;

    Swal.fire({
      title: 'Confermare la prenotazione?',
      html: confirmMessage,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Conferma',
      cancelButtonText: 'Annulla'
    }).then(result => {
      if (result.isConfirmed) {
        Swal.fire('Prenotazione Confermata! 🎉', 'Riceverai una mail di conferma<br>Buona visione!', 'success');
        resetBooking();
      }
    });
  });

  function animateElements(selector, animation) {
    document.querySelectorAll(selector).forEach(el => {
      el.style.animation = `${animation} 0.5s ease-out`;
      el.addEventListener('animationend', () => el.style.animation = '');
    });
  }

  function showCustomAlert(icon, title, text = '') {
    Swal.fire({ icon, title, text, toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
  }

  function resetBooking() {
    bookingState.selectedTime = null;
    bookingState.selectedSeats = [];
    bookingState.totalPrice = 0;
    bookingState.discountApplied = 0;
    uiElements.totalElement.textContent = '0.00';
    uiElements.seatsList.innerHTML = '';
    generateSeats();
    updateSteps(1);
  }

  function updateSteps(step) {
    uiElements.steps.forEach((el, idx) => el.classList.toggle('active', idx <= step));
  }

  generateSeats();
});
