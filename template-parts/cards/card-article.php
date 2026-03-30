<?php
/**
 * Card: Approfondimento (post standard).
 *
 * @package NMCOR
 */
?>
<article class="card card--article" id="post-<?php the_ID(); ?>">
	<?php if (has_post_thumbnail()) : ?>
	<div class="card__image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('nmcor-card-medium'); ?>
		</a>
	</div>
	<?php endif; ?>
	<div class="card__body">
		<div class="card__meta">
			<?php
			$article_type = nmcor_term_links('article_type');
			if ($article_type) {
				echo $article_type;
			}
			?>
		</div>
		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
		<div class="card__footer">
			<span><?php echo esc_html(get_the_date('j M Y')); ?></span>
			<span><?php echo esc_html(nmcor_reading_time()); ?></span>
		</div>
	</div>
</article>
