<?php
/**
 * Card: Evento.
 *
 * @package NMCOR
 */

$location = get_post_meta(get_the_ID(), 'nmcor_event_location', true);
$city = get_post_meta(get_the_ID(), 'nmcor_event_city', true);
$date_start = get_post_meta(get_the_ID(), 'nmcor_event_date_start', true);
?>
<article class="card card--event" id="event-<?php the_ID(); ?>">
	<?php if (has_post_thumbnail()) : ?>
	<div class="card__image">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail('nmcor-card-medium'); ?>
		</a>
		<div class="card__badge">
			<?php echo nmcor_event_status_badge(); ?>
		</div>
	</div>
	<?php endif; ?>
	<div class="card__body">
		<div class="card__meta">
			<?php echo nmcor_term_links('event_type'); ?>
			<?php if ($date_start) : ?>
				<span><?php echo esc_html(nmcor_format_event_dates()); ?></span>
			<?php endif; ?>
		</div>
		<h3 class="card__title">
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h3>
		<?php if ($location || $city) : ?>
		<p style="font-size:0.875rem;color:var(--c-on-surface-variant);margin-bottom:0.5rem;">
			<span class="material-symbols-outlined" style="font-size:1rem;vertical-align:-2px;">location_on</span>
			<?php echo esc_html(implode(', ', array_filter([$location, $city]))); ?>
		</p>
		<?php endif; ?>
		<p class="card__excerpt"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>
	</div>
</article>
