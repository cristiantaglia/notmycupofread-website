/**
 * Navigation — Mobile menu, sticky header, search overlay.
 *
 * @package NMCOR
 */

document.addEventListener('DOMContentLoaded', () => {
	const header = document.getElementById('siteHeader');
	const menuToggle = document.getElementById('menuToggle');
	const primaryNav = document.getElementById('primaryNav');
	const searchInput = document.getElementById('headerSearchInput');
	const searchOverlay = document.getElementById('searchOverlay');
	const searchClose = document.getElementById('searchClose');

	// ─── Mobile menu toggle ──────────────────────────
	if (menuToggle && primaryNav) {
		menuToggle.addEventListener('click', () => {
			const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
			menuToggle.setAttribute('aria-expanded', !expanded);
			menuToggle.classList.toggle('active');
			primaryNav.classList.toggle('active');
		});
	}

	// ─── Sticky header shadow ────────────────────────
	if (header) {
		let lastScroll = 0;
		window.addEventListener('scroll', () => {
			const scrollY = window.scrollY;
			if (scrollY > 10) {
				header.classList.add('scrolled');
			} else {
				header.classList.remove('scrolled');
			}
			lastScroll = scrollY;
		}, { passive: true });
	}

	// ─── Search overlay ──────────────────────────────
	if (searchInput && searchOverlay) {
		searchInput.addEventListener('focus', (e) => {
			e.preventDefault();
			searchInput.blur();
			searchOverlay.classList.add('active');
			const overlayInput = searchOverlay.querySelector('input[type="search"]');
			if (overlayInput) {
				setTimeout(() => overlayInput.focus(), 100);
			}
		});
	}

	if (searchClose && searchOverlay) {
		searchClose.addEventListener('click', () => {
			searchOverlay.classList.remove('active');
		});

		// Close on Escape
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape' && searchOverlay.classList.contains('active')) {
				searchOverlay.classList.remove('active');
			}
		});

		// Close on overlay background click
		searchOverlay.addEventListener('click', (e) => {
			if (e.target === searchOverlay) {
				searchOverlay.classList.remove('active');
			}
		});
	}
});
