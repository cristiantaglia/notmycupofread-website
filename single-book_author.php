<?php
/**
 * Single: Autore letterario.
 *
 * Layout: header con avatar, bio, link, libri dell'autore, articoli e episodi correlati.
 *
 * @package NMCOR
 */

get_header();

while (have_posts()) : the_post();

$author_id   = get_the_ID();
$nationality = get_post_meta($author_id, 'nmcor_author_nationality', true);
$birth_year  = get_post_meta($author_id, 'nmcor_author_birth_year', true);
$website     = get_post_meta($author_id, 'nmcor_author_website', true);
$wikipedia   = get_post_meta($author_id, 'nmcor_author_wikipedia_url', true);
$content     = nmcor_get_author_content($author_id);
?>

<!-- Breadcrumb -->
<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<!-- Author Header -->
<section class="hero hero--editorial" style="padding:3rem 0 3rem;">
	<div class="container" style="max-width:1100px;">
		<div style="display:flex;align-items:center;gap:2.5rem;flex-wrap:wrap;">
			<?php if (has_post_thumbnail()) : ?>
			<div style="width:160px;height:160px;border-radius:50%;overflow:hidden;flex-shrink:0;filter:grayscale(100%);box-shadow:var(--shadow-lg);">
				<?php the_post_thumbnail('nmcor-avatar', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
			</div>
			<?php endif; ?>
			<div style="flex:1;min-width:280px;">
				<span class="overline overline--teal"><?php esc_html_e('Autore', 'nmcor'); ?></span>
				<h1 style="margin-bottom:0.5rem;"><?php the_title(); ?></h1>
				<div class="single-meta" style="border:none;padding:0;margin-bottom:1rem;">
					<?php if ($nationality) : ?>
					<span class="single-meta__item" style="color:rgba(255,255,255,0.6);">
						<?php echo esc_html($nationality); ?>
					</span>
					<?php endif; ?>
					<?php if ($birth_year) : ?>
					<span class="single-meta__item" style="color:rgba(255,255,255,0.6);">
						<?php printf(esc_html__('Nato nel %d', 'nmcor'), (int) $birth_year); ?>
					</span>
					<?php endif; ?>
				</div>

				<?php if ($website || $wikipedia) : ?>
				<div style="display:flex;gap:1rem;flex-wrap:wrap;">
					<?php if ($website) : ?>
					<a href="<?php echo esc_url($website); ?>" class="btn btn--outline btn--small" target="_blank" rel="noopener" style="color:#fff;border-color:rgba(255,255,255,0.3);">
						<span class="material-symbols-outlined" style="font-size:1.125rem;">language</span>
						<?php esc_html_e('Sito web', 'nmcor'); ?>
					</a>
					<?php endif; ?>
					<?php if ($wikipedia) : ?>
					<a href="<?php echo esc_url($wikipedia); ?>" class="btn btn--outline btn--small" target="_blank" rel="noopener" style="color:#fff;border-color:rgba(255,255,255,0.3);">
						<span class="material-symbols-outlined" style="font-size:1.125rem;">info</span>
						<?php esc_html_e('Wikipedia', 'nmcor'); ?>
					</a>
					<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>

<!-- Biografia -->
<?php if (get_the_content()) : ?>
<section class="section section--surface">
	<div class="container container--narrow">
		<div class="single-content">
			<?php the_content(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Libri dell'autore -->
<?php if (!empty($content['books'])) : ?>
<section class="section section--container-low">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Biblioteche', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Libri', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(get_post_type_archive_link('book')); ?>" class="view-all">
				<?php esc_html_e('Tutti i libri', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>
		<div class="grid grid--4">
			<?php foreach ($content['books'] as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'book'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Articoli correlati -->
<?php
$author_articles = get_posts([
	'post_type'      => ['post', 'review'],
	'posts_per_page' => 6,
	'meta_query'     => [
		'relation' => 'OR',
		[
			'key'     => 'nmcor_post_books',
			'value'   => array_map(fn($b) => sprintf(':"%d";', $b->ID), $content['books'] ?: []),
			'compare' => 'IN',
		],
	],
]);
// Fallback: get reviews for this author's books
if (!$author_articles && !empty($content['books'])) {
	$book_ids = wp_list_pluck($content['books'], 'ID');
	$author_articles = get_posts([
		'post_type'      => 'review',
		'posts_per_page' => 6,
		'meta_query'     => [
			[
				'key'     => 'nmcor_review_book',
				'value'   => array_map(fn($id) => sprintf(':"%d";', $id), $book_ids),
				'compare' => 'REGEXP',
			],
		],
	]);
}
if ($author_articles) :
?>
<section class="section section--surface">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Da leggere', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Articoli e recensioni', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($author_articles as $post) : setup_postdata($post); ?>
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
<?php endif; ?>

<!-- Episodi podcast correlati -->
<?php
$author_episodes = get_posts([
	'post_type'      => 'podcast_episode',
	'posts_per_page' => 3,
	'meta_query'     => [
		[
			'key'     => 'nmcor_episode_authors',
			'value'   => sprintf(':"%d";', $author_id),
			'compare' => 'LIKE',
		],
	],
]);
if ($author_episodes) :
?>
<section class="section section--dark">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline overline--teal"><?php esc_html_e('Ascolta', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Nel podcast', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($author_episodes as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'podcast'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php endwhile; ?>

<?php get_footer(); ?>
