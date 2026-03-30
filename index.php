<?php
/**
 * Index: template di fallback.
 *
 * Mostra gli ultimi contenuti pubblicati in un layout a griglia.
 *
 * @package NMCOR
 */

get_header();
?>

<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<section class="section section--surface">
	<div class="container" style="max-width:1100px;">
		<header class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Tutti i contenuti', 'nmcor'); ?></span>
				<h1><?php esc_html_e('Archivio', 'nmcor'); ?></h1>
			</div>
		</header>

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
			<p class="lead"><?php esc_html_e('Nessun contenuto trovato.', 'nmcor'); ?></p>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
