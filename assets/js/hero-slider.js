/**
 * Hero Slider — lightweight vanilla JS carousel.
 *
 * @package NMCOR
 */
(function () {
	'use strict';

	var slider = document.getElementById('heroSlider');
	if (!slider) return;

	var track = document.getElementById('heroTrack');
	var slides = track.children;
	var dots = slider.querySelectorAll('.hero-slider__dot');
	var prevBtn = slider.querySelector('.hero-slider__arrow--prev');
	var nextBtn = slider.querySelector('.hero-slider__arrow--next');
	var current = 0;
	var total = slides.length;
	var autoplayDelay = 6000;
	var timer;

	function goTo(index) {
		if (index < 0) index = total - 1;
		if (index >= total) index = 0;
		current = index;
		track.style.transform = 'translateX(-' + (current * 100) + '%)';
		dots.forEach(function (dot, i) {
			dot.classList.toggle('active', i === current);
		});
	}

	function next() { goTo(current + 1); }
	function prev() { goTo(current - 1); }

	function startAutoplay() {
		stopAutoplay();
		timer = setInterval(next, autoplayDelay);
	}

	function stopAutoplay() {
		if (timer) clearInterval(timer);
	}

	nextBtn.addEventListener('click', function () { next(); startAutoplay(); });
	prevBtn.addEventListener('click', function () { prev(); startAutoplay(); });

	dots.forEach(function (dot) {
		dot.addEventListener('click', function () {
			goTo(parseInt(dot.dataset.slide, 10));
			startAutoplay();
		});
	});

	// Pause on hover
	slider.addEventListener('mouseenter', stopAutoplay);
	slider.addEventListener('mouseleave', startAutoplay);

	// Touch swipe support
	var touchStartX = 0;
	slider.addEventListener('touchstart', function (e) {
		touchStartX = e.touches[0].clientX;
		stopAutoplay();
	}, { passive: true });

	slider.addEventListener('touchend', function (e) {
		var diff = touchStartX - e.changedTouches[0].clientX;
		if (Math.abs(diff) > 50) {
			diff > 0 ? next() : prev();
		}
		startAutoplay();
	}, { passive: true });

	startAutoplay();
})();
