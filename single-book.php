<?php
/**
 * Single: Libro.
 *
 * Layout: header con copertina + info, metadata table, contenuto,
 * recensioni collegate, episodi, approfondimenti, profili autore.
 *
 * @package NMCOR
 */

get_header();

while (have_posts()) : the_post();

$book_id          = get_the_ID();
$authors          = nmcor_get_book_authors($book_id);
$subtitle         = get_post_meta($book_id, 'nmcor_book_subtitle', true);
$year             = get_post_meta($book_id, 'nmcor_book_year', true);
$pages            = get_post_meta($book_id, 'nmcor_book_pages', true);
$isbn             = get_post_meta($book_id, 'nmcor_book_isbn', true);
$original_title   = get_post_meta($book_id, 'nmcor_book_original_title', true);
$original_lang    = get_post_meta($book_id, 'nmcor_book_original_language', true);
$buy_url          = get_post_meta($book_id, 'nmcor_book_buy_url', true);
$publisher        = get_the_terms($book_id, 'publisher');
$genres           = get_the_terms($book_id, 'genre');
$related          = nmcor_get_book_related_content($book_id);
?>

<!-- Breadcrumb -->
<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<!-- Book Detail Header -->
<div class="container" style="max-width:1100px;">
	<div class="book-detail-header">
		<!-- Cover -->
		<div class="book-cover">
			<?php if (has_post_thumbnail()) : ?>
				<?php the_post_thumbnail('nmcor-book-cover'); ?>
			<?php endif; ?>
		</div>

		<!-- Info -->
		<div class="book-detail-info">
			<?php if ($genres && !is_wp_error($genres)) : ?>
			<span class="overline text-tertiary">
				<?php echo esc_html(implode(', ', wp_list_pluck($genres, 'name'))); ?>
			</span>
			<?php endif; ?>

			<h1><?php the_title(); ?></h1>

			<?php if ($subtitle) : ?>
			<p class="subtitle"><?php echo esc_html($subtitle); ?></p>
			<?php endif; ?>

			<?php if ($authors) : ?>
			<p style="font-size:1.125rem;color:var(--c-on-surface-variant);margin-bottom:1.5rem;">
				<?php
				$names = array_map(fn($a) => '<a href="' . get_permalink($a) . '">' . esc_html($a->post_title) . '</a>', $authors);
				echo implode(', ', $names);
				?>
			</p>
			<?php endif; ?>

			<!-- Metadata Table -->
			<table class="book-meta-table">
				<?php if ($authors) : ?>
				<tr>
					<td><?php esc_html_e('Autore', 'nmcor'); ?></td>
					<td><?php echo esc_html(implode(', ', array_map(fn($a) => $a->post_title, $authors))); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($publisher && !is_wp_error($publisher)) : ?>
				<tr>
					<td><?php esc_html_e('Editore', 'nmcor'); ?></td>
					<td><?php echo esc_html($publisher[0]->name); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($year) : ?>
				<tr>
					<td><?php esc_html_e('Anno', 'nmcor'); ?></td>
					<td><?php echo esc_html($year); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($pages) : ?>
				<tr>
					<td><?php esc_html_e('Pagine', 'nmcor'); ?></td>
					<td><?php echo esc_html($pages); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($genres && !is_wp_error($genres)) : ?>
				<tr>
					<td><?php esc_html_e('Genere', 'nmcor'); ?></td>
					<td><?php echo esc_html(implode(', ', wp_list_pluck($genres, 'name'))); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($isbn) : ?>
				<tr>
					<td><?php esc_html_e('ISBN', 'nmcor'); ?></td>
					<td><?php echo esc_html($isbn); ?></td>
				</tr>
				<?php endif; ?>
				<?php if ($original_title) : ?>
				<tr>
					<td><?php esc_html_e('Titolo originale', 'nmcor'); ?></td>
					<td>
						<?php echo esc_html($original_title); ?>
						<?php if ($original_lang) : ?>
							<span style="color:var(--c-outline);font-size:0.8125rem;">(<?php echo esc_html($original_lang); ?>)</span>
						<?php endif; ?>
					</td>
				</tr>
				<?php endif; ?>
			</table>

			<?php if ($buy_url) : ?>
			<a href="<?php echo esc_url($buy_url); ?>" class="btn btn--teal" target="_blank" rel="noopener">
				<?php esc_html_e('Acquista il libro', 'nmcor'); ?>
			</a>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- Book Description / Content -->
<?php if (get_the_content()) : ?>
<section class="section section--surface">
	<div class="container container--narrow">
		<div class="single-content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Recensioni collegate -->
<?php if (!empty($related['reviews'])) : ?>
<section class="section section--container-low">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Cosa ne pensiamo', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Recensioni', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($related['reviews'] as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'review'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Episodi podcast collegati -->
<?php
$book_episodes = get_posts([
	'post_type'      => 'podcast_episode',
	'posts_per_page' => 4,
	'meta_query'     => [
		[
			'key'     => 'nmcor_episode_books',
			'value'   => sprintf(':"%d";', $book_id),
			'compare' => 'LIKE',
		],
	],
]);
if ($book_episodes) :
?>
<section class="section section--dark">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline overline--teal"><?php esc_html_e('Ascolta', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Ne parliamo nel podcast', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($book_episodes as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'podcast'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Approfondimenti collegati -->
<?php
$book_articles = get_posts([
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'meta_query'     => [
		[
			'key'     => 'nmcor_post_books',
			'value'   => sprintf(':"%d";', $book_id),
			'compare' => 'LIKE',
		],
	],
]);
if ($book_articles) :
?>
<section class="section section--container-low">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Analisi e saggi', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Approfondimenti', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($book_articles as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'article'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Autori -->
<?php if ($authors) : ?>
<section class="section section--surface">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php echo esc_html(_n('L\'autore', 'Gli autori', count($authors), 'nmcor')); ?></span>
				<h2><?php echo esc_html(_n('Chi ha scritto questo libro', 'Chi ha scritto questo libro', count($authors), 'nmcor')); ?></h2>
			</div>
		</div>
		<div class="grid grid--2" style="gap:2rem;">
			<?php foreach ($authors as $author) : ?>
			<div class="host-card">
				<?php if (has_post_thumbnail($author->ID)) : ?>
					<div style="width:100px;height:100px;border-radius:50%;overflow:hidden;flex-shrink:0;filter:grayscale(100%);">
						<?php echo get_the_post_thumbnail($author->ID, 'nmcor-avatar', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
					</div>
				<?php endif; ?>
				<div>
					<p class="host-card__name">
						<a href="<?php echo esc_url(get_permalink($author)); ?>" style="color:var(--c-on-surface);">
							<?php echo esc_html($author->post_title); ?>
						</a>
					</p>
					<?php
					$nationality = get_post_meta($author->ID, 'nmcor_author_nationality', true);
					if ($nationality) :
					?>
					<p style="font-size:0.8125rem;color:var(--c-on-surface-variant);margin:0 0 0.5rem;">
						<?php echo esc_html($nationality); ?>
					</p>
					<?php endif; ?>
					<p class="host-card__role" style="margin:0;"><?php echo wp_trim_words(get_the_excerpt($author), 25); ?></p>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
