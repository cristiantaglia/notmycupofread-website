<?php
/**
 * Header template.
 *
 * @package NMCOR
 */

defined('ABSPATH') || exit;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Newsreader:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php if (is_singular()) : ?>
<div class="reading-progress" id="readingProgress"></div>
<?php endif; ?>

<header class="site-header" id="siteHeader">
	<div class="header-outer">
		<div class="header-inner">
			<div class="site-branding">
				<?php if (has_custom_logo()) : ?>
					<?php the_custom_logo(); ?>
				<?php else : ?>
				<div class="site-branding__icon">
					<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
						<path d="M10 24V18a2 2 0 0 1 2-2h20a2 2 0 0 1 2 2v6c0 4.42-3.58 8-8 8H18c-4.42 0-8-3.58-8-8Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M34 19h2a3.5 3.5 0 0 1 3.5 3.5v0A3.5 3.5 0 0 1 36 26h-2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M8 36h28" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
						<path d="M22 14V6c0-.5-.4-1-1-1h-4.5c-1.1 0-2 .7-2 1.5V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M22 14V6c0-.5.4-1 1-1h4.5c1.1 0 2 .7 2 1.5V12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						<path d="M17 8h5" stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
						<path d="M17 10h4" stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
						<path d="M26 8h5" stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
						<path d="M26 10h4" stroke="currentColor" stroke-width="1" stroke-linecap="round" opacity="0.5"/>
						<circle cx="22" cy="23" r="2" stroke="currentColor" stroke-width="1.5"/>
						<path d="M22 25v2" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
						<path d="M20 27h4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
				</div>
				<?php endif; ?>
				<h1 class="site-title">
					<a href="<?php echo esc_url(home_url('/')); ?>">
						<?php bloginfo('name'); ?>
					</a>
				</h1>
			</div>

			<nav class="primary-nav" id="primaryNav" aria-label="<?php esc_attr_e('Menu principale', 'nmcor'); ?>">
				<?php
				wp_nav_menu([
					'theme_location' => 'primary',
					'container'      => false,
					'fallback_cb'    => false,
					'depth'          => 2,
				]);
				?>
			</nav>

			<div class="header-search">
				<input type="text" placeholder="<?php esc_attr_e('Cerca...', 'nmcor'); ?>" id="headerSearchInput" aria-label="<?php esc_attr_e('Cerca nel sito', 'nmcor'); ?>">
			</div>
		</div>

		<div class="header-extras">
			<div class="header-social">
				<?php
				$social_links = [
					'youtube'   => ['label' => 'YouTube',   'icon' => '<path d="M23.5 6.19a3 3 0 0 0-2.11-2.13C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.39.56A3 3 0 0 0 .5 6.19 31.4 31.4 0 0 0 0 12a31.4 31.4 0 0 0 .5 5.81 3 3 0 0 0 2.11 2.13c1.89.56 9.39.56 9.39.56s7.5 0 9.39-.56a3 3 0 0 0 2.11-2.13A31.4 31.4 0 0 0 24 12a31.4 31.4 0 0 0-.5-5.81ZM9.75 15.02V8.98L15.5 12l-5.75 3.02Z"/>'],
					'instagram' => ['label' => 'Instagram', 'icon' => '<path d="M12 2.16c3.2 0 3.58.01 4.85.07 1.17.05 1.97.24 2.44.41.61.24 1.05.52 1.51.98.46.46.74.9.98 1.51.17.47.36 1.27.41 2.44.06 1.27.07 1.65.07 4.85s-.01 3.58-.07 4.85c-.05 1.17-.24 1.97-.41 2.44a4.07 4.07 0 0 1-.98 1.51c-.46.46-.9.74-1.51.98-.47.17-1.27.36-2.44.41-1.27.06-1.65.07-4.85.07s-3.58-.01-4.85-.07c-1.17-.05-1.97-.24-2.44-.41a4.07 4.07 0 0 1-1.51-.98 4.07 4.07 0 0 1-.98-1.51c-.17-.47-.36-1.27-.41-2.44C2.17 15.58 2.16 15.2 2.16 12s.01-3.58.07-4.85c.05-1.17.24-1.97.41-2.44.24-.61.52-1.05.98-1.51a4.07 4.07 0 0 1 1.51-.98c.47-.17 1.27-.36 2.44-.41C8.42 2.17 8.8 2.16 12 2.16ZM12 0C8.74 0 8.33.01 7.05.07 5.78.13 4.9.33 4.14.63a5.85 5.85 0 0 0-2.13 1.38A5.85 5.85 0 0 0 .63 4.14C.33 4.9.13 5.78.07 7.05.01 8.33 0 8.74 0 12s.01 3.67.07 4.95c.06 1.27.26 2.15.56 2.91.31.79.72 1.46 1.38 2.13a5.85 5.85 0 0 0 2.13 1.38c.76.3 1.64.5 2.91.56C8.33 23.99 8.74 24 12 24s3.67-.01 4.95-.07c1.27-.06 2.15-.26 2.91-.56a5.85 5.85 0 0 0 2.13-1.38 5.85 5.85 0 0 0 1.38-2.13c.3-.76.5-1.64.56-2.91.06-1.28.07-1.69.07-4.95s-.01-3.67-.07-4.95c-.06-1.27-.26-2.15-.56-2.91a5.85 5.85 0 0 0-1.38-2.13A5.85 5.85 0 0 0 19.86.63C19.1.33 18.22.13 16.95.07 15.67.01 15.26 0 12 0Zm0 5.84a6.16 6.16 0 1 0 0 12.32 6.16 6.16 0 0 0 0-12.32ZM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8Zm6.41-11.85a1.44 1.44 0 1 0 0 2.88 1.44 1.44 0 0 0 0-2.88Z"/>'],
					'spotify'   => ['label' => 'Spotify',   'icon' => '<path d="M12 0a12 12 0 1 0 0 24 12 12 0 0 0 0-24Zm5.5 17.31a.75.75 0 0 1-1.03.25c-2.82-1.72-6.37-2.11-10.55-1.16a.75.75 0 1 1-.34-1.46c4.57-1.05 8.49-.6 11.67 1.34a.75.75 0 0 1 .25 1.03Zm1.47-3.27a.94.94 0 0 1-1.29.31c-3.23-1.98-8.15-2.56-11.97-1.4a.94.94 0 1 1-.54-1.8c4.36-1.32 9.78-.68 13.49 1.6a.94.94 0 0 1 .31 1.29Zm.13-3.4C15.5 8.4 9 8.2 5.24 9.33a1.13 1.13 0 1 1-.65-2.16c4.33-1.3 11.52-1.05 16.07 1.68a1.13 1.13 0 0 1-1.56 1.79Z"/>'],
					'x'         => ['label' => 'X',         'icon' => '<path d="M18.9 1.15h3.68l-8.04 9.19L24 22.85h-7.4l-5.8-7.58-6.63 7.58H.49l8.6-9.83L0 1.15h7.59l5.24 6.93 6.07-6.93Zm-1.29 19.5h2.04L6.48 3.24H4.3L17.61 20.65Z"/>'],
				];
				foreach ($social_links as $slug => $data) :
				?>
				<a href="#" class="header-social__link" aria-label="<?php echo esc_attr($data['label']); ?>">
					<svg viewBox="0 0 24 24"><?php echo $data['icon']; ?></svg>
				</a>
				<?php endforeach; ?>
			</div>
			<div class="header-auth">
				<?php if (is_user_logged_in()) : ?>
				<a href="<?php echo esc_url(get_edit_profile_url()); ?>" class="header-auth__btn">
					<span class="material-symbols-outlined">person</span>
					<?php echo esc_html(wp_get_current_user()->display_name); ?>
				</a>
				<?php else : ?>
				<a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>" class="header-auth__btn">
					<span class="material-symbols-outlined">person</span>
					<?php esc_html_e('Login', 'nmcor'); ?>
				</a>
				<div class="header-auth__dropdown">
					<a href="<?php echo esc_url(wp_login_url(get_permalink())); ?>">
						<span class="material-symbols-outlined">login</span>
						<?php esc_html_e('Area Personale', 'nmcor'); ?>
					</a>
					<a href="<?php echo esc_url(get_permalink(get_page_by_path('community'))); ?>">
						<span class="material-symbols-outlined">person_add</span>
						<?php esc_html_e('Registrati', 'nmcor'); ?>
					</a>
				</div>
				<?php endif; ?>
			</div>
			<button class="menu-toggle" id="menuToggle" aria-label="<?php esc_attr_e('Apri menu', 'nmcor'); ?>" aria-expanded="false">
				<span class="bar"></span>
				<span class="bar"></span>
				<span class="bar"></span>
			</button>
		</div>
	</div>
</header>

<!-- Search overlay -->
<div class="search-overlay" id="searchOverlay" role="dialog" aria-label="<?php esc_attr_e('Ricerca', 'nmcor'); ?>">
	<button class="search-overlay__close" id="searchClose" aria-label="<?php esc_attr_e('Chiudi ricerca', 'nmcor'); ?>">&times;</button>
	<div class="search-overlay__inner">
		<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
			<input type="search" name="s" placeholder="<?php esc_attr_e('Cerca articoli, libri, autori...', 'nmcor'); ?>" value="<?php echo get_search_query(); ?>">
		</form>
	</div>
</div>

<main id="main" class="site-main">
