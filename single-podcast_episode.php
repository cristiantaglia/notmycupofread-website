<?php
/**
 * Single: Episodio Podcast.
 *
 * @package NMCOR
 */

get_header();

while (have_posts()) : the_post();

$ep_number = get_post_meta(get_the_ID(), 'nmcor_episode_number', true);
$ep_season = get_post_meta(get_the_ID(), 'nmcor_episode_season', true);
$ep_duration = get_post_meta(get_the_ID(), 'nmcor_episode_duration', true);
$spotify_url = get_post_meta(get_the_ID(), 'nmcor_episode_spotify_url', true);
$youtube_url = get_post_meta(get_the_ID(), 'nmcor_episode_youtube_url', true);
$apple_url = get_post_meta(get_the_ID(), 'nmcor_episode_apple_url', true);
$embed_url = get_post_meta(get_the_ID(), 'nmcor_episode_embed_url', true);
$books = nmcor_get_episode_books();
?>

<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<!-- Hero episodio -->
<section class="hero hero--editorial" style="padding:3rem 0 2.5rem;">
	<div class="container" style="max-width:1100px;">
		<span class="overline overline--teal">
			<?php
			if ($ep_season && $ep_number) {
				printf('Stagione %s — Episodio %s', esc_html($ep_season), esc_html($ep_number));
			} elseif ($ep_number) {
				printf('Episodio %s', esc_html($ep_number));
			} else {
				esc_html_e('Podcast', 'nmcor');
			}
			?>
		</span>
		<h1><?php the_title(); ?></h1>
		<div class="single-meta" style="border:none;padding:0;margin-bottom:0;">
			<span class="single-meta__item" style="color:rgba(255,255,255,0.6);"><?php echo esc_html(get_the_date('j F Y')); ?></span>
			<?php if ($ep_duration) : ?>
			<span class="single-meta__item" style="color:rgba(255,255,255,0.6);"><?php echo esc_html($ep_duration); ?></span>
			<?php endif; ?>
		</div>
	</div>
</section>

<div class="container" style="max-width:1100px;">
	<div style="display:grid;grid-template-columns:1fr 320px;gap:3rem;padding:3rem 0;">
		<div>
			<!-- Player embed -->
			<?php if ($embed_url) : ?>
			<div class="podcast-player" style="margin-top:0;">
				<iframe src="<?php echo esc_url($embed_url); ?>" width="100%" height="232" frameborder="0" allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture" loading="lazy" style="border-radius:var(--radius-md);"></iframe>
			</div>
			<?php endif; ?>

			<!-- Ascolta su -->
			<div style="margin-bottom:2rem;">
				<div class="listen-on">
					<?php if ($spotify_url) : ?>
					<a href="<?php echo esc_url($spotify_url); ?>" class="listen-on__link" target="_blank" rel="noopener" style="color:var(--c-on-surface);box-shadow:inset 0 0 0 1.5px var(--c-surface-container-high);">
						Spotify
					</a>
					<?php endif; ?>
					<?php if ($youtube_url) : ?>
					<a href="<?php echo esc_url($youtube_url); ?>" class="listen-on__link" target="_blank" rel="noopener" style="color:var(--c-on-surface);box-shadow:inset 0 0 0 1.5px var(--c-surface-container-high);">
						YouTube
					</a>
					<?php endif; ?>
					<?php if ($apple_url) : ?>
					<a href="<?php echo esc_url($apple_url); ?>" class="listen-on__link" target="_blank" rel="noopener" style="color:var(--c-on-surface);box-shadow:inset 0 0 0 1.5px var(--c-surface-container-high);">
						Apple Podcasts
					</a>
					<?php endif; ?>
				</div>
			</div>

			<!-- Contenuto / Show notes -->
			<div class="single-content" style="max-width:none;padding-bottom:2rem;">
				<?php the_content(); ?>
			</div>

			<!-- Libri trattati -->
			<?php if ($books) : ?>
			<div class="related-content">
				<h3><?php esc_html_e('Libri trattati in questo episodio', 'nmcor'); ?></h3>
				<div class="grid grid--4">
					<?php foreach ($books as $book) : ?>
						<?php
						$post = $book;
						setup_postdata($post);
						get_template_part('template-parts/cards/card', 'book');
						?>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
			</div>
			<?php endif; ?>
		</div>

		<!-- Sidebar -->
		<aside class="review-sidebar">
			<?php if (has_post_thumbnail()) : ?>
			<div style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:1.5rem;box-shadow:var(--shadow-md);">
				<?php the_post_thumbnail('nmcor-card-large'); ?>
			</div>
			<?php endif; ?>

			<?php echo nmcor_term_links('podcast_format'); ?>
			<?php echo nmcor_term_links('literary_theme'); ?>

			<!-- Episodi correlati -->
			<?php
			$related_eps = get_posts([
				'post_type'      => 'podcast_episode',
				'posts_per_page' => 3,
				'post__not_in'   => [get_the_ID()],
			]);
			if ($related_eps) :
			?>
			<div style="margin-top:2rem;">
				<h4 style="font-size:0.75rem;font-family:var(--font-body);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--c-on-surface-variant);">
					<?php esc_html_e('Altri episodi', 'nmcor'); ?>
				</h4>
				<?php foreach ($related_eps as $ep) : ?>
				<div style="margin-bottom:1rem;">
					<a href="<?php echo esc_url(get_permalink($ep)); ?>" style="color:var(--c-on-surface);font-family:var(--font-headline);font-size:0.9375rem;font-weight:700;">
						<?php echo esc_html($ep->post_title); ?>
					</a>
					<p style="font-size:0.75rem;color:var(--c-on-surface-variant);margin:0.25rem 0 0;">
						<?php echo esc_html(get_the_date('j M Y', $ep)); ?>
					</p>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</aside>
	</div>
</div>

<?php nmcor_episode_schema(); ?>

<?php endwhile; ?>

<?php get_footer(); ?>
