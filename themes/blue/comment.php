<dl class="comment">
	<dt>查看评论</dt>
	<?php foreach ($comments as $rst):?>
	<dd>
		<div class="comment-avatar">
			<img src="http://www.gravatar.com/avatar/<?php echo md5($rst['cm_email']); ?>?s=40&r=X" />
		</div>
		<div class="comment-details">
			<span class="comment-name">
				<?php if ($rst['cm_url']): ?>
				<a target="_blank" href="http://<?php echo $rst['cm_url']; ?>"><?php echo $rst['cm_name']; ?></a>
				<?php else: ?>
				<?php echo $rst['cm_name']; ?>
				<?php endif; ?>
			</span>
			<p class="comment-content">
				<?php echo $rst['cm_content']; ?>
			</p>
			<span class="comment-datetime"><?php echo date('Y-m-d H:i', $rst['cm_time']); ?></span>
			<a href="javascript:void(0);" class="comment-post-reply" topid="<?php echo $rst['cm_id']; ?>" toid="<?php echo $rst['cm_id']; ?>" toname="<?php echo $rst['cm_name']; ?>">回复</a>
		</div>
		<ol class="comment-reply">
		<?php foreach ($M->getComments($rst['cm_id']) as $rst2): ?>
			<li>
				<div class="comment-avatar"><img src="http://www.gravatar.com/avatar/<?php echo md5($rst2['cm_email']); ?>?s=30&r=X" /></div>
				<div class="comment-details">
					<span class="comment-name">
					<?php if ($rst2['cm_url']): ?>
					<a target="_blank" href="http://<?php echo $rst2['cm_url']; ?>"><?php echo $rst2['cm_name']; ?></a>
					<?php else: ?>
					<?php echo $rst2['cm_name']; ?>
					<?php endif; ?>@<?php echo $rst2['cm_toname']; ?>
					</span>
					<p class="comment-content">
					<?php echo $rst2['cm_content']; ?>
					</p>
					<span class="comment-datetime"><?php echo date('Y-m-d H:i', $rst2['cm_time']); ?></span>
					<span><a href="javascript:void(0);" class="comment-post-reply" topid="<?php echo $rst2['cm_id']; ?>" toid="<?php echo $rst2['cm_id']; ?>" toname="<?php echo $rst2['cm_name']; ?>">回复</a></span>
				</div>
			</li>
		<?php endforeach; ?>
		</ol>
	</dd>
	<?php endforeach; ?>
</dl>
	<?php echo $turnner; ?>
	<h1>发表评论</h1>
    <div id="comment_form_box">
	<?php
	if ($quiet)
	{
		echo '当前页面已关闭评论。';
	}
	else
	{
	?>
		<form onSubmit="comment.submit(); return false;" id="form_comment">
			<input type="hidden" name="cm_control" id="cm_control" value="<?php echo $R->controller; ?>" />
			<input type="hidden" name="cm_cid" id="cm_cid" value="0" />
			<input type="hidden" name="cm_ctitle" id="cm_ctitle" value="" />
			<input type="hidden" name="cm_toid" id="cm_toid" value="0" />
			<input type="hidden" name="cm_topid" id="cm_topid" value="0" />
			<input type="hidden" name="cm_toname" id="cm_toname" value="" />
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td><input type="text" class="text" name="cm_name" id="cm_name" /><label>*称呼</label></td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="cm_email" id="cm_email" /><label>邮箱</label></td>
				</tr>
				<tr>
					<td><input type="text" class="text" name="cm_url" id="cm_url" /><label>网站</label></td>
				</tr>
				<tr>
					<td><textarea class="text" name="cm_content" id="cm_content"></textarea><label>*内容</label></td>
				</tr>
				<tr>
					<td class="submit"><input type="submit" value="提交" /> <input type="button" value="取消" id="cancel" /><label id="comment_info" style="display:none;"></label></td>
				</tr>
			</table>
		</form>
	<?php
	}
	?>
    </div>