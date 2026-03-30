/**
 * Main JS — Reading progress, smooth scroll, scroll reveal.
 *
 * @package NMCOR
 */

document.addEventListener('DOMContentLoaded', function() {

	// ─── Smooth Page Transitions ────────────────────
	(function() {
		// Create transition overlay
		var transition = document.createElement('div');
		transition.className = 'page-transition';
		transition.innerHTML = '<div class="page-transition__overlay"></div>';
		document.body.appendChild(transition);

		// Fade in on page load
		document.body.classList.add('page-fade');

		// Intercept internal link clicks
		document.addEventListener('click', function(e) {
			var link = e.target.closest('a[href]');
			if (!link) return;

			var href = link.getAttribute('href');
			// Skip anchors, external links, javascript:, #
			if (!href) return;
			if (href.startsWith('#') || href.startsWith('javascript') || href.startsWith('mailto') || href.startsWith('http')) return;
			// Skip if modifier key held (new tab)
			if (e.metaKey || e.ctrlKey || e.shiftKey) return;
			// Skip if target="_blank"
			if (link.getAttribute('target') === '_blank') return;

			// Only internal demo pages
			if (!href.match(/^demo.*\.html/) && href !== 'index.html') return;

			e.preventDefault();

			// Trigger leave animation
			transition.classList.add('leaving');

			setTimeout(function() {
				window.location.href = href;
			}, 450);
		});

		// On back/forward navigation
		window.addEventListener('pageshow', function(e) {
			if (e.persisted) {
				transition.classList.remove('leaving');
				transition.classList.add('entering');
				document.body.classList.add('page-fade');
				setTimeout(function() {
					transition.classList.remove('entering');
				}, 500);
			}
		});
	})();

	// ─── Reading Progress Bar ────────────────────────
	var progressBar = document.getElementById('readingProgress');
	if (progressBar) {
		var updateProgress = function() {
			var scrollTop = window.scrollY;
			var docHeight = document.documentElement.scrollHeight - window.innerHeight;
			var progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
			progressBar.style.width = progress + '%';
		};
		window.addEventListener('scroll', updateProgress, { passive: true });
		updateProgress();
	}

	// ─── Sticky Header Shrink + Hide/Show ───────────
	(function() {
		var header = document.getElementById('siteHeader');
		if (!header) return;

		var shrinkThreshold = 80;  // px scrolled before shrinking
		var lastScrollY = 0;
		var hideThreshold = 300;   // px before hide-on-scroll-down kicks in
		var ticking = false;

		function onScroll() {
			var scrollY = window.scrollY;

			// Shrink
			if (scrollY > shrinkThreshold) {
				header.classList.add('header--shrunk');
			} else {
				header.classList.remove('header--shrunk');
			}

			// Hide on scroll down, show on scroll up
			if (scrollY > hideThreshold) {
				if (scrollY > lastScrollY + 5) {
					// Scrolling down
					header.classList.add('header--hidden');
				} else if (scrollY < lastScrollY - 5) {
					// Scrolling up
					header.classList.remove('header--hidden');
				}
			} else {
				header.classList.remove('header--hidden');
			}

			lastScrollY = scrollY;
			ticking = false;
		}

		window.addEventListener('scroll', function() {
			if (!ticking) {
				requestAnimationFrame(onScroll);
				ticking = true;
			}
		}, { passive: true });
	})();

	// ─── Smooth scroll for anchor links ──────────────
	document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
		anchor.addEventListener('click', function(e) {
			var target = document.querySelector(this.getAttribute('href'));
			if (target) {
				e.preventDefault();
				target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		});
	});

	// ─── Logo micro-interaction ─────────────────────
	(function() {
		var branding = document.querySelector('.site-branding');
		if (!branding) return;

		// Split title text into individual letter spans
		var titleLink = branding.querySelector('.site-title a');
		if (titleLink && !titleLink.querySelector('.letter')) {
			var text = titleLink.textContent;
			var html = '';
			for (var i = 0; i < text.length; i++) {
				if (text[i] === ' ') {
					html += '<span class="letter-space"> </span>';
				} else {
					html += '<span class="letter">' + text[i] + '</span>';
				}
			}
			titleLink.innerHTML = html;
		}

		// Prevent the <a href="#"> from navigating
		if (titleLink) {
			titleLink.addEventListener('click', function(e) {
				e.preventDefault();
			});
		}

		// Animate on click anywhere in branding area
		function animateLogo() {
			// Spin the logo icon
			var logo = branding.querySelector('.site-branding__icon img');
			if (logo) {
				logo.classList.remove('logo-spin');
				void logo.offsetWidth;
				logo.classList.add('logo-spin');
				logo.addEventListener('animationend', function() {
					logo.classList.remove('logo-spin');
				}, { once: true });
			}

			// Wave the letters with stagger
			var letters = branding.querySelectorAll('.site-title .letter');
			letters.forEach(function(letter, index) {
				letter.classList.remove('wave');
				void letter.offsetWidth;
				setTimeout(function() {
					letter.classList.add('wave');
					letter.addEventListener('animationend', function() {
						letter.classList.remove('wave');
					}, { once: true });
				}, index * 30);
			});
		}

		branding.addEventListener('click', function(e) {
			e.preventDefault();
			e.stopPropagation();
			animateLogo();

			// Navigate to homepage after animation
			setTimeout(function() {
				var href = titleLink ? titleLink.getAttribute('href') : '#';
				if (href && href !== '#') {
					window.location.href = href;
				} else {
					window.location.href = 'demo.html';
				}
			}, 700);
		});
	})();

	// ─── Podcast Waveform on Card Hover ────────────
	(function() {
		var podcastCards = document.querySelectorAll('.card--podcast-ep .card__image, .card--podcast .card__image');
		if (!podcastCards.length) return;

		podcastCards.forEach(function(imageContainer) {
			// Don't add twice
			if (imageContainer.querySelector('.podcast-waveform')) return;

			var waveform = document.createElement('div');
			waveform.className = 'podcast-waveform';

			// Generate random bars
			var barCount = 24;
			for (var i = 0; i < barCount; i++) {
				var bar = document.createElement('div');
				bar.className = 'podcast-waveform__bar';
				var h = 8 + Math.random() * 32;
				bar.style.height = h + 'px';
				bar.style.setProperty('--wave-speed', (0.4 + Math.random() * 0.6).toFixed(2) + 's');
				bar.style.animationDelay = (Math.random() * 0.8).toFixed(2) + 's';
				waveform.appendChild(bar);
			}

			// Play button
			var play = document.createElement('div');
			play.className = 'podcast-waveform__play';
			play.innerHTML = '<span class="material-symbols-outlined">play_arrow</span>';
			waveform.appendChild(play);

			imageContainer.appendChild(waveform);
		});
	})();

	// ─── Card 3D Tilt on Hover ─────────────────────
	(function() {
		// Only on non-touch devices
		if ('ontouchstart' in window) return;

		var cards = document.querySelectorAll('.card, .review-card');
		var maxTilt = 8; // degrees
		var perspective = 800; // px
		var glareOpacity = 0.08;

		cards.forEach(function(card) {
			// Add perspective wrapper style
			card.style.perspective = perspective + 'px';

			card.addEventListener('mousemove', function(e) {
				var rect = card.getBoundingClientRect();
				var x = (e.clientX - rect.left) / rect.width;  // 0 to 1
				var y = (e.clientY - rect.top) / rect.height;   // 0 to 1
				var rotateX = (0.5 - y) * maxTilt;
				var rotateY = (x - 0.5) * maxTilt;

				card.style.transform = 'perspective(' + perspective + 'px) rotateX(' + rotateX.toFixed(2) + 'deg) rotateY(' + rotateY.toFixed(2) + 'deg) translateY(-4px) scale(1.02)';

				// Light glare effect
				var glare = card.querySelector('.card-glare');
				if (glare) {
					glare.style.background = 'radial-gradient(circle at ' + (x * 100) + '% ' + (y * 100) + '%, rgba(255,255,255,' + glareOpacity + ') 0%, transparent 60%)';
				}
			});

			card.addEventListener('mouseleave', function() {
				card.style.transform = '';
			});

			// Add glare overlay element
			var glare = document.createElement('div');
			glare.className = 'card-glare';
			glare.style.cssText = 'position:absolute;inset:0;pointer-events:none;border-radius:inherit;z-index:10;transition:opacity 0.3s;';
			card.style.position = 'relative';
			card.appendChild(glare);
		});
	})();

	// ─── Magnetic Buttons ──────────────────────────
	(function() {
		if ('ontouchstart' in window) return;

		var selectors = '.btn, .header-trailer__btn, .breaker-cta .btn, .view-all, .footer-totop, .term-pill';
		var buttons = document.querySelectorAll(selectors);
		var magnetStrength = 0.35; // how much the button moves (0-1)
		var magnetRadius = 60; // px — distance at which the pull starts

		buttons.forEach(function(btn) {
			var parentRect, btnRect;

			btn.addEventListener('mouseenter', function() {
				btnRect = btn.getBoundingClientRect();
			});

			btn.addEventListener('mousemove', function(e) {
				if (!btnRect) btnRect = btn.getBoundingClientRect();
				var centerX = btnRect.left + btnRect.width / 2;
				var centerY = btnRect.top + btnRect.height / 2;
				var dx = e.clientX - centerX;
				var dy = e.clientY - centerY;

				var moveX = dx * magnetStrength;
				var moveY = dy * magnetStrength;

				btn.style.transform = 'translate(' + moveX.toFixed(1) + 'px, ' + moveY.toFixed(1) + 'px)';
				btn.style.transition = 'transform 0.15s ease-out';
			});

			btn.addEventListener('mouseleave', function() {
				btn.style.transform = '';
				btn.style.transition = 'transform 0.4s cubic-bezier(0.22, 1, 0.36, 1)';
				btnRect = null;
			});
		});
	})();

	// ─── Text Reveal — word-by-word on scroll ──────
	(function() {
		if (!('IntersectionObserver' in window)) return;

		// Target: breaker quotes, pullquotes, blockquotes in breaker sections
		var targets = document.querySelectorAll('.breaker-quote__text, .review-pullquote p, blockquote.text-reveal');
		if (!targets.length) return;

		targets.forEach(function(el) {
			// Split text into word spans
			var text = el.textContent.trim();
			var words = text.split(/\s+/);
			var html = '';
			words.forEach(function(word, i) {
				html += '<span class="word" style="transition-delay:' + (i * 0.04) + 's">' + word + '</span>';
				if (i < words.length - 1) html += '<span class="word-space"> </span>';
			});
			el.innerHTML = html;
			el.classList.add('text-reveal');
		});

		// Also prep cite elements for delayed fade-in
		var cites = document.querySelectorAll('.breaker-quote__author');
		cites.forEach(function(cite) {
			cite.classList.add('text-reveal-cite');
		});

		// Observe
		var revealTextObserver = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('text-revealed');

					// Also reveal the sibling cite after a delay
					var parent = entry.target.closest('.breaker-quote__inner, .review-pullquote');
					if (parent) {
						var cite = parent.querySelector('.breaker-quote__author, cite');
						if (cite && cite.classList.contains('text-reveal-cite')) {
							var wordCount = entry.target.querySelectorAll('.word').length;
							var delay = wordCount * 40 + 300; // after all words + buffer
							setTimeout(function() {
								cite.classList.add('text-revealed');
							}, delay);
						}
					}

					revealTextObserver.unobserve(entry.target);
				}
			});
		}, { threshold: 0.2 });

		targets.forEach(function(el) {
			revealTextObserver.observe(el);
		});
	})();

	// ─── Scroll Reveal ──────────────────────────────
	// Auto-tag elements for reveal: section headers, cards grids, individual blocks
	if ('IntersectionObserver' in window) {
		// Tag section headers
		document.querySelectorAll('.section-header, .section-header--stack, .section-header--center').forEach(function(el) {
			if (!el.classList.contains('reveal')) el.classList.add('reveal');
		});

		// Tag card grids for staggered reveal
		document.querySelectorAll('.grid, .archive-grid, .archive-grid--3, .archive-grid--4').forEach(function(el) {
			if (!el.classList.contains('reveal-stagger')) el.classList.add('reveal-stagger');
		});

		// Tag standalone blocks
		document.querySelectorAll('blockquote, .lead, .newsletter-block, .cta-block').forEach(function(el) {
			if (!el.classList.contains('reveal')) el.classList.add('reveal');
		});

		// Observe all reveal elements
		var revealObserver = new IntersectionObserver(function(entries) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('revealed');
					revealObserver.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.08,
			rootMargin: '0px 0px -40px 0px'
		});

		document.querySelectorAll('.reveal, .reveal-stagger').forEach(function(el) {
			revealObserver.observe(el);
		});
	}
});
