<?php if (!defined('SYSTEM_INCLUDE')) die('Access Denied.'); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $SITE['site_title']; ?></title>
<meta name="generator" content="<?php echo SYSTEM_NAME.' '.SYSTEM_VERSION; ?>" />
<meta name="viewport" content="width=1136" />
<meta name="description" content="<?php echo $SITE['site_description']; ?>" />
<meta name="keywords" content="<?php echo $SITE['site_keywords']; ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo URL_THEME; ?>images/style.css" />
<script type="text/javascript" src="<?php echo URL_THEME; ?>images/jquery.js"></script>
<script type="text/javascript" src="<?php echo URL_THEME; ?>images/scrolltop.js"></script>
<script type="text/javascript">
function gbook()
{
	$('#comment_info').removeClass().html('<img src="<?php echo URL_THEME; ?>images/loading.gif" />').show();
	$.ajax
	({
		type    : 'post',
		url     : '<?php echo $R->controller; ?>?mode=gbook.insert',
		cache   : false,
		data    : $('#form_comment').serialize(),
		success : function(data, textStatus)
		{
			var a = data ? data.split('|') : ['无效的服务器响应。'];
			if (a[0] == 'YES')
			{
				$('#comment_info').addClass('comm_yes').html(a[1]);
				setTimeout(function()
				{
					$('#comment_info').removeClass().html('');
					$('#form_comment').get(0).reset();
					$('#cancel').click();
					location.href = location.href;
				},
				800);
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
}

$(function()
{
	scrolltotop.init();
	
	$('#comment-list .comment-post-reply').click(function()
	{
		$('#comment_info').removeClass().html('');
		var form = $('#form_comment').clone(true);
		$('#form_comment').remove();
		$(this).parent().after(form);
		$('#gb_topid').val($(this).attr('topid'));
		$('#gb_toid').val($(this).attr('toid'));
		$('#gb_toname').val($(this).attr('toname'));
		$('#comment-form-avatar').hide();
		$('#cancel').css('display', 'inline').bind('click', function()
		{
			$('#comment_info').removeClass().html('');
			$('#form_comment').remove();
			$('#comment_form_box').after(form);
			$('#gb_topid').val('0');
			$('#gb_toid').val('0');
			$(this).css('display', 'none');
			$('#comment-form-avatar').show();
		});
	});
});
</script>
</head>
<body>
    <div class="container">
	<?php include 'inc.header.php'; ?>
		<div id="content">
			<section id="content-main">
				<div class="title">
					<h2>交流</h2>
				</div>
				<!--发表评论表单开始-->
				<div id="comment_form_box">
				<?php if ($quiet): ?>
					当前页面已关闭评论。
				<?php else: ?>
					<div class="comment-form-avatar" id="comment-form-avatar"><img src="http://www.gravatar.com/avatar/<?php echo md5('234'); ?>?s=40&r=X" /></div>
					<form onsubmit="gbook(); return false;" id="form_comment" class="comment-form">
						<fieldset>
							<legend>留言信息</legend>	
							<input type="hidden" name="cm_control" id="cm_control" value="<?php echo $R->controller; ?>" />
							<input type="hidden" name="cm_cid" id="cm_cid" value="0" />
							<input type="hidden" name="cm_ctitle" id="cm_ctitle" value="" />
							<input type="hidden" name="gb_toid" id="cm_toid" value="0" />
							<input type="hidden" name="gb_topid" id="cm_topid" value="0" />
							<input type="hidden" name="gb_toname" id="cm_toname" value="" />
							<table cellpadding="1" cellspacing="1" border="0">
								<tr>
									<td width="212"><input type="text" class="text" name="cm_name" id="cm_name" placeholder="称呼" /></td>
									<td colspan="2">*称呼</td>
								</tr>
								<tr>
									<td><input type="text" class="text" name="cm_email" id="cm_email" placeholder="邮箱" /></td>
									<td colspan="2">&nbsp;邮箱</td>
								</tr>
								<tr>
									<td><input type="text" class="text" name="cm_url" id="cm_url" placeholder="网站" /></td>
									<td colspan="2">&nbsp;网站</td>
								</tr>
								<tr>
									<td colspan="2"><textarea class="text" name="cm_content" id="cm_content" placeholder="说点什么吧..."></textarea></td>
									<td valign="top" colspan="2">*内容</td>
								</tr>
								<tr>
									<td colspan="2"><button type="submit" value="提交">提交</button> <button type="button" id="cancel">取消</button><label id="comment_info" style="display:none;"></label></td>
									<td></td>
								</tr>
							</table>
						</fieldset>
					</form>
				<?php endif; ?>
				</div>
				<!--发表评论表单结束-->
				<!--评论列表开始-->
				<dl class="comment" id="comment-list">
					<dt>评论列表</dt>
					<?php foreach ($gbook as $rst):?>
					<dd>
						<div class="comment-avatar">
							<img src="http://www.gravatar.com/avatar/<?php echo md5($rst['gb_email']); ?>?s=40&r=X" />
						</div>
						<div class="comment-details">
							<span class="comment-name">
								<?php if ($rst['gb_url']): ?>
								<a target="_blank" href="http://<?php echo $rst['gb_url']; ?>"><?php echo $rst['gb_name']; ?></a>
								<?php else: ?>
								<?php echo $rst['gb_name']; ?>
								<?php endif; ?>
							</span>
							<p class="comment-content">
								<?php echo $rst['gb_content']; ?>
							</p>
							<span class="comment-datetime"><?php echo date('Y-m-d H:i', $rst['gb_time']); ?></span>
							<a href="javascript:void(0);" class="comment-post-reply" topid="<?php echo $rst['gb_id']; ?>" toid="<?php echo $rst['gb_id']; ?>" toname="<?php echo $rst['gb_name']; ?>">回复</a>
						</div>
						<ol class="comment-reply">
						<?php foreach ($M->getGbook($rst['gb_id']) as $rst2): ?>
							<li>
								<div class="comment-avatar"><img src="http://www.gravatar.com/avatar/<?php echo md5($rst2['gb_email']); ?>?s=30&r=X" /></div>
								<div class="comment-details">
									<span class="comment-name">
									<?php if ($rst2['gb_url']): ?>
									<a target="_blank" href="http://<?php echo $rst2['gb_url']; ?>"><?php echo $rst2['gb_name']; ?></a>
									<?php else: ?>
									<?php echo $rst2['gb_name']; ?>
									<?php endif; ?>@<?php echo $rst2['gb_toname']; ?>
									</span>
									<p class="comment-content">
									<?php echo $rst2['gb_content']; ?>
									</p>
									<span class="comment-datetime"><?php echo date('Y-m-d H:i', $rst2['gb_time']); ?></span>
									<span><a href="javascript:void(0);" class="comment-post-reply" topid="<?php echo $rst2['gb_id']; ?>" toid="<?php echo $rst2['gb_id']; ?>" toname="<?php echo $rst2['gb_name']; ?>">回复</a></span>
								</div>
							</li>
						<?php endforeach; ?>
						</ol>
					</dd>
					<?php endforeach; ?>
				</dl>
				<?php echo $turnner; ?>
				<!--评论列表结束-->
			</section>
			<?php include 'inc.sider.php'; ?>
		</div>
	<?php include 'inc.footer.php'; ?>
	</div>
</body>
</html>
