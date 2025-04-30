document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.carousel-wrapper').forEach(wrapper => {
      const carousel = wrapper.querySelector('.movie-carousel');
      const prevBtn  = wrapper.querySelector('.carousel-btn.prev');
      const nextBtn  = wrapper.querySelector('.carousel-btn.next');
  
      const firstCard = carousel.querySelector('.movie-card');
      if (!firstCard) return;
  
      const cardWidth = firstCard.getBoundingClientRect().width;
      const style     = getComputedStyle(carousel);
      const gap       = parseFloat(style.gap || style.columnGap || 0);
      const scrollAmount = cardWidth + gap;
  
      console.log(`scrollAmount = ${scrollAmount}px`);
  
      prevBtn.addEventListener('click', () => {
        carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
      });
      nextBtn.addEventListener('click', () => {
        carousel.scrollBy({ left:  scrollAmount, behavior: 'smooth' });
      });
    });
  });
  