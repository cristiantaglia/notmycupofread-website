<?php
/**
 * Single: Recensione.
 *
 * Layout dal mockup: header con titolo + rating, contenuto + sidebar libro.
 *
 * @package NMCOR
 */

get_header();

while (have_posts()) : the_post();

$book = nmcor_get_review_book();
$book_authors = $book ? nmcor_get_book_authors($book->ID) : [];
$rating = get_post_meta(get_the_ID(), 'nmcor_review_rating', true);
$subtitle = get_post_meta(get_the_ID(), 'nmcor_review_subtitle', true);
$verdict = get_post_meta(get_the_ID(), 'nmcor_review_verdict', true);
$linked_episode = nmcor_get_linked_episode();
?>

<!-- Breadcrumb -->
<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<!-- Header -->
<div class="container" style="max-width:1100px;">
	<header class="single-header">
		<span class="overline text-tertiary"><?php esc_html_e('Recensione', 'nmcor'); ?></span>
		<h1><?php the_title(); ?></h1>
		<?php if ($subtitle) : ?>
			<p class="subtitle"><?php echo esc_html($subtitle); ?></p>
		<?php endif; ?>

		<div class="single-meta">
			<?php if ($book_authors) : ?>
			<span class="single-meta__item">
				<?php
				$names = array_map(fn($a) => '<a href="' . get_permalink($a) . '">' . esc_html($a->post_title) . '</a>', $book_authors);
				echo implode(', ', $names);
				?>
			</span>
			<?php endif; ?>
			<span class="single-meta__item"><?php echo esc_html(get_the_date('j F Y')); ?></span>
			<span class="single-meta__item"><?php echo esc_html(nmcor_reading_time()); ?></span>
		</div>

		<?php if ($rating) : ?>
		<div class="rating-circle" style="margin:0;">
			<?php echo esc_html(number_format((float) $rating, 1)); ?>
		</div>
		<?php endif; ?>
	</header>
</div>

<!-- Content + Sidebar -->
<div class="container" style="max-width:1100px;">
	<div class="review-layout">
		<div class="single-content">
			<?php the_content(); ?>

			<?php if ($verdict) : ?>
			<div style="background:var(--c-surface-container);border-radius:var(--radius-lg);padding:2rem;margin-top:2rem;">
				<h3 style="margin-top:0;"><?php esc_html_e('Il nostro giudizio', 'nmcor'); ?></h3>
				<p style="font-family:var(--font-headline);font-style:italic;font-size:1.125rem;line-height:1.6;">
					<?php echo esc_html($verdict); ?>
				</p>
			</div>
			<?php endif; ?>

			<?php if ($linked_episode) : ?>
			<div class="linked-episode">
				<span class="material-symbols-outlined" style="font-size:2rem;color:var(--c-accent);">podcasts</span>
				<div>
					<span class="linked-episode__label"><?php esc_html_e('Ascolta nel Podcast', 'nmcor'); ?></span>
					<p class="linked-episode__title" style="margin:0;">
						<a href="<?php echo esc_url(get_permalink($linked_episode)); ?>">
							<?php echo esc_html($linked_episode->post_title); ?>
						</a>
					</p>
				</div>
			</div>
			<?php endif; ?>

			<!-- Contenuti correlati -->
			<div class="related-content">
				<h3><?php esc_html_e('Potrebbe interessarti', 'nmcor'); ?></h3>
				<?php
				$related = get_posts([
					'post_type'      => 'review',
					'posts_per_page' => 3,
					'post__not_in'   => [get_the_ID()],
					'orderby'        => 'rand',
				]);
				if ($related) :
				?>
				<div class="grid grid--3">
					<?php foreach ($related as $post) : setup_postdata($post); ?>
						<?php get_template_part('template-parts/cards/card', 'review'); ?>
					<?php endforeach; wp_reset_postdata(); ?>
				</div>
				<?php endif; ?>
			</div>
		</div>

		<!-- Sidebar: Scheda Tecnica -->
		<?php if ($book) : ?>
		<aside class="review-sidebar">
			<div class="book-info-card">
				<h4 style="margin-top:0;font-size:0.75rem;font-family:var(--font-body);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--c-on-surface-variant);">
					<?php esc_html_e('Scheda Tecnica', 'nmcor'); ?>
				</h4>

				<?php if (has_post_thumbnail($book->ID)) : ?>
				<div class="book-info-card__cover">
					<?php echo get_the_post_thumbnail($book->ID, 'nmcor-book-cover'); ?>
				</div>
				<?php endif; ?>

				<p class="book-info-card__title"><?php echo esc_html($book->post_title); ?></p>

				<?php if ($book_authors) : ?>
				<p class="book-info-card__author">
					<?php echo esc_html(implode(', ', array_map(fn($a) => $a->post_title, $book_authors))); ?>
				</p>
				<?php endif; ?>

				<ul class="book-info-card__details">
					<?php
					$publisher = get_the_terms($book->ID, 'publisher');
					$year = get_post_meta($book->ID, 'nmcor_book_year', true);
					$pages = get_post_meta($book->ID, 'nmcor_book_pages', true);
					$genres = get_the_terms($book->ID, 'genre');
					?>
					<?php if ($publisher && !is_wp_error($publisher)) : ?>
					<li>
						<span class="book-info-card__label"><?php esc_html_e('Editore', 'nmcor'); ?></span>
						<span><?php echo esc_html($publisher[0]->name); ?></span>
					</li>
					<?php endif; ?>
					<?php if ($year) : ?>
					<li>
						<span class="book-info-card__label"><?php esc_html_e('Anno', 'nmcor'); ?></span>
						<span><?php echo esc_html($year); ?></span>
					</li>
					<?php endif; ?>
					<?php if ($pages) : ?>
					<li>
						<span class="book-info-card__label"><?php esc_html_e('Pagine', 'nmcor'); ?></span>
						<span><?php echo esc_html($pages); ?></span>
					</li>
					<?php endif; ?>
					<?php if ($genres && !is_wp_error($genres)) : ?>
					<li>
						<span class="book-info-card__label"><?php esc_html_e('Genere', 'nmcor'); ?></span>
						<span><?php echo esc_html(implode(', ', wp_list_pluck($genres, 'name'))); ?></span>
					</li>
					<?php endif; ?>
				</ul>

				<?php
				$buy_url = get_post_meta($book->ID, 'nmcor_book_buy_url', true);
				if ($buy_url) :
				?>
				<a href="<?php echo esc_url($buy_url); ?>" class="btn btn--teal btn--small" target="_blank" rel="noopener" style="width:100%;justify-content:center;">
					<?php esc_html_e('Acquista', 'nmcor'); ?>
				</a>
				<?php endif; ?>
			</div>

			<!-- Link rapidi sidebar -->
			<div style="margin-top:1.5rem;background:var(--c-surface-container);border-radius:var(--radius-lg);padding:1.5rem;">
				<h4 style="font-size:0.75rem;font-family:var(--font-body);font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:var(--c-on-surface-variant);margin-top:0;">
					<?php esc_html_e('Scopri anche', 'nmcor'); ?>
				</h4>
				<ul style="list-style:none;padding:0;margin:0;">
					<li style="margin-bottom:0.75rem;">
						<a href="<?php echo esc_url(home_url('/podcast/')); ?>" style="display:flex;align-items:center;gap:0.5rem;color:var(--c-on-surface);font-size:0.875rem;">
							<span class="material-symbols-outlined" style="font-size:1.25rem;color:var(--c-accent);">podcasts</span>
							<?php esc_html_e('Podcast', 'nmcor'); ?>
						</a>
					</li>
					<li>
						<a href="#" style="display:flex;align-items:center;gap:0.5rem;color:var(--c-on-surface);font-size:0.875rem;">
							<span class="material-symbols-outlined" style="font-size:1.25rem;color:var(--c-accent);">photo_camera</span>
							<?php esc_html_e('Instagram', 'nmcor'); ?>
						</a>
					</li>
				</ul>
			</div>
		</aside>
		<?php endif; ?>
	</div>
</div>

<!-- Autori -->
<?php if ($book_authors) : ?>
<section class="section section--container" style="margin-top:2rem;">
	<div class="container" style="max-width:1100px;">
		<div class="grid grid--2" style="gap:2rem;">
			<?php foreach ($book_authors as $author) : ?>
			<div class="host-card">
				<?php if (has_post_thumbnail($author->ID)) : ?>
					<?php echo get_the_post_thumbnail($author, 'nmcor-avatar', ['class' => 'host-card__avatar']); ?>
				<?php endif; ?>
				<div>
					<p class="host-card__name"><?php echo esc_html($author->post_title); ?></p>
					<p class="host-card__role" style="margin:0;"><?php echo wp_trim_words(get_the_excerpt($author), 25); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php nmcor_review_schema(); ?>

<?php endwhile; ?>

<?php get_footer(); ?>
