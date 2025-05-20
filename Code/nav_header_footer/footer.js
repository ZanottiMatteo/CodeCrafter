document.addEventListener('DOMContentLoaded', function () {
  const form  = document.getElementById('newsletter-form');
  const email = document.getElementById('newsletter-email');

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const address = email.value.trim();
    if (!address) {
      // Alert di errore email vuota/invalid
      Swal.fire({
        icon: 'error',
        title: 'Email non valida',
        text: 'Per favore inserisci un indirizzo email valido.',
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
      });
      return;
    }

    fetch('subscribe_newsletter.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: address })
    })
    .then(res => res.json().then(body => ({ status: res.status, body })))
    .then(({ status, body }) => {
      if (status === 200 && body.status === 'ok') {
        // Conferma di successo
        Swal.fire({
          icon: 'success',
          title: 'Iscrizione avvenuta con successo',
          text: 'Grazie per esserti iscritto alla nostra newsletter!',
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
        form.reset();
      } else if (status === 400 && body.status === 'invalid_email') {
        // Alert email non valida dal server
        Swal.fire({
          icon: 'error',
          title: 'Email non valida',
          text: 'Controlla e riprova.',
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
      } else {
        // Errore generico
        Swal.fire({
          icon: 'error',
          title: 'Errore',
          text: 'Errore durante l\'iscrizione. Riprova più tardi.',
          toast: true,
          position: 'top-end',
          timer: 3000,
          showConfirmButton: false
        });
      }
    })
    .catch(err => {
      console.error('Network or parsing error:', err);
      Swal.fire({
        icon: 'error',
        title: 'Errore di rete',
        text: 'Impossibile contattare il server. Riprova più tardi.',
        toast: true,
        position: 'top-end',
        timer: 3000,
        showConfirmButton: false
      });
    });
  });
});
