<?php
/**
 * The template used for displaying a 3d Earth
 *
 * @package wp3d
 */
?>

<article <?php post_class(); ?>>

	<div id="panel">
		<div class="title-planet"><?php the_title(); ?></div>
		<div class="globe-wrapper">


				<div id="globe" radius="500" textures-directory="<?php echo get_template_directory_uri();?>/inc/earth/img/textures" sun-intensity="0.55" animation-speed="9.5">

				<?php if( have_rows('employees') ):

				while( have_rows('employees') ): the_row();

				?>

					<div class="poi" latitude="<?php the_sub_field('latitude'); ?>"  longitude="<?php the_sub_field('longitude'); ?>"   label="<?php the_sub_field('earth_name'); ?>">
					</div>

					<?php

						endwhile;

					endif;

					?>

				</div>

		</div>

		<script> initializeInteractiveEarth3D(); </script>

</article>