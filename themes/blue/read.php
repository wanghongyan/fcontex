<?php if (!defined('SYSTEM_INCLUDE')) die('Access Denied.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include 'inc.head.php'; ?>
<script type="text/javascript">
var comment = 
{
	//读取评论
	fetch : function(url)
	{
		$.get(url, function(data)
		{
			$('#comment_box').html(data);
			$('#cm_cid').val('<?php echo $page['ct_id']; ?>');
			$('#cm_ctitle').val('<?php echo $page['ct_title']; ?>');
			$('#comment_box .comment-post-reply').click(function()
			{
				$('#comment_info').removeClass().html('');
				var form = $('#form_comment').clone(true);
				$('#form_comment').remove();
				$(this).parent().after(form);
				$('#cm_topid').val($(this).attr('topid'));
				$('#cm_toid').val($(this).attr('toid'));
				$('#cm_toname').val($(this).attr('toname'));
				$('#comment-form-avatar').hide();
				$('#cancel').css('display', 'inline').bind('click', function()
				{
					$('#comment_info').removeClass().html('');
					$('#form_comment').remove();
					$('#comment_form_box').after(form);
					$('#cm_topid').val('0');
					$('#cm_toid').val('0');
					$(this).css('display', 'none');
					$('#comment-form-avatar').show();
				});
			});
		});
		
	},
	
	//提交评论
	submit : function()
	{
		$('#comment_info').removeClass().html('<img src="<?php echo URL_THEME; ?>images/loading.gif" />').show();
		$.ajax
		({
			type    : 'post',
			url     : $('#cm_control').val()+'?mode=comment.insert',
			cache   : false,
			data    : $('#form_comment').serialize(),
			success : function(data, textStatus)
			{
				var a = data ? data.split('|') : ['无效的服务器响应。'];
				if (a[0] == 'YES')
				{
					$('#comment_info').addClass('comm_yes').html(a[1]);
					setTimeout(comment.loader, 800);
				}
				else if (a[0] == 'ERR')
				{
					$('#comment_info').addClass('comm_err').html(a[1]);
					if (a[2]) $('#'+a[2]).focus();
				}
				else
				{
					$('#comment_info').html(data);
				}
			},
			error  : function(XMLHttpRequest, textStatus, errorThrown)
			{
				$('#comment_info').addClass('comm_err').html('超求超时.');
			}
		});	
	},
	
	loader : function()
	{
		comment.fetch('<?php echo $R->getUrl('message/comment-'.$page['ct_id'].'-1'); ?>?'+Math.random());
	}
};

$(function()
{
	comment.loader();
	
	scrolltotop.init();
	
	$('.list_img a').hover(function()
	{
		$(this).animate({'opacity':'0.1'}, 300);
	}
	, function()
	{
		$(this).stop(true,false).animate({'opacity':'0'}, 300);
	});
	
	var atts = '', dot = '', fcatts = $('#content .fcattached');
	fcatts.each(function(){atts += dot+$(this).attr('key'); dot=',';});
	$.get('<?php echo $R->controller.'?mode=count&id='.$page['ct_id']; ?>&atts='+atts, null, function(data)
	{
		eval('obj = ' + data);
		if (typeof obj == 'object')
		{
			$('#hits').html(obj.hits || 0);
			$('#talks').html(obj.talks || 0);
			if (typeof obj.atts == 'object' && obj.atts.length == fcatts.length)
			{
				var i = 0;
				fcatts.each(function(){$(this).find('span').html(' (已下载'+(obj.atts[i++])+'次)');});
			}
		}
	});
});
</script>
</head>
<body class="read">
	<div class="container">
		<?php include 'inc.header.php'; ?>
		<div id="content">
			<section id="content-main">
			<?php if (empty($page)): ?>
				<div>信息不存在或已被删除。</div>
			<?php else: ?>
				<article>
					<div class="title">
						<h2><?php echo $page['ct_title']; ?></h2>
						<div class="sprite">
							<strong>
								<?php echo $page['ct_username']; ?>
							</strong>
							发表于
							<span>
								<?php echo date('Y-m-d', $page['ct_inserttime']); ?>
							</span>
							分类
							<span>
								互联网
							</span> |
							评论：
							<span>
								<a href="#comment_box" id="talks"><?php echo $page['ct_talks']; ?></a>
							</span>
							<?php
							$tags = explode(',', $page['ct_tags']);
							if ($tags[0]):
							?>
							| 标签:
							<span>
							<?php foreach ($tags as $tag): ?>
								<a href="<?php echo $R->getUrl('content/tag/'.urlencode($tag), ''); ?>"><?php echo $tag; ?></a>&nbsp;
							<?php endforeach; ?>
							</span>
							<?php endif; ?>
						</div>
					</div>
					<div class="content">
						<?php echo $page['ct_content']; ?>
					</div>
				</article>
			<?php endif; ?>
				<div id="comment_box"><b style="padding:20px 0; display:block;">正在加载评论...</b></div>
			</section>
			<?php include 'inc.sider.php'; ?>
		</div>
		<?php include 'inc.footer.php'; ?>
	</div>
</body>
</html>
