<!--侧栏开始-->
<aside id="content-sidebar">
	<div id="globalsearch">
		<form method="get" class="search-form" onsubmit="var key=$('#keyw').val().replace('内容检索','');if(key!='')location.href='<?php echo $R->getUrl('content/search/', ''); ?>'+encodeURIComponent(key);return false;">
			<fieldset>
				<legend>内容检索</legend>
				<div class="search-label">
					<input type="text" onfocus="$('#globalsearch').css('width', '80%');" onblur="$('#globalsearch').css('width', '66%');" class="text" name="keyw" id="keyw" placeholder="关键词" <?php if (!empty($keyw)): ?> value="<?php  echo $keyw; ?>"<?php endif; ?> /><button type="submit"><img src="<?php echo URL_THEME; ?>images/magnifier.gif" width="20" height="20" border="0" /></button>
				</div>
			</fieldset>
		</form>
	</div>
	<div class="widget">
		<dl>
			<dt>分类</dt>
			<?php foreach ($C->getCategories(0) as $cateP): ?>
			<dd><span class="icon"> ➽ </span><a href="<?php if ($cateP['cg_type'] == 1){echo $cateP['cg_url'];}else{echo $R->getUrl('content/list-'.$cateP['cg_id']);}?>" title="<?php echo $cateP['cg_title']; ?>"<?php if ($cateP['cg_target'] == '_blank' && $cateP['cg_type'] == 1){echo ' target="_blank"';} ?>><?php echo $cateP['cg_title']; ?></a></dd>
			<?php endforeach; ?>
			<?php foreach ($C->getCategories($cateP['cg_id']) as $cate): ?>
			<dd><a href="<?php if ($cate['cg_type'] == 1){echo $cate['cg_url'];}else{echo $R->getUrl('content/list-'.$cate['cg_id']);}?>" title="<?php echo $cate['cg_title']; ?>"<?php if ($cate['cg_target'] == '_blank' && $cate['cg_type'] == 1){echo ' target="_blank"';} ?>><?php echo $cate['cg_title']; ?></a></dd>
			<?php endforeach; ?>
		</dl>
	</div>
	<div class="widget">
		<dl>
			<dt>标签</dt>
			<dd id="tag_cloud">
			<?php foreach ($C->getTags() as $tag): ?>
			<a href="<?php echo $R->getUrl('content/tag/'.urlencode($tag['tg_title']), ''); ?>" title="<?php echo $tag['tg_title']; ?>" style="<?php echo $tag['tg_color'] ? 'color:'.$tag['tg_color'] : ''; ?>"><?php echo $tag['tg_title']; ?></a>
			<?php endforeach; ?>
			</dd>
		</dl>
	</div>
	<script src="<?php echo URL_THEME; ?>/images/miaov.js" type="text/javascript"></script>
</aside>
<!--侧栏结束-->