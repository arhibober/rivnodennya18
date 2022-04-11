<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>

	<article id="post-<?php the_ID (); ?>" <?php post_class (); ?>>
		<?php
			if ((is_sticky ()) && (is_home ()) && (!is_paged ())) :
		?>
		<div class="featured-post">
			<?php
				_e ("Featured post", "rivnodennya");
			?>
		</div>
		<?php
			endif;
		?>
		<header class="entry-header">
			<?php
				if ((!post_password_required ()) && (!is_attachment ())) :
					the_post_thumbnail ();
				endif;
			if (is_single ()) :
			?>
			<h1 class="entry-title">
			<?php
				the_title ();
			?>
			</h1>
			<?php
			else :
			?>
			<h1 class="entry-title">
				<a href="<?php the_permalink (); ?>" rel="bookmark">
				<?php
					the_title ();
				?>
				</a>
			</h1>
			<?php
				endif; // is_single()
			?>
		</header><!-- .entry-header -->
		<?php
			if (is_search ()) : // Only display Excerpts for Search ?>
				<div class="entry-summary">
				<?php
					the_excerpt ();
				?>
			</div><!-- .entry-summary -->
			<?php
				else :
			?>
			<div class="entry-content">
				<?php
					if ((strstr (get_the_content (), "[embed")) || (strstr (get_the_content (), "[youtube")) || (strstr (get_the_content (), "[vimeo")))
					{
						$texts = preg_split ("/\[(youtube|embed|vimeo)\](.*?)\[\/(youtube|embed|vimeo)\]/s", get_the_content ());
						preg_match_all ("/\[(youtube|embed|vimeo)\](.*?)\[\/(youtube|embed|vimeo)\]/s", get_the_content (), $videos);
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
								$content1 .= "<span class='vvqbox vvqyoutube'><iframe src='https://player.vimeo.com/video/".substr (strrchr (substr ($videos [0][$i], 0, strlen ($videos [0][$i]) - 8), "/"), 1, strlen (strrchr (substr ($videos [0][$i], 0, strlen ($videos [0][$i]) - 8), "/")) - 1)."' onLoad='this.style.height=document.getElementById (\"primary\").offsetWidth * ".($height / $width)." + \"px\";' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></span>";
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
						$content = preg_replace ("/\[audio.+?audio\]/", "", $content);
						echo $content;
					}
					else
						the_content (__ ('Continue reading <span class="meta-nav">&rarr;</span>', "rivnodennya"));
					wp_link_pages (array ("before" => '<div class="page-links">'.__ ("Pages:", "rivnodennya"), "after" => "</div>"));
			?>
		</div><!-- .entry-content -->
		<?php
			endif;
		?>
		<footer class="entry-meta">
			<?php
				global $blog_id;
				$sqlstr = "select path from wp_blogs where blog_id=".$blog_id;
				$path = $wpdb->get_results ($sqlstr, ARRAY_A);
				rivnodennya_entry_meta ();
				if (current_user_can ("edit_posts"))
					echo "<a href='".$path [0]["path"]."wp-admin/post.php?post=".get_the_ID ()."&action=edit'>Редагувати</a><br/>";
				edit_post_link (__ ("Редагувати (швидко)", "rivnodennya"), '<span class="edit-link">', "</span>");
				if (current_user_can ("edit_posts"))
					echo "<a href='".$path [0]["path"].wp_nonce_url ("wp-admin/post.php?action=delete&post=".get_the_ID (), "delete-post_".get_the_ID ())."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;'>Видалити пост</a>";
				if ((is_singular ()) && (get_the_author_meta ("description")) && (is_multi_author ())) : // If a user has filled out their description and this is a multi-author blog, show a bio on their entries. 
				endif;
			?>
		</footer><!-- .entry-meta -->
		<?php
			if (comments_open ()) :
			?>
				<div class="comments-link">
					<?php
						comments_popup_link ('<span class="leave-reply">'.__ ("Leave a reply", "rivnodennya")."</span>", __ ("1 Reply", "rivnodennya"), __("% Replies", "rivnodennya"));
					?>
				</div><!-- .comments-link -->
			<?php
				endif; // comments_open()
			?>
	</article><!-- #post -->