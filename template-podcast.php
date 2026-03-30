<?php
/**
 * Template Name: Podcast Landing
 * Template Post Type: page
 *
 * Pagina strategica del podcast con tutte le sezioni dal brief:
 * 1. Hero con claim
 * 2. Ultimo episodio
 * 3. Format del podcast
 * 4. Archivio episodi
 * 5. "Da dove iniziare"
 * 6. Libri del podcast
 * 7. Team / Host
 * 8. Ascoltaci su
 * 9. Community
 * 10. Supporta il progetto
 *
 * @package NMCOR
 */

get_header();
?>

<!-- ─── HERO ──────────────────────────────────────── -->
<section class="hero hero--podcast">
	<div class="hero__bg">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('nmcor-hero'); ?>
		<?php endif; ?>
	</div>
	<div style="position:relative;z-index:1;max-width:80rem;margin:0 auto;padding:5rem 2rem;">
		<div style="max-width:600px;">
			<span class="overline overline--teal"><?php esc_html_e('Il Podcast', 'nmcor'); ?></span>
			<h1 style="font-size:clamp(3rem,6vw,5rem);">
				<?php esc_html_e('Voci tra le Foglie', 'nmcor'); ?>
			</h1>
			<p class="hero__claim">
				<?php esc_html_e('Dove la letteratura prende voce. Analisi, conversazioni e letture per chi ama i libri oltre la superficie.', 'nmcor'); ?>
			</p>
			<div class="hero__actions">
				<?php
				$latest = nmcor_get_latest_episode();
				if ($latest) :
					$spotify = get_post_meta($latest->ID, 'nmcor_episode_spotify_url', true);
				?>
				<a href="<?php echo esc_url($spotify ?: '#'); ?>" class="btn btn--teal">
					<span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;">play_arrow</span>
					<?php esc_html_e('Ascolta ora', 'nmcor'); ?>
				</a>
				<?php endif; ?>
				<a href="#ascoltaci" class="btn btn--outline">
					<?php esc_html_e('Dove ascoltarci', 'nmcor'); ?>
				</a>
			</div>
		</div>
	</div>
</section>

<!-- ─── ULTIMO EPISODIO ───────────────────────────── -->
<?php if ($latest) : ?>
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline overline--teal"><?php esc_html_e('Appena uscito', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Ultimo Episodio', 'nmcor'); ?></h2>
			</div>
		</div>

		<div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;">
			<?php if (has_post_thumbnail($latest)) : ?>
			<div style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg);">
				<?php echo get_the_post_thumbnail($latest, 'nmcor-card-large'); ?>
			</div>
			<?php endif; ?>
			<div>
				<?php
				$ep_num = get_post_meta($latest->ID, 'nmcor_episode_number', true);
				$ep_dur = get_post_meta($latest->ID, 'nmcor_episode_duration', true);
				?>
				<?php if ($ep_num) : ?>
					<span class="overline text-tertiary"><?php printf(esc_html__('Episodio %s', 'nmcor'), esc_html($ep_num)); ?></span>
				<?php endif; ?>
				<h3 style="font-size:1.75rem;margin-bottom:1rem;">
					<?php echo esc_html($latest->post_title); ?>
				</h3>
				<?php if ($ep_dur) : ?>
					<p style="font-size:0.875rem;color:var(--c-on-surface-variant);margin-bottom:1rem;">
						<span class="material-symbols-outlined" style="font-size:1rem;vertical-align:-2px;">schedule</span>
						<?php echo esc_html($ep_dur); ?>
					</p>
				<?php endif; ?>
				<p style="color:var(--c-on-surface-variant);line-height:1.7;margin-bottom:2rem;">
					<?php echo esc_html(get_the_excerpt($latest)); ?>
				</p>
				<?php
				$embed = get_post_meta($latest->ID, 'nmcor_episode_embed_url', true);
				if ($embed) :
				?>
				<div style="margin-bottom:1.5rem;">
					<iframe src="<?php echo esc_url($embed); ?>" width="100%" height="152" frameborder="0" allow="autoplay; clipboard-write; encrypted-media" loading="lazy" style="border-radius:var(--radius-md);"></iframe>
				</div>
				<?php endif; ?>
				<a href="<?php echo esc_url(get_permalink($latest)); ?>" class="btn btn--tertiary">
					<?php esc_html_e('Leggi le note complete &rarr;', 'nmcor'); ?>
				</a>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ─── FORMAT DEL PODCAST ────────────────────────── -->
<section class="section section--container">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header section-header--center">
			<span class="overline text-tertiary"><?php esc_html_e('Come funziona', 'nmcor'); ?></span>
			<h2><?php esc_html_e('I nostri Formati', 'nmcor'); ?></h2>
			<p class="section-desc"><?php esc_html_e('Ogni format è pensato per esplorare la letteratura da una prospettiva diversa.', 'nmcor'); ?></p>
		</div>

		<div class="format-grid">
			<?php
			$formats = get_terms(['taxonomy' => 'podcast_format', 'hide_empty' => false]);
			if ($formats && !is_wp_error($formats)) :
				$icons = ['auto_stories', 'groups', 'psychology', 'bookmark'];
				$i = 0;
				foreach ($formats as $format) :
			?>
			<div class="format-card">
				<div style="font-size:2rem;color:var(--c-accent);margin-bottom:1rem;">
					<span class="material-symbols-outlined" style="font-size:2rem;"><?php echo esc_html($icons[$i % count($icons)]); ?></span>
				</div>
				<p class="format-card__title"><?php echo esc_html($format->name); ?></p>
				<p class="format-card__desc"><?php echo esc_html($format->description); ?></p>
			</div>
			<?php $i++; endforeach; endif; ?>
		</div>
	</div>
</section>

<!-- ─── ARCHIVIO EPISODI ──────────────────────────── -->
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Esplora', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Archivio Episodi', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(get_post_type_archive_link('podcast_episode')); ?>" class="view-all">
				<?php esc_html_e('Vedi tutti', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>

		<div class="grid grid--3">
			<?php
			$episodes = nmcor_get_latest('podcast_episode', 6);
			foreach ($episodes as $post) : setup_postdata($post);
				get_template_part('template-parts/cards/card', 'podcast');
			endforeach; wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<!-- ─── DA DOVE INIZIARE ──────────────────────────── -->
<?php
$featured_eps = nmcor_get_featured_episodes(3);
if ($featured_eps) :
?>
<section class="section section--container-low">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="start-here">
			<div class="section-header section-header--center" style="margin-bottom:2rem;">
				<span class="overline overline--teal"><?php esc_html_e('Non sai da dove partire?', 'nmcor'); ?></span>
				<h3><?php esc_html_e('Inizia da qui', 'nmcor'); ?></h3>
				<p class="section-desc"><?php esc_html_e('I nostri episodi più amati, perfetti per cominciare.', 'nmcor'); ?></p>
			</div>
			<div class="grid grid--3">
				<?php foreach ($featured_eps as $post) : setup_postdata($post);
					get_template_part('template-parts/cards/card', 'podcast');
				endforeach; wp_reset_postdata(); ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ─── LIBRI DEL PODCAST ─────────────────────────── -->
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('I libri di cui parliamo', 'nmcor'); ?></span>
				<h2><?php esc_html_e('La libreria del Podcast', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(get_post_type_archive_link('book')); ?>" class="view-all">
				<?php esc_html_e('Tutti i libri', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>
		<div class="grid grid--4" style="grid-template-columns:repeat(6,1fr);">
			<?php
			$books = nmcor_get_latest('book', 6);
			foreach ($books as $post) : setup_postdata($post);
				get_template_part('template-parts/cards/card', 'book');
			endforeach; wp_reset_postdata();
			?>
		</div>
	</div>
</section>

<!-- ─── TEAM / HOST ───────────────────────────────── -->
<section class="section section--container">
	<div class="container" style="max-width:900px;">
		<div class="section-header section-header--center">
			<span class="overline text-tertiary"><?php esc_html_e('Chi siamo', 'nmcor'); ?></span>
			<h2><?php esc_html_e('Le voci del Podcast', 'nmcor'); ?></h2>
		</div>
		<?php the_content(); ?>
	</div>
</section>

<!-- ─── ASCOLTACI SU ──────────────────────────────── -->
<section class="section section--dark" id="ascoltaci">
	<div class="container" style="max-width:700px;text-align:center;">
		<h2 style="margin-bottom:0.5rem;"><?php esc_html_e('Ascoltaci ovunque', 'nmcor'); ?></h2>
		<p style="color:rgba(255,255,255,0.7);margin-bottom:2rem;"><?php esc_html_e('Disponibile su tutte le principali piattaforme.', 'nmcor'); ?></p>
		<div class="listen-on" style="justify-content:center;">
			<a href="#" class="listen-on__link">Spotify</a>
			<a href="#" class="listen-on__link">Apple Podcasts</a>
			<a href="#" class="listen-on__link">YouTube</a>
			<a href="#" class="listen-on__link">Google Podcasts</a>
		</div>
	</div>
</section>

<!-- ─── COMMUNITY ─────────────────────────────────── -->
<section class="section section--surface">
	<div class="container" style="max-width:700px;">
		<div class="newsletter-block" style="margin:0;">
			<h3><?php esc_html_e('Unisciti alla conversazione', 'nmcor'); ?></h3>
			<p class="text-muted" style="max-width:400px;margin:0 auto 1.5rem;">
				<?php esc_html_e('Iscriviti alla newsletter e entra nel gruppo Telegram per non perderti nessun episodio.', 'nmcor'); ?>
			</p>
			<div class="newsletter-form">
				<input type="email" placeholder="<?php esc_attr_e('La tua email', 'nmcor'); ?>">
				<button class="btn btn--teal btn--small"><?php esc_html_e('Iscriviti', 'nmcor'); ?></button>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
