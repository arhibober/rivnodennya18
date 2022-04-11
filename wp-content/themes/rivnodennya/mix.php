<?php
/*
Template Name: Общая лента постов
*/

function subCat2 ($id, &$sql, $root, $blog)
{
	global $wpdb;
	if ($id != $root)
		$sql .= "or ";
	$sql .= "p.id in(select object_id from wp_".$blog."_term_relationships where term_taxonomy_id=".$id.") ";	
	$sqlstr = "select term_id from wp_".$blog."_term_taxonomy where parent=".$id;
	$children = $wpdb->get_results ($sqlstr, ARRAY_A);
	foreach ($children as $child)
		subCat2 ($child ["term_id"], $sql, $root, $blog);
}

define ("WP_USE_THEMES", false);
get_header ();
?>	
<section id="primary" class="site-content mcs">
	<div id="content" class="mix-content">
		<header class="archive-header">
			<?php
			if (category_description ()) : // Show an optional category description
			?>
				<div class="archive-meta">
				<?php
					echo category_description ();
				?>
				</div>
			<?php endif; ?>
		</header><!-- .archive-header -->
		<?php			
		global $switched;
		global $wpdb;
		$table_prefix = $wpdb->base_prefix;
		$sqlstr = "select blog_id from wp_blogs";
		$blogs = $wpdb->get_results ($sqlstr, ARRAY_A);		
		$output = ""; 		
		$sqlstr = "";
		$i = 0;
		$j = 0;
		global $user_ID;
		$sqlstr = "select meta_value from wp_usermeta where user_id=".$user_ID." and meta_key='primary_blog'";
		$blog1 = $wpdb->get_results ($sqlstr, ARRAY_A);
		$sqlstr = "";
		foreach ($blogs as $blog)
			if ($blog ["blog_id"] != 1)
			{
				$sqlstr1 = "select term_id from wp_".$blog ["blog_id"]."_terms where name='Поезія'";
				$terms_id = $wpdb->get_results ($sqlstr1, ARRAY_A);
				if ($j > 0)
					$sqlstr .= " union ";
				$sqlstr .= "SELECT b.blog_id, b.path as path, p.id, p.post_date, p.post_modified, p.comment_count as comment_count, o.option_value as value from wp_".$blog ["blog_id"]."_posts as p, wp_".$blog ["blog_id"]."_options as o, wp_blogs as b where p.post_status = 'publish' and b.blog_id=".$blog ["blog_id"]." and (";
				subCat2 ($terms_id [0]["term_id"], $sqlstr, $terms_id [0]["term_id"], $blog ["blog_id"]);
				$sqlstr .= ") and o.option_name='blogname' and b.blog_id!=14 and b.blog_id<18";
				$j++;
			}
		$sqlstr1 = $sqlstr." ORDER BY post_date desc";
		$sqlstr.= " ORDER BY post_date desc limit 10";
		if ($_GET ["page1"] != "")
			$sqlstr .= " offset ".($_GET ["page1"] - 1) * 10;
		$post_list = $wpdb->get_results ($sqlstr, ARRAY_A);	
		$post_list1 = $wpdb->get_results ($sqlstr1, ARRAY_A);
		foreach ($post_list as $post1) 
		{
			$txt = "{title}";
			$p = get_blog_post ($post1 ["blog_id"], $post1 ["id"]);	
			
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
					$content = strstr ($content, "<!--more-->", true);					$content = closetags ($content);
			if ($post1 ["comment_count"] > 0)
				$txt = str_replace ("{title}", '<div class="poetryname"><a href="'.$post1 ["path"].'">'.$post1 ["value"].'</a></div><h3><a href="' .get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">'.$p->post_title.'</a></h3><div class="poetry">'.$content.'</div><div class="readmore"><a href="'.get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">Читати далі...</a></div><div class="poetrydata">'.mysql2date('F j, Y, G:i', $p->post_date).'</div><div class="map-comments"><a href="'.get_blog_permalink ($post1 ['blog_id'], $post1 ["id"]).'/#comments">Коментарі ('.$post1 ["comment_count"].')</a></div>', $txt);
			else			
				$txt = str_replace ("{title}", '<div class="poetryname"><a href="'.$post1 ["path"].'">'.$post1 ["value"].'</a></div><h3><a href="' .get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">'.$p->post_title.'</a></h3><div class="poetry">'.$content.'</div><div class="readmore"><a href="'.get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">Читати далі...</a></div><div class="poetrydata">'.mysql2date ('F j, Y, G:i', $p->post_date).'</div>', $txt);
			$output .=  $txt."<br/>";
			if (($user_ID == 1) || ($user_ID == 7) || ($blog1 [0]["meta_value"] == $post1 ["blog_id"]))
				$output .= "<footer class='entry-meta'>
					<a href='".$post1 ["path"]."wp-admin/post.php?post=".$post1 ["id"]."&action=edit'>Редагувати</a><br/>
					<a href='".$post1 ["path"]."edit/?pid=".$post1 ["id"]."&_wpnonce=dc3d194358'>Редагувати (швидко)</a><br/>
					<a href='".$post1 ["path"].wp_nonce_url ("wp-admin/post.php?action=delete&post=".$post1 ["id"], "delete-post_".$post1 ["id"])."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;'>Видалити пост</a>
					</footer>";
			$i++;
		}
		
		$output .=  $wpdb->print_error ();
			echo "<h3 class='widget-title'>Нові вірші</h3>".$output;
			if (count ($post_list1) > 10)
			{
				echo "<br/><span class='mix-navigation'>Сторінка ";
				if ($_GET ["page1"] != "")
					echo $_GET ["page1"];
				else
					echo "1";
				echo " з ";
				if (count ($post_list1) % 10 == 0)
					$pages = (int)(count ($post_list1) / 10);
				else
					$pages = (int)(count ($post_list1) / 10) + 1;
				echo $pages;
				if ($_GET ["page1"] > 1)
				{
					echo " <a href='/zagalna-strichka-postiv/?page1=".($_GET ["page1"] - 1)."'>«</a>";
				}
				for ($i = 1; $i <= $pages; $i++)
				{
					echo " <a href='/zagalna-strichka-postiv/?page1=".$i."'";
					if ((($_GET ["page1"] == "") && ($i == 1)) || (($_GET ["page1"] != "") && ($i == $_GET ["page1"])))
						echo " style='font-weight: bold;'";
					echo ">".$i."</a>";
				}
				if ($_GET ["page1"] != $pages)
				{
					echo " <a href='/zagalna-strichka-postiv/?page1=";
					if ($_GET ["page1"] != "")
						echo $_GET ["page1"] + 1;
					else
						echo "2";
					echo "'>»</a>";
				}
				echo "</span>";
			}
			if (function_exists ('wp_corenavi'))
				wp_corenavi ();
		?>

		</div><!-- #content -->
	</section><!-- #primary -->

<?php
	get_sidebar ();
	get_footer ();
?>