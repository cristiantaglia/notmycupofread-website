<?php
/**
 * Template Name: Contatti
 * Template Post Type: page
 *
 * @package NMCOR
 */

get_header();
?>

<section class="hero hero--editorial" style="padding:3rem 0 2rem;">
	<div class="container" style="max-width:900px;">
		<span class="overline overline--teal"><?php esc_html_e('Scrivici', 'nmcor'); ?></span>
		<h1><?php esc_html_e('Contatti', 'nmcor'); ?></h1>
		<p class="hero__description"><?php esc_html_e('Per collaborazioni, proposte editoriali, inviti a eventi o semplicemente per salutarci.', 'nmcor'); ?></p>
	</div>
</section>

<section class="section section--surface">
	<div class="container" style="max-width:900px;">
		<div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;">
			<div>
				<?php
				while (have_posts()) : the_post();
					the_content();
				endwhile;
				?>

				<div style="margin-top:2rem;">
					<h3><?php esc_html_e('Contatto diretto', 'nmcor'); ?></h3>
					<p>
						<strong><?php esc_html_e('Email:', 'nmcor'); ?></strong>
						<a href="mailto:info@notmycupofread.it">info@notmycupofread.it</a>
					</p>
				</div>
			</div>

			<div>
				<div style="background:var(--c-surface-container);border-radius:var(--radius-lg);padding:2rem;">
					<h3 style="margin-top:0;"><?php esc_html_e('Collaborazioni', 'nmcor'); ?></h3>
					<p class="text-muted" style="font-size:0.9375rem;"><?php esc_html_e('Sei un editore, un organizzatore di eventi, un festival o un brand culturale? Scrivici per esplorare possibilità di collaborazione.', 'nmcor'); ?></p>

					<h4 style="margin-top:2rem;"><?php esc_html_e('Lavoriamo con', 'nmcor'); ?></h4>
					<ul style="padding-left:1.25rem;color:var(--c-on-surface-variant);font-size:0.9375rem;">
						<li><?php esc_html_e('Editori e case editrici', 'nmcor'); ?></li>
						<li><?php esc_html_e('Festival e fiere letterarie', 'nmcor'); ?></li>
						<li><?php esc_html_e('Librerie indipendenti', 'nmcor'); ?></li>
						<li><?php esc_html_e('Brand e sponsor culturali', 'nmcor'); ?></li>
						<li><?php esc_html_e('Autori e autrici', 'nmcor'); ?></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
