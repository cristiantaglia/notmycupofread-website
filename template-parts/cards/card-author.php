<?php
/**
 * Card: Autore letterario.
 *
 * @package NMCOR
 */

$nationality = get_post_meta(get_the_ID(), 'nmcor_author_nationality', true);
?>
<article class="card card--author" style="text-align:center;" id="author-<?php the_ID(); ?>">
	<?php if (has_post_thumbnail()) : ?>
	<div style="width:120px;height:120px;border-radius:50%;overflow:hidden;margin:1.5rem auto 0;filter:grayscale(100%);">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('nmcor-avatar', ['style' => 'width:100%;height:100%;object-fit:cover;']); ?>
		</a>
	</div>
	<?php endif; ?>
	<div class="card__body" style="text-align:center;">
		<h4 class="card__title" style="font-size:1.125rem;">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h4>
		<?php if ($nationality) : ?>
		<p style="font-size:0.8125rem;color:var(--c-on-surface-variant);margin:0;"><?php echo esc_html($nationality); ?></p>
		<?php endif; ?>
	</div>
</article>
