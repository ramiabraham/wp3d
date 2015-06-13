<?php
/**
 * Template Name: Panorama
 *
 * This is the template that displays the Earth, along with metadata for each
 * employee added on the relevant page template, via custom fields.
 *
 * @package wp3d
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<?php get_template_part( 'content', 'panorama' ); ?>


			<?php endwhile; ?>

		</main>
	</div>


<?php get_footer(); ?>
