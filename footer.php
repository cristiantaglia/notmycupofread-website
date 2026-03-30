<?php
/**
 * Footer template.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;
?>
</main><!-- #main -->

<footer class="site-footer">
	<div class="container--wide" style="max-width: 80rem; margin: 0 auto; padding: 0 2rem;">
		<div class="footer-main">
			<div class="footer-brand">
				<p class="site-title"><?php bloginfo('name'); ?></p>
				<p><?php echo esc_html__('Esploriamo la letteratura contemporanea attraverso podcast, recensioni e approfondimenti. Non è solo lettura, è un rito.', 'nmcor'); ?></p>
				<div class="footer-social">
					<a href="#" aria-label="Spotify"><span class="material-symbols-outlined" style="font-size: 18px;">podcasts</span></a>
					<a href="#" aria-label="YouTube"><span class="material-symbols-outlined" style="font-size: 18px;">play_circle</span></a>
					<a href="#" aria-label="Instagram"><span class="material-symbols-outlined" style="font-size: 18px;">photo_camera</span></a>
					<a href="#" aria-label="Telegram"><span class="material-symbols-outlined" style="font-size: 18px;">send</span></a>
				</div>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e('Esplora', 'nmcor'); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>"><?php esc_html_e('Recensioni', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/approfondimenti/')); ?>"><?php esc_html_e('Approfondimenti', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/podcast/')); ?>"><?php esc_html_e('Podcast', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(get_post_type_archive_link('nmcor_event')); ?>"><?php esc_html_e('Eventi', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(get_post_type_archive_link('book')); ?>"><?php esc_html_e('Libri', 'nmcor'); ?></a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e('Progetto', 'nmcor'); ?></h4>
				<ul>
					<li><a href="<?php echo esc_url(home_url('/chi-siamo/')); ?>"><?php esc_html_e('Chi siamo', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/community/')); ?>"><?php esc_html_e('Community', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/contatti/')); ?>"><?php esc_html_e('Contatti', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/collaborazioni/')); ?>"><?php esc_html_e('Collaborazioni', 'nmcor'); ?></a></li>
					<li><a href="<?php echo esc_url(home_url('/supporta/')); ?>"><?php esc_html_e('Supporta il progetto', 'nmcor'); ?></a></li>
				</ul>
			</div>

			<div class="footer-col">
				<h4><?php esc_html_e('Newsletter', 'nmcor'); ?></h4>
				<p style="font-size: 0.875rem; margin-bottom: 1rem; opacity: 0.75;">
					<?php esc_html_e('Ricevi le nostre selezioni editoriali.', 'nmcor'); ?>
				</p>
				<div class="newsletter-form">
					<input type="email" placeholder="<?php esc_attr_e('La tua email', 'nmcor'); ?>">
					<button class="btn btn--teal btn--small"><?php esc_html_e('Iscriviti', 'nmcor'); ?></button>
				</div>
			</div>
		</div>

		<div class="footer-bottom">
			<span>&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('Editorial framework per chi ama leggere.', 'nmcor'); ?></span>
			<div class="footer-bottom-links">
				<a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('Privacy Policy', 'nmcor'); ?></a>
				<a href="<?php echo esc_url(home_url('/cookie-policy/')); ?>"><?php esc_html_e('Cookie Policy', 'nmcor'); ?></a>
				<a href="<?php echo esc_url(home_url('/contatti/')); ?>"><?php esc_html_e('Contatti', 'nmcor'); ?></a>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
