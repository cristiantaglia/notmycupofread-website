<?php
/**
 * Single: Approfondimento (post standard).
 *
 * Layout: breadcrumb, overline tipo articolo, titolo, meta, immagine,
 * contenuto, episodio collegato, libri collegati, correlati, newsletter CTA.
 *
 * @package NMCOR
 */

get_header();

while (have_posts()) : the_post();

$post_id        = get_the_ID();
$linked_episode = nmcor_get_linked_episode($post_id);
$linked_books   = nmcor_field('nmcor_post_books', $post_id);
?>

<!-- Breadcrumb -->
<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<!-- Header -->
<div class="container" style="max-width:1100px;">
	<header class="single-header">
		<?php
		$article_type = nmcor_term_links('article_type');
		if ($article_type) :
		?>
		<span class="overline text-tertiary"><?php echo $article_type; ?></span>
		<?php endif; ?>

		<h1><?php the_title(); ?></h1>

		<div class="single-meta">
			<span class="single-meta__item"><?php echo esc_html(get_the_date('j F Y')); ?></span>
			<span class="single-meta__item"><?php echo esc_html(nmcor_reading_time($post_id)); ?></span>
			<?php
			$themes = nmcor_term_links('literary_theme');
			if ($themes) :
			?>
			<span class="single-meta__item"><?php echo $themes; ?></span>
			<?php endif; ?>
		</div>
	</header>
</div>

<!-- Featured Image -->
<?php if (has_post_thumbnail()) : ?>
<div class="container" style="max-width:1100px;">
	<div style="border-radius:var(--radius-lg);overflow:hidden;margin-bottom:2.5rem;box-shadow:var(--shadow-md);">
		<?php the_post_thumbnail('nmcor-hero'); ?>
	</div>
</div>
<?php endif; ?>

<!-- Content -->
<div class="container container--narrow">
	<div class="single-content">
		<?php the_content(); ?>
	</div>

	<!-- Episodio podcast collegato -->
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

	<!-- Libri collegati -->
	<?php
	if ($linked_books && is_array($linked_books)) :
		$books = array_filter(array_map('get_post', $linked_books));
		if ($books) :
	?>
	<div class="related-content">
		<h3><?php esc_html_e('Libri citati', 'nmcor'); ?></h3>
		<div class="grid grid--4">
			<?php foreach ($books as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'book'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
	<?php endif; endif; ?>
</div>

<!-- Approfondimenti correlati -->
<?php
$related_posts = get_posts([
	'post_type'      => 'post',
	'posts_per_page' => 3,
	'post__not_in'   => [$post_id],
	'orderby'        => 'rand',
]);
if ($related_posts) :
?>
<section class="section section--container-low">
	<div class="container" style="max-width:1100px;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('Continua a leggere', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Potrebbe interessarti', 'nmcor'); ?></h2>
			</div>
			<a href="<?php echo esc_url(home_url('/approfondimenti/')); ?>" class="view-all">
				<?php esc_html_e('Tutti gli approfondimenti', 'nmcor'); ?>
				<span class="material-symbols-outlined">arrow_forward</span>
			</a>
		</div>
		<div class="grid grid--3">
			<?php foreach ($related_posts as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'article'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Newsletter CTA -->
<section class="section section--surface">
	<div class="container container--narrow">
		<div class="newsletter-block">
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
</section>

<?php endwhile; ?>

<?php get_footer(); ?>
