<?php
/**
 * 404 Page template.
 *
 * @package NMCOR
 */

get_header();
?>

<section class="hero hero--editorial" style="padding:var(--sp-16) 0;">
	<div class="container" style="max-width:1100px;text-align:center;">
		<span class="overline overline--teal"><?php esc_html_e('Errore 404', 'nmcor'); ?></span>
		<h1 style="font-size:clamp(5rem, 12vw, 10rem);margin-bottom:0.5rem;opacity:0.3;">404</h1>
		<h2 style="color:#fff;margin-bottom:1rem;"><?php esc_html_e('Pagina non trovata', 'nmcor'); ?></h2>
		<p style="color:rgba(255,255,255,0.7);max-width:500px;margin:0 auto 2rem;font-size:1.0625rem;line-height:1.7;">
			<?php esc_html_e('La pagina che stai cercando non esiste o potrebbe essere stata spostata. Torna alla home o esplora i nostri contenuti.', 'nmcor'); ?>
		</p>
		<div style="display:flex;gap:1rem;justify-content:center;flex-wrap:wrap;">
			<a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn--teal">
				<span class="material-symbols-outlined" style="font-size:1.125rem;">home</span>
				<?php esc_html_e('Torna alla home', 'nmcor'); ?>
			</a>
			<a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>" class="btn btn--outline" style="color:#fff;border-color:rgba(255,255,255,0.3);">
				<?php esc_html_e('Leggi le recensioni', 'nmcor'); ?>
			</a>
		</div>
	</div>
</section>

<!-- Contenuti suggeriti -->
<section class="section section--surface">
	<div class="container" style="max-width:1100px;">
		<div class="section-header section-header--center">
			<div style="text-align:center;">
				<span class="overline text-tertiary"><?php esc_html_e('Mentre sei qui', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Esplora i nostri contenuti', 'nmcor'); ?></h2>
			</div>
		</div>

		<?php
		$latest = nmcor_get_latest('review', 3);
		if ($latest) :
		?>
		<div class="grid grid--3">
			<?php foreach ($latest as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'review'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<?php get_footer(); ?>
