<?php
/**
 * Card: Episodio Podcast.
 *
 * @package NMCOR
 */

$ep_number = get_post_meta(get_the_ID(), 'nmcor_episode_number', true);
$ep_duration = get_post_meta(get_the_ID(), 'nmcor_episode_duration', true);
$ep_season = get_post_meta(get_the_ID(), 'nmcor_episode_season', true);
?>
<article class="card card--podcast" id="episode-<?php the_ID(); ?>">
	<?php if (has_post_thumbnail()) : ?>
	<div class="card__image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('nmcor-card-medium'); ?>
		</a>
	</div>
	<?php endif; ?>
	<div class="card__body">
		<div class="card__meta">
			<?php if ($ep_number) : ?>
				<span class="card__episode-number">
					<?php
					if ($ep_season) {
						printf('S%s E%s', esc_html($ep_season), esc_html($ep_number));
					} else {
						printf('Ep. %s', esc_html($ep_number));
					}
					?>
				</span>
			<?php endif; ?>
			<?php if ($ep_duration) : ?>
				<span class="card__duration"><?php echo esc_html($ep_duration); ?></span>
			<?php endif; ?>
		</div>
		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<p class="card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
		<div class="card__footer">
			<span><?php echo esc_html(get_the_date('j M Y')); ?></span>
			<?php echo nmcor_term_links('podcast_format'); ?>
		</div>
	</div>
</article>
