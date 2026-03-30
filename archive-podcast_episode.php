<?php
/**
 * Archive: Episodi Podcast.
 *
 * @package NMCOR
 */

get_header();
?>

<section class="hero hero--editorial">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<span class="overline overline--teal"><?php esc_html_e('Archivio', 'nmcor'); ?></span>
		<h1><?php esc_html_e('Tutti gli episodi', 'nmcor'); ?></h1>
		<p class="hero__description"><?php esc_html_e('Ogni episodio è un viaggio nella letteratura: analisi, interviste, letture e conversazioni.', 'nmcor'); ?></p>
	</div>
</section>

<!-- Filtri format -->
<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:2rem 2rem 0;">
	<div class="term-pills" id="archiveFilters" data-post-type="podcast_episode">
		<button class="term-pill active" data-filter="all"><?php esc_html_e('Tutti', 'nmcor'); ?></button>
		<?php
		$formats = get_terms(['taxonomy' => 'podcast_format', 'hide_empty' => true]);
		if ($formats && !is_wp_error($formats)) :
			foreach ($formats as $format) :
		?>
		<button class="term-pill" data-filter="<?php echo esc_attr($format->slug); ?>" data-taxonomy="podcast_format">
			<?php echo esc_html($format->name); ?>
		</button>
		<?php endforeach; endif; ?>
	</div>
</div>

<div class="container--wide archive-content" style="max-width:80rem;margin:0 auto;padding:0 2rem 4rem;">
	<div class="archive-grid archive-grid--3" id="archiveGrid">
		<?php
		if (have_posts()) :
			while (have_posts()) : the_post();
				get_template_part('template-parts/cards/card', 'podcast');
			endwhile;
		else :
		?>
		<div class="no-results">
			<p><?php esc_html_e('Nessun episodio trovato.', 'nmcor'); ?></p>
		</div>
		<?php endif; ?>
	</div>

	<?php the_posts_pagination(['prev_text' => '&larr;', 'next_text' => '&rarr;']); ?>
</div>

<?php get_footer(); ?>
