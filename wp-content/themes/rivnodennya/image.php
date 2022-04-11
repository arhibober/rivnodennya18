<?php
/**
 * The template for displaying image attachments
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header (); 
global $blog_id;
global $wpdb;
$sqlstr = "select path from wp_blogs where blog_id=".$blog_id;
$paths = $wpdb->get_results ($sqlstr, ARRAY_A);  
?>
	<div id="primary" class="site-content">
		<div id="content" role="main">
		<?php
			echo "<div id='begin-".$post->ID."' class='begin'></div>";
			while (have_posts ()) :
				the_post ();
			if (((substr_count ($_SERVER ["REQUEST_URI"], "/") == 4) || (substr_count ($_SERVER ["REQUEST_URI"], "/") == 7)) && ((!strstr ($_SERVER ["REQUEST_URI"], $paths [0]["path"]."/category/")) || (strstr ($_SERVER ["REQUEST_URI"], $paths [0]["path"].substr ($paths [0]["path"], 1, strlen ($paths [0]["path"]) - 1)."category/"))))
			{
				$prev_link = "";
				$next_link = "";
				$sqlstr = "select id, post_name, post_title, post_content from wp_".$blog_id."_posts where post_name='".substr (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/"), 1, strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1), "/")) - 1)."'";
				$posts = $wpdb->get_results ($sqlstr, ARRAY_A);
				$photo_list = strstr (substr (strstr ($posts [0]["post_content"], "ids=\""), 5, strlen (strstr ($posts [0]["post_content"], "ids=\"")) - 5), "\"]", true);
				$photos = explode (",", $photo_list);
				$album_title = $posts [0]["post_title"];
				$album_address = substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - strlen (strrchr (substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1), "/")) - 1);
				for ($i = 0; $i < count ($photos); $i++)
				{
					$sqlstr = "select post_name from wp_".$blog_id."_posts where id=".$photos [$i - 1];
					$guid_prev = $wpdb->get_results ($sqlstr, ARRAY_A);
					$sqlstr = "select post_name from wp_".$blog_id."_posts where id=".$photos [$i + 1];
					$guid_next = $wpdb->get_results ($sqlstr, ARRAY_A);  
					if (($photos [$i] == $post->ID) && ($i > 0))
						$prev_link = "<a href='".$paths [0]["path"].$posts [0]["post_name"]."/".$guid_prev [0]["post_name"]."/#begin-".$photos [$i - 1]."' class='left-link'>";
					if (($photos [$i] == $post->ID) && ($i < count ($photos) - 1))
						$next_link = "<a href='".$paths [0]["path"].$posts [0]["post_name"]."/".$guid_next [0]["post_name"]."/#begin-".$photos [$i + 1]."' class='right-link'>";
				}
			}
			if (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="))
			{
				$sqlstr = "select id, post_name, post_title, post_content from wp_".$blog_id."_posts where post_content like '%[gallery%' and post_status='publish'";
				$posts2 = $wpdb->get_results ($sqlstr, ARRAY_A);
				foreach ($posts2 as $post2)
					if ((strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=") - 15)).",")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=") - 15)).",")) || (strstr ($post2 ["post_content"], ",".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=") - 15))."\"")) || (strstr ($post2 ["post_content"], "\"".substr (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id="), 15, strlen (strstr ($_SERVER ["REQUEST_URI"], "/attachment_id=") - 15))."\"")))
					{
						$photo_list = strstr (substr (strstr ($post2 ["post_content"], "ids=\""), 5, strlen (strstr ($post2 ["post_content"], "ids=\"")) - 5), "\"]", true);
						$photos = explode (",", $photo_list);
						$album_title = $post2 ["post_content"];
						$prev_link = "";
						$next_link = "";
						for ($i = 0; $i < count ($photos); $i++)
						{
							if (($photos [$i] == $post->ID) && ($i > 0))
								$prev_link = "<a href='".$paths [0]["path"]."?attachment_id=".($photos [$i - 1])."#begin-".($photos [$i - 1])."' class='left-link'>";
							if (($photos [$i] == $post->ID) && ($i < count ($photos) - 1))
								$next_link = "<a href='".$paths [0]["path"]."?attachment_id=".($photos [$i + 1])."#begin-".($photos [$i + 1])."' class='right-link'>";
						}
					}
			}
		?>
		<article id="post-<?php
		the_ID ();	?>
		"
		<?php
		post_class ('image-attachment');
		?>
		>					
		<div class="entry-content image-content">
			<nav id="image-navigation" class="navigation" role="navigation">							
			<?php 
				$previous_image = m_previous_image_link (false, __ ("&larr;", "rivnodennya"));
				$previous_image = str_replace ("</a>", "", $previous_image);
				$previous_image = str_replace ("&larr;", "", $previous_image);
				if ($prev_link != "")
				{ 
					echo $prev_link;
					?>
					<span class="previous-image"></span></a>
					<?php
				}
				$next_image = m_next_image_link (false, __ ("&rarr;", "rivnodennya"));
				$next_image = str_replace ("</a>", "", $next_image);
				$next_image = str_replace ("&rarr;", "", $next_image);
				if ($next_link != "")
				{
					echo $next_link;
					?>
					<span class="next-image"></span></a>
					<?php
				}
				?>
				<div class="entry-attachment">
					<div class="attachment">
<?php
/*
 * Grab the IDs of all the image attachments in a gallery so we can get the URL of the next adjacent image in a gallery,
 * or the first image (if we're looking at the last image in a gallery), or, in a gallery of one, just the link to that image file
 */
$attachments = array_values (get_children (array ("post_parent" => $post->post_parent, "post_status" => "inherit", "post_type" => "attachment", "post_mime_type" => "image", "order" => "ASC", "orderby" => "menu_order ID")));
foreach ($attachments as $k => $attachment) :
	if ($attachment->ID == $post->ID )
		break;
endforeach;

// If there is more than 1 attachment in a gallery
if (count ($attachments) > 1) :
	$k++;
	if (isset ($attachments [$k])) :
		// get the URL of the next image attachment
		$next_attachment_url = get_attachment_link ($attachments [$k]->ID);
	else :
		// or get the URL of the first image attachment
		$next_attachment_url = get_attachment_link ($attachments [0]->ID);
	endif;
else :
	// or, if there's only 1 image, get the URL of the image
	$next_attachment_url = wp_get_attachment_url ();
endif;
	$sqlstr = "select guid from wp_".$blog_id."_posts where id=".$post->ID;
	$guids = $wpdb->get_results ($sqlstr, ARRAY_A);
	if (strstr ($guids [0]["guid"], "?attachment_id="))
	{
		$sqlstr = "select meta_value from wp_".$blog_id."_postmeta where post_id=".$post->ID." and meta_key='_wp_attached_file'";
		$iae = $wpdb->get_results ($sqlstr, ARRAY_A);
		$ia = $paths [0]["path"]."wp-content/uploads/sites/".$blog_id."/".$iae [0]["meta_value"];
	}
	else
		if (strstr ($guids [0]["guid"], "http://rivnodennya"))
			$ia = substr ($guids [0]["guid"], 24, strlen ($guids [0]["guid"]) - 24);
		else
			$ia = $guids [0]["guid"];
?>
								<a href="<?php
								echo $ia;
								?>" title="<?php
								the_title_attribute ();
								?>
								" rel="lightbox"><?php
								/**
 								 * Filter the image attachment size to use.
								 *
								 * @since Twenty Twelve 1.0
								 *
								 * @param array $size {
								 *     @type int The attachment height in pixels.
								 *     @type int The attachment width in pixels.
								 * }
								 */
								$attachment_size = apply_filters ("rivnodennya_attachment_size", array (960, 960));							
								echo wp_get_attachment_image ($post->ID, $attachment_size);
								?></a>
							</div><!-- .attachment -->
						</div><!-- .entry-attachment -->
						</nav><!-- #image-navigation -->
					</div><!-- .entry-content -->
						<div class="entry-description">
								<?php if (!empty ($post->post_excerpt)) : ?>
								<div class="entry-caption image-caption">
									<?php the_excerpt (); ?>
								</div>
								<?php endif;
								the_content ();
								wp_link_pages (array ("before" => "<div class='page-links'>". __("Pages:", "rivnodennya"), "after" => "</div>"));
							?>
						</div><!-- .entry-description -->														
						<footer class="entry-meta image-meta">
							<?php
								$metadata = wp_get_attachment_metadata ();							
								printf (__ ('<div class="image-description">
									<div class="image-album">
									З альбому "<a href="'.$album_address.'" title="Повернутися до '.$album_title.'" rel="gallery">'.$album_title.'</a>"
									</div>
									<time class="entry-date" datetime="%1$s">%2$s</time>
									</div>', "rivnodennya"),
									esc_attr (get_the_date ("c")),
									esc_html (get_the_date ()),
									esc_url (wp_get_attachment_url ()),
									$metadata ["width"],
									$metadata ["height"],
									esc_url (get_permalink ($post->post_parent)),
									esc_attr (strip_tags (get_the_title ($post->post_parent))),
									get_the_title ($post->post_parent)
								);
								edit_post_link (__ ("Edit", "rivnodennya"), '<span class="edit-link">', "</span>");
							?>
						</footer><!-- .entry-meta -->
				</article><!-- #post -->				
				<?php
					if(function_exists ("the_views"))
					{
						echo "<div class='views-count'>";
						the_views ();
						echo "</div>";
					}
					if (function_exists ("the_ratings"))
					{
						the_ratings ();
					}
					comments_template ();
					endwhile; // end of the loop. ?>
		</div><!-- #content -->
	</div><!-- #primary -->
	<script>
		function windowHeight ()
		{
			var wh = document.documentElement;
			return self.innerHeight || (wh && wh.clientHeight) || document.body.clientHeight;
		}
		var post = document.getElementById ("content");
		var all_nav = post.getElementsByTagName ("nav"); 
		var all_img = all_nav [0].getElementsByTagName ("img");
		var left_href = all_nav [0].getElementsByClassName ("left-link");
		var hrefs = all_nav [0].getElementsByTagName ("a");
		var w = all_img [0].getAttribute ("width");
		var h = all_img [0].getAttribute ("height");
		var p_w = post.offsetWidth;
		if ((w > h) || (h / w < (windowHeight () - 86) / p_w))
		{
			all_nav [0].setAttribute ("style", "width: calc(100% - 40px);");
			<?php
				if (($prev_link == "") && ($next_link != ""))
					echo "hrefs [0].setAttribute ('style','right: 20px; width: calc ((100% - 40px)/3');";
					if ($prev_link != "")
					{
						echo "hrefs [0].setAttribute ('style','left: 20px; width: calc((100% - 40px)/3');";
						if ($next_link != "")
							echo "hrefs [1].setAttribute('style','right: 20px; width: calc((100% - 40px)/3');";
					}
			?>
		}
		else						
		{
			all_nav [0].setAttribute ("style", "width: " + (windowHeight () - 106) * w / h + "px;");
			<?php
				if (($prev_link == "") && ($next_link != ""))
					echo "hrefs [0].setAttribute ('style','right: calc(50% - ' + (windowHeight () - 106) * w / h / 2 + 'px); width: ' + ((windowHeight () - 106) * w / h / 3) + 'px;');";
				if ($prev_link != "")
				{
					echo "hrefs [0].setAttribute ('style','left: calc(50% - ' + (windowHeight () - 106) * w / h / 2 + 'px); width: ' + ((windowHeight () - 106) * w / h / 3) + 'px;');";
					if ($next_link != "")
						echo "hrefs [1].setAttribute('style','right: calc(50% - ' + (windowHeight () - 106) * w / h / 2 + 'px); width: ' + ((windowHeight () - 106) * w / h / 3) + 'px;');";
				}
			?>
		}
	</script>
<?php
	get_sidebar ();
	get_footer ();
?>