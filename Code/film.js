document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.carousel-wrapper').forEach(wrapper => {
    const daySections = document.querySelectorAll('.day-section');
    daySections.forEach((section, index) => {
      section.style.setProperty('--day-index', index);
    });

    const carousel = wrapper.querySelector('.movie-carousel');
    const prevBtn = wrapper.querySelector('.carousel-btn.prev');
    const nextBtn = wrapper.querySelector('.carousel-btn.next');

    if (!carousel || !prevBtn || !nextBtn) return;

    const calculateScrollAmount = () => {
      const cards = carousel.querySelectorAll('.movie-card');
      if (cards.length === 0) return 0;

      const firstCard = cards[0];
      const style = window.getComputedStyle(carousel);
      const gap = parseFloat(style.gap) || 0;
      return firstCard.offsetWidth + gap;
    };

    let scrollAmount = calculateScrollAmount();

    window.addEventListener('resize', () => {
      scrollAmount = calculateScrollAmount();
    });

    const scrollCarousel = (direction) => {
      const currentScroll = carousel.scrollLeft;
      const maxScroll = carousel.scrollWidth - carousel.clientWidth;

      prevBtn.disabled = direction === -1 && currentScroll <= 0;
      nextBtn.disabled = direction === 1 && currentScroll >= maxScroll - 1;

      if (!(prevBtn.disabled || nextBtn.disabled)) {
        carousel.scrollBy({
          left: direction * scrollAmount,
          behavior: 'smooth'
        });
      }
    };

    prevBtn.addEventListener('click', () => scrollCarousel(-1));
    nextBtn.addEventListener('click', () => scrollCarousel(1));

    carousel.addEventListener('scroll', () => {
      const currentScroll = carousel.scrollLeft;
      const maxScroll = carousel.scrollWidth - carousel.clientWidth;

      prevBtn.disabled = currentScroll <= 0;
      nextBtn.disabled = currentScroll >= maxScroll - 1;
    }, { passive: true });

    carousel.dispatchEvent(new Event('scroll'));
  });
});