document.addEventListener('DOMContentLoaded', function () {
  // Configurazioni
  const SEAT_TYPES = {
    VIP: { price: 18, class: 'vip', seats: ['A1', 'A2', 'A7', 'A8'] },
    STANDARD: { price: 12, class: 'standard' }
  };

  const PROMO_CODES = {
    'CINEMA20': 20,
    'PREMIUM35': 35,
    'FESTA10': 10
  };

  // Stato prenotazione
  let bookingState = {
    selectedMovie: null,
    selectedTime: null,
    selectedSeats: [],
    totalPrice: 0,
    discountApplied: 0
  };

  // Elementi UI
  const uiElements = {
    movieCards: document.querySelectorAll('.movie-card'),
    steps: document.querySelectorAll('.step'),
    timeSlots: document.querySelectorAll('.time-slot'),
    seatsGrid: document.querySelector('.seats-grid'),
    seatsList: document.querySelector('.selected-seats'),
    totalElement: document.getElementById('total'),
    promoInput: document.querySelector('.promo-section input')
  };

  // Animazioni iniziali
  animateElements('.movie-card', 'zoomIn');
  animateElements('.time-slot', 'fadeIn');

  // Gestione film
  uiElements.movieCards.forEach(card => {
    card.querySelector('.select-movie').addEventListener('click', () => {
      uiElements.movieCards.forEach(c => c.classList.remove('selected'));
      card.classList.add('selected', 'pulse');
      bookingState.selectedMovie = card.querySelector('h3').textContent;
      updateSteps(1);
    });
  });

  // Gestione orari
  uiElements.timeSlots.forEach(slot => {
    slot.addEventListener('click', () => {
      uiElements.timeSlots.forEach(t => t.classList.remove('selected'));
      slot.classList.add('selected', 'rubberBand');
      bookingState.selectedTime = slot.textContent;
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
      if (bookingState.totalPrice == 0) {
        updateSteps(2);
      }
    }

    updateCart();
  }

  // Funzioni di supporto
  function updateCart() {
    uiElements.seatsList.innerHTML = bookingState.selectedSeats
      .map(seat => `<li>Posto ${seat.number} (€${seat.price})</li>`)
      .join('');

    uiElements.totalElement.textContent = (bookingState.totalPrice * (1 - bookingState.discountApplied / 100)).toFixed(2);
  }

  function getOccupiedSeats() {
    // Esempio: recupera posti occupati dal server o usa valori fissi
    return [];
  }


  // Gestione promozioni
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

  // Conferma prenotazione
  document.querySelector('.checkout-button').addEventListener('click', () => {
    if (!bookingState.selectedMovie || !bookingState.selectedTime || !bookingState.selectedSeats.length) {
      showCustomAlert('warning', 'Completa tutti i passaggi!', 'Seleziona film, orario e posti');
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
      cancelButtonText: 'Annulla',
      customClass: {
        popup: 'animated zoomIn'
      }
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: 'Prenotazione Confermata! 🎉',
          html: 'Riceverai una mail di conferma<br>Buona visione!',
          icon: 'success',
          timer: 4000,
          timerProgressBar: true,
          customClass: {
            popup: 'animated tada'
          }
        });
        resetBooking();
      }
    });
  });

  // Funzioni animazione
  function animateElements(selector, animation) {
    document.querySelectorAll(selector).forEach(el => {
      el.style.animation = `${animation} 0.5s ease-out`;
      el.addEventListener('animationend', () => el.style.animation = '');
    });
  }

  function showCustomAlert(icon, title, text = '') {
    Swal.fire({
      icon: icon,
      title: title,
      text: text,
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000,
      timerProgressBar: true
    });
  }


  function resetBooking() {
    bookingState = {
      selectedMovie: null,
      selectedTime: null,
      selectedSeats: [],
      totalPrice: 0,
      discountApplied: 0
    };

    document.querySelectorAll('.selected').forEach(el => el.classList.remove('selected'));
    uiElements.totalElement.textContent = '0.00';
    uiElements.seatsList.innerHTML = '';
    generateSeats();
    updateSteps(0);
  }

  function updateSteps(currentStep) {
    uiElements.steps.forEach((step, index) => {
      step.classList.toggle('active', index <= currentStep);
      step.style.transform = index === currentStep ? 'scale(1.3)' : 'scale(1)';
    });
  }

  // Inizializzazione
  generateSeats();
});