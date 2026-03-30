/**
 * Archive Filters — AJAX filtering and "Load More" functionality.
 *
 * @package NMCOR
 */

document.addEventListener('DOMContentLoaded', () => {

	// ─── Term pill filters ───────────────────────────
	const filterContainers = document.querySelectorAll('.term-pills');

	filterContainers.forEach(container => {
		const postType = container.dataset.postType;
		const grid = document.getElementById('archiveGrid');
		if (!grid) return;

		container.addEventListener('click', (e) => {
			const pill = e.target.closest('.term-pill');
			if (!pill) return;

			// Update active state
			container.querySelectorAll('.term-pill').forEach(p => p.classList.remove('active'));
			pill.classList.add('active');

			const filter = pill.dataset.filter;
			const taxonomy = pill.dataset.taxonomy || '';

			if (filter === 'all') {
				// Reload default
				fetchPosts(postType, '', '', grid);
			} else {
				fetchPosts(postType, taxonomy, filter, grid);
			}
		});
	});

	// ─── Load More button ────────────────────────────
	const loadMoreBtn = document.getElementById('loadMore');
	if (loadMoreBtn) {
		loadMoreBtn.addEventListener('click', () => {
			const postType = loadMoreBtn.dataset.postType;
			const page = parseInt(loadMoreBtn.dataset.page, 10);
			const grid = document.getElementById('archiveGrid');
			if (!grid) return;

			loadMoreBtn.disabled = true;
			loadMoreBtn.textContent = 'Caricamento...';

			const formData = new FormData();
			formData.append('action', 'nmcor_load_more');
			formData.append('nonce', nmcorAjax.nonce);
			formData.append('post_type', postType);
			formData.append('paged', page);

			fetch(nmcorAjax.ajaxUrl, {
				method: 'POST',
				body: formData,
			})
				.then(res => res.json())
				.then(data => {
					if (data.success && data.data.html) {
						grid.insertAdjacentHTML('beforeend', data.data.html);
						loadMoreBtn.dataset.page = page + 1;
						loadMoreBtn.disabled = false;
						loadMoreBtn.textContent = 'Carica altri';

						if (!data.data.has_more) {
							loadMoreBtn.style.display = 'none';
						}
					} else {
						loadMoreBtn.style.display = 'none';
					}
				})
				.catch(() => {
					loadMoreBtn.disabled = false;
					loadMoreBtn.textContent = 'Carica altri';
				});
		});
	}

	/**
	 * Fetch filtered posts via AJAX.
	 */
	function fetchPosts(postType, taxonomy, term, grid) {
		grid.style.opacity = '0.5';
		grid.style.transition = 'opacity 0.3s ease';

		const formData = new FormData();
		formData.append('action', 'nmcor_filter_posts');
		formData.append('nonce', nmcorAjax.nonce);
		formData.append('post_type', postType);
		formData.append('taxonomy', taxonomy);
		formData.append('term', term);

		fetch(nmcorAjax.ajaxUrl, {
			method: 'POST',
			body: formData,
		})
			.then(res => res.json())
			.then(data => {
				if (data.success) {
					grid.innerHTML = data.data.html;
				}
				grid.style.opacity = '1';
			})
			.catch(() => {
				grid.style.opacity = '1';
			});
	}
});
