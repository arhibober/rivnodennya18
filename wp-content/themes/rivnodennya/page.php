<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

	get_header ();
	global $user_ID;	
	$sqlstr = "select path from wp_blogs where blog_id=(select meta_value from wp_usermeta where user_id=".$user_ID." and meta_key='primary_blog')";
	$paths = $wpdb->get_results($sqlstr, ARRAY_A);
	global $post;
    $post_slug = $post->post_name;
	if (($post_slug == "login") && ($_GET ["action"] == "profile") && ($user_ID != 4))
		header ("Location: ".$paths [0]["path"]."vash-profil");
	if (($post_slug == "vash-profil") && ($blog_id != 14))
		echo "<style>
			.entry-title
			{
				color: #6c300b !important;
				white-space: nowrap;
				text-transform: uppercase;
				font-weight: bold;
			}
		</style>";
?>

	<div id="primary" class="site-content">
		<div id="content" role="main">

			<?php while (have_posts ()) :
				the_post ();
				get_template_part ("content", "page");
				comments_template ("", true);
				endwhile; // end of the loop.
			?>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php
	get_sidebar ();
	get_footer ();
?>