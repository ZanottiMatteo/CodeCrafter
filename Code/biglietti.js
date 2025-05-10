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

  const bookingState = {
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

  const preselectedTime = new URLSearchParams(window.location.search).get('orario');
  if (preselectedTime) {
    uiElements.timeSlots.forEach(slot => {
      const shortTime = slot.getAttribute('data-time')?.slice(0, 5);
      if (shortTime === preselectedTime.slice(0, 5)) {
        slot.classList.add('selected');
        bookingState.selectedTime = shortTime;
        generateSeats();
        updateSteps(2);
      }
    });
  }

  uiElements.timeSlots.forEach(slot => {
    slot.addEventListener('click', () => {
      uiElements.timeSlots.forEach(t => t.classList.remove('selected'));
      slot.classList.add('selected', 'rubberBand');
      const fullTime = slot.getAttribute('data-time');
      bookingState.selectedTime = fullTime.slice(0, 5);
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
    const isVIP = SEAT_TYPES.VIP.seats.includes(seatNumber);
    const seatType = isVIP ? SEAT_TYPES.VIP : SEAT_TYPES.STANDARD;
    const seat = document.createElement('div');

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
      bookingState.selectedSeats.push({ number: seatNumber, price });
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

    const discountedTotal = bookingState.totalPrice * (1 - bookingState.discountApplied / 100);
    uiElements.totalElement.textContent = discountedTotal.toFixed(2);
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
    if (!bookingState.selectedTime || bookingState.selectedSeats.length === 0) {
      showCustomAlert('warning', 'Completa tutti i passaggi!', 'Seleziona orario e posti');
      return;
    }

    const filmTitle = document.querySelector('.movie-title span')?.textContent.trim() || '';
    const confirmMessage = `📽️ Film: ${filmTitle}<br>⏰ Orario: ${bookingState.selectedTime}<br>💺 Posti: ${bookingState.selectedSeats.map(s => s.number).join(', ')}<br>💰 Totale: €${(bookingState.totalPrice * (1 - bookingState.discountApplied / 100)).toFixed(2)}`;

    Swal.fire({
      title: 'Confermare la prenotazione?',
      html: confirmMessage,
      icon: 'question',
      showCancelButton: true,
      confirmButtonText: 'Conferma',
      cancelButtonText: 'Annulla'
    }).then(result => {
      if (!result.isConfirmed) return;

      if (typeof userMail !== 'undefined' && userMail) {
        inviaMail(userMail, filmTitle);
      } else {
        Swal.fire({
          title: 'Inserisci la tua email',
          input: 'email',
          inputLabel: 'Riceverai il riepilogo della prenotazione',
          inputPlaceholder: 'email@example.com',
          showCancelButton: true,
          confirmButtonText: 'Invia'
        }).then(input => {
          if (input.isConfirmed && input.value) {
            inviaMail(input.value, filmTitle);
          }
        });
      }
    });
  });

  function inviaMail(email, film) {
    const payload = {
      email,
      film,
      orario: bookingState.selectedTime,
      posti: bookingState.selectedSeats.map(s => s.number).join(', '),
      totale: (bookingState.totalPrice * (1 - bookingState.discountApplied / 100)).toFixed(2)
    };

    fetch('send_ticket_email.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    }).then(res => res.json())
      .then(data => {
        if (data.status === 'ok') {
          Swal.fire({
            title: 'Prenotazione Confermata! 🎉',
            html: 'Riceverai una mail di conferma<br>Buona visione!',
            icon: 'success',
            timer: 3000,
            showConfirmButton: false,
            willClose: () => {
              localStorage.removeItem('bigliettiAttivo');
              navigator.sendBeacon('clear_session.php');
              window.location.href = 'index.php';
            }
          });
        } else {
          showCustomAlert('error', 'Errore nell’invio della mail');
        }
      });
  }

  function animateElements(selector, animation) {
    document.querySelectorAll(selector).forEach(el => {
      el.style.animation = `${animation} 0.5s ease-out`;
      el.addEventListener('animationend', () => el.style.animation = '');
    });
  }

  function showCustomAlert(icon, title, text = '') {
    Swal.fire({ icon, title, text, toast: true, position: 'top-end', timer: 3000, showConfirmButton: false });
  }

  function updateSteps(step) {
    uiElements.steps.forEach((el, idx) => {
      el.classList.toggle('active', idx <= step);
      el.classList.toggle('completed', idx < step);
    });
  }
  generateSeats();
});