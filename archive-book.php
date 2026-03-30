<?php
/**
 * Archive: Libri.
 *
 * @package NMCOR
 */

get_header();
?>

<section class="hero hero--editorial">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<span class="overline overline--teal"><?php esc_html_e('Esplora', 'nmcor'); ?></span>
		<h1><?php esc_html_e('Libri', 'nmcor'); ?></h1>
		<p class="hero__description"><?php esc_html_e('L\'archivio completo dei libri trattati nel progetto: recensiti, analizzati, discussi nel podcast.', 'nmcor'); ?></p>
	</div>
</section>

<!-- Filtri -->
<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:2rem 2rem 0;">
	<div class="term-pills" id="archiveFilters" data-post-type="book">
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
</div>

<div class="container--wide archive-content" style="max-width:80rem;margin:0 auto;padding:0 2rem 4rem;">
	<div class="archive-grid archive-grid--4" id="archiveGrid">
		<?php
		if (have_posts()) :
			while (have_posts()) : the_post();
				get_template_part('template-parts/cards/card', 'book');
			endwhile;
		else :
		?>
		<div class="no-results">
			<p><?php esc_html_e('Nessun libro trovato.', 'nmcor'); ?></p>
		</div>
		<?php endif; ?>
	</div>

	<?php the_posts_pagination(['prev_text' => '&larr;', 'next_text' => '&rarr;']); ?>
</div>

<?php get_footer(); ?>
