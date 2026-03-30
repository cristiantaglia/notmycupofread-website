<?php
/**
 * Template Name: Approfondimenti Landing
 * Template Post Type: page
 *
 * Sezione chiave "rivista" con:
 * 1. Hero editoriale
 * 2. Articolo in evidenza
 * 3. Articoli secondari
 * 4. Categorie/sotto-sezioni
 * 5. Feed articoli
 * 6. "Da dove iniziare"
 * 7. Contenuti podcast correlati
 * 8. Percorsi editoriali
 * 9. CTA finale
 *
 * @package NMCOR
 */

get_header();
?>

<!-- ─── HERO ──────────────────────────────────────── -->
<section class="hero hero--editorial" style="padding:4rem 0 3rem;">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<span class="overline overline--teal"><?php esc_html_e('Analisi e saggi', 'nmcor'); ?></span>
		<h1 style="font-size:clamp(3rem,6vw,5rem);margin-bottom:0.75rem;"><?php esc_html_e('Approfondimenti', 'nmcor'); ?></h1>
		<p class="hero__description"><?php esc_html_e('Saggi, analisi comparative, percorsi di lettura e focus critici. Qui la letteratura diventa conversazione.', 'nmcor'); ?></p>
	</div>
</section>

<!-- ─── ARTICOLO IN EVIDENZA ──────────────────────── -->
<?php
$featured = nmcor_get_featured_posts('post', 1);
$secondary = nmcor_get_latest('post', 2, ['post__not_in' => $featured ? [get_the_ID()] : []]);

if ($featured) :
	$feat = $featured[0];
?>
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<!-- Featured + secondary grid -->
		<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:2rem;">
			<!-- Featured -->
			<article class="card card--large" style="grid-row:1/3;">
				<?php if (has_post_thumbnail($feat)) : ?>
				<div class="card__image" style="aspect-ratio:4/3;">
					<a href="<?php echo esc_url(get_permalink($feat)); ?>">
						<?php echo get_the_post_thumbnail($feat, 'nmcor-card-large'); ?>
					</a>
				</div>
				<?php endif; ?>
				<div class="card__body">
					<div class="card__meta">
						<?php echo nmcor_term_links('article_type', $feat->ID); ?>
					</div>
					<h2 class="card__title" style="font-size:2rem;">
						<a href="<?php echo esc_url(get_permalink($feat)); ?>"><?php echo esc_html($feat->post_title); ?></a>
					</h2>
					<p class="card__excerpt" style="font-size:1rem;"><?php echo wp_trim_words(get_the_excerpt($feat), 30); ?></p>
					<div class="card__footer">
						<span><?php echo esc_html(get_the_date('j M Y', $feat)); ?></span>
						<span><?php echo esc_html(nmcor_reading_time($feat->ID)); ?></span>
					</div>
				</div>
			</article>

			<!-- Secondary -->
			<?php foreach ($secondary as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'article'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ─── CATEGORIE ─────────────────────────────────── -->
<section class="section section--container">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Esplora per categoria', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Categorie Tematiche', 'nmcor'); ?></h2>
			</div>
		</div>

		<div class="grid" style="grid-template-columns:repeat(5,1fr);gap:1.5rem;">
			<?php
			$types = get_terms(['taxonomy' => 'article_type', 'hide_empty' => false]);
			if ($types && !is_wp_error($types)) :
				foreach ($types as $type) :
			?>
			<a href="<?php echo esc_url(get_term_link($type)); ?>" class="format-card" style="text-decoration:none;transition:transform 0.2s ease;">
				<p class="format-card__title"><?php echo esc_html($type->name); ?></p>
				<p class="format-card__desc">
					<?php
					$count = $type->count;
					printf(esc_html(_n('%d articolo', '%d articoli', $count, 'nmcor')), $count);
					?>
				</p>
			</a>
			<?php endforeach; endif; ?>
		</div>
	</div>
</section>

<!-- ─── FEED ARTICOLI ─────────────────────────────── -->
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Ultimi pubblicati', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Tutti gli approfondimenti', 'nmcor'); ?></h2>
			</div>
		</div>

		<!-- Filtri -->
		<div class="term-pills" id="archiveFilters" data-post-type="post">
			<button class="term-pill active" data-filter="all"><?php esc_html_e('Tutti', 'nmcor'); ?></button>
			<?php
			if ($types && !is_wp_error($types)) :
				foreach ($types as $type) :
			?>
			<button class="term-pill" data-filter="<?php echo esc_attr($type->slug); ?>" data-taxonomy="article_type">
				<?php echo esc_html($type->name); ?>
			</button>
			<?php endforeach; endif; ?>
		</div>

		<div class="archive-grid archive-grid--3" id="archiveGrid">
			<?php
			$all_articles = nmcor_get_latest('post', 9);
			foreach ($all_articles as $post) : setup_postdata($post);
				get_template_part('template-parts/cards/card', 'article');
			endforeach; wp_reset_postdata();
			?>
		</div>

		<div class="load-more-wrap">
			<button class="btn btn--secondary" id="loadMore" data-post-type="post" data-page="2">
				<?php esc_html_e('Carica altri', 'nmcor'); ?>
			</button>
		</div>
	</div>
</section>

<!-- ─── PODCAST CORRELATO ─────────────────────────── -->
<?php $latest_ep = nmcor_get_latest_episode(); ?>
<?php if ($latest_ep) : ?>
<section class="section section--dark">
	<div class="container" style="max-width:900px;">
		<div class="linked-episode" style="padding:2.5rem;margin:0;">
			<span class="material-symbols-outlined" style="font-size:3rem;color:var(--c-accent);">podcasts</span>
			<div style="flex:1;">
				<span class="linked-episode__label"><?php esc_html_e('Il ritmo della pagina nel Podcast della settimana', 'nmcor'); ?></span>
				<p class="linked-episode__title" style="font-size:1.375rem;margin:0;">
					<a href="<?php echo esc_url(get_permalink($latest_ep)); ?>">
						<?php echo esc_html($latest_ep->post_title); ?>
					</a>
				</p>
			</div>
			<a href="<?php echo esc_url(get_permalink($latest_ep)); ?>" class="btn btn--teal btn--small">
				<span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;font-size:1rem;">play_arrow</span>
				<?php esc_html_e('Ascolta', 'nmcor'); ?>
			</a>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- ─── NEWSLETTER CTA ────────────────────────────── -->
<section class="section section--container-low">
	<div class="container" style="max-width:700px;">
		<div class="newsletter-block" style="margin:0;">
			<span class="overline overline--teal"><?php esc_html_e('Resta aggiornato', 'nmcor'); ?></span>
			<h3><?php esc_html_e("L'invito alla conversazione", 'nmcor'); ?></h3>
			<p class="text-muted" style="max-width:400px;margin:0 auto 1.5rem;">
				<?php esc_html_e('Iscriviti alla newsletter per ricevere i nuovi approfondimenti, saggi e percorsi di lettura.', 'nmcor'); ?>
			</p>
			<div class="newsletter-form">
				<input type="email" placeholder="<?php esc_attr_e('La tua email', 'nmcor'); ?>">
				<button class="btn btn--teal btn--small"><?php esc_html_e('Iscriviti', 'nmcor'); ?></button>
			</div>
		</div>
	</div>
</section>

<?php get_footer(); ?>
