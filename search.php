<?php
/**
 * Search Results template.
 *
 * @package NMCOR
 */

get_header();
?>

<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<section class="hero hero--editorial" style="padding:2.5rem 0 2rem;">
	<div class="container" style="max-width:1100px;">
		<span class="overline overline--teal"><?php esc_html_e('Risultati ricerca', 'nmcor'); ?></span>
		<h1>
			<?php printf(esc_html__('Risultati per: "%s"', 'nmcor'), get_search_query()); ?>
		</h1>
		<?php if (have_posts()) : ?>
		<p style="color:rgba(255,255,255,0.6);margin-bottom:0;">
			<?php
			printf(
				esc_html(_n('%d risultato trovato', '%d risultati trovati', (int) $wp_query->found_posts, 'nmcor')),
				(int) $wp_query->found_posts
			);
			?>
		</p>
		<?php endif; ?>
	</div>
</section>

<section class="section section--surface">
	<div class="container" style="max-width:1100px;">
		<?php if (have_posts()) : ?>
		<div class="grid grid--3">
			<?php while (have_posts()) : the_post(); ?>
				<?php
				$type = get_post_type();
				switch ($type) {
					case 'review':
						get_template_part('template-parts/cards/card', 'review');
						break;
					case 'podcast_episode':
						get_template_part('template-parts/cards/card', 'podcast');
						break;
					case 'nmcor_event':
						get_template_part('template-parts/cards/card', 'event');
						break;
					case 'book':
						get_template_part('template-parts/cards/card', 'book');
						break;
					case 'book_author':
						get_template_part('template-parts/cards/card', 'author');
						break;
					default:
						get_template_part('template-parts/cards/card', 'article');
						break;
				}
				?>
			<?php endwhile; ?>
		</div>

		<!-- Paginazione -->
		<div style="margin-top:var(--sp-12);text-align:center;">
			<?php
			the_posts_pagination([
				'mid_size'  => 2,
				'prev_text' => '<span class="material-symbols-outlined">arrow_back</span> ' . esc_html__('Precedente', 'nmcor'),
				'next_text' => esc_html__('Successiva', 'nmcor') . ' <span class="material-symbols-outlined">arrow_forward</span>',
			]);
			?>
		</div>

		<?php else : ?>
		<div style="text-align:center;padding:var(--sp-16) 0;">
			<span class="material-symbols-outlined" style="font-size:4rem;color:var(--c-outline);margin-bottom:1rem;display:block;">search_off</span>
			<h2><?php esc_html_e('Nessun risultato', 'nmcor'); ?></h2>
			<p class="lead" style="max-width:500px;margin:0 auto 2rem;">
				<?php esc_html_e('Non abbiamo trovato contenuti per la tua ricerca. Prova con termini diversi o esplora le nostre sezioni.', 'nmcor'); ?>
			</p>
			<div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
				<a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>" class="btn btn--teal btn--small">
					<?php esc_html_e('Recensioni', 'nmcor'); ?>
				</a>
				<a href="<?php echo esc_url(home_url('/podcast/')); ?>" class="btn btn--outline btn--small">
					<?php esc_html_e('Podcast', 'nmcor'); ?>
				</a>
				<a href="<?php echo esc_url(home_url('/approfondimenti/')); ?>" class="btn btn--outline btn--small">
					<?php esc_html_e('Approfondimenti', 'nmcor'); ?>
				</a>
			</div>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
