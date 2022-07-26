<?php get_header( bitunit_lite_template_base() ); ?>

	<?php do_action( 'bitunit_lite_render_widget_area', 'full-width-header-area' ); ?>

	<?php do_action( 'bitunit_lite_render_widget_area', 'before-content-area' ); ?>

	<div class="row">

		<div id="primary" <?php bitunit_lite_primary_content_class(); ?>>

			<?php do_action( 'bitunit_lite_render_widget_area', 'before-loop-area' ); ?>

			<main id="main" class="site-main" role="main">

				<?php include bitunit_lite_template_path(); ?>

			</main><!-- #main -->

			<?php do_action( 'bitunit_lite_render_widget_area', 'after-loop-area' ); ?>

		</div><!-- #primary -->

		<?php get_sidebar( 'sidebar' ); // Loads the sidebar.php template. ?>

	</div><!-- .row -->

	<?php do_action( 'bitunit_lite_render_widget_area', 'after-content-area' ); ?>

	<?php do_action( 'bitunit_lite_render_widget_area', 'after-content-full-width-area' ); ?>

<?php get_footer( bitunit_lite_template_base() ); ?>
