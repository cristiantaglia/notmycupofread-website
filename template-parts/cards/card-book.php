<?php
/**
 * Card: Libro.
 *
 * @package NMCOR
 */

$authors = nmcor_get_book_authors();
$year = get_post_meta(get_the_ID(), 'nmcor_book_year', true);
?>
<article class="card card--book" id="book-<?php the_ID(); ?>">
	<div class="card__image">
		<a href="<?php the_permalink(); ?>">
			<?php if (has_post_thumbnail()) : ?>
				<?php the_post_thumbnail('nmcor-book-cover'); ?>
			<?php endif; ?>
		</a>
	</div>
	<div class="card__body" style="padding:1rem 0.5rem;">
		<h4 class="card__title" style="font-size:1rem;">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h4>
		<?php if ($authors) : ?>
		<p class="card__author" style="font-size:0.8125rem;color:var(--c-on-surface-variant);margin:0;">
			<?php
			$names = array_map(fn($a) => '<a href="' . get_permalink($a) . '">' . esc_html($a->post_title) . '</a>', $authors);
			echo implode(', ', $names);
			?>
		</p>
		<?php endif; ?>
		<?php if ($year) : ?>
		<span style="font-size:0.75rem;color:var(--c-outline);"><?php echo esc_html($year); ?></span>
		<?php endif; ?>
	</div>
</article>
