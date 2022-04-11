<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon">
	<div id="futer_mnu"><?php
	if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('FooterSidebar') ) { 
 }
 ?></div>
	 
	 	<div class="site-info">
			<h2><a href="/">Рівнодення 2015</a></h2>
			<!--LiveInternet counter--><script type="text/javascript"><!--
document.write("<a href='http://www.liveinternet.ru/stat/rivnodennya17.com/index.html?lang=uk' "+
"target=_blank><img src='//counter.yadro.ru/hit?t11.9;r"+
escape(document.referrer)+((typeof(screen)=="undefined")?"":
";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
";"+Math.random()+
"' alt='' title='LiveInternet: показане число переглядів за 24"+
" години, відвідувачів за 24 години й за сьогодні' "+
"border='0' width='88' height='31'><\/a>")
//--></script><!--/LiveInternet--><!-- LINKS.I.UA button --><a href="http://links.i.ua" id="iualink" onclick="this.href='http://links.i.ua/add/?cid=186257';" title="Добавить в Закладки I.UA"><img src="http://i.i.ua/r/bms1_11.gif" border="0" width="88" height="19"></a><!-- End of LINKS.I.UA button -->
		</div><!-- .site-info -->
		
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>