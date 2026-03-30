<?php
/**
 * Template Name: Community
 * Template Post Type: page
 *
 * @package NMCOR
 */

get_header();
?>

<section class="hero hero--editorial">
	<div class="container" style="max-width:900px;">
		<span class="overline overline--teal"><?php esc_html_e('Entra nel progetto', 'nmcor'); ?></span>
		<h1><?php esc_html_e('Community', 'nmcor'); ?></h1>
		<p class="hero__description"><?php esc_html_e('Not My Cup of Read non è solo un sito: è una community di lettori, ascoltatori e appassionati di letteratura.', 'nmcor'); ?></p>
	</div>
</section>

<section class="section section--surface">
	<div class="container" style="max-width:900px;">
		<?php
		while (have_posts()) : the_post();
			the_content();
		endwhile;
		?>
	</div>
</section>

<!-- Newsletter -->
<section class="section section--container">
	<div class="container" style="max-width:600px;">
		<div class="newsletter-block" style="margin:0;">
			<h3><?php esc_html_e('Newsletter', 'nmcor'); ?></h3>
			<p class="text-muted"><?php esc_html_e('Ricevi le nostre selezioni editoriali ogni settimana.', 'nmcor'); ?></p>
			<div class="newsletter-form">
				<input type="email" placeholder="<?php esc_attr_e('La tua email', 'nmcor'); ?>">
				<button class="btn btn--teal btn--small"><?php esc_html_e('Iscriviti', 'nmcor'); ?></button>
			</div>
		</div>
	</div>
</section>

<!-- Canali -->
<section class="section section--surface">
	<div class="container" style="max-width:700px;text-align:center;">
		<h2><?php esc_html_e('Dove trovarci', 'nmcor'); ?></h2>
		<div class="grid grid--3" style="margin-top:2rem;">
			<div class="format-card">
				<div style="font-size:2rem;color:var(--c-accent);margin-bottom:0.75rem;">
					<span class="material-symbols-outlined" style="font-size:2rem;">send</span>
				</div>
				<p class="format-card__title">Telegram</p>
				<p class="format-card__desc"><?php esc_html_e('Il gruppo per discussioni in tempo reale.', 'nmcor'); ?></p>
			</div>
			<div class="format-card">
				<div style="font-size:2rem;color:var(--c-accent);margin-bottom:0.75rem;">
					<span class="material-symbols-outlined" style="font-size:2rem;">photo_camera</span>
				</div>
				<p class="format-card__title">Instagram</p>
				<p class="format-card__desc"><?php esc_html_e('Contenuti visivi, citazioni e storie.', 'nmcor'); ?></p>
			</div>
			<div class="format-card">
				<div style="font-size:2rem;color:var(--c-accent);margin-bottom:0.75rem;">
					<span class="material-symbols-outlined" style="font-size:2rem;">podcasts</span>
				</div>
				<p class="format-card__title">Spotify</p>
				<p class="format-card__desc"><?php esc_html_e('Ascolta tutti gli episodi del podcast.', 'nmcor'); ?></p>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
