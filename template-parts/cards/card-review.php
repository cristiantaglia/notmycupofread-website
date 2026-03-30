<?php
/**
 * Card: Recensione.
 *
 * @package NMCOR
 */

$rating = get_post_meta(get_the_ID(), 'nmcor_review_rating', true);
$book = nmcor_get_review_book();
$book_authors = $book ? nmcor_get_book_authors($book->ID) : [];
?>
<article class="card card--review" id="review-<?php the_ID(); ?>">
	<div class="card__image">
		<?php if ($book && has_post_thumbnail($book->ID)) : ?>
			<a href="<?php the_permalink(); ?>">
				<?php echo get_the_post_thumbnail($book->ID, 'nmcor-book-cover'); ?>
			</a>
		<?php elseif (has_post_thumbnail()) : ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('nmcor-card-medium'); ?>
			</a>
		<?php endif; ?>
		<?php if ($rating) : ?>
		<div class="card__badge">
			<div class="rating-circle" style="width:44px;height:44px;font-size:1rem;">
				<?php echo esc_html(number_format((float) $rating, 1)); ?>
			</div>
		</div>
		<?php endif; ?>
	</div>
	<div class="card__body">
		<div class="card__meta">
			<?php echo nmcor_term_links('genre'); ?>
		</div>
		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<?php if ($book_authors) : ?>
		<p class="card__author text-muted" style="font-size:0.875rem;margin-bottom:0.5rem;">
			<?php
			$names = array_map(fn($a) => $a->post_title, $book_authors);
			echo esc_html(implode(', ', $names));
			?>
		</p>
		<?php endif; ?>
		<p class="card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
		<div class="card__footer">
			<span><?php echo esc_html(get_the_date('j M Y')); ?></span>
			<span><?php echo esc_html(nmcor_reading_time()); ?></span>
		</div>
	</div>
</article>
