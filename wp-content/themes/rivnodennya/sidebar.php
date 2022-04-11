<?php
	/**
	* The sidebar containing the main widget area
	*
	* If no active widgets are in the sidebar, hide it completely.
	*
	* @package WordPress
	* @subpackage Twenty_Twelve
	* @since Twenty Twelve 1.0
	*/ 

	function subCat3 ($id, &$sql, $root, $blog)
	{
		global $wpdb;
		if ($id != $root)
			$sql .= "or ";
		$sql .= "p.id in(select object_id from wp_".$blog."_term_relationships where term_taxonomy_id=".$id.") ";	
		$sqlstr = "select term_id from wp_".$blog."_term_taxonomy where parent=".$id;
		$children = $wpdb->get_results ($sqlstr, ARRAY_A);
		foreach ($children as $child)
			subCat3 ($child ["term_id"], $sql, $root, $blog);
	}
?>
<div id="secondary" class="widget-area" role="complementary">
<?php
	global $blog_id;		
	global $wpdb;
	if ($blog_id == 1)
	{
		$blog_list = get_blog_list (0, "all"); 		
		$output = ""; 		
		$sqlstr = "";
		$i = 0;		
		$j = 0;		
		$sqlstr = "select blog_id from wp_blogs";
		$blogs = $wpdb->get_results ($sqlstr, ARRAY_A);
		$sqlstr = "";
		foreach ($blogs as $blog)
			if ($blog ["blog_id"] != 1)
			{
				//echo " bid: ".$blog ["blog_id"];
				$sqlstr1 = "select term_id from wp_".$blog ["blog_id"]."_terms where name='Поезія'";
				$terms_id = $wpdb->get_results ($sqlstr1, ARRAY_A);
				if ($j > 0)
					$sqlstr .= " union ";
				$sqlstr .= "SELECT b.blog_id, b.path as path, p.id, p.post_date, p.post_modified, p.comment_count as comment_count, o.option_value as value, pm.meta_value as mv from wp_".$blog ["blog_id"]."_posts as p, wp_".$blog ["blog_id"]."_options as o, wp_blogs as b, wp_".$blog ["blog_id"]."_postmeta as pm where p.post_status = 'publish' and b.blog_id=".$blog ["blog_id"]." and pm.meta_key='ratings_users' and p.ID=pm.post_id and (";
				subCat3 ($terms_id [0]["term_id"], $sqlstr, $terms_id [0]["term_id"], $blog ["blog_id"]);
				$sqlstr .= ") and o.option_name='blogname' and b.blog_id!=14 and b.blog_id<18";
				$j++;
			}
		$sqlstr.= " ORDER BY post_date desc";

	$post_list = $wpdb->get_results ($sqlstr, ARRAY_A);
		foreach ($post_list as $post1) 
		{
			$txt = "{title}";
			$p = get_blog_post ($post1 ["blog_id"], $post1 ["id"]);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*/m", "</p><br/><br/>", $p->post_content);
			$content = preg_replace ("/<img.+?>/", "", $content);
			$content = preg_replace ("/\[audio.+?audio\]/", "", $content);
			$content = preg_replace ("/<a.+?><\/a>(\n)*/s", "", $content);
			$content = preg_replace ("/(\n)+?/", "<br/>", $content);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*/m", "</p>", $content);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*((\s)*<br\/?>(\s)*)+/m", "</p><br/>", $content);
			$content = preg_replace ("/((\s)*<br\/?>(\s)*)*((\s)*<br\/?>(\s)*)+/m", "<br/>", $content);
$content = preg_replace ("/\[gallery(.*)?\]/", "", $content);

			$content = preg_replace ("/\[youtube\]http(s)?:\/\/(www\.)?youtube\.com\/watch\?v=(.*?)\[\/youtube\]/", "", $content);
			$content = preg_replace ("/\[embed\]http(s)?:\/\/(www\.)?vimeo\.com\/(.*?)\[\/embed\]/", "", $content);
			$content = preg_replace ("/\[vimeo\]http(s)?:\/\/(www\.)?vimeo\.com\/(.*?)\[\/vimeo\]/", "", $content);
			$content = preg_replace ("/\[embed\]http(s)?:\/\/(www\.)?youtube\.com\/watch\?v=(.*?)\[\/embed\]/", "", $content);
            $content = trim ($content);
			if ((substr ($content, 0, 5) == "<div>") && (substr ($content, strlen ($content) - 6, 6) == "</div>"))
				$content = substr ($content, 5, strlen ($content) - 11);
			if (strstr ($content, "<!--more-->"))
				$content = strstr ($content, "<!--more-->", true);
			$content = trim ($content);
			/*$preview = preg_split ("/(<p>(&nbsp;)*<\/p>|((\s)*<br\/?>(\s)*)+&nbsp;((\s)*<br\/?>(\s)*)+)/m", $content);
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
			if ($post1 ["comment_count"] > 0)
				$txt = str_replace ("{title}", '<div class="poetryname"><a href="'.$post1 ["path"].'">'.$post1 ["value"].'</a></div><h3><a href="' .get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">'.$p->post_title.'</a></h3><div class="poetry">'.$content.'</div><div class="readmore"><a href="' .get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">Читати далі...</a></div><div class="poetrydata">'.mysql2date ("F j, Y, G:i", $p->post_date).'</div><span class="map-comments"><a href="'.get_blog_permalink ($post1 ["blog_id"], $post1["id"]).'#comments">Коментарі ('.$post1 ["comment_count"].')</a></span>', $txt);
			else
				$txt = str_replace ("{title}", '<div class="poetryname"><a href="'.$post1 ["path"].'">'.$post1 ["value"].'</a></div><h3><a href="' .get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">'.$p->post_title.'</a></h3><div class="poetry">'.$content.'</div><div class="readmore"><a href="' .get_blog_permalink ($post1 ["blog_id"], $post1 ["id"]).'">Читати далі...</a></div><div class="poetrydata">'.mysql2date ("F j, Y, G:i", $p->post_date)."</div>", $txt);

if ($post1 ["mv"] > 0)
{
	if ($post1 ["comment_count"] > 0)
		$txt .= ", ";
	$txt .= "<span class='map-comments'>
		Голоси (".$post1 ["mv"].")
	</span>";
}
$output .=  $txt."<br/>";
			$i++;
			if ($i > 4)
				break;
		}		
		$output .=  $wpdb->print_error();
		echo "<h3 class='widget-title'>Нові твори на рівноденні</h3>".$output."<br/>
		<a href='/zagalna-strichka-postiv/' id='lbl'>Стрічка віршів</a>";
	}
	if (is_active_sidebar ("sidebar-1")) :
		dynamic_sidebar ("sidebar-1");
	endif;
	if (($blog_id == 14) && (($_SERVER ["REQUEST_URI"] == "/teachers/") || ((substr_count ($_SERVER ["REQUEST_URI"], "/") == 4) && ($_GET ["autor"] == "") && ($_GET ["s"] == "") && (strstr ($_SERVER ["REQUEST_URI"], "/teachers/page/")) && (!strstr ($_SERVER ["REQUEST_URI"], "/teachers/teachers/page/")))))
	{
		$output = "";
		$i = 0;	
		$sqlstr = "SELECT id, p.post_date as post_date,p.post_modified as post_modified, p.comment_count as comment_count, pm.meta_value as mv from wp_14_posts p, wp_14_postmeta pm where post_status='publish' and pm.meta_key='ratings_users' and p.ID=pm.post_id and(";
		subCat3 (15, $sqlstr, 15, 14);
		$sqlstr .= ") ORDER BY post_date desc";

		$post_list = $wpdb->get_results ($sqlstr, ARRAY_A);
		foreach ($post_list as $post1) 
		{
			$sqlstr = "select name, term_id from wp_14_terms where term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id=".$post1 ["id"].") and term_id in(select term_id from wp_14_term_taxonomy where parent=4)";
			$autors = $wpdb->get_results ($sqlstr, ARRAY_A);
			$txt = "{title}";
			$p = get_blog_post (14, $post1 ["id"]);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*/m", "</p><br/><br/>", $p->post_content);
			$content = preg_replace ("/<img.+?>/", "", $content);
			$content = preg_replace ("/\[audio.+?audio\]/", "", $content);
			$content = preg_replace ("/<a.+?><\/a>(\n)*/s", "", $content);
			$content = preg_replace ("/(\n)+?/m", "<br/>", $content);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*/m", "</p>", $content);
			$content = preg_replace ("/<\/p>(\s)*<br\/?>(\s)*((\s)*<br(\/)?>(\s)*)+/m", "</p><br/>", $content);
			$content = preg_replace ("/((\s)*<br\/?>(\s)*)*((\s)*<br(\/)?>(\s)*)+/m", "<br/><br/>", $content);
			$content = preg_replace ("/\[youtube\]http(s)?:\/\/(www\.)?youtube\.com\/watch\?v=(.*?)\[\/youtube\]/", "", $content);
$content = preg_replace ("/\[gallery(.*)?\]/", "", $content);

			$content = preg_replace ("/\[embed\]http(s)?:\/\/(www\.)?vimeo\.com\/(.*?)\[\/embed\]/", "", $content);
			$content = preg_replace ("/\[vimeo\]http(s)?:\/\/(www\.)?vimeo\.com\/(.*?)\[\/vimeo\]/", "", $content);
			$content = preg_replace ("/\[embed\]http(s)?:\/\/(www\.)?youtube\.com\/watch\?v=(.*?)\[\/embed\]/", "", $content);
			if ((substr ($content, 0, 5) == "<div>") && (substr ($content, strlen ($content) - 6, 6) == "</div>"))
				$content = substr ($content, 5, strlen ($content) - 11);
			if (strstr ($content, "<!--more-->"))
				$content = strstr ($content, "<!--more-->", true);
			$content = trim ($content);
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
			echo " fggffff ";
			$content = closetags ($content);
			if ($post1 ["comment_count"] > 0)
				$txt = str_replace ("{title}", '<div class="poetryname"><a href="/teachers/?autor='.$autors [0]["term_id"].'">'.$autors [0]["name"].'</a></div><h3><a href="'.get_blog_permalink (14, $post1 ["id"]).'">'.$p->post_title.'</a></h3><div class="poetry">'.$content.'</div><div class="readmore"><a href="'.get_blog_permalink (14, $post1 ["id"]).'">Читати далі...</a></div><div class="poetrydata">'.mysql2date ("l, F j, Y, G:i", $p->post_date).'</div><span class="map-comments"><a href="'.get_blog_permalink (14, $post1 ["id"]).'#comments">Коментарі ('.$post1 ["comment_count"].')</a></span>";
', $txt);

else			
				$txt = str_replace ("{title}", '<div class="poetryname"><a href="/teachers/?autor='.$autors [0]["term_id"].'">'.$autors [0]["name"].'</a></div><h3><a href="'.get_blog_permalink (14, $post1 ["id"]).'">'.$p->post_title.'</a></h3><div class="poetry">'.$content.'</div><div class="readmore"><a href="'.get_blog_permalink (14, $post1 ["id"]).'">Читати далі...</a></div><div class="poetrydata">'.mysql2date ("l, F j, Y, G:i", $p->post_date)."</div>", $txt);
if ($post1 ["mv"] > 0)
{
	if ($post1 ["comment_count"] > 0)
		$txt .= ", ";
	$txt .= "<span class='map-comments'>
		Голоси (".$post1 ["mv"].")
	</span>";
}
$output .=  $txt."<br/>";
			$i++;
			if ($i > 4)
				break;
		}		
		$output .=  $wpdb->print_error();
		echo "<h3 class='widget-title'>Улюблені поети. Нові записи</h3>".$output;
	}
?>