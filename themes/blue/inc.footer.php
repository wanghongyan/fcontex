<!--页脚开始-->
<footer id="footer">
	<p>
		<span class="copyright"><a href="http://www.fcontex.com/" target="_blank">Powered By <?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?></a>&nbsp; &nbsp; <?php echo $SITE['site_copyright']; ?></span>
		<a href="<?php echo $R->getUrl('system', ''); ?>" rel="nofollow">Login</a>&nbsp;|&nbsp;
		<a href="<?php echo $R->getUrl('content/rss', ''); ?>" target="_blank">Rss</a>&nbsp;|&nbsp;
		<a href="/sitemap.xml" target="_blank">Sitemap</a>
		<span style="display:none"><?php echo $SITE['site_counter']; ?></span>
	</p>
</footer>
<!--页脚结束-->