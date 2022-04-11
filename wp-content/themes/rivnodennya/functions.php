<?php
/**
 * Twenty Twelve functions and definitions
 *
 * Sets up the theme and provides some helper functions, which are used
 * in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * When using a child theme (see https://codex.wordpress.org/Theme_Development and
 * https://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook.
 *
 * For more information on hooks, actions, and filters, @link https://codex.wordpress.org/Plugin_API
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

// Set up the content width value based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 625;

/**
 * Twenty Twelve setup.
 *
 * Sets up theme defaults and registers the various WordPress features that
 * Twenty Twelve supports.
 *
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_editor_style() To add a Visual Editor stylesheet.
 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,
 * 	custom background, and post formats.
 * @uses register_nav_menu() To add support for navigation menus.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_setup() {
	/*
	 * Makes Twenty Twelve available for translation.
	 *
	 * Translations can be added to the /languages/ directory.
	 * If you're building a theme based on Twenty Twelve, use a find and replace
	 * to change 'rivnodennya' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'rivnodennya', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// This theme supports a variety of post formats.
	add_theme_support( 'post-formats', array( 'aside', 'image', 'link', 'quote', 'status' ) );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menu( 'primary', __( 'Primary Menu', 'rivnodennya' ) );

	/*
	 * This theme supports custom background color and image,
	 * and here we also set up the default background color.
	 */
	add_theme_support( 'custom-background', array(
		'default-color' => 'e6e6e6',
	) );

	// This theme uses a custom image size for featured images, displayed on "standard" posts.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 624, 9999 ); // Unlimited height, soft crop
}
add_action( 'after_setup_theme', 'rivnodennya_setup' );

/**
 * Add support for a custom header image.
 */
require( get_template_directory() . '/inc/custom-header.php' );

/**
 * Return the Google font stylesheet URL if available.
 *
 * The use of Open Sans by default is localized. For languages that use
 * characters not supported by the font, the font can be disabled.
 *
 * @since Twenty Twelve 1.2
 *
 * @return string Font stylesheet or empty string if disabled.
 */
function rivnodennya_get_font_url() {
	$font_url = '';

	/* translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'rivnodennya' ) ) {
		$subsets = 'latin,latin-ext';

		/* translators: To add an additional Open Sans character subset specific to your language,
		 * translate this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language.
		 */
		$subset = _x( 'no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'rivnodennya' );

		if ( 'cyrillic' == $subset )
			$subsets .= ',cyrillic,cyrillic-ext';
		elseif ( 'greek' == $subset )
			$subsets .= ',greek,greek-ext';
		elseif ( 'vietnamese' == $subset )
			$subsets .= ',vietnamese';

		$query_args = array(
			'family' => 'Open+Sans:400italic,700italic,400,700',
			'subset' => $subsets,
		);
		$font_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
	}

	return $font_url;
}

/**
 * Enqueue scripts and styles for front-end.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_scripts_styles() {
	global $wp_styles;

	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	// Adds JavaScript for handling the navigation menu hide-and-show behavior.
	wp_enqueue_script( 'rivnodennya-navigation', get_template_directory_uri() . '/js/navigation.js', array( 'jquery' ), '20140711', true );

	$font_url = rivnodennya_get_font_url();
	if ( ! empty( $font_url ) )
		wp_enqueue_style( 'rivnodennya-fonts', esc_url_raw( $font_url ), array(), null );

	// Loads our main stylesheet.
	wp_enqueue_style( 'rivnodennya-style', get_stylesheet_uri() );

	// Loads the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'rivnodennya-ie', get_template_directory_uri() . '/css/ie.css', array( 'rivnodennya-style' ), '20121010' );
	$wp_styles->add_data( 'rivnodennya-ie', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'rivnodennya_scripts_styles' );

/**
 * Filter TinyMCE CSS path to include Google Fonts.
 *
 * Adds additional stylesheets to the TinyMCE editor if needed.
 *
 * @uses rivnodennya_get_font_url() To get the Google Font stylesheet URL.
 *
 * @since Twenty Twelve 1.2
 *
 * @param string $mce_css CSS path to load in TinyMCE.
 * @return string Filtered CSS path.
 */
function rivnodennya_mce_css( $mce_css ) {
	$font_url = rivnodennya_get_font_url();

	if ( empty( $font_url ) )
		return $mce_css;

	if ( ! empty( $mce_css ) )
		$mce_css .= ',';

	$mce_css .= esc_url_raw( str_replace( ',', '%2C', $font_url ) );

	return $mce_css;
}
add_filter( 'mce_css', 'rivnodennya_mce_css' );

/**
 * Filter the page title.
 *
 * Creates a nicely formatted and more specific title element text
 * for output in head of document, based on current view.
 *
 * @since Twenty Twelve 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string Filtered title.
 */
function rivnodennya_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() )
		return $title;

	// Add the site name.
	$title .= get_bloginfo( 'name', 'display' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		$title = "$title $sep $site_description";

	// Add a page number if necessary.
	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() )
		$title = "$title $sep " . sprintf( __( 'Page %s', 'rivnodennya' ), max( $paged, $page ) );

	return $title;
}
add_filter( 'wp_title', 'rivnodennya_wp_title', 10, 2 );

/**
 * Filter the page menu arguments.
 *
 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_page_menu_args( $args ) {
	if ( ! isset( $args['show_home'] ) )
		$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'rivnodennya_page_menu_args' );

/**
 * Register sidebars.
 *
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_widgets_init() {
	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'rivnodennya' ),
		'id' => 'sidebar-1',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'rivnodennya' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'First Front Page Widget Area', 'rivnodennya' ),
		'id' => 'sidebar-2',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'rivnodennya' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	register_sidebar( array(
		'name' => __( 'Second Front Page Widget Area', 'rivnodennya' ),
		'id' => 'sidebar-3',
		'description' => __( 'Appears when using the optional Front Page template with a page set as Static Front Page', 'rivnodennya' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
	if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name' => 'FooterSidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
	if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name' => 'FooterComments',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
	if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name' => 'FooterArchives',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
	if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name' => 'FooterCategories',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
	if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name' => 'LeftColumn',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
	if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'name' => 'TopSidebar',
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
}
add_action( 'widgets_init', 'rivnodennya_widgets_init' );

if ( ! function_exists( 'rivnodennya_content_nav' ) ) :
/**
 * Displays navigation to next/previous pages when applicable.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_content_nav( $html_id ) {
	global $wp_query;

	if ( $wp_query->max_num_pages > 1 ) : ?>
		<nav id="<?php echo esc_attr( $html_id ); ?>" class="navigation" role="navigation">
			<h3 class="assistive-text"><?php _e( 'Post navigation', 'rivnodennya' ); ?></h3>
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts', 'rivnodennya' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>', 'rivnodennya' ) ); ?></div>
		</nav><!-- .navigation -->
	<?php endif;
}
endif;

if ( ! function_exists( 'rivnodennya_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own rivnodennya_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_comment ($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :
		// Display trackbacks differently than normal comments.
	?>
	<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
		<p><?php _e( 'Pingback:', 'rivnodennya' ); ?> <?php comment_author_link(); ?> <?php edit_comment_link( __( '(Edit)', 'rivnodennya' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
		// Proceed with normal comments.
		global $post;
		global $wpdb;
		global $blog_id;
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<article id="comment-<?php comment_ID(); ?>" class="comment">
			<header class="comment-meta comment-author vcard">
				<?php
					$sqlstr = "select display_name, id from wp_users where id in(select user_id from wp_".$blog_id."_comments where comment_ID =".$comment->comment_ID.")";
					$name = $wpdb->get_results($sqlstr, ARRAY_A);
					$sqlstr = "select blog_id, path from wp_blogs where blog_id in(select meta_value from wp_usermeta where meta_key='primary_blog' and user_id=(select user_id from wp_".$blog_id."_comments where comment_ID =".$comment->comment_ID."))";
					$link = $wpdb->get_results($sqlstr, ARRAY_A);
					$sqlstr = "select meta_value from wp_".$link [0]["blog_id"]."_postmeta where post_id in(select meta_value from wp_usermeta where user_id=".$name [0]["id"]." and meta_key='wp_".$link [0]["blog_id"]."_user_avatar') and meta_key='_wp_attachment_metadata'";
					$meta_value = $wpdb->get_results($sqlstr, ARRAY_A);					
					$avatars = unserialize ($meta_value [0]["meta_value"]);
					echo "<img src='/wp-content/uploads/sites/".$link [0]["blog_id"]."/".substr ($avatars ["file"], 0, 8).$avatars ["sizes"]["medium"]["file"]."'/>";
					printf( '<cite><b class="fn"><a href="'.$link [0]["path"].'">%1$s</b> %2$s</cite>',
						$name [0]["display_name"],
						// If current post author is also comment author, make it known visually.
						( $comment->user_id === $post->post_author ) ? '<span>' . __( 'Post author', 'rivnodennya' ) . '</span>' : ''
					);
					printf( '<a href="%1$s"><time datetime="%2$s">%3$s</time></a>',
						esc_url( get_comment_link( $comment->comment_ID ) ),
						get_comment_time( 'c' ),
						/* translators: 1: date, 2: time */
						sprintf( __( '%1$s at %2$s', 'rivnodennya' ), get_comment_date(), get_comment_time() )
					);
				?>
			</header><!-- .comment-meta -->

			<?php if ( '0' == $comment->comment_approved ) : ?>
				<p class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'rivnodennya' ); ?></p>
			<?php endif; ?>

			<section class="comment-content comment">
				<?php comment_text(); ?>
				<?php edit_comment_link( __( 'Edit', 'rivnodennya' ), '<p class="edit-link">', '</p>' ); ?>
			</section><!-- .comment-content -->

			<div class="reply">
				<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply', 'rivnodennya' ), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
			</div><!-- .reply -->
		</article><!-- #comment-## -->
	<?php
		break;
	endswitch; // end comment_type check
}
endif;

if ( ! function_exists( 'rivnodennya_entry_meta' ) ) :
/**
 * Set up post entry meta.
 *
 * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.
 *
 * Create your own rivnodennya_entry_meta() to override in a child theme.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_entry_meta() {
	// Translators: used between list items, there is a space after the comma.
	$categories_list = get_the_category_list( __( ', ', 'rivnodennya' ) );

	// Translators: used between list items, there is a space after the comma.
	$tag_list = get_the_tag_list( '', __( ', ', 'rivnodennya' ) );

	global $blog_id;
	$p = get_blog_post($blog_id, get_the_ID());
	$date = sprintf( "<div class='poetrydata'>".esc_html( mysql2date('F j, Y, G:i', $p->post_date) )."</div>");

	$author = sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>',
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		esc_attr( sprintf( __( 'View all posts by %s', 'rivnodennya' ), get_the_author() ) ),
		get_the_author()
	);

	// Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.
	if ( $tag_list ) {
		$utility_text = __( 'This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'rivnodennya' );
	} elseif ( $categories_list ) {
		$utility_text = __( 'This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'rivnodennya' );
	} else {
		$utility_text = __( 'This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'rivnodennya' );
	}

	printf(
		$date
	);
}
endif;

/**
 * Extend the default WordPress body classes.
 *
 * Extends the default WordPress body class to denote:
 * 1. Using a full-width layout, when no active widgets in the sidebar
 *    or full-width template.
 * 2. Front Page template: thumbnail in use and number of sidebars for
 *    widget areas.
 * 3. White or empty background color to change the layout and spacing.
 * 4. Custom fonts enabled.
 * 5. Single or multiple authors.
 *
 * @since Twenty Twelve 1.0
 *
 * @param array $classes Existing class values.
 * @return array Filtered class values.
 */
function rivnodennya_body_class( $classes ) {
	$background_color = get_background_color();
	$background_image = get_background_image();

	if ( ! is_active_sidebar( 'sidebar-1' ) || is_page_template( 'page-templates/full-width.php' ) )
		$classes[] = 'full-width';

	if ( is_page_template( 'page-templates/front-page.php' ) ) {
		$classes[] = 'template-front-page';
		if ( has_post_thumbnail() )
			$classes[] = 'has-post-thumbnail';
		if ( is_active_sidebar( 'sidebar-2' ) && is_active_sidebar( 'sidebar-3' ) )
			$classes[] = 'two-sidebars';
	}

	if ( empty( $background_image ) ) {
		if ( empty( $background_color ) )
			$classes[] = 'custom-background-empty';
		elseif ( in_array( $background_color, array( 'fff', 'ffffff' ) ) )
			$classes[] = 'custom-background-white';
	}

	// Enable custom font class only if the font CSS is queued to load.
	if ( wp_style_is( 'rivnodennya-fonts', 'queue' ) )
		$classes[] = 'custom-font-enabled';

	if ( ! is_multi_author() )
		$classes[] = 'single-author';

	return $classes;
}
add_filter( 'body_class', 'rivnodennya_body_class' );

/**
 * Adjust content width in certain contexts.
 *
 * Adjusts content_width value for full-width and single image attachment
 * templates, and when there are no active widgets in the sidebar.
 *
 * @since Twenty Twelve 1.0
 */
function rivnodennya_content_width()
{
	if ( is_page_template( 'page-templates/full-width.php' ) || is_attachment() || ! is_active_sidebar( 'sidebar-1' ) )
	{
		global $content_width;
		$content_width = 960;
	}
}
add_action( 'template_redirect', 'rivnodennya_content_width' );

/**
 * Register postMessage support.
 *
 * Add postMessage support for site title and description for the Customizer.
 *
 * @since Twenty Twelve 1.0
 *
 * @param WP_Customize_Manager $wp_customize Customizer object.
 */
function rivnodennya_customize_register ($wp_customize)
{
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

add_action ("customize_register", "rivnodennya_customize_register");

/**
 * Enqueue Javascript postMessage handlers for the Customizer.
 *
 * Binds JS handlers to make the Customizer preview reload changes asynchronously.
 *
 * @since Twenty Twelve 1.0
 */
 
function rivnodennya_customize_preview_js ()
{
	wp_enqueue_script ("rivnodennya-customizer", get_template_directory_uri ()."/js/theme-customizer.js", array ("customize-preview"), "20141120", true);
}
add_action("customize_preview_init", "rivnodennya_customize_preview_js");

function register_buttons_editor ($buttons)
{
    //register buttons with their id.
    array_push($buttons, "su_spacer");
    return $buttons;
}

add_filter("mce_buttons", "register_buttons_editor");

/*кнопки текстового редактора*/
function enable_more_buttons ($buttons)
{
	$buttons[] = 'hr';
	$buttons[] = 'sub';
	$buttons[] = 'sup';
	$buttons[] = 'fontselect';
	$buttons[] = 'fontsizeselect';
	$buttons[] = 'cleanup';
	$buttons[] = 'styleselect';
	$buttons[] = 'anchor';
	return $buttons;
}

add_filter("mce_buttons_2", "enable_more_buttons");

function register_buttons_editor1 ($buttons)
{
    //register buttons with their id.
    array_push($buttons, "su_float_clear");
	array_push($buttons, "light-gallery");
	array_push($buttons, "images_set");
    return $buttons;
}

add_filter("mce_buttons_3", "register_buttons_editor1");

add_action("admin_head", "moi_novii_style");

function moi_novii_style ()
{
	global $user_ID;
	if ($user_ID != 1)
	print "<style>
		/*адмінка*/
		body #cft dt
		{
			margin-left: 0px;
			display:none;
		}
		body #cft dd
		{
			clear:both; margin:5px 0px 20px 0px;
		}
	</style>";
}

add_action ("admin_head", "mystylesheet");

function mystylesheet ()
{
	global $wpdb;
	global $blog_id;
	if ($blog_id == 14)
	{
		$sqlstr = "select term_taxonomy_id from wp_14_term_taxonomy where parent=4";
		$autors = $wpdb->get_results($sqlstr, ARRAY_A);
		wp_enqueue_style ("style-1", "/wp-content/themes/rivnodennya/teachers.css");
		foreach ($autors as $autor)
		{
			$my_style_line="#parent option[value=\"".$autor ["term_taxonomy_id"]."\"]
			{
				display: none;
			}";
			wp_add_inline_style ("style-1", $my_style_line);
		}
		$my_style_line="#parent option[value=\"-1\"], .selectit #in-category-4, .selectit #in-category-5, #cb-select-4, #cb-select-5, #tag-4 .edit, #tag-4 .hide-if-no-js, #tag-4 .delete, #tag-5 .edit, #tag-5 .hide-if-no-js, #tag-5 .delete
		{
			display: none;
		}";
		wp_add_inline_style ("style-1", $my_style_line);
		$my_style_line="#tag-4 .row-title, #tag-5 .row-title
		{
			pointer-events: none;
		}";
		wp_add_inline_style ("style-1", $my_style_line);
	}
	$sqlstr = "select term_id from wp_".$blog_id."_terms where slug='obrazotvorche-mystetstvo'";
	$terms = $wpdb->get_results($sqlstr, ARRAY_A);
	wp_enqueue_style ("style-1", "/wp-content/themes/rivnodennya/teachers.css");
	if (count ($terms) > 0)
	{
		$my_style_line="#parent option[value=\"".$terms [0]["term_id"]."\"]
		{
			display: none;
		}";
		wp_add_inline_style ("style-1", $my_style_line);
	}
	$sqlstr = "select term_id from wp_".$blog_id."_terms where slug='moyi-svitlyny'";
	$terms = $wpdb->get_results($sqlstr, ARRAY_A);
	wp_enqueue_style ("style-1", "/wp-content/themes/rivnodennya/teachers.css");
	if (count ($terms) > 0)
	{
		$my_style_line="#parent option[value=\"".$terms [0]["term_id"]."\"]
		{
			display: none;
		}";
		wp_add_inline_style ("style-1", $my_style_line);
	}
	$sqlstr = "select term_id from wp_".$blog_id."_terms where slug='pro-avtora-golovna-storinka'";
	$terms = $wpdb->get_results($sqlstr, ARRAY_A);
	wp_enqueue_style ("style-1", "/wp-content/themes/rivnodennya/teachers.css");
	if (count ($terms) > 0)
	{
		$my_style_line="#parent option[value=\"".$terms [0]["term_id"]."\"]
		{
			display: none;
		}";
		wp_add_inline_style ("style-1", $my_style_line);
	}
	echo '<style>@import url("/wp-content/themes/rivnodennya/admin_general.css");</style>';
	global $user_ID;
	switch ($user_ID)
	{
		case 1:
			echo '<style>@import url("/wp-content/themes/rivnodennya/admin_style_virovec.css");</style>';
			break;		
		default:
			echo '<style>@import url("/wp-content/themes/rivnodennya/admin_style_khvorost.css");</style>';
		break;
	}
}

function wp_corenavi ()
{
	global $wp_query;
	$pages = "";
	$max = $wp_query->max_num_pages;
	if (!$current = get_query_var ("paged"))
		$current = 1;
	$a ["base"] = str_replace (999999999, "%#%", get_pagenum_link (999999999));
	$a ["total"] = $max;
	$a ["current"] = $current;
	$total = 1; //1 - выводить текст "Страница N из N", 0 - не выводить
	$a ["mid_size"] = 3; //сколько ссылок показывать слева и справа от текущей
	$a ["end_size"] = 1; //сколько ссылок показывать в начале и в конце
	$a ["prev_text"] = '&laquo;'; //текст ссылки "Предыдущая страница"
	$a ["next_text"] = '&raquo;'; //текст ссылки "Следующая страница"
	if ($max > 1)
		echo '<div class="navigation">';
	if ($total == 1 && $max > 1)
		$pages = '<span class="pages">Сторінка '.$current." з ".$max."</span>\r\n";
	echo $pages.paginate_links($a);
	if ($max > 1)
		echo "</div>";
}

add_filter ("upload_size_limit", "PBP_increase_upload");

function PBP_increase_upload ($bytes)
{
	return 10485760; // 1 megabyte
}
 
function m_adjacent_image_link ($prev = true, $size = "thumbnail", $text = false)
{
    $post = get_post ();
    $attachments = array_values (get_children (array ("post_parent" => $post->post_parent, "post_status" => "inherit", "post_type" => "attachment", "post_mime_type" => "image", "order" => "ASC", "orderby" => "menu_order ID"))); 
    foreach ($attachments as $k => $attachment)
        if ($attachment->ID == $post->ID)
            break;
    $output = '';
    $attachment_id = 0; 
    if ($attachments)
	{
        $k = $prev ? $k - 1 : $k + 1; 
        if (isset ($attachments [$k]))
		{
            $attachment_id = $attachments [$k]->ID;
            $output = wp_get_attachment_link ($attachment_id, $size, true, false, $text);
        }
    } 
    $adjacent = $prev ? 'previous' : 'next'; 
    /**
     * Filter the adjacent image link.
     *
     * The dynamic portion of the hook name, `$adjacent`, refers to the type of adjacency,
     * either 'next', or 'previous'.
     *
     * @since 3.5.0
     *
     * @param string $output        Adjacent image HTML markup.
     * @param int    $attachment_id Attachment ID
     * @param string $size          Image size.
     * @param string $text          Link text.
     */
    return apply_filters( "{$adjacent}_image_link", $output, $attachment_id, $size, $text );
}
 
function m_previous_image_link ($size = 'thumbnail', $text = false)
{
	return m_adjacent_image_link (true, $size, $text);
}
function m_next_image_link ($size = 'thumbnail', $text = false)
{
	return m_adjacent_image_link (false, $size, $text);
}

function add_media_upload_scripts ()
{
    if (is_admin ())
	{
         return;
    }
    wp_enqueue_media ();
}

function my_style_load()
{
	global $blog_id;
	$theme_uri = get_template_directory_uri();
	if ($blog_id == 1)
		wp_register_style ("my_theme_style", $theme_uri."/home.css", false, "0.1");
	else
		wp_register_style ("my_theme_style", $theme_uri."/user.css", false, "0.1");
	wp_enqueue_style ("my_theme_style");
}
add_action('wp_enqueue_scripts', 'my_style_load');

add_action('wp_enqueue_scripts', 'add_media_upload_scripts');