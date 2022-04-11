<?php
	/**
	* The Template for displaying all single posts
	*
	* @package WordPress
	* @subpackage Twenty_Twelve
	* @since Twenty Twelve 1.0
	*/
	get_header ();
	global $blog_id;
	global $wpdb;
?>
<div id="primary" class="site-content">
	<div id="content" role="main">
	<?php
		echo "<script>
			if ((documentElement.scrollWidth < 1050) || ((document.scrollWidth > 1100) && (document.scrollWidth < 1500)))
				(document.getElemebtByTagName ('iframe').foreach (frame, document.getElemebtByTagName ('iframe')))
				frame.style.height=(document.getElementById ('primary').offsetWidth * 0.5625) + px;
						</script>";
		while (have_posts ()) :		
			$sqlstr = "select path from wp_blogs where blog_id=".$blog_id;
			$path = $wpdb->get_results ($sqlstr, ARRAY_A);
			if ($blog_id == 14)
			{
				$sqlstr = "select term_id from wp_14_term_taxonomy where parent=4 and term_id in(select term_taxonomy_id from wp_14_term_relationships where object_id=".get_the_ID ().")";
				$poet = $wpdb->get_results ($sqlstr, ARRAY_A);
			}
			echo "<style>";
			if ($poet [0]["term_id"] > 0)
				echo "
					.teachers a[href=\"/teachers/?autor=".$poet [0]["term_id"]."\"]
					{
						color: #FFFDDC;
					}";
			$is_album = false;
			$sqlstr = "select term_id, slug from wp_".$blog_id."_terms where term_id in(select term_taxonomy_id from wp_".$blog_id."_term_relationships where object_id=".get_the_ID ().")";
			$slugs = $wpdb->get_results ($sqlstr, ARRAY_A);
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
				#secondary h3 a[href=\"".$path [0]["path"]."category/".$slug ["slug"]."\"]
				{
					color: #FFFDDC;
				}";
				if ($poet [0]["term_id"] > 0)
					echo "#top_sidebar a[href=\"/teachers/category/".$slug ["slug"]."/?autor=".$poet [0]["term_id"]."\"]
					{
						background: #D9B66D;
						transition: all 1s linear;
					}
					#secondary h3 a[href=\"/teachers/category/".$slug ["slug"]."/?autor=".$poet [0]["term_id"]."\"]
					{
						color: #FFFDDC;
					}";
				echo "@media screen and (max-width: 600px)
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
					}";
				if ($poet [0]["term_id"] > 0)
					echo "#top_sidebar a[href=\"/teachers/category/".$slug ["slug"]."/?autor=".$poet [0]["term_id"]."\"]
					{	
						background: none;
						color: #84469F !important;
						transition: all 1s linear;
					}";
				echo "
				}";
				$is_liric = false;
				if (($slug ["slug"] == "obrazotvorche-mystetstvo") || ($slug ["slug"] == "moyi-svitlyny"))
					$is_album = true;
				if ($slug ["slug"] == "poeziya")
					$is_liric = true;
				if ($poet [0]["term_id"] == 0)
				{
					$sqlstr = "select parent from wp_".$blog_id."_term_taxonomy where term_id=".$slug ["term_id"];
					$parents = $wpdb->get_results ($sqlstr, ARRAY_A);
					if ($parents [0]["parent"] > 0)
					{
						while ($parents [0]["parent"] > 0)
						{
							$root_cat = $parents [0]["parent"];
							$sqlstr = "select slug from wp_".$blog_id."_terms where term_id=".$parents [0]["parent"];
							$slugs1 = $wpdb->get_results ($sqlstr, ARRAY_A);
							echo "#secondary h3 a[href=\"".$path [0]["path"]."category/".$slugs1 [0]["slug"]."\"]
								{
									color: #FFFDDC;
								}";
							$sqlstr = "select parent from wp_".$blog_id."_term_taxonomy where term_id=".$parents [0]["parent"];
							$parents = $wpdb->get_results ($sqlstr, ARRAY_A);
						}
						$sqlstr = "select slug from wp_".$blog_id."_terms where term_id=".$root_cat;
						$slug1 = $wpdb->get_results ($sqlstr, ARRAY_A);
						if ($slug1 [0]["slug"] == "poeziya")
							$is_liric = true;
						echo "#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$slug1 [0]["slug"]."/\"]
							{
								background: #D9B66D;
								transition:all 1s linear;
							}
							#top_sidebar a[href=\"".$path [0]["path"]."category/".$slug1 [0]["slug"]."/\"]
							{
								background: #D9B66D;
								transition:all 1s linear;
							}
							@media screen and (max-width: 600px)
							{
								#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$slug1 [0]["slug"]."/\"]
								{
									background: none;
									color: #84469F !important;
									transition:all 1s linear;
								}
								#top_sidebar a[href=\"".$path [0]["path"]."category/".$slug1 [0]["slug"]."/\"]
								{
									background: none;
									color: #84469F !important;
									transition:all 1s linear;
								}";
					}
				}
				else					
				{
					if ($slug ["term_id"] == 15)
						$is_liric = true;
					$sqlstr = "select parent from wp_14_term_taxonomy where term_id=".$slug ["term_id"];
					$parents = $wpdb->get_results ($sqlstr, ARRAY_A);
					if (($parents [0]["parent"] != 5) && ($parents [0]["parent"] != 4))
					{
						while ($parents [0]["parent"] != 5)
						{
							$sqlstr = "select slug from wp_14_terms where term_id=".$parents [0]["parent"];
							$slugs1 = $wpdb->get_results ($sqlstr, ARRAY_A);
							echo "#secondary h3 a[href=\"/teachers/category/".$slugs1 [0]["slug"]."/?autor=".$poet [0]["term_id"]."\"]
								{
									color: #FFFDDC;
								}";
							$root_cat = $parents [0]["parent"];
							$sqlstr = "select parent from wp_14_term_taxonomy where term_id=".$parents [0]["parent"];
							$parents = $wpdb->get_results ($sqlstr, ARRAY_A);
						}
						if ($root_cat == 15)
							$is_liric = true;
						$sqlstr = "select slug from wp_14_terms where term_id=".$root_cat;
						$slug1 = $wpdb->get_results ($sqlstr, ARRAY_A);
						echo "#top_sidebar a[href=\"/teachers/category/".$slug1 [0]["slug"]."/?autor=".$poet [0]["term_id"]."\"]
							{
								background: #D9B66D;
								transition: all 1s linear;
							}
							@media screen and (max-width: 600px)
							{
								#top_sidebar a[href=\"/teachers/category/".$slug1 [0]["slug"]."/?autor=".$poet [0]["term_id"]."\"]
								{
									background: none;
									color: #84469F !important;
									transition: all 1s linear;
								}
							}";
					}
				}
			}
			echo "</style>";
			$sqlstr = "select slug from wp_".$blog_id."_terms where term_id in(select parent from wp_".$blog_id."_term_taxonomy where term_taxonomy_id in(select term_taxonomy_id from wp_".$blog_id."_term_relationships where object_id=".get_the_ID ()."))";
			$slugs = $wpdb->get_results($sqlstr, ARRAY_A);
			foreach ($slugs as $slug)
			{
				echo "<style>
				#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$slug ["slug"]."/\"]
				{	
					background: #D9B66D;
					transition: all 1s linear;
				}";
				if ($poet [0]["term_id"] > 0)
					echo "#top_sidebar a[href=\"".$path [0]["path"]."category/".$slug ["slug"]."/?autor=".$poet [0]["term_id"]."\"]
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
					#top_sidebar a[href=\"http://rivnodennya17.com".$path [0]["path"]."category/".$slug ["slug"]."/\"]{	
					background: none;
					color: #84469F !important;
					transition: all 1s linear;
				}";
				if ($poet [0]["term_id"] > 0)
					echo "#top_sidebar a[href=\"".$path [0]["path"]."category/".$slug ["slug"]."/?autor=".$poet [0]["term_id"]."\"]
					{	
						background: none;
						color: #84469F !important;
						transition: all 1s linear;
					}";
				echo "
				}
				.liric-autor a[href=\"".substr ($_SERVER ["REQUEST_URI"], 0, strlen ($_SERVER ["REQUEST_URI"]) - 1)."\"]
				{
					color: #FFFDDC;
				}
				</style>";
				if (($slug ["slug"] == "obrazotvorche-mystetstvo") || ($slug ["slug"] == "moyi-svitlyny"))
					$is_album = true;
			}
			the_post ();
			if ($is_album)
			{
				$thumb_id = get_post_thumbnail_id ();
				$thumb_url = wp_get_attachment_image_src ($thumb_id, "thumbnail-size", true);
				echo "<div class='album-avatar'><a href='".$thumb_url [0]."' rel='lightbox'>".get_the_post_thumbnail ()."</a></div>";
				echo "<div class='album_thumbs'>
				<h3>".get_the_title ()."</h3>";
				$images = explode (",", substr (strstr (strstr (get_the_content (), "\"]", true), "[gallery ids="), 14, strlen (strstr (strstr (get_the_content (), "\"]", true), "[gallery ids=")) - 14));
				foreach ($images as $image)
				{
					$sqlstr = "select guid, post_excerpt, post_name from wp_".$blog_id."_posts where id=".$image;
					$image_data = $wpdb->get_results ($sqlstr, ARRAY_A);
					if (strstr ($image_data [0]["guid"], "?attachment_id="))
					{
						$sqlstr = "select meta_value from wp_".$blog_id."_postmeta where post_id=".$image." and meta_key='_wp_attached_file'";
						$iae = $wpdb->get_results ($sqlstr, ARRAY_A);
						$ia = $path [0]["path"]."wp-content/uploads/sites/".$blog_id."/".$iae [0]["meta_value"];
					}
					else
						if (strstr ($image_data [0]["guid"], "http://rivnodennya"))
							$ia = substr ($image_data [0]["guid"], 24, strlen ($image_data [0]["guid"]) - 24);
						else
							$ia = $image_data [0]["guid"];
					if (file_exists (substr ($ia, strlen ($path [0]["path"]), strlen ($ia) - strlen (strrchr ($ia, ".")) - strlen ($path [0]["path"]))."-150x150".strrchr ($ia, ".")))
						echo "<div class='album_avatars'>
							<a href='".get_permalink ().$image_data [0]["post_name"]."#begin-".$image."'>
								<img src='".substr ($ia, 0, strlen ($ia) - strlen (strrchr ($ia, ".")))."-150x150".strrchr ($ia, ".")."'/>
								<div id='album-title'>".$image_data [0]["post_excerpt"]."
								</div>
							</a>
							</div>";
					else
					{
						$dir = opendir (substr ($ia, strlen ($path [0]["path"]), strlen ($ia) - strlen (strrchr ($ia, "/")) - strlen ($path [0]["path"])));
						while ($file = readdir ($dir))
						{
							if ((substr ($file, 0, strlen ($file) - strlen (strrchr ($file, "-"))) == substr (strrchr ($ia, "/"), 1, strlen (strrchr ($ia, "/")) - strlen (strrchr ($ia, ".")) - 1)) && ((strstr (strrchr ($file, "-"), "x", true) == "-150") || (strstr (strrchr ($file, "x"), ".", true) == "x150") || ((strstr (substr (strrchr ($file, "-"), 1, strlen (strrchr ($file, "-")) - 1), "x", true) < 150) && (strstr (substr (strrchr ($file, "x"), 1, strlen (strrchr ($file, "x")) - 1), ".", true) < 150))))
								echo "<div class='album_avatars'>
									<a href='".get_permalink ().$image_data [0]["post_name"]."#begin-".$image."'>
										<img src='".substr ($ia, strlen ($path [0]["path"]) - 1, strlen ($ia) - strlen (strrchr ($ia, "/")) - strlen ($path [0]["path"]) + 2).$file."'/>
										<div id='album-title'>".$image_data [0]["post_excerpt"]."
										</div>
									</a>
							</div>";
						}
					}
				}
				echo "</div>";
			}
			else
				get_template_part ("content", get_post_format ());
			if (function_exists ("the_views"))
			{
				echo "<div class='views-count'>";
				the_views ();
				echo "</div>";
			}
			if (function_exists ("the_ratings"))
			{
				the_ratings ();
			}
			comments_template ("", true);
		endwhile; // end of the loop.
		global $blog_id;
		if ($is_liric)
			switch ($blog_id)
			{
				case 9:
					echo "<h4 class='all-date'><a href='/virovec'>Переглянути всі вірші автора (за датою)</a></h4><br/>
					<h4 class='all-abc'><a href='/virovec?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
					break;
				case 13:
					echo "<h4 class='all-date'><a href='/khvorost'>Переглянути всі вірші автора (за датою)</a></h4><br/>
					<h4 class='all-abc'><a href='/khvorost?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
					break;
				case 15:
					echo "<h4 class='all-date'><a href='/bashkirova'>Переглянути всі вірші автора (за датою)</a></h4><br/>
					<h4 class='all-abc'><a href='/bashkirova?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
					break;
				case 16:
					echo "<h4 class='all-date'><a href='/olir'>Переглянути всі вірші автора (за датою)</a></h4><br/>
					<h4 class='all-abc'><a href='/olir?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
					break;
				case 17:
					echo "<h4 class='all-date'><a href='/naumenko'>Переглянути всі вірші автора (за датою)</a></h4><br/>
					<h4 class='all-abc'><a href='/naumenko?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
					break;					
				case 18:
					echo "<h4 class='all-date'><a href='/kostenko'>Переглянути всі вірші автора (за датою)</a></h4><br/>
					<h4 class='all-abc'><a href='/kostenko?sort=abc'>Переглянути всі вірші автора (за абеткою)</a></h4>";
					break;
				case 14:
					echo "<h4 class='all-date'><a href='/teachers/?autor=".$poet [0]["term_id"]."'>Переглянути всі вірші автора (за датою)</a></h4><br/>
					<h4 class='all-abc'><a href='/teachers/?sort=abc&autor=".$poet [0]["term_id"]."'>Переглянути всі вірші автора (за алфавітом)</a></h4>";
					break;
			}
	?>
		</div><!-- #content -->
	</div><!-- #primary -->
<?php
	get_sidebar ();
	get_footer ();
?>