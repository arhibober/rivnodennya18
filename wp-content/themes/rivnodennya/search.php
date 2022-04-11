<?php
/**
 * The template for displaying Search Results pages
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
	get_header ();
?>
<section id="primary" class="site-content">
	<div id="content" role="main">
	<?php
		if (have_posts ()) :
	?>
	<header class="page-header">
		<h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'rivnodennya' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
	</header>
	<?php
	/* Start the Loop */
		while ( have_posts ()) :
			the_post();
			get_template_part ("content", get_post_format ());
		endwhile;
		if (function_exists ("wp_corenavi"))
			wp_corenavi ();
		?>
		<?php
		else :
		?>
			<article id="post-0" class="post no-results not-found">
				<header class="entry-header">
					<h1 class="entry-title">
					<?php
						_e ("Nothing Found", "rivnodennya");
					?>
					</h1>
				</header>
				<div class="entry-content">
					<p><?php _e ("Sorry, but nothing matched your search criteria. Please try again with some different keywords.", "rivnodennya"); ?></p>
					<?php
						get_search_form ();
					?>
				</div><!-- .entry-content -->
			</article><!-- #post-0 -->
			<?php
		endif;
		?>
		</div><!-- #content -->
	</section><!-- #primary -->
<?php
	get_sidebar (); 
	get_footer ();
?>