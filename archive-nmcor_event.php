<?php
/**
 * Archive: Eventi — Timeline layout dal mockup.
 *
 * @package NMCOR
 */

get_header();
?>

<section class="hero hero--editorial">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<span class="overline overline--teal"><?php esc_html_e('I nostri appuntamenti', 'nmcor'); ?></span>
		<h1><?php esc_html_e('Il Nostro Cammino', 'nmcor'); ?></h1>
		<p class="hero__description"><?php esc_html_e('Scopriamo le iniziative letterarie che abbiamo vissuto e vivremo: fiere, festival, presentazioni e incontri.', 'nmcor'); ?></p>
	</div>
</section>

<!-- Prossimi eventi -->
<?php
$upcoming = nmcor_get_upcoming_events(6);
if ($upcoming) :
?>
<section class="section section--surface">
	<div class="container--wide" style="max-width:80rem;margin:0 auto;padding:0 2rem;">
		<div class="section-header">
			<div>
				<span class="overline text-tertiary"><?php esc_html_e('In programma', 'nmcor'); ?></span>
				<h2><?php esc_html_e('Prossimi eventi', 'nmcor'); ?></h2>
			</div>
		</div>
		<div class="grid grid--3">
			<?php foreach ($upcoming as $post) : setup_postdata($post); ?>
				<?php get_template_part('template-parts/cards/card', 'event'); ?>
			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- Timeline eventi passati -->
<?php
$past = nmcor_get_past_events(12);
if ($past) :
?>
<section class="section section--container-low">
	<div class="container" style="max-width:900px;">
		<div class="section-header section-header--center">
			<span class="overline text-tertiary"><?php esc_html_e('La nostra storia', 'nmcor'); ?></span>
			<h2><?php esc_html_e('Timeline', 'nmcor'); ?></h2>
		</div>

		<div class="timeline">
			<?php
			$current_month = '';
			foreach ($past as $event) :
				$date_start = get_post_meta($event->ID, 'nmcor_event_date_start', true);
				$month_year = $date_start ? date_i18n('F Y', strtotime($date_start)) : '';

				if ($month_year !== $current_month) :
					$current_month = $month_year;
			?>
				<div style="text-align:center;margin:2rem 0 1rem;position:relative;z-index:3;">
					<span style="display:inline-block;padding:0.25rem 1.25rem;background:var(--c-surface-container-low);font-family:var(--font-headline);font-weight:700;font-size:0.875rem;color:var(--c-on-surface-variant);border-radius:var(--radius-full);">
						<?php echo esc_html($current_month); ?>
					</span>
				</div>
			<?php endif; ?>

			<div class="timeline__item">
				<div class="timeline__dot"></div>
				<div class="timeline__content">
					<?php echo nmcor_event_status_badge($event->ID); ?>
					<h4 style="margin:0.75rem 0 0.25rem;">
						<a href="<?php echo esc_url(get_permalink($event)); ?>" style="color:var(--c-on-surface);">
							<?php echo esc_html($event->post_title); ?>
						</a>
					</h4>
					<p style="font-size:0.8125rem;color:var(--c-on-surface-variant);margin:0;">
						<?php echo esc_html(nmcor_format_event_dates($event->ID)); ?>
						<?php
						$city = get_post_meta($event->ID, 'nmcor_event_city', true);
						if ($city) echo ' — ' . esc_html($city);
						?>
					</p>
				</div>
				<div class="timeline__image">
					<?php if (has_post_thumbnail($event)) : ?>
						<?php echo get_the_post_thumbnail($event, 'nmcor-card-medium', ['style' => 'border-radius:var(--radius-md);']); ?>
					<?php endif; ?>
				</div>
			</div>

			<?php endforeach; wp_reset_postdata(); ?>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- CTA -->
<section class="section section--surface">
	<div class="container" style="max-width:700px;">
		<div class="cta-block">
			<h3><?php esc_html_e('Vuoi collaborare al prossimo evento?', 'nmcor'); ?></h3>
			<p><?php esc_html_e('Organizziamo e partecipiamo a eventi letterari in tutta Italia. Scrivici per collaborazioni e partnership.', 'nmcor'); ?></p>
			<a href="<?php echo esc_url(home_url('/contatti/')); ?>" class="btn btn--teal"><?php esc_html_e('Contattaci', 'nmcor'); ?></a>
		</div>
	</div>
</section>

<?php get_footer(); ?>
