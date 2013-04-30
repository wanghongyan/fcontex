<!--头部开始-->
<div id="header">
	<div class="logo">
		<img src="<?php echo URL_THEME; ?>images/logo.png" alt="<?php echo $SITE['site_name']; ?>" title="<?php echo $SITE['site_name']; ?>" />
	</div>
	<ul class="nav">
	<?php
		$navi = $C->getNavigate();
		foreach ($navi as $v):
	?>
		<li><a href="<?php echo $v['nv_url']; ?>" target="<?php echo $v['nv_target'] ? '_blank' : ''; ?>"><?php echo $v['nv_title']; ?></a></li>
	<?php
		endforeach;
	?>
	</ul>
</div>
<!--头部结束-->