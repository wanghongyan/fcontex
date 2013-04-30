<div class="right">
	<div class="widget">
		<h2>Category</h2>
		<ul class="category">
			<?php
			foreach ($C->getCategories(0) as $cateP)
			{
			?>
			<li class="P"><a href="<?php if ($cateP['cg_type'] == 1){echo $cateP['cg_url'];}else{echo $R->getUrl('content/list-'.$cateP['cg_id']);}?>" title="<?php echo $cateP['cg_title']; ?>"<?php if ($cateP['cg_target'] == '_blank' && $cateP['cg_type'] == 1){echo ' target="_blank"';} ?>><?php echo $cateP['cg_title']; ?></a></li>
			<?php
			foreach ($C->getCategories($cateP['cg_id']) as $cate)
			{
			?>
			<li><a href="<?php if ($cate['cg_type'] == 1){echo $cate['cg_url'];}else{echo $R->getUrl('content/list-'.$cate['cg_id']);}?>" title="<?php echo $cate['cg_title']; ?>"<?php if ($cate['cg_target'] == '_blank' && $cate['cg_type'] == 1){echo ' target="_blank"';} ?>><?php echo $cate['cg_title']; ?></a></li>
			<?php
			}
			}
			?>
		</ul>
	</div>
	<div class="widget">
		<h2>Tags</h2>
		<div class="tags">
		<?php
		foreach ($C->getTags() as $tag)
		{
		?>
		<p><a href="<?php echo $R->getUrl('content/tag/'.urlencode($tag['tg_title']), ''); ?>" title="<?php echo $tag['tg_title']; ?>" style="<?php echo $tag['tg_color'] ? 'color:'.$tag['tg_color'] : ''; ?>"><?php echo $tag['tg_title']; ?></a></p>
		<?php
		}
		?>
		<div class="clear"></div>
		</div>
	</div>
</div>
<!--侧栏结束-->