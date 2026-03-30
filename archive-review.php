<?php
/**
 * Archive: Recensioni.
 * Layout: hero scuro, filtri, riga featured + card con copertina, mini recensione, paginazione.
 *
 * @package NMCOR
 */

get_header();
?>

<!-- Hero -->
<section class="hero hero--editorial">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<span class="overline overline--teal"><?php esc_html_e("L'Archivio", 'nmcor'); ?></span>
		<div style="display:grid;grid-template-columns:1fr auto;gap:3rem;align-items:end;">
			<div>
				<h1 style="font-size:clamp(3rem,6vw,5rem);margin-bottom:0.75rem;"><?php esc_html_e('Recensioni', 'nmcor'); ?></h1>
				<p class="hero__description">
					<?php esc_html_e('Esplora la nostra collezione curata di analisi letterarie. Dalla narrativa contemporanea ai saggi di nicchia, ogni recensione è un viaggio nel cuore di una nuova storia.', 'nmcor'); ?>
				</p>
			</div>
			<div style="text-align:right;">
				<p style="margin:0;font-family:var(--font-headline);font-style:italic;color:var(--c-on-primary-container);font-size:1.0625rem;line-height:1.5;max-width:300px;">
					"Un libro è un sogno che tieni in mano."
				</p>
				<cite style="font-size:0.8125rem;font-style:normal;color:rgba(255,255,255,0.5);">— Neil Gaiman</cite>
			</div>
		</div>
	</div>
</section>

<!-- Filtri -->
<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:2rem 2rem 0;">
	<div style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
		<div class="term-pills" id="archiveFilters" data-post-type="review" style="margin-bottom:0;">
			<button class="term-pill active" data-filter="all"><?php esc_html_e('Tutti', 'nmcor'); ?></button>
			<?php
			$genres = get_terms(['taxonomy' => 'genre', 'hide_empty' => true]);
			if ($genres && !is_wp_error($genres)) :
				foreach ($genres as $genre) :
			?>
			<button class="term-pill" data-filter="<?php echo esc_attr($genre->slug); ?>" data-taxonomy="genre">
				<?php echo esc_html($genre->name); ?>
			</button>
			<?php endforeach; endif; ?>
		</div>
		<div style="display:flex;gap:0.75rem;align-items:center;">
			<span style="font-size:0.8125rem;color:var(--c-on-surface-variant);"><?php esc_html_e('Ordina per:', 'nmcor'); ?></span>
			<select class="review-sort">
				<option><?php esc_html_e('Più recenti', 'nmcor'); ?></option>
				<option><?php esc_html_e('Voto più alto', 'nmcor'); ?></option>
				<option><?php esc_html_e('Più letti', 'nmcor'); ?></option>
			</select>
		</div>
	</div>
</div>

<!-- Griglia recensioni -->
<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:2rem 2rem 4rem;">
	<?php if (have_posts()) : ?>
	<div class="review-grid-featured" id="archiveGrid">
		<?php
		$i = 0;
		while (have_posts()) : the_post();
			$i++;
			$rating = get_post_meta(get_the_ID(), 'nmcor_review_rating', true);
			$book = nmcor_get_review_book();
			$book_authors = $book ? nmcor_get_book_authors($book->ID) : [];
			$genres_list = get_the_terms(get_the_ID(), 'genre');
			$genre_name = ($genres_list && !is_wp_error($genres_list)) ? $genres_list[0]->name : '';
			$is_featured = ($i === 1);
		?>
		<article class="review-card<?php echo $is_featured ? ' review-card--featured' : ''; ?>">
			<div class="review-card__cover">
				<?php if ($book && has_post_thumbnail($book->ID)) : ?>
					<a href="<?php the_permalink(); ?>">
						<?php echo get_the_post_thumbnail($book->ID, 'nmcor-book-cover'); ?>
					</a>
				<?php elseif (has_post_thumbnail()) : ?>
					<a href="<?php the_permalink(); ?>">
						<?php the_post_thumbnail('nmcor-card-large'); ?>
					</a>
				<?php endif; ?>

				<?php if ($is_featured) : ?>
				<div class="review-card__badges">
					<span class="review-card__badge review-card__badge--featured"><?php esc_html_e('In primo piano', 'nmcor'); ?></span>
					<?php if ($rating) : ?>
					<span class="review-card__badge review-card__badge--vote"><?php printf('Voto: %s/5', esc_html(number_format((float) $rating, 1))); ?></span>
					<?php endif; ?>
				</div>
				<?php else : ?>
					<?php if ($genre_name) : ?>
					<span class="review-card__genre-badge"><?php echo esc_html($genre_name); ?></span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
			<div class="review-card__body">
				<?php if ($is_featured && $genre_name) : ?>
				<span class="review-card__genre"><?php echo esc_html($genre_name); ?></span>
				<?php endif; ?>
				<h3 class="review-card__title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
				<p class="review-card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), $is_featured ? 25 : 20); ?></p>
				<div class="review-card__meta">
					<?php if ($is_featured) : ?>
					<div class="review-card__reviewer">
						<div class="review-card__avatar">ED</div>
						<span>Editor's Pick</span> · <span><?php echo esc_html(get_the_date('j F Y')); ?></span>
					</div>
					<?php else : ?>
					<span class="review-card__reviewer-name">
						<?php
						if ($book_authors) {
							$names = array_map(fn($a) => $a->post_title, $book_authors);
							printf('Recensione di %s', esc_html(implode(', ', $names)));
						} else {
							printf('Recensione di %s', esc_html(get_the_author()));
						}
						?>
					</span>
					<?php if ($rating) : ?>
					<span class="review-card__vote"><?php printf('Voto: %s/5', esc_html(number_format((float) $rating, 1))); ?></span>
					<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
		</article>

		<?php
			// After first 3 posts, switch to secondary grid
			if ($i === 3) :
		?>
	</div>
	<div class="review-grid-secondary">
		<!-- Mini recensione statica -->
		<article class="review-mini">
			<span class="review-mini__label"><?php esc_html_e('Mini Recensione', 'nmcor'); ?></span>
			<blockquote class="review-mini__quote">
				<?php esc_html_e('"Una breve fuga dalla realtà, un libro che si legge in un pomeriggio ma resta per anni."', 'nmcor'); ?>
			</blockquote>
			<a href="#" class="review-mini__link"><?php esc_html_e('Leggi tutto', 'nmcor'); ?> <span class="material-symbols-outlined" style="font-size:1rem;vertical-align:-2px;">arrow_forward</span></a>
		</article>
		<?php endif; ?>
		<?php endwhile; ?>
	</div>
	<?php else : ?>
	<div class="no-results">
		<p><?php esc_html_e('Nessuna recensione trovata.', 'nmcor'); ?></p>
	</div>
	<?php endif; ?>

	<?php
	the_posts_pagination([
		'prev_text' => '&lsaquo;',
		'next_text' => '&rsaquo;',
		'class'     => 'archive-pagination',
	]);
	?>
</div>

<?php get_footer(); ?>
