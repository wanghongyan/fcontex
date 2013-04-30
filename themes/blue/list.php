<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include 'inc.head.php'; ?>
<script type="text/javascript">
$(function()
{
	scrolltotop.init();
	
	$('.list_img a').hover(function()
	{
		$(this).animate({'opacity':'0.1'}, 300);
	}
	,function()
	{
		$(this).stop(true,false).animate({'opacity':'0'}, 300);
	});
})
</script>
</head>
<body>
	<div class="container">
		<?php include 'inc.header.php'; ?>
		<div id="content">
			<section id="content-main">
			<?php foreach ($items as $item): ?>
				<article>
					<div class="title">
						<h2><a href="<?php echo $R->getUrl('content/read-'.$item['ct_id']); ?>"><?php echo $item['ct_title']; ?></a></h2>
						<div class="sprite">
							<strong>
								<?php echo $item['ct_username']; ?>
							</strong>
							发表于
							<span>
								<?php echo date('Y-m-d', $item['ct_inserttime']); ?>
							</span>
							分类
							<span>
								互联网
							</span> |
							评论：
							<span>
								<a href="<?php echo $R->getUrl('content/read-'.$item['ct_id']); ?>#comment_box"><?php echo $item['ct_talks']; ?></a>
							</span>
							<?php
							$tags = explode(',', $item['ct_tags']);
							if ($tags[0]):
							?>
							| Tags:
							<span>
							<?php foreach ($tags as $tag): ?>
								<a href="<?php echo $R->getUrl('content/tag/'.urlencode($tag), ''); ?>"><?php echo $tag; ?></a>&nbsp;
							<?php endforeach; ?>
							</span>
							<?php endif; ?>
						</div>
					</div>
					<div class="content">
					<?php if ($item['ct_cover']): ?>
						<p><a href="<?php echo $R->getUrl('content/read-'.$item['ct_id']); ?>"><img src="<?php echo $A->getThumb($item['ct_cover']); ?>" width="620" /></a></p>
					<?php endif; ?>
						<p><?php echo $item['ct_summary']; ?></p>
						<p class="readMore"><a href="<?php echo $R->getUrl('content/read-'.$item['ct_id']); ?>">阅读全文>>></a></p>
					</div>
				</article>
			<?php endforeach; ?>
			<?php if (count($items) == 0): ?>
				<strong>暂无记录。</strong>
			<?php else: echo $turnner;?>
			<?php endif;?>
			</section>
			<?php include 'inc.sider.php'; ?>
		</div>
		<?php include 'inc.footer.php'; ?>
	</div>
</body>
</html>
