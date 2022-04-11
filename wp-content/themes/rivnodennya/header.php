<?php
/**
 * The Header template for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

if ($_GET ["loggedout"] == "true")
	header ("Location: /");
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7"
<?php
	language_attributes ();
?>
>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8"
<?php
	language_attributes ();
?>
>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php
	language_attributes ();
?>
>
<!--<![endif]-->
<head>
<meta charset="
<?php
	bloginfo ("charset");
?>
"/>
<meta name="viewport" content="width=device-width"/>
<meta property="og:image"              content="http://rivnodennya17.com/virovec/wp-content/uploads/sites/9/2015/09/cropped-favicon-300x300.png" />

<title>
	<?php
		wp_title ("|", true, "right");
	?>
	</title>
<link rel="profile" href="http://gmpg.org/xfn/11"/>
<link rel="pingback" href="
<?php
	bloginfo ("pingback_url");
?>
"/>
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php
	echo get_template_directory_uri ();
?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<?php 
	include "simple_html_dom.php";
	wp_enqueue_script ("jquery");
	wp_head ();	
	global $blog_id;
	global $wpdb;
	$sqlstr = "select path from wp_blogs where blog_id=".$blog_id;
	$path = $wpdb->get_results ($sqlstr, ARRAY_A);	
	if ($_SERVER ["REQUEST_URI"] == $path [0]["path"]."category/poeziya/")
		header ("Location: ".$path [0]["path"]);
	if (($_GET ["autor"] > 0) && ($_SERVER ["REQUEST_URI"] == "/teachers/category/poeziya/?autor=".$_GET ["autor"]))
		header ("Location: /teachers/?autor=".$_GET ["autor"]);
	if ($_GET ["deleted"] == 1)
		header ("Location: ".$path [0]["path"]."vydalyty-post/");
	function closetags ($html)
	{
		preg_match_all ("#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU", $html, $result);
		$openedtags = $result [1];
		preg_match_all ("#</([a-z]+)>#iU", $html, $result);
		$closedtags = $result[1];
		$len_opened = count ($openedtags);
		if (count ($closedtags) == $len_opened)
			return $html;
		$openedtags = array_reverse($openedtags);
		for ($i = 0; $i < $len_opened; $i++)
			if (!in_array ($openedtags [$i], $closedtags))
				$html .= "</".$openedtags [$i].'>';
			else
				unset ($closedtags [array_search ($openedtags [$i], $closedtags)]);
		return $html;
	}
	echo "<style>";
	if ($blog_id == 1)
		echo '@import url("/wp-content/themes/rivnodennya/home.css");';
	else		
		echo '@import url("/wp-content/themes/rivnodennya/user.css");';
	if ($_SERVER ["REQUEST_URI"] == "/vydalyty-post/")
		echo "#primary
			{
				padding-top: 20px;
				padding-bottom: 20px;
				margin-top: 20px;
			}";
	if ($blog_id == 14)
		echo ".wpuf-category-checklist #in-category-4, .wpuf-category-checklist #in-category-5
			{
				display: none;
			}";
	if (($blog_id == 14) && (strstr ($_SERVER ["REQUEST_URI"], "/category/poety/")) && (substr_count ($_SERVER ["REQUEST_URI"], "/") == 5))
	{
		$sqlstr = "select term_id from wp_14_terms where slug='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1)."'";
		$autor = $wpdb->get_results ($sqlstr, ARRAY_A);
		header ("Location: /teachers/?autor=".$autor [0]["term_id"]);
	}
	echo "#menu-avtory a[href=\"".$path [0]["path"]."\"]
	{
		color: #793D74 !important;
	}";
	$path = $wpdb->get_results ($sqlstr, ARRAY_A);
	global $user_ID;
	if ($user_ID > 0)
		echo "#enter-text
			{
				display: none !important;
			}";
	else
		echo ".reply
			{
				display: none !important;
			}";
if (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="))
{
	$sqlstr = "select post_content, id from wp_".$blog_id."_posts where post_content like '%[gallery%' and post_status='publish'";
	$posts2 = $wpdb->get_results ($sqlstr, ARRAY_A);
	foreach ($posts2 as $post2)
		if ((strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15).",")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15).",")) || (strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15)."\"")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15)."\"")))
		{
			$sqlstr1 = "select slug from wp_".$blog_id."_terms where term_id in(select term_taxonomy_id from wp_".$blog_id."_term_relationships where object_id=".$post2 ["id"].") and (slug='obrazotvorche-mystetstvo' or slug='moyi-svitlyny')";
			$terms = $wpdb->get_results ($sqlstr1, ARRAY_A);
			if ($blog_id == 14)
			{				
				$sqlstr1 = "select term_taxonomy_id from wp_14_term_relationships where object_id=".$post2 ["id"]." and term_taxonomy_id in(select term_id from wp_14_term_taxonomy where parent=4";
				$poets = $wpdb->get_results ($sqlstr1, ARRAY_A);
				foreach ($terms as $term)
				{
					echo "#top_sidebar a[href=\"/teachers/category/".$term ["slug"]."/autor=".$poets [0]["term_taxonomy_id"]."\"]
						{	
							background: #D9B66D;
							transition: all 1s linear;
						}
						@media screen and (max-width: 600px)
						{
							#top_sidebar a[href=\"/teachers/category/".$term ["slug"]."/autor=".$poets [0]["term_taxonomy_id"]."\"]
							{
								background: none;
								color: #84469F !important;
								transition: all 1s linear;							
							}
						}";
			}
			}
			foreach ($terms as $term)
			{
				echo "#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$term ["slug"]."/\"]
					{	
						background: #D9B66D;
						transition:all 1s linear;
					}
					#top_sidebar a[href=\"".$path [0]["path"]."category/".$term ["slug"]."/\"]
					{	
						background: #D9B66D;
						transition: all 1s linear;
					}
					@media screen and (max-width: 600px)
					{
						#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$term ["slug"]."/\"]
						{
							background: none;
							color: #84469F !important;
							transition: all 1s linear;
						}
						#top_sidebar a[href=\"".$path [0]["path"]."category/".$term ["slug"]."/\"]
						{
							background: none;
							color: #84469F !important;
							transition:all 1s linear;
						}";
			}
		}
	}
	echo "#secondary a[href=\"".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1)."\"]
		{
			color: #FFFDDC;
		}
		#secondary a[href=\"".$_SERVER ["REQUEST_URI"]."\"]
		{
			color: #FFFDDC;
		}";
	if (((substr_count ($_SERVER ["REQUEST_URI"], "/") == 4) || (substr_count ($_SERVER ["REQUEST_URI"], "/") == 7)) && ((!strstr ($_SERVER ["REQUEST_URI"], $path [0]["path"]."category/")) || (strstr ($_SERVER ["REQUEST_URI"], $path [0]["path"].substr ($path [0]["path"], 1, strlen ($path [0]["path"]) - 1)."category/"))) && ((!strstr ($_SERVER ["REQUEST_URI"], $path [0]["path"]."page/")) || (strstr ($_SERVER ["REQUEST_URI"], $path [0]["path"].substr ($path [0]["path"], 1, strlen ($path [0]["path"]) - 1)."page/"))))
	{
		$sqlstr = "select slug from wp_".$blog_id."_terms where term_id in(select term_taxonomy_id from wp_".$blog_id."_term_relationships where object_id in(select id from wp_".$blog_id."_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/")) - 1)."')) and (slug='obrazotvorche-mystetstvo' or slug='moyi-svitlyny')";
		$slugs = $wpdb->get_results ($sqlstr, ARRAY_A);
		if ($blog_id == 14)
		{
			$sqlstr = "select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select id from wp_14_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/")) - 1)."'))";
			$poet = $wpdb->get_results ($sqlstr, ARRAY_A);
			foreach ($slugs as $slug)
			{
				echo "#top_sidebar a[href=\"/teachers/category/".$slug ["slug"]."/?autor=".$poet [0]["term_id"]."\"]
				{
					background: #D9B66D;
					transition: all 1s linear;
				}
				.teachers a[href=\"/teachers/?autor=".$poet [0]["term_id"]."\"]
				{	
					color: #FFFDDC;
				}";
				echo "@media screen and (max-width: 600px)
				{
					#top_sidebar a[href=\"/teachers/category/".$slug ["slug"]."/?autor=".$poet [0]["term_id"]."\"]
					{	
						background: none;
						color: #84469F !important;
						transition: all 1s linear;
					}
				}";
			}
		}
		else			
			foreach ($slugs as $slug)
			{
				echo "#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$slug ["slug"]."/\"]
				{
					background: #D9B66D;
					transition: all 1s linear;
				}
				#top_sidebar a[href=\"".$path [0]["path"]."category/".$slug ["slug"]."/\"]
				{
					background: #D9B66D;
					transition: all 1s linear;
				}
				@media screen and (max-width: 600px)
				{
					#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$slug ["slug"]."/\"]
					{
						background: none;
						color: #84469F !important;
						transition: all 1s linear;
					}
					#top_sidebar a[href=\"".$path [0]["path"]."category/".$slug ["slug"]."/\"]
					{
						background: none;
						color: #84469F !important;
						transition: all 1s linear;
					}
				}";
			}
	}
	echo "</style>";
?>
	<script src='https://www.google.com/recaptcha/api.js'></script>
</head>

<body <?php
	body_class ();
?>>
<a href="/">
<div id="upper_header">
<div id="back-autor">Вийти на головну сторінку</div>
<div id="slog">Літературно-мистецький портал</div>
<div id="upper_header_left"></div>
<div id="upper_header_center"></div>
<div id="upper_header_right"></div>
<div id="violette"></div>	
<div id="yellow"></div>
</div>
</a>
<div id="page" class="hfeed site">	
	<header id="masthead" class="site-header">		
		<nav id="site-navigation" class="main-navigation">
			<button class="menu-toggle">
				<?php
					_e ("Menu", "rivnodennya");
				?>
			</button>
			<a class="assistive-text" href="#content" title="
				<?php
					esc_attr_e ("Skip to content", "rivnodennya");
				?>
			">
			<?php
				_e ("Skip to content", "rivnodennya");
			?>
			</a>
			<?php
				wp_nav_menu (array ("theme_location" => "primary", "menu_class" => "nav-menu"));
			?>
		</nav><!-- #site-navigation -->
<div id="top_sidebar">
<?php
	if (!function_exists ("dynamic_sidebar") || (!dynamic_sidebar ("TopSidebar")))
	{ 
	}
	if ($blog_id == 14)
		if ($_GET ["autor"] != "")
		{
			$sqlstr1 = "select slug, name from wp_14_terms where term_id in(select term_id from wp_14_term_taxonomy where parent=5 and term_id!=17 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id=".$_GET ["autor"].")))";
			$terms = $wpdb->get_results($sqlstr1, ARRAY_A);
			echo "<ul>";
			foreach ($terms as $term)
				echo "<li class='cat-item'><a href='/teachers/category/".$term ["slug"]."/?autor=".$_GET ["autor"]."'>".$term ["name"]."</a></li>";
			echo "</ul>";
		}
		else
			if ((substr_count ($_SERVER ["REQUEST_URI"], "/") == 3) && (!strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")))
			{
				$sqlstr1 = "select slug, name from wp_14_terms where term_id in(select term_id from wp_14_term_taxonomy where parent=5 and term_id!=17 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select id from wp_14_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER["REQUEST_URI"]) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1)."'))))))";
				$terms = $wpdb->get_results($sqlstr1, ARRAY_A);
				$sqlstr2 = "select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships id where object_id in(select id from wp_14_posts where post_name='".substr (strrchr (substr ($_SERVER["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1)."'))";
				$poet = $wpdb->get_results($sqlstr2, ARRAY_A);
				echo "<ul>";
				foreach ($terms as $term)
					echo "<li class='cat-item'>
						<a href='/teachers/category/".$term ["slug"]."/?autor=".$poet [0]["term_id"]."'>".$term ["name"]."</a>
					</li>";
				echo "</ul>";
			}
			else
				if ((substr_count ($_SERVER ["REQUEST_URI"], "/") == 4) || (substr_count ($_SERVER ["REQUEST_URI"], "/") == 7))
				{
					$sqlstr1 = "select slug, name from wp_14_terms where term_id in(select term_id from wp_14_term_taxonomy where parent=5 and term_id!=17 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select id from wp_14_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/")) - 1)."'))))))";
					$terms = $wpdb->get_results ($sqlstr1, ARRAY_A);
					$sqlstr2 = "select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships id where object_id in(select id from wp_14_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/")) - 1)."'))";
					$poet = $wpdb->get_results ($sqlstr2, ARRAY_A);
					echo "<ul>";
					foreach ($terms as $term)
						echo "<li class='cat-item'>
							<a href='/teachers/category/".$term ["slug"]."/?autor=".$poet [0]["term_id"]."'>".$term ["name"]."</a>
						</li>";
					echo "</ul>";
				}
				else					
					if (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="))
					{
						$sqlstr = "select id, post_name, post_title, post_content from wp_14_posts where post_content like '%[gallery%' and post_status='publish'";
						$posts2 = $wpdb->get_results ($sqlstr, ARRAY_A);
						foreach ($posts2 as $post2)
							if ((strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15).",")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15).",")) || (strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15)."\"")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15)."\"")))
							{								
								$sqlstr1 = "select slug, name from wp_14_terms where term_id in(select term_id from wp_14_term_taxonomy where parent=5 and term_id!=17 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id='".$post2 ["id"]."'))))))";
								$terms = $wpdb->get_results ($sqlstr1, ARRAY_A);
								$sqlstr2 = "select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships id where object_id='".$post2 ["id"]."')";
								$poet = $wpdb->get_results ($sqlstr2, ARRAY_A);
								echo "<ul>";
								foreach ($terms as $term)
									echo "<li class='cat-item'>
										<a href='/teachers/category/".$term ["slug"]."/?autor=".$poet [0]["term_id"]."'>".$term ["name"]."</a>
									</li>";
								echo "</ul>";
							}
					}
					
?>
</div>
</header><!-- #masthead -->
<div id="main" class="wrapper">
<div id="left_column">
<?php
	switch ($blog_id)
	{
		case 1:
?>
<div class="poster">
<?php
	$args = array (
		'page_id' => 122,
		'posts_per_page' => 1
	);
	$my_pages = get_posts ($args);
	foreach ($my_pages as $post) :
		setup_postdata($post);
?>
<h2>
<?php
	the_title ();
?>
</h2>
<?php 
	echo "<div class='about-text'>";
	the_content ();
	echo "</div>";
	if (($user_ID == 1) || ($user_ID == 7))
		echo "<a href='/wp-admin/post.php?post=122&action=edit' class='post-edit-link'>Редагувати</a><br/>
			<a href='/edit/?pid=122&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>
			<a href='/".wp_nonce_url ("wp-admin/post.php?action=delete&post=122", "delete-post_122")."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link'>Видалити пост</a>";
	endforeach;
	wp_reset_query ();
?>
</div>
<?php
	break;
	case 9:
?>
<div class="about-autor">
<?php
	$args = array (
		'page_id' => 138,
		'posts_per_page' => 1
	);
	$my_pages = get_posts ($args);
	foreach ($my_pages as $post) :
		setup_postdata($post);
?>
<h2>
<?php
	the_title ();
?>
</h2>
<?php 
	echo "<div class='about-text'>";
	the_content ();
	echo "</div>";
	if (($user_ID == 1) || ($user_ID == 7))
		echo "<a href='/virovec/wp-admin/post.php?post=138&action=edit' class='post-edit-link'>Редагувати</a><br/>
			<a href='/virovec/edit/?pid=138&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>			
			<a href='/virovec/".wp_nonce_url ("wp-admin/post.php?action=delete&post=138", "delete-post_138")."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link'>Видалити пост</a>";	
	endforeach;
	wp_reset_query ();
?>
</div>
<?php
	break;
	case 13:
?>
<div class="about-autor">
<?php
	$args = array (
	'page_id' => 100,
	'posts_per_page' => 1
);
$my_pages = get_posts ($args);
foreach ($my_pages as $post):
	setup_postdata($post);
?>
<h2>
<?php
	the_title ();
?>
</h2>
<?php 
echo "<div class='about-text'>";
the_content ();
echo "</div>";
if (($user_ID == 1) || ($user_ID == 2) || ($user_ID == 7))
	echo "<a href='/khvorost/wp-admin/post.php?post=100&action=edit' class='post-edit-link'>Редагувати</a><br/>
		<a href='/khvorost/edit/?pid=100&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>			
		<a href='/khvorost/".wp_nonce_url ("wp-admin/post.php?action=delete&post=100", "delete-post_100")."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link'>Видалити пост</a>";
endforeach;
wp_reset_query ();
?>
</div>
<?php
	break;
	case 15:
?>
<div class="about-autor">
	<?php
		$args = array (
		'page_id' => 41,
		'posts_per_page' => 1
	);
	$my_pages = get_posts( $args );
	foreach($my_pages as $post) : setup_postdata($post); ?>
	<h2><?php the_title(); ?></h2>
	<?php 
		echo "<div class='about-text'>";
		the_content ();
	echo "</div>";
	if (($user_ID == 1) || ($user_ID == 3) || ($user_ID == 7))
		echo "<a href='/bashkirova/wp-admin/post.php?post=41&action=edit' class='post-edit-link'>Редагувати</a><br/>
			<a href='/bashkirova/edit/?pid=41&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>
			<a href='/bashkirova/".wp_nonce_url ("wp-admin/post.php?action=delete&post=41", "delete-post_41")."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link'>Видалити пост</a>";
	endforeach;
		wp_reset_query ();
	?>
	</div>
	<?php
		break;
		case 16:
	?>
	<div class="about-autor">
	<?php
		$args = array
		(
			'page_id' => 42,
			'posts_per_page' => 1
		);
		$my_pages = get_posts ($args);
		foreach ($my_pages as $post) :
			setup_postdata ($post);
	?>
	<h2>
	<?php
		the_title ();
	?>
	</h2>
	<?php 
		echo "<div class='about-text'>";
		the_content ();
		echo "</div>";
		if (($user_ID == 1) || ($user_ID == 5) || ($user_ID == 7))
			echo "<a href='/olir/wp-admin/post.php?post=42&action=edit' class='post-edit-link'>Редагувати</a><br/>
				<a href='/olir/edit/?pid=42&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>
				<a href='/olir/".wp_nonce_url ("wp-admin/post.php?action=delete&post=42", "delete-post_42")."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link'>Видалити пост</a>";
		endforeach;
		wp_reset_query ();
	?>
	</div>
	<?php
		break;
		case 17:
	?>
	<div class="about-autor">
		<?php
			$args = array
			(
				"page_id" => 38,
				"posts_per_page" => 1
			);
			$my_pages = get_posts( $args );
			foreach($my_pages as $post) :
				setup_postdata($post);
		?>
	<h2>
	<?php
		the_title ();
	?>
	</h2>
	<?php 
		echo "<div class='about-text'>";
		the_content ();
		echo "</div>";
		if (($user_ID == 1) || ($user_ID == 6) || ($user_ID == 7))
			echo "<a href='/naumenko/wp-admin/post.php?post=38&action=edit' class='post-edit-link'>Редагувати</a><br/>
				<a href='/naumenko/edit/?pid=38&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>
				<a href='/naumenko/".wp_nonce_url ("wp-admin/post.php?action=delete&post=38", "delete-post_38")."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link''>Видалити пост</a>";
		endforeach;
		wp_reset_query ();
		?>
	</div>
	<?php
	break;
	case 14:
	$sqlstr = "select object_id from wp_14_term_relationships where term_taxonomy_id=".$_GET ["autor"]." and object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id=17)";
	$post_id = $wpdb->get_results ($sqlstr, ARRAY_A);
	if ((!((((substr_count ($_SERVER ["REQUEST_URI"], "/") == 2) || ((substr_count ($_SERVER ["REQUEST_URI"], "/") == 4) && (strstr ($_SERVER ["REQUEST_URI"], "/teachers/page/")) && (!strstr ($_SERVER ["REQUEST_URI"], "/teachers/teachers/page/")))) || ((substr_count ($_SERVER ["REQUEST_URI"], "/") > 5) || ((strstr ($_SERVER ["REQUEST_URI"], "/teachers/category/")) && (!strstr ($_SERVER ["REQUEST_URI"], "/teachers/teachers/category/"))))) && ($_GET ["autor"] == ""))) && ($_GET ["s"] == "") && ($_SERVER ["REQUEST_URI"] != "/teachers/dodaty-novyj-post/") && (!((substr_count ($_SERVER ["REQUEST_URI"], "/") == 3) && (strstr ($_SERVER ["REQUEST_URI"], "/teachers/edit/")))))
	{
		?>
		<div class="about-autor">
		<?php
			if ($_GET ["autor"] != "")
			{
				$sqlstr = "select object_id from wp_14_term_relationships where term_taxonomy_id=".$_GET ["autor"]." and object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id=17)";
				$post_id = $wpdb->get_results ($sqlstr, ARRAY_A);
				if (count ($post_id) > 0)
					$args = array
					(
						"page_id" => $post_id [0]["object_id"],
						"posts_per_page" => 1
					);
			}
			else
			if ((substr_count ($_SERVER ["REQUEST_URI"], "/") == 3) && (!strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")))
			{
				$sqlstr = "select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select id from wp_14_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) -1)."') and term_taxonomy_id in(select term_taxonomy_id from wp_14_term_taxonomy where parent=4)) and object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id=17)";
				$post_id = $wpdb->get_results ($sqlstr, ARRAY_A);
				if (count ($post_id) > 0)
					$args = array
					(
						"page_id" => $post_id [0]["object_id"],
						"posts_per_page" => 1
					);
			}
			else			
			if ((substr_count ($_SERVER ["REQUEST_URI"], "/") == 4) || (substr_count ($_SERVER ["REQUEST_URI"], "/") == 7))
			{
				$sqlstr = "select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_taxonomy_id from wp_14_term_relationships where object_id in(select id from wp_14_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/")) - 1)."') and term_taxonomy_id in(select term_taxonomy_id from wp_14_term_taxonomy where parent=4)) and object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id=17)";
				$post_id = $wpdb->get_results ($sqlstr, ARRAY_A);				
				if (count ($post_id) > 0)
					$args = array
					(
						"page_id" => $post_id [0]["object_id"],
						"posts_per_page" => 1
					);
			}
			else
			if (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="))
			{
				$sqlstr = "select id, post_name, post_title, post_content from wp_14_posts where post_content like '%[gallery%' and post_status='publish'";
				$posts2 = $wpdb->get_results ($sqlstr, ARRAY_A);
				foreach ($posts2 as $post2)
					if ((strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15).",")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15).",")) || (strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15)."\"")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=")) - 15)."\"")))
					{					
						$sqlstr = "select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_taxonomy_id from wp_14_term_relationships where object_id=".$post2 ["id"]." and term_taxonomy_id in(select term_taxonomy_id from wp_14_term_taxonomy where parent=4)) and object_id in(select object_id from wp_14_term_relationships where term_taxonomy_id=17)";
						$post_id = $wpdb->get_results ($sqlstr, ARRAY_A);						
						if (count ($post_id) > 0)
							$args = array
							(
								"page_id" => $post_id [0]["object_id"],
								"posts_per_page" => 1
							);
					}
			}
			if (count ($post_id) > 0)
			{
				$my_pages = get_posts ($args);
				foreach ($my_pages as $post) :
					setup_postdata ($post);
				?>
				<h2>
				<?php
					the_title ();
				?>
				</h2>
				<?php 
					echo "<div class='about-text'>";
						the_content ();
					echo "</div>";
				if (($user_ID == 1) || ($user_ID == 4) || ($user_ID == 7))
					echo "<a href='/teachers/wp-admin/post.php?post=".$post_id [0]["object_id"]."&action=edit'class='post-edit-link'>Редагувати</a><br/>
						<a href='/teachers/edit/?pid=".$post_id [0]["object_id"]."&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>
						<a href='/teachers/".wp_nonce_url ("wp-admin/post.php?action=delete&post=".$post_id [0]["object_id"], "delete-post_".$post_id [0]["object_id"])."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link'>Видалити пост</a>";
				endforeach;
				wp_reset_query ();
			}
			?>
			</div>
			<?php
	}
	break;
	default:
	break;
}
?>
<div class="teachers">
	<a href="/teachers/"><h2>ЧИТАЄМО УЛЮБЛЕНИХ ПОЕТІВ</h2></a>
	<?php
		$sqlstr2 = "select term_id, name from wp_14_terms where term_id in(select term_id from wp_14_term_taxonomy where parent=4)";
		$poets = $wpdb->get_results ($sqlstr2, ARRAY_A);
		foreach ($poets as $poet)
			echo "<a href='/teachers/?autor=".$poet ["term_id"]."'>".$poet ["name"]."</a><br/>";
		wp_reset_query ();
	?>
</div>
<?php
	/*if ($user_ID > 0)
		echo "<a href='/spysok-chytachskyh-golosiv/'>Список читачських голосів</a>";*/
	if (!function_exists ("dynamic_sidebar") || (!dynamic_sidebar ("LeftColumn")))
	{ 
	}
?>
</div>