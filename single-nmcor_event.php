<?php
/**
 * Single: Evento.
 *
 * Layout: hero editoriale con data/luogo/badge, contenuto, articoli e episodi collegati.
 *
 * @package NMCOR
 */

get_header();

while (have_posts()) : the_post();

$event_id    = get_the_ID();
$location    = get_post_meta($event_id, 'nmcor_event_location', true);
$address     = get_post_meta($event_id, 'nmcor_event_address', true);
$city        = get_post_meta($event_id, 'nmcor_event_city', true);
$time        = get_post_meta($event_id, 'nmcor_event_time', true);
$event_url   = get_post_meta($event_id, 'nmcor_event_url', true);
$articles    = nmcor_field('nmcor_event_articles', $event_id);
$episodes    = nmcor_field('nmcor_event_episodes', $event_id);
?>

<!-- Breadcrumb -->
<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<!-- Hero -->
<section class="hero hero--editorial" style="padding:3rem 0 2.5rem;">
	<div class="container" style="max-width:1100px;">
		<div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:1rem;">
			<?php echo nmcor_term_links('event_type'); ?>
			<?php echo nmcor_event_status_badge($event_id); ?>
		</div>
		<h1><?php the_title(); ?></h1>
		<div class="single-meta" style="border:none;padding:0;margin-bottom:0;">
			<span class="single-meta__item" style="color:rgba(255,255,255,0.6);">
				<span class="material-symbols-outlined" style="font-size:1.125rem;vertical-align:-2px;">calendar_today</span>
				<?php echo esc_html(nmcor_format_event_dates($event_id)); ?>
			</span>
			<?php if ($time) : ?>
			<span class="single-meta__item" style="color:rgba(255,255,255,0.6);">
				<span class="material-symbols-outlined" style="font-size:1.125rem;vertical-align:-2px;">schedule</span>
				<?php echo esc_html($time); ?>
			</span>
			<?php endif; ?>
			<?php if ($location || $city) : ?>
			<span class="single-meta__item" style="color:rgba(255,255,255,0.6);">
				<span class="material-symbols-outlined" style="font-size:1.125rem;vertical-align:-2px;">location_on</span>
				<?php echo esc_html(implode(', ', array_filter([$location, $city]))); ?>
			</span>
			<?php endif; ?>
		</div>
	</div>
</section>

<!-- Featured Image -->
<?php if (has_post_thumbnail()) : ?>
<div class="container" style="max-width:1100px;">
	<div style="border-radius:var(--radius-lg);overflow:hidden;margin:-2rem 0 2rem;box-shadow:var(--shadow-lg);">
		<?php the_post_thumbnail('nmcor-hero'); ?>
	</div>
</div>
<?php endif; ?>

<!-- Content -->
<div class="container" style="max-width:1100px;">
	<div class="review-layout">
		<div class="single-content">
			<?php the_content(); ?>
		</div>

		<!-- Sidebar: Dettagli luogo -->
		<aside class="review-sidebar">
			<?php if ($location || $address || $city) : ?>
			<div class="book-info-card">
				<h4 style="margin-top:0;font-size:0.75rem;font-family:var(--font-body);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--c-on-surface-variant);">
					<?php esc_html_e('Dove', 'nmcor'); ?>
				</h4>
				<?php if ($location) : ?>
				<p style="font-family:var(--font-headline);font-weight:700;font-size:1.125rem;margin-bottom:0.25rem;">
					<?php echo esc_html($location); ?>
				</p>
				<?php endif; ?>
				<?php if ($address) : ?>
				<p style="font-size:0.875rem;color:var(--c-on-surface-variant);margin-bottom:0.25rem;">
					<?php echo esc_html($address); ?>
				</p>
				<?php endif; ?>
				<?php if ($city) : ?>
				<p style="font-size:0.875rem;color:var(--c-on-surface-variant);margin-bottom:0;">
					<?php echo esc_html($city); ?>
				</p>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if ($event_url) : ?>
			<div style="margin-top:1.5rem;">
				<a href="<?php echo esc_url($event_url); ?>" class="btn btn--teal btn--small" target="_blank" rel="noopener" style="width:100%;justify-content:center;">
					<span class="material-symbols-outlined" style="font-size:1.125rem;">open_in_new</span>
					<?php esc_html_e('Sito evento', 'nmcor'); ?>
				</a>
			</div>
			<?php endif; ?>

			<!-- Quando -->
			<div style="margin-top:1.5rem;background:var(--c-surface-container);border-radius:var(--radius-lg);padding:1.5rem;">
				<h4 style="font-size:0.75rem;font-family:var(--font-body);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--c-on-surface-variant);margin-top:0;">
					<?php esc_html_e('Quando', 'nmcor'); ?>
				</h4>
				<p style="font-family:var(--font-headline);font-weight:700;margin-bottom:0.25rem;">
					<?php echo esc_html(nmcor_format_event_dates($event_id)); ?>
				</p>
				<?php if ($time) : ?>
				<p style="font-size:0.875rem;color:var(--c-on-surface-variant);margin:0;">
					<?php echo esc_html($time); ?>
				</p>
				<?php endif; ?>
			</div>
		</aside>
	</div>
</div>

<!-- Articoli collegati -->
<?php
if ($articles && is_array($articles)) :
	$linked_articles = array_filter(array_map('get_post', $articles));
	if ($linked_articles) :
?>
<section class="section section--container-low">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Da leggere', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Articoli collegati', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($linked_articles as $post) : setup_postdata($post); ?>
				<?php
				if (get_post_type() === 'review') {
					get_template_part('template-parts/cards/card', 'review');
				} else {
					get_template_part('template-parts/cards/card', 'article');
				}
				?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; endif; ?>

<!-- Episodi podcast collegati -->
<?php
if ($episodes && is_array($episodes)) :
	$linked_episodes = array_filter(array_map('get_post', $episodes));
	if ($linked_episodes) :
?>
<section class="section section--dark">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline overline--teal"><?php esc_html_e('Ascolta', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Episodi collegati', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($linked_episodes as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'podcast'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; endif; ?>

<!-- Eventi correlati -->
<?php
$related_events = get_posts([
	'post_type'      => 'nmcor_event',
	'posts_per_page' => 3,
	'post__not_in'   => [$event_id],
	'meta_key'       => 'nmcor_event_date_start',
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
]);
if ($related_events) :
?>
<section class="section section--surface">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Scopri anche', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Altri eventi', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(get_post_type_archive_link('nmcor_event')); ?>" class="view-all">
				<?php esc_html_e('Tutti gli eventi', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>
		<div class="grid grid--3">
			<?php foreach ($related_events as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'event'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
