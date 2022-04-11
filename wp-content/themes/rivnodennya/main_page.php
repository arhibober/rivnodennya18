<?php
/*
Template Name: Портрети
*/
global $wpdb;
define('WP_USE_THEMES', false);
get_header();
?>	
<div id="primary" class="site-content mcs">
<div id="content" class="main-page">
<div class="ramka1">
<div class="ramka">
<div class="photo1">
<div class="main-avatar">
<a href="/bashkirova/">
<div class="ramka_avatar1">
<div class="ramka_avatar">
</div>
<?php
	$sqlstr = "select meta_value from wp_15_postmeta where post_id in(select meta_value from wp_usermeta where user_id=3 and meta_key='wp_15_user_avatar') and meta_key='_wp_attachment_metadata'";
	$meta_value = $wpdb->get_results ($sqlstr, ARRAY_A);
	$avatars = unserialize ($meta_value [0]["meta_value"]);
	echo "<img src='/wp-content/uploads/sites/15/".substr ($avatars ["file"], 0, 8).$avatars ["sizes"]["medium"]["file"]."' alt=''/>";
?>
</div>
</a>
</div>
<p class="caption"><a href="/bashkirova/">Ольга<br/>
Башкірова</a></p>
</div>
<div class="photo1">
<div class="main-avatar">
<a href="/virovec/">
<div class="ramka_avatar1">
<div class="ramka_avatar">
</div>
<?php
	$sqlstr = "select meta_value from wp_9_postmeta where post_id in(select meta_value from wp_usermeta where user_id=1 and meta_key='wp_9_user_avatar') and meta_key='_wp_attachment_metadata'";
	$meta_value = $wpdb->get_results ($sqlstr, ARRAY_A);
	$avatars = unserialize ($meta_value [0]["meta_value"]);
	echo "<img src='/wp-content/uploads/sites/9/".substr ($avatars ["file"], 0, 8).$avatars ["sizes"]["medium"]["file"]."' alt=''/>";
?>
</div>
</a>
</div>
<p class="caption"><a href="/virovec/">Лариса<br/>
Вировець</a></p>
</div>
<div class="photo1">
<div class="main-avatar">
<a href="/olir/">
<div class="ramka_avatar1">
<div class="ramka_avatar">
</div>
<?php
	$sqlstr = "select meta_value from wp_16_postmeta where post_id in(select meta_value from wp_usermeta where user_id=5 and meta_key='wp_16_user_avatar') and meta_key='_wp_attachment_metadata'";
	$meta_value = $wpdb->get_results ($sqlstr, ARRAY_A);
	$avatars = unserialize ($meta_value [0]["meta_value"]);
	echo "<img src='/wp-content/uploads/sites/16/".substr ($avatars ["file"], 0, 8).$avatars ["sizes"]["medium"]["file"]."' alt=''/>";
?>
</div>
</a>
</div>
<p class="caption"><a href="/olir/">Олена<br/>
О'Лір</a></p>
</div>
<div class="photo1">
<div class="main-avatar">
<a href="/naumenko/">
<div class="ramka_avatar1">
<div class="ramka_avatar">
</div>
<?php
	$sqlstr = "select meta_value from wp_17_postmeta where post_id in(select meta_value from wp_usermeta where user_id=6 and meta_key='wp_17_user_avatar') and meta_key='_wp_attachment_metadata'";
	$meta_value = $wpdb->get_results ($sqlstr, ARRAY_A);
	$avatars = unserialize ($meta_value [0]["meta_value"]);
	echo "<img src='/wp-content/uploads/sites/17/".substr ($avatars ["file"], 0, 8).$avatars ["sizes"]["medium"]["file"]."' alt=''/>";
?>
</div>
</a>
</div>
<p class="caption"><a href="/naumenko/">Наталія<br/>
Науменко</a></p>
</div>
<div class="photo1">
<div class="main-avatar">
<a href="/khvorost/">
<div class="ramka_avatar1">
<div class="ramka_avatar">
</div>
<?php
	$sqlstr = "select meta_value from wp_13_postmeta where post_id in(select meta_value from wp_usermeta where user_id=2 and meta_key='wp_13_user_avatar') and meta_key='_wp_attachment_metadata'";
	$meta_value = $wpdb->get_results ($sqlstr, ARRAY_A);
	$avatars = unserialize ($meta_value [0]["meta_value"]);
	echo "<img src='/wp-content/uploads/sites/13/".substr ($avatars ["file"], 0, 8).$avatars ["sizes"]["medium"]["file"]."' alt=''/>";
?>
</div>
</a>
</div>
<p class="caption"><a href="/khvorost/">Люцина<br/>
Хворост</a></p>
</div>
</div>
</div>
<div class="about-site">
	<?php
		$args = array (
			'page_id' => 149,
			'posts_per_page' => 1
		);
		$my_pages = get_posts ($args);
		foreach	($my_pages as $post) :
			setup_postdata ($post);
		the_content ();
		if (($user_ID == 1) || ($user_ID == 7))
			echo "<a href='/wp-admin/post.php?post=149&action=edit' class='post-edit-link'>Редагувати</a><br/>
			<a href='/edit/?pid=149&_wpnonce=dc3d194358' class='post-edit-link'>Редагувати (швидко)</a><br/>			
			<a href='/".wp_nonce_url ("wp-admin/post.php?action=delete&post=149", "delete-post_149")."' onClick='return confirm (\"Ви впевнені?\");' onContextMenu='return false;' class='post-edit-link'>Видалити пост</a>";
		endforeach;
		wp_reset_query ();
	?>
</div>
</div><!-- #content -->
</div><!-- #primary -->

<?php
	get_sidebar ();
	get_footer ();
?>