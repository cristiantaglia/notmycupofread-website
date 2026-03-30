<?php
/**
 * Front Page — Homepage editoriale.
 *
 * Struttura:
 * 1. Hero con identità del progetto
 * 2. "Dalla nostra libreria" — ultime recensioni
 * 3. Podcast in evidenza
 * 4. Approfondimenti in evidenza
 * 5. Eventi in evidenza
 * 6. "Da dove iniziare"
 * 7. Community / Newsletter
 *
 * @package NMCOR
 */

get_header();
?>

<!-- ─── 1. HERO ────────────────────────────────────── -->
<section class="hero hero--home">
	<div class="hero__bg">
		<?php if (has_post_thumbnail()) : ?>
			<?php the_post_thumbnail('nmcor-hero'); ?>
		<?php else : ?>
			<div style="width:100%;height:100%;background:var(--c-primary-container);"></div>
		<?php endif; ?>
	</div>
	<div class="hero__content">
		<span class="overline overline--teal"><?php esc_html_e('Primo Piano', 'nmcor'); ?></span>
		<h1>
			<?php esc_html_e("L'eleganza del", 'nmcor'); ?><br>
			<span class="teal"><?php esc_html_e('leggere oggi.', 'nmcor'); ?></span>
		</h1>
		<p class="hero__subtitle">
			<?php esc_html_e('Esploriamo la letteratura contemporanea attraverso podcast immersivi e recensioni curate. Non è solo lettura, è un rito.', 'nmcor'); ?>
		</p>
		<div class="hero__actions">
			<a href="<?php echo esc_url(home_url('/podcast/')); ?>" class="btn btn--teal">
				<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">play_arrow</span>
				<?php esc_html_e('Ascolta ora', 'nmcor'); ?>
			</a>
			<a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>" class="btn btn--outline">
				<?php esc_html_e("Leggi l'ultima recensione", 'nmcor'); ?>
			</a>
		</div>
	</div>
</section>

<!-- ─── 2. DALLA NOSTRA LIBRERIA ──────────────────── -->
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Curated Selection', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Dalla nostra libreria', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>" class="view-all">
				<?php esc_html_e('Vedi tutto', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>

		<?php
		$reviews = nmcor_get_latest('review', 4);
		if ($reviews) :
		?>
		<div class="grid grid--4">
			<?php foreach ($reviews as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'review'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
		<?php else : ?>
		<p class="text-muted"><?php esc_html_e('Nessuna recensione pubblicata.', 'nmcor'); ?></p>
		<?php endif; ?>
	</div>
</section>

<!-- ─── 3. PODCAST IN EVIDENZA ────────────────────── -->
<?php $latest_episode = nmcor_get_latest_episode(); ?>
<?php if ($latest_episode) : ?>
<section class="section section--dark">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline overline--teal"><?php esc_html_e('Ultimo Episodio', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Voci tra le Foglie', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(home_url('/podcast/')); ?>" class="view-all" style="color:rgba(255,255,255,0.7);">
				<?php esc_html_e('Tutti gli episodi', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>

		<div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;">
			<div>
				<?php
				$ep_number = get_post_meta($latest_episode->ID, 'nmcor_episode_number', true);
				$ep_duration = get_post_meta($latest_episode->ID, 'nmcor_episode_duration', true);
				?>
				<?php if ($ep_number) : ?>
					<span class="overline overline--teal"><?php printf(esc_html__('Episodio %s', 'nmcor'), esc_html($ep_number)); ?></span>
				<?php endif; ?>
				<h3 style="color:#fff;font-size:1.75rem;margin-bottom:1rem;">
					<a href="<?php echo esc_url(get_permalink($latest_episode)); ?>" style="color:#fff;">
						<?php echo esc_html($latest_episode->post_title); ?>
					</a>
				</h3>
				<p style="color:rgba(255,255,255,0.75);margin-bottom:2rem;">
					<?php echo esc_html(get_the_excerpt($latest_episode)); ?>
				</p>
				<div class="hero__actions">
					<?php
					$spotify_url = get_post_meta($latest_episode->ID, 'nmcor_episode_spotify_url', true);
					$youtube_url = get_post_meta($latest_episode->ID, 'nmcor_episode_youtube_url', true);
					?>
					<?php if ($spotify_url) : ?>
						<a href="<?php echo esc_url($spotify_url); ?>" class="btn btn--teal btn--small" target="_blank" rel="noopener">Spotify</a>
					<?php endif; ?>
					<?php if ($youtube_url) : ?>
						<a href="<?php echo esc_url($youtube_url); ?>" class="btn btn--outline btn--small" target="_blank" rel="noopener">YouTube</a>
					<?php endif; ?>
					<a href="<?php echo esc_url(get_permalink($latest_episode)); ?>" class="btn btn--outline btn--small">
						<?php esc_html_e('Leggi le note', 'nmcor'); ?>
					</a>
				</div>
			</div>
			<div>
				<?php if (has_post_thumbnail($latest_episode)) : ?>
					<div style="border-radius:var(--radius-lg);overflow:hidden;box-shadow:var(--shadow-lg);">
						<?php echo get_the_post_thumbnail($latest_episode, 'nmcor-card-large'); ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ─── 4. APPROFONDIMENTI ────────────────────────── -->
<section class="section section--container-low">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Analisi e saggi', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Approfondimenti', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(home_url('/approfondimenti/')); ?>" class="view-all">
				<?php esc_html_e('Vedi tutto', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>

		<?php
		$articles = nmcor_get_latest('post', 3);
		if ($articles) :
		?>
		<div class="grid grid--3">
			<?php foreach ($articles as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'article'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<!-- ─── 5. EVENTI ─────────────────────────────────── -->
<?php
$upcoming = nmcor_get_upcoming_events(3);
if ($upcoming) :
?>
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Prossimi appuntamenti', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Eventi', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(get_post_type_archive_link('nmcor_event')); ?>" class="view-all">
				<?php esc_html_e('Tutti gli eventi', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>

		<div class="grid grid--3">
			<?php foreach ($upcoming as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'event'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ─── 6. COMMUNITY / NEWSLETTER ─────────────────── -->
<section class="section section--container">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div style="display:grid;grid-template-columns:1fr 1fr;gap:3rem;align-items:center;">
			<div>
				<h2 style="font-style:italic;">
					<?php esc_html_e('La nostra', 'nmcor'); ?><br>
					<em><?php esc_html_e('Community', 'nmcor'); ?></em>
				</h2>
				<p class="lead">
					<?php esc_html_e('Unisciti a oltre 1.000 lettori appassionati. Condividi letture, partecipa alle discussioni e diventa parte del mondo Not My Cup of Read.', 'nmcor'); ?>
				</p>
				<a href="<?php echo esc_url(home_url('/community/')); ?>" class="btn btn--teal">
					<?php esc_html_e('Unisciti alla Community', 'nmcor'); ?>
				</a>
			</div>
			<div class="newsletter-block" style="margin:0;">
				<h3><?php esc_html_e('La conversazione continua oltre le pagine.', 'nmcor'); ?></h3>
				<p class="text-muted" style="max-width:380px;margin:0 auto 1.5rem;">
					<?php esc_html_e('Ricevi ogni settimana le nostre selezioni editoriali, le novità dal podcast e gli eventi in programma.', 'nmcor'); ?>
				</p>
				<div class="newsletter-form">
					<input type="email" placeholder="<?php esc_attr_e('La tua email', 'nmcor'); ?>">
					<button class="btn btn--teal btn--small"><?php esc_html_e('Iscriviti', 'nmcor'); ?></button>
				</div>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
