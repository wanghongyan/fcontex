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
<h3>发表评论</h3>
<div id="comment_form_box">
<?php if ($quiet): ?>
	当前页面已关闭评论。
<?php else: ?>
	<form onSubmit="comment.submit(); return false;" id="form_comment" class="comment-form">
		<fieldset>
			<legend>留言信息</legend>
			<input type="hidden" name="cm_control" id="cm_control" value="<?php echo $R->controller; ?>" />
			<input type="hidden" name="cm_cid" id="cm_cid" value="0" />
			<input type="hidden" name="cm_ctitle" id="cm_ctitle" value="" />
			<input type="hidden" name="cm_toid" id="cm_toid" value="0" />
			<input type="hidden" name="cm_topid" id="cm_topid" value="0" />
			<input type="hidden" name="cm_toname" id="cm_toname" value="" />
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td width="60">*称呼</td>
					<td><input type="text" class="text" name="cm_name" id="cm_name" placeholder="称呼" /></td>
				</tr>
				<tr>
					<td>&nbsp;邮箱</td>
					<td><input type="text" class="text" name="cm_email" id="cm_email" placeholder="邮箱" /></td>
				</tr>
				<tr>
					<td>&nbsp;网站</td>
					<td><input type="text" class="text" name="cm_url" id="cm_url" placeholder="网站" /></td>
				</tr>
				<tr>
					<td valign="top">*内容</td>
					<td><textarea class="text" name="cm_content" id="cm_content" placeholder="说点什么吧..."></textarea></td>
				</tr>
				<tr>
					<td></td>
					<td><button type="submit" value="提交">提交</button> <button type="button" id="cancel">取消</button><label id="comment_info" style="display:none;"></label></td>
				</tr>
			</table>
		</fieldset>
	</form>
<?php endif; ?>
    </div>