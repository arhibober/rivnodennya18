<?php
/**
 * The template for displaying Category pages
 *
 * Used to display archive-type pages for posts in a category.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

function subCat1 ($id, &$sql, $cat, $blog)
{
	global $wpdb;
	if ($id != $cat)
		$sql .= "or ";
	$sql .= "id in(select object_id from wp_".$blog."_term_relationships where term_taxonomy_id=".$id.") ";	
	$sqlstr = "select term_id from wp_".$blog."_term_taxonomy where parent=".$id;
	$children = $wpdb->get_results ($sqlstr, ARRAY_A);
	foreach ($children as $child)
		subCat1 ($child ["term_id"], $sql, $cat, $blog);
}

get_header ();
$sqlstr = "select term_id from wp_".$blog_id."_terms where slug='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) - 1)."'";
$cat_id = $wpdb->get_results ($sqlstr, ARRAY_A);
global $blog_id;
$sqlstr = "select path from wp_blogs where blog_id=".$blog_id;
$path = $wpdb->get_results ($sqlstr, ARRAY_A);
global $user_ID;
$sqlstr = "select meta_value from wp_usermeta where user_id=".$user_ID." and meta_key='primary_blog'";
$blog1 = $wpdb->get_results($sqlstr, ARRAY_A);
echo "<style>";
if ($_GET ["autor"] != "")
{
	if ($_GET ["page1"] > 0)
		echo "#secondary h3 a[href=\"".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "&page1")))."\"]
		{
			color: #FFFDDC;
		}";
	echo "#top_sidebar a[href=\"".$_SERVER ["REQUEST_URI"]."\"]
	{	
		background: #D9B66D;
		transition: all 1s linear;
	}
	#top_sidebar a[href=\"".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (strstr ($_SERVER ["REQUEST_URI"], "/?autor=", true), "/")) - strlen (strstr ($_SERVER ["REQUEST_URI"], "/?autor=")) + 1)."?autor=".$_GET ["autor"]."\"]
	{
		background: #D9B66D;
		transition: all 1s linear;
	}
	.teachers a[href=\"/teachers/?autor=".$_GET ["autor"]."\"]
	{	
		color: #FFFDDC;
	}";
}
else
{
	if ($_GET ["page1"] > 0)
		echo "#secondary h3 a[href=\"".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/?page1")))."\"]
		{
			color: #FFFDDC;
		}";
	echo "#top_sidebar a[href=\"http://rivnodennya17.com".$_SERVER ["REQUEST_URI"]."\"]
	{	
		background: #D9B66D;
		transition: all 1s linear;
	}
	#top_sidebar a[href=\"".$_SERVER ["REQUEST_URI"]."\"]
	{	
		background: #D9B66D;
		transition: all 1s linear;
	}
	#top_sidebar a[href=\"http://rivnodennya17.com".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")))."\"]
	{	
		background: #D9B66D;
		transition: all 1s linear;
	}";
}
echo "@media screen and (max-width: 600px)
{";
	if ($_GET ["autor"] != "")
		echo "#top_sidebar a[href=\"".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (strstr ($_SERVER ["REQUEST_URI"], "/?autor=", true), "/")) - strlen (strstr ($_SERVER ["REQUEST_URI"], "/?autor=")) + 1)."?autor=".$_GET ["autor"]."\"]
		{	
			background: none;
			color: #84469F !important;
			transition: all 1s linear;
		}
		#top_sidebar a[href=\"".$_SERVER ["REQUEST_URI"]."\"]
		{	
			background: none;
			color: #84469F !important;
			transition: all 1s linear;
		}";
	else
		echo "#top_sidebar a[href=\"http://rivnodennya17.com".$_SERVER ["REQUEST_URI"]."\"]
		{	
			background: none;
			color: #84469F !important;
			transition: all 1s linear;
		}
		#top_sidebar a[href=\"".$_SERVER ["REQUEST_URI"]."\"]
		{	
			background: none;
			color: #84469F !important;
			transition: all 1s linear;
		}
		#top_sidebar a[href=\"http://rivnodennya17.com".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")))."\"]
		{	
			background: none;
			color: #84469F !important;
			transition: all 1s linear;
		}";
	echo "
	}
	</style>";
		$is_liric = false;
	$sqlstr = "select parent from wp_".$blog_id."_term_taxonomy where term_id=".$cat_id [0]["term_id"];
	$parents = $wpdb->get_results ($sqlstr, ARRAY_A);
	if ($_GET ["autor"] == 0)
	{
		if ($parents [0]["parent"] > 0)
		{
			while ($parents [0]["parent"] > 0)
			{
				$root_cat = $parents [0]["parent"];
				$sqlstr = "select parent from wp_".$blog_id."_term_taxonomy where term_id=".$parents [0]["parent"];
				$parents = $wpdb->get_results ($sqlstr, ARRAY_A);
			}
			$sqlstr = "select slug from wp_".$blog_id."_terms where term_id=".$root_cat;
			$slugs = $wpdb->get_results ($sqlstr, ARRAY_A);
			if ($slugs [0]["slug"] == "poeziya")
				$is_liric = true;
		}
	}
	else
		if ($parents [0]["parent"] != 5)
		{
			while ($parents [0]["parent"] != 5)
			{
				$root_cat = $parents [0]["parent"];
				$sqlstr = "select parent from wp_".$blog_id."_term_taxonomy where term_id=".$parents [0]["parent"];
				$parents = $wpdb->get_results ($sqlstr, ARRAY_A);
			}
			$sqlstr = "select slug from wp_".$blog_id."_terms where term_id=".$root_cat;
			$slugs = $wpdb->get_results ($sqlstr, ARRAY_A);
			if ($slugs [0]["slug"] == "poeziya")
				$is_liric = true;
		}
?>

	<section id="primary" class="site-content">
		<div id="content" role="main">
		<?php
			if (have_posts ()) :
			/* Start the Loop */
				/* Include the post format-specific template for the content. If you want to
				 * this in a child theme then include a file called called content-___.php
				 * (where ___ is the post format) and that will be used instead.
				 */
			if (strstr ($_SERVER ["REQUEST_URI"], "/obrazotvorche-mystetstvo/"))
			{
				echo "<style>
					div.navigation
					{
						display: none;
					}
					</style>
					<article class='image-attachment'>";
				if ($blog_id == 14)
					$sqlstr = "select id, post_title, post_name, post_content from wp_14_posts where id in(select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_id from wp_14_terms where slug='obrazotvorche-mystetstvo')) and id in(select object_id from wp_14_term_relationships where term_taxonomy_id=".$_GET ["autor"].") and post_status='publish'";
				else
					$sqlstr = "select id, post_title, post_name, post_content from wp_".$blog_id."_posts where id in(select object_id from wp_".$blog_id."_term_relationships where term_taxonomy_id in(select term_id from wp_".$blog_id."_terms where slug='obrazotvorche-mystetstvo')) and post_status='publish'";
				$attachment_size = apply_filters("rivnodennya_attachment_size", array (960, 960));
				$next_link = "";
				$post = $wpdb->get_results ($sqlstr, ARRAY_A);
				if (strstr ($post [0]["post_content"], ","))
				{
					$first_image = strstr (substr (strstr ($post [0]["post_content"], "ids=\""), 5, strlen (strstr ($post [0]["post_content"], "ids=\"")) - 5), ",", true);
					if (substr_count ($post [0]["post_content"], ",") > 1)
						$second_image = strstr (substr (strstr (strstr ($post [0]["post_content"], "ids=\""), ","), 1, strlen (strstr (strstr ($post [0]["post_content"], "ids=\""), ",")) - 5), ",", true);
					else
						$second_image = strstr (substr (strstr (strstr ($post [0]["post_content"], "ids=\""), ","), 1, strlen (strstr (strstr ($post [0]["post_content"], "ids=\""), ",")) - 5), "]", true);			
					$sqlstr = "select post_name from wp_".$blog_id."_posts where id=".$second_image;
					$next_guid = $wpdb->get_results($sqlstr, ARRAY_A);
					$next_link = "<a href='".$path [0]["path"].$post [0]["post_name"]."/".$next_guid [0]["post_name"]."/#begin-".$second_image."' class='right-link'>";
				}
				else
					$first_image = strstr (substr (strstr ($post [0]["post_content"], "ids=\""), 5, strlen (strstr ($post [0]["post_content"], "ids=\"")) - 5), "]", true);
				$sqlstr = "select post_excerpt, guid from wp_".$blog_id."_posts where id=".$first_image;
				$images = $wpdb->get_results($sqlstr, ARRAY_A);
				echo "<div class='entry-content image-content icc'>
						<nav id='image-navigation' class='navigation' role='navigation'>";
				if ($next_link != "")
				{
					echo $next_link;
?>

<span class="next-image"></span></a>

<?php
}

?>

						<!-- #image-navigation -->
						<div class="entry-attachment">
							<div class="attachment">
<?php
	echo "<div id='begin-".$first_image."' class='begin'></div><a href='".$images [0]["guid"]."' rel='lightbox'>".wp_get_attachment_image($first_image, $attachment_size)."</a>
		</div>
		</div>
		</nav>
		</div>
		<div class='entry-caption image-caption'><p>".$images [0]["post_excerpt"]."</p></div>
		<footer class='entry-meta image-meta'>						
			<div class='image-description'><div class='image-album'>З альбому \"<a href='".$path [0]["path"].$post [0]["post_name"]."' title='".$post [0]["post_title"]."' rel='gallery'>".$post [0]["post_title"]."</a>\"
			</div>
			</div>
			</footer>
		</article>
	<script>
	function windowHeight ()
	{
		var wh = document.documentElement;
		return self.innerHeight || (wh && wh.clientHeight) || document.body.clientHeight;
	}
	var post = document.getElementById('content'); 
	var all_nav = post.getElementsByTagName('nav');
	var all_img = all_nav [0].getElementsByTagName('img');
	var hrefs = all_nav [0].getElementsByTagName('a');
	var w = all_img[0].offsetWidth;
	var h = all_img[0].offsetHeight;
	var p_w=post.offsetWidth;
	if ((w > h) || (h / w < (windowHeight () - 86) / p_w))
	{
		all_nav [0].setAttribute('style', 'width: calc(100% - 20px);');";
		if ($next_link != "")
			echo "hrefs [0].setAttribute('style','right: 10px; width: calc((100% - 20px)/3);');";
		echo "}
	else						
	{
		all_nav [0].setAttribute('style','width: ' + (windowHeight () - 106) * w / h + 'px;');";
		if ($next_link != "")
			echo "hrefs [0].setAttribute('style','right: calc(50% - ' + (windowHeight () - 106) * w / h / 2 + 'px); width: ' + ((windowHeight () - 106) * w / h / 3) + 'px;');";
		echo "}
			</script>";
		if (function_exists ("wp_corenavi"))
			wp_corenavi ();
	}
	else
		if (strstr ($_SERVER ["REQUEST_URI"], "/moyi-svitlyny/"))
		{
			echo "<style>
				div.navigation
				{
					display: none;
				}
				</style>
				<article class='image-attachment'>";
			if ($blog_id == 14)
				$sqlstr = "select id, post_title, post_name, post_content from wp_14_posts where id in(select object_id from wp_14_term_relationships where term_taxonomy_id in(select term_id from wp_14_terms where slug='moyi-svitlyny')) and id in(select object_id from wp_14_term_relationships where term_taxonomy_id=".$_GET ["autor"].") and post_status='publish'";
			else
				$sqlstr = "select id, post_title, post_name, post_content from wp_".$blog_id."_posts where id in(select object_id from wp_".$blog_id."_term_relationships where term_taxonomy_id in(select term_id from wp_".$blog_id."_terms where slug='moyi-svitlyny')) and post_status='publish'";
				$attachment_size = apply_filters ("rivnodennya_attachment_size", array (960, 960));
				$next_link = "";
				$post = $wpdb->get_results ($sqlstr, ARRAY_A);
				if (strstr ($post [0]["post_content"], ","))
				{
					$first_image = strstr (substr (strstr ($post [0]["post_content"], "ids=\""), 5, strlen (strstr ($post [0]["post_content"], "ids=\"")) - 5), ",", true);
					if (substr_count ($post [0]["post_content"], ",") > 1)
						$second_image = strstr (substr (strstr (strstr ($post [0]["post_content"], "ids=\""), ","), 1, strlen (strstr (strstr ($post [0]["post_content"], "ids=\""), ",")) - 5), ",", true);
					else
						$second_image = strstr (substr (strstr (strstr ($post [0]["post_content"], "ids=\""), ","), 1, strlen (strstr (strstr ($post [0]["post_content"], "ids=\""), ",")) - 5), "]", true);			
					$sqlstr = "select post_name from wp_".$blog_id."_posts where id=".$second_image;
					$next_guid = $wpdb->get_results ($sqlstr, ARRAY_A);
					$next_link = "<a href='".$path [0]["path"].$post [0]["post_name"]."/".$next_guid [0]["post_name"]."/#begin-".$second_image."' class='right-link'>";
				}
				else
					$first_image = strstr (substr (strstr ($post [0]["post_content"], "ids=\""), 5, strlen (strstr ($post [0]["post_content"], "ids=\"")) - 5), "]", true);
				$sqlstr = "select post_excerpt, guid from wp_".$blog_id."_posts where id=".$first_image;
				$images = $wpdb->get_results ($sqlstr, ARRAY_A);
				echo "<div class='entry-content image-content icc'>
					<nav id='image-navigation' class='navigation' role='navigation'>";
						if ($next_link != "")
						{
							echo $next_link;
?>
<span class="next-image"></span></a>
<?php
}
?>
						<!-- #image-navigation -->
						<div class="entry-attachment">
							<div class="attachment">
<?php
	echo "<div id='begin-".$first_image."' class='begin'></div><a href='".$images [0]["guid"]."' rel='lightbox'>".wp_get_attachment_image ($first_image, $attachment_size)."</a>
		</div>
		</div>
		</nav>
		</div>
	<div class='entry-caption image-caption'><p>".$images [0]["post_excerpt"]."</p></div>
	<footer class='entry-meta image-meta'>						
		<div class='image-description'><div class='image-album'>З альбому \"<a href='".$path [0]["path"].$post [0]["post_name"]."' title='".$post [0]["post_title"]."' rel='gallery'>".$post [0]["post_title"]."</a>\"</div></div>
	</footer>
	</article>
	<script>
	function windowHeight ()
	{
		var wh = document.documentElement;
		return self.innerHeight || (wh && wh.clientHeight) || document.body.clientHeight;
	}
	var post = document.getElementById('content'); 
	var all_nav = post.getElementsByTagName('nav');
	var all_img = all_nav [0].getElementsByTagName('img');
	var hrefs = all_nav [0].getElementsByTagName('a');
	var w = all_img [0].offsetWidth;
	var h = all_img [0].offsetHeight;
	var p_w = post.offsetWidth;
	if ((w > h) || (h / w < (windowHeight () - 86) / p_w))
	{
		all_nav [0].setAttribute('style', 'width: calc(100% - 20px);');";
	if ($next_link != "")
		echo "hrefs [0].setAttribute('style','right: 10px; width: calc((100% - 20px)/3);');";
	echo "}
		else						
		{
			all_nav [0].setAttribute('style','width: ' + (windowHeight () - 106) * w / h + 'px;');";
			if ($next_link != "")
				echo "hrefs [0].setAttribute('style','right: calc(50% - ' + (windowHeight () - 106) * w / h / 2 + 'px); width: ' + ((windowHeight () - 106) * w / h / 3) + 'px;');";
			echo "}
		</script>";
		if (function_exists ("wp_corenavi"))
			wp_corenavi ();
	}
	else
		if ($_GET ["autor"] != "")
		{
			$sql = "select id, post_name, post_title, post_content, post_date, comment_count from wp_14_posts where id in(select object_id from wp_14_term_relationships where term_taxonomy_id=".$_GET ["autor"].") and (";
			subCat1 ($cat_id [0]["term_id"], $sql, $cat_id [0]["term_id"], 14);
			$sql .= ") and post_status='publish' order by post_date desc";
		}
		else
		{
			$sql = "select id, post_name, post_title, post_content, post_date, comment_count from wp_".$blog_id."_posts where (";
			subCat1 ($cat_id [0]["term_id"], $sql, $cat_id [0]["term_id"], $blog_id);
			$sql .= ") and post_status='publish' order by post_date desc";
		}
		$sql1 = $sql." limit 10";
		if ($_GET ["page1"] != "")
		$sql1 .= " offset ".($_GET ["page1"] - 1) * 10;			
		$lirics = $wpdb->get_results ($sql, ARRAY_A);
		$lirics1 = $wpdb->get_results ($sql1, ARRAY_A);
		$output = "";
		foreach ($lirics1 as $liric1)
		{
			$txt = "{title}";
			$p = get_blog_post ($blog_id, $liric1 ["id"]);				
			$ex = $p->post_excerpt;
			if ((!isset ($ex)) || (trim ($ex) == ""))
				$ex = mb_substr (strip_tags ($p->post_content), 0, 65)."...";
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*/m", "</p><br/><br/>", $p->post_content);
			
$content = preg_replace ("/\[gallery.+?\]/", "", $content);

			$content = preg_replace ("/<img.+?>/", "", $content);
			$content = preg_replace ("/\[audio.+?audio\]/", "", $content);
			$content = preg_replace ("/<a.+?><\/a>(\n)*/s", "", $content);
			$content = preg_replace ("/(\n)+?/m", "<br/>", $content);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*/m", "</p>", $content);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*((\s)*<br\/?>(\s)*)+/m", "</p><br/>", $content);
			$content = preg_replace ("/((\s)*<br\/?>(\s)*)*((\s)*<br(\/)?>(\s)*)+/", "<br/>", $content);
			$texts = preg_split ("/\[(youtube|embed|vimeo)\](.*?)\[\/(youtube|embed|vimeo)\]/s", $content);
			preg_match_all ("/\[(youtube|embed|vimeo)\](.*?)\[\/(youtube|embed|vimeo)\]/s", $content, $videos);
			$content1 = "";
			for ($i = 0; $i < count ($texts) - 1; $i++)
			{
				$content1 .= $texts [$i];
				if (strstr ($videos [0][$i], "[youtube"))
				{
					$html = new simple_html_dom ();
					$html = file_get_html (substr ($videos [0][$i], 9, strlen ($videos [0][$i]) - 19));
					$tags_w = $html->find ("meta[property=\"og:video:width\"]");
					$tags_h = $html->find ("meta[property=\"og:video:height\"]");
					$width = $tags_w [0] -> content;
					$height = $tags_h [0] -> content;
					$content1 .= "<span class='vvqbox vvqyoutube'><iframe src='https://www.youtube.com/embed/".substr (strstr ($videos [0][$i], "="), 1, strlen (strstr ($videos [0][$i], "=")) - 11)."' onLoad='this.style.height=document.getElementById (\"primary\").offsetWidth * ".($height / $width)." + \"px\";' allowfullscreen></iframe></span>";
				}
				if (strstr ($videos [0][$i], "[vimeo"))
				{
					$html = new simple_html_dom ();
					$html = file_get_html (substr ($videos [0][$i], 7, strlen ($videos [0][$i]) - 15));
					$tags_w = $html->find ("meta[property=\"og:video:width\"]");
					$tags_h = $html->find ("meta[property=\"og:video:height\"]");
					$width = $tags_w [0] -> content;
					$height = $tags_h [0] -> content;
					$content1 .= "<span class='vvqbox vvqyoutube'><iframe src='https://player.vimeo.com/video/".substr (strrchr (substr ($videos [0][$i], 0, strlen ($videos [0][$i]) - 8), "/"), 1, strlen (strrchr (substr ($videos [0][$i], 0, strlen ($videos [0][$i]) - 8), "/")) - 1)."'  onLoad='this.style.height=document.getElementById (\"primary\").offsetWidth * ".($height / $width)." + \"px\";' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></span>";
				}
				if (strstr ($videos [0][$i], "[embed"))						
				{
					$html = new simple_html_dom ();
					$html = file_get_html (substr ($videos [0][$i], 7, strlen ($videos [0][$i]) - 15));
					if (strstr ($videos [0][$i], "youtube"))
					{
						$tags = $html->find ("meta[property=\"og:video:width\"]");
						$tags_w = $html->find ("meta[property=\"og:video:width\"]");
						$tags_h = $html->find ("meta[property=\"og:video:height\"]");
						$width = $tags_w [0] -> content;
						$height = $tags_h [0] -> content;
						$content1 .= "<span class='vvqbox vvqyoutube'><iframe src='https://www.youtube.com/embed/".substr (strstr ($videos [0][$i], "="), 1, strlen (strstr ($videos [0][$i], "=")) - 9)."'  onLoad='this.style.height=document.getElementById (\"primary\").offsetWidth * ".($height / $width)." + \"px\";' allowfullscreen></iframe></span>";
					}
					if (strstr ($videos [0][$i], "vimeo"))
					{							
						$tags = $html->find ("meta[property=\"og:video:height\"]");					
						$tags_w = $html->find ("meta[property=\"og:video:width\"]");
						$tags_h = $html->find ("meta[property=\"og:video:height\"]");
						$width = $tags_w [0] -> content;
						$height = $tags_h [0] -> content;
						$content1 .= "<span class='vvqbox vvqyoutube'><iframe src='https://player.vimeo.com/video/".substr (strrchr (substr ($videos [0][$i], 0, strlen ($videos [0][$i]) - 8), "/"), 1, strlen (strrchr (substr ($videos [0][$i], 0, strlen ($videos [0][$i]) - 8), "/")) - 1)."' onLoad='this.style.height=document.getElementById (\"primary\").offsetWidth * ".($height / $width)." + \"px\";' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></span>";
					}
				}
			}
			$content1 .= $texts [count ($texts) - 1];
			$content = $content1;
			if ((substr ($content, 0, 5) == "<div>") && (substr ($content, strlen ($content) - 6, 6) == "</div>"))
				$content = substr ($content, 5, strlen ($content) - 11);
			$content = trim ($content);
			if (strstr ($content, "<!--more-->"))
				$content = strstr ($content, "<!--more-->", true);
			$preview = preg_split ("/(<p><\/p>|((\s)*<br(\/)?>(\s)*)+&nbsp;((\s)*<br(\/)?>(\s)*)+)/m", $content);
			/*$content = $preview [0];
			$i = 0;
			while (substr_count ($content, "<p>") + substr_count ($content, "<br>") + substr_count ($content, "
") + substr_count ($content, "<br/>") + substr_count ($content, "<div>") < 15)
			{
				$i++;
				if (substr ($content, strlen ($content) - 4, 4) == "</p>")
					$content .= "<br/>";
				else
					$content .= "<br/><br/>";
				$content .= $preview [$i];
			}*/
			$content = closetags ($content);
			if ($liric1 ["comment_count"] > 0)
				$txt = str_replace ("{title}", "<h3>
					<a href='".get_blog_permalink ($blog_id, $liric1 ["id"])."'>".$p->post_title."</a>
					</h3>
					<div class='poetry'>"
						.$content.'
					</div>
					<div class="readmore">
						<a href="'.get_blog_permalink ($blog_id, $liric1 ["id"]).'">Читати далі...</a>
					</div>
					<div class="poetrydata">'.
						mysql2date ("F j, Y, G:i", $p->post_date).
					'</div>
					<div class="map-comments">
					<a href="'.get_blog_permalink ($blog_id, $liric1 ["id"]).'/#comments">Коментарі ('.$liric1 ["comment_count"].")</a>
					</div>", $txt);
			else			
				$txt = str_replace ("{title}", "<h3>
					<a href='".get_blog_permalink ($blog_id, $liric1 ["id"])."'>".$p->post_title."</a>
					</h3>
					<div class='poetry'>"
						.$content.'
					</div>
					<div class="readmore">
						<a href="'.get_blog_permalink ($blog_id, $liric1 ["id"]).'">Читати далі...</a>
					</div>
					<div class="poetrydata">'.
						mysql2date ("F j, Y, G:i", $p->post_date).
					"</div>", $txt);
			$output .=  $txt."<br/>";	
			if (($user_ID == 1) || ($user_ID == 7) || ($blog1 [0]["meta_value"] == $blog_id))
				$output .= "<footer class='entry-meta'>
					<a href='".$path [0]["path"]."wp-admin/post.php?post=".$liric1 ["id"]."&action=edit'>Редагувати</a><br/>
					<a href='".$path [0]["path"]."edit/?pid=".$liric1 ["id"]."&_wpnonce=dc3d194358'>Редагувати (швидко)</a><br/>
					<a href='".$path [0]["path"].wp_nonce_url ("wp-admin/post.php?action=delete&post=".$liric1 ["id"], "delete-post_".$liric1 ["id"])."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;'>Видалити пост</a>
					</footer>";
			$i++;
		}
		$output .=  $wpdb->print_error ();
		echo "<div class='mix-content'>".$output;
		if (count ($lirics) > 10)
		{
			echo "<br/><span class='mix-navigation'>Сторінка ";
			if ($_GET ["page1"] != "")
				echo $_GET ["page1"];
			else
				echo "1";
			echo " з ";
			if (count ($lirics) % 10 == 0)
				$pages = (int)(count ($lirics) / 10);
			else
				$pages = (int)(count ($lirics) / 10) + 1;
			echo $pages;
			if ($_GET ["autor"] != "")
			{
				if ($_GET ["page1"] > 1)
					echo " <a href='/teachers/category/".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) - 1)."/?autor=".$_GET ["autor"]."&page1=".($_GET ["page1"] - 1)."'>«</a>";
				for ($i = 1; $i <= $pages; $i++)
				{
					echo " <a href='/teachers/category/".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) - 1)."/?autor=".$_GET ["autor"]."&page1=".$i."'";
					if ((($_GET ["page1"] == "") && ($i == 1)) || (($_GET ["page1"] != "") && ($i == $_GET ["page1"])))
						echo " style='font-weight: bold;'";
					echo ">".$i."</a>";
				}
				if ($_GET ["page1"] != $pages)
				{
					echo " <a href='/teachers/category/".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) - 1)."/?autor=".$_GET ["autor"]."&page1=";
					if ($_GET ["page1"] != "")
						echo $_GET ["page1"] + 1;
					else
						echo "2";
					echo "'>»</a>";
				}
			}
			else
			{
				if ($_GET ["page1"] > 1)
					echo " <a href='".$path [0]["path"]."category/".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) - 1)."/?page1=".($_GET ["page1"] - 1)."'>«</a>";
				for ($i = 1; $i <= $pages; $i++)
				{
					echo " <a href='".$path [0]["path"]."category/".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) - 1)."/?page1=".$i."'";
					if ((($_GET ["page1"] == "") && ($i == 1)) || (($_GET ["page1"] != "") && ($i == $_GET ["page1"])))
						echo " style='font-weight: bold;'";
					echo ">".$i."</a>";
				}
				if ($_GET ["page1"] != $pages)
				{
					echo " <a href='".$path [0]["path"]."category/".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr ($_SERVER ["REQUEST_URI"], "/"))), "/")) - 1)."/?page1=";
					if ($_GET ["page1"] != "")
						echo $_GET ["page1"] + 1;
					else
						echo "2";
					echo "'>»</a>";
				}
			}
			echo "</span>";
		}
		echo "</div>";
	?>
	<?php
	else :
		get_template_part ("content", "none");
	endif;
	if ($is_liric)
	{
		if ($blog_id == 9)
			echo "<h4 class='all-date'><a href='/virovec'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/virovec?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
		if ($blog_id == 13)
			echo "<h4 class='all-date'><a href='/khvorost'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/khvorost?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
		if ($blog_id == 15)
			echo "<h4 class='all-date'><a href='/bashkirova'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/bashkirova?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";	
		if ($blog_id == 16)
			echo "<h4 class='all-date'><a href='/olir'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/olir?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
		if ($blog_id == 17)
			echo "<h4 class='all-date'><a href='/naumenko'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/naumenko?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";	
		if ($blog_id == 18)
			echo "<h4 class='all-date'><a href='/kostenko'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/kostenko?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
		if (($blog_id == 14) && ($_GET ["autor"] != ""))
			echo "<h4 class='all-date'><a href='/teachers/?autor=".$_GET ["autor"]."'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/teachers/?sort=abc&autor=".$_GET ["autor"]."'>Переглянути всі вірші автора (за абеткою)</a></h4>";
	}
	?>
	</div><!-- #content -->
	</section><!-- #primary -->
<?php
	get_sidebar ();
	get_footer ();
?>