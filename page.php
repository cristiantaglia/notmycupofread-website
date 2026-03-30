<?php
/**
 * Default Page template.
 *
 * Layout semplice: breadcrumb, titolo, contenuto.
 *
 * @package NMCOR
 */

get_header();

while (have_posts()) : the_post();
?>

<!-- Breadcrumb -->
<div class="container" style="max-width:1100px;">
	<?php nmcor_breadcrumbs(); ?>
</div>

<!-- Page Header -->
<div class="container container--narrow">
	<header class="single-header">
		<h1><?php the_title(); ?></h1>
	</header>
</div>

<!-- Content -->
<div class="container container--narrow">
	<div class="single-content">
		<?php the_content(); ?>
	</div>
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
