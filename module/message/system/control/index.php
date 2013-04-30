<?php
/***
 * 名称：消息模块动作处理程序
 * Alan 2012.07
 * www.fcontex.com
*/

include '../../../../kernel/startup.php';

switch ($mode = $A->strGet('mode'))
{
	case 'gbook.delete':
		
		if (!$U->hasRights('gbook.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = $A->strGet('args');
		if (!preg_match('/^[0-9]+?(,[0-9]+?)*$/', $args))
		{
			echo 'ERR|未选中任何项。';
		}
		else
		{
			$D->delete('T[gbook]', 'gb_id in ('.$args.')');
			
			$A->logInsert('删除了留言 #'.$args);
			echo 'YES';
		}
		break;
		
	case 'gbook.check_1':
	case 'gbook.check_0':
		if (!$U->hasRights('gbook.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = $A->strGet('args');
		$check = intval($A->strGet('check'));
		if (!$args) die('ERR|请选择记录。');
		$ids = explode(',', $args);
		foreach ($ids as $id)
		{
			$D->update('T[gbook]', array('gb_check' => $check), array('gb_id' => $id));
		}
		echo 'YES';
		break;
	
	case 'gbook.update':
		if (!$U->hasRights('gbook.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = intval($A->strGet('args'));
		$D->update('T[gbook]', array('gb_reply' => trim($A->strPost('gb_reply'))), array('gb_id' => $args));
		echo 'YES';
		break;
		
	case 'comment.delete':
		
		if (!$U->hasRights('comment.delete'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = $A->strGet('args');
		if (!preg_match('/^[0-9]+?(,[0-9]+?)*$/', $args))
		{
			echo 'ERR|未选中任何项。';
		}
		else
		{
			$ct_ids = $D->fetch($D->query('select cm_cid from T[comment] where cm_id in ('.$args.')'));
			$D->delete('T[comment]', 'cm_id in ('.$args.')');
			foreach ($ct_ids as $ct_id)
			{
				$rst = $D->fetch($D->query('select count(*) as talks from T[comment] where cm_cid = '.$ct_id));
				if ($rst) $D->query('update T[content] set ct_talks = '.$rst['talks'].' where ct_id = '.$ct_id);
			}
			
			$A->logInsert('删除了评论 #'.$args);
			echo 'YES';
		}
		break;
		
	case 'comment.check_1':
	case 'comment.check_0':
		if (!$U->hasRights('comment.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = $A->strGet('args');
		$check = intval($A->strGet('check'));
		if (!$args) die('ERR|请选择记录。');
		$ids = explode(',', $args);
		foreach ($ids as $id)
		{
			$D->update('T[comment]', array('cm_check' => $check), array('cm_id' => $id));
		}
		echo 'YES';
		break;
		
	case 'comment.update':
		if (!$U->hasRights('comment.update'))
		{
			die('ERR|'.ERROR_NO_RIGHTS);
		}
		$args = intval($A->strGet('args'));
		$cm_content = trim($A->strPost('cm_content'));
		$cm_check = intval($A->strPost('cm_content')) ? 1 : 0;
		$array = array
		(
			'cm_content' => $cm_content,
			'cm_check' => $cm_check
		);
		$D->update('T[comment]', $array, array('cm_id' => $args));
		echo 'YES';
		break;
		
	default:
		echo 'ERR|无效请求。';
		break;
}
?>