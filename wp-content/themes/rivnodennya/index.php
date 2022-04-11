<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

function subCat ($id, &$sql, $root, $blog)
{
	global $wpdb;
	if ($id != $root)
		$sql .= "or ";
	$sql .= "id in(select object_id from wp_".$blog."_term_relationships where term_taxonomy_id=".$id.") ";	
	$sqlstr = "select term_id from wp_".$blog."_term_taxonomy where parent=".$id;
	$children = $wpdb->get_results ($sqlstr, ARRAY_A);
	foreach ($children as $child)
		subCat ($child ["term_id"], $sql, $root, $blog);
}

get_header ();
global $blog_id;
global $wpdb;
$sqlstr = "select path from wp_blogs where blog_id=".$blog_id;
$path = $wpdb->get_results ($sqlstr, ARRAY_A);
$sqlstr = "select term_id from wp_".$blog_id."_terms where slug='poeziya'";
$poeziya = $wpdb->get_results ($sqlstr, ARRAY_A);
global $user_ID;
$sqlstr = "select meta_value from wp_usermeta where user_id=".$user_ID." and meta_key='primary_blog'";
$blog1 = $wpdb->get_results($sqlstr, ARRAY_A);
echo "<style>
	#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/poeziya/\"]
	{
		background: #D9B66D;
		transition:all 1s linear;
	}
	#top_sidebar a[href=\"".$path [0]["path"]."category/poeziya/\"]
	{	
		background: #D9B66D;
		transition:all 1s linear;
	}";
	if ($_GET ["autor"] != "")
		echo "#top_sidebar a[href=\"/teachers/category/poeziya/?autor=".$_GET ["autor"]."\"]
		{	
			background: #D9B66D;
			transition:all 1s linear;
		}
		.teachers a[href=\"/teachers/?autor=".$_GET ["autor"]."\"]
		{	
			color: #FFFDDC;
		}";
	echo "@media screen and (max-width: 600px)
	{
		#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/poeziya/\"]
		{	
			background: none;
			color: #84469F !important;
			transition:all 1s linear;
		}
		#top_sidebar a[href=\"".$path [0]["path"]."category/poeziya/\"]
		{	
			background: none;
			color: #84469F !important;
			transition:all 1s linear;
		}";
	if ($_GET ["autor"] != "")
		echo "#top_sidebar a[href=\"/teachers/category/poeziya/?autor=".$_GET ["autor"]."\"]
		{
			background: none;
			color: #84469F !important;
			transition:all 1s linear;
		}";
		echo "
	}
</style>";
?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
		<?php
		global $blog_id;
		if ($_GET ["sort"] == "abc")
			echo "<h1 class='arhive-title'>Вірші (за абеткою)</h1>";
		else
			echo "<h1 class='arhive-title'>Вірші (за датою)</h1>";
		if ($_GET ["autor"] != "")
			$sql = "select id, post_name, post_title, post_content, post_date, comment_count from wp_14_posts where id in(select object_id from wp_14_term_relationships where term_taxonomy_id=".$_GET ["autor"].") and (";
		else
			$sql = "select id, post_name, post_title, post_content, post_date, comment_count from wp_".$blog_id."_posts where (";
		subCat ($poeziya [0]["term_id"], $sql, $poeziya [0]["term_id"], $blog_id);
		if ($_GET ["sort"] == "abc")
			$sql .= ") and post_status='publish' order by post_title asc";
		else
			$sql .= ") and post_status='publish' order by post_date desc";
		$sql1 = $sql." limit 10";
		if ($_GET ["page1"] != "")
			$sql1 .= " offset ".($_GET ["page1"] - 1) * 10;
		$lirics = $wpdb->get_results ($sql, ARRAY_A);
		$lirics1 = $wpdb->get_results ($sql1, ARRAY_A);
		if (count ($lirics) > 0)
		{
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
				$content = preg_replace ("/<\/p>(\s)*<br(\/)?>(\s)*((\s)*<br(\/)?>(\s)*)+/m", "</p><br/>", $content);
				$content = preg_replace ("/((\s)*<br(\/)?>(\s)*)*((\s)*<br(\/)?>(\s)*)+/", "<br/>", $content);
				if ((substr ($content, 0, 5) == "<div>") && (substr ($content, strlen ($content) - 6, 6) == "</div>"))
					$content = substr ($content, 5, strlen ($content) - 11);
				$content = trim ($content);
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
				if (strstr ($content, "<!--more-->"))
					$content = strstr ($content, "<!--more-->", true);
				/*$preview = preg_split ("/(<p><\/p>|((\s)*<br(\/)?>(\s)*)+&nbsp;((\s)*<br(\/)?>(\s)*)+)/m", $content);
				$content = $preview [0];
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
				if (($blog_id == 14) && ($_GET ["autor"] == ""))
				{
					$sqlstr= "select term_id, name from wp_14_terms where term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id=".$liric1 ["id"].") and term_id in(select term_taxonomy_id from wp_14_term_taxonomy where parent=4)";
					$poet = $wpdb->get_results ($sqlstr, ARRAY_A);
					$output .= "<div class='poetryname poetryname-teachers'>
						<a href='/teachers/?autor=".$poet [0]["term_id"]."'>".$poet [0]["name"]."</a>
						</div>
						</h3>";
				}
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
						<a href="' .get_blog_permalink ($blog_id, $liric1 ["id"]).'">Читати далі...</a>
					</div>
					<div class="poetrydata">'.
						mysql2date ("F j, Y, G:i", $p->post_date).
					"</div>", $txt);
					$output .=  $txt."<br/>";
					if (($user_ID == 1) || ($user_ID == 4) || ($blog1 [0]["meta_value"] == $blog_id))
						$output .= "<footer class='entry-meta'>
							<a href='".$path [0]["path"]."wp-admin/post.php?post=".$liric1 ["id"]."&action=edit'>Редагувати</a><br/>
							<a href='".$path [0]["path"]."edit/?pid=".$liric1 ["id"]."&_wpnonce=dc3d194358'>Редагувати (швидко)</a><br/>
							<a href='".$path [0]["path"].wp_nonce_url ("wp-admin/post.php?action=delete&post=".$liric1 ["id"], "delete-post_".$liric1 ["id"])."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;'>Видалити пост</a>
						</footer>";
					$i++;
			}
			$output .= $wpdb->print_error ();
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
					if ($_GET ["sort"] == "abc")
					{
						if ($_GET ["page1"] > 1)
							echo " <a href='/teachers/?autor=".$_GET ["autor"]."&page1=".($_GET ["page1"] - 1)."&sort=abc'>«</a>";
						for ($i = 1; $i <= $pages; $i++)
						{
							echo " <a href='/teachers/?autor=".$_GET ["autor"]."&page1=".$i."&sort=abc'";
							if ((($_GET ["page1"] == "") && ($i == 1)) || (($_GET ["page1"] != "") && ($i == $_GET ["page1"])))
								echo " style='font-weight: bold;'";
							echo ">".$i."</a>";
						}
						if ($_GET ["page1"] != $pages)
						{
							echo " <a href='/teachers/?autor=".$_GET ["autor"]."&page1=";
							if ($_GET ["page1"] != "")
								echo $_GET ["page1"] + 1;
							else
								echo "2";
							echo "&sort=abc'>»</a>";
						}
					}
					else
					{
						if ($_GET ["page1"] > 1)
							echo " <a href='/teachers/?autor=".$_GET ["autor"]."&page1=".($_GET ["page1"] - 1)."'>«</a>";
						for ($i = 1; $i <= $pages; $i++)
						{
							echo " <a href='/teachers/?autor=".$_GET ["autor"]."&page1=".$i."'";
							if ((($_GET ["page1"] == "") && ($i == 1)) || (($_GET ["page1"] != "") && ($i == $_GET ["page1"])))
								echo " style='font-weight: bold;'";
							echo ">".$i."</a>";
						}
						if ($_GET ["page1"] != $pages)
						{
							echo " <a href='/teachers/?autor=".$_GET ["autor"]."&page1=";
							if ($_GET ["page1"] != "")
								echo $_GET ["page1"] + 1;
							else
								echo "2";
							echo "'>»</a>";
						}
					}
					else				
						if ($_GET ["sort"] == "abc")
						{
							if ($_GET ["page1"] > 1)
								echo " <a href='".$path [0]["path"]."?page1=".($_GET ["page1"] - 1)."&sort=abc'>«</a>";
							for ($i = 1; $i <= $pages; $i++)
							{
								echo " <a href='".$path [0]["path"]."?page1=".$i."&sort=abc'";
								if ((($_GET ["page1"] == "") && ($i == 1)) || (($_GET ["page1"] != "") && ($i == $_GET ["page1"])))
									echo " style='font-weight: bold;'";
								echo ">".$i."</a>";
							}
							if ($_GET ["page1"] != $pages)
							{
								echo " <a href='".$path [0]["path"]."?page1=";
								if ($_GET ["page1"] != "")
									echo $_GET ["page1"] + 1;
								else
									echo "2";
								echo "&sort=abc'>»</a>";
							}
						}
						else
						{
							if ($_GET ["page1"] > 1)
								echo " <a href='".$path [0]["path"]."?page1=".($_GET ["page1"] - 1)."'>«</a>";
							for ($i = 1; $i <= $pages; $i++)
							{
								echo " <a href='".$path [0]["path"]."?page1=".$i."'";
								if ((($_GET ["page1"] == "") && ($i == 1)) || (($_GET ["page1"] != "") && ($i == $_GET ["page1"])))
									echo " style='font-weight: bold;'";
								echo ">".$i."</a>";
							}
							if ($_GET ["page1"] != $pages)
							{
								echo " <a href='".$path [0]["path"]."?page1=";
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
		}
		else
		{
			global $user_ID;
			echo "<article id='post-0' class='post no-results not-found'>";
			if (($user_ID == 1) || ($user_ID == 4) || ($blog1 [0]["meta_value"] == $blog_id))
			{
				echo "<header class='entry-header'>
				<h1 class='entry-title'>";
					_e ("No posts to display", "rivnodennya");
				echo "</h1>
				</header>
				<div class='entry-content'>
				<p>";
					printf( __ ('Ready to publish your first post? <a href="%s">Get started here</a>.', "rivnodennya"), admin_url("post-new.php"));
				echo "</p>
				</div><!-- .entry-content -->";
			}
			else
			{
				// Show the default message to everyone else.
				echo "<header class='entry-header'>
					<h1 class='entry-title'>";
						_e ("Nothing Found", "rivnodennya");
				echo "</h1>
				</header>
				<div class='entry-content'>
				<p>";
					_e ("Apologies, but no results were found. Perhaps searching will help find a related post.", "rivnodennya");
				echo "</p>";
					get_search_form ();
				echo "</div><!-- .entry-content -->";
			}
			echo "</article><!-- #post-0 -->";
		}
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
			<h4 class='all-abc'><a href='h/olir?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
		if ($blog_id == 17)
			echo "<h4 class='all-date'><a href='/naumenko'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/naumenko?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";	
		if (($blog_id == 14) && ($_GET ["autor"] != ""))
			echo "<h4 class='all-date'><a href='/teachers/?autor=".$_GET ["autor"]."'>Переглянути всі вірші автора (за датою)</a></h4><br/>
			<h4 class='all-abc'><a href='/teachers/?sort=abc&autor=".$_GET ["autor"]."'>Переглянути всі вірші автора (за абеткою)</a></h4>";
		?>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php
	get_sidebar ();
	get_footer ();
?>