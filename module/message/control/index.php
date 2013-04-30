<?php
/***
 * 名称： 前端评论处理
 * Joe 2012.11
 * www.fcontex.com
*/

include '../../../kernel/startup.php';

switch ($mode = $A->strGet('mode'))
{
	case 'comment.insert':
		$cm_cid		= intval($A->strPost('cm_cid'));
		$cm_ctitle	= strip_tags(trim($A->strPost('cm_ctitle')));
		$cm_toid	= intval($A->strPost('cm_toid'));
		$cm_topid	= intval($A->strPost('cm_topid'));
		$cm_name	= strip_tags(trim($A->strPost('cm_name')));
		$cm_toname	= strip_tags(trim($A->strPost('cm_toname')));
		$cm_email	= strip_tags(trim($A->strPost('cm_email')));
		$cm_url		= strip_tags(trim($A->strPost('cm_url')));
		$cm_content	= strip_tags(trim($A->strPost('cm_content')));
		$cm_ip      = $A->getUserIP();
		$cm_iparea  = $A->loadLibrary('iparea')->dataMini($cm_ip);
		
		if (!$cm_cid || strlen($cm_ctitle)>100 || strlen($cm_toname)>100)
		{
			echo 'ERR|发生意外，请刷新页面后重试。';
		}
		elseif ($cm_name == '')
		{
			echo 'ERR|请填写称呼。|cm_name';
		}
		elseif (strlen($cm_name) > 36)
		{
			echo 'ERR|称呼长度超出限制。|cm_name';
		}
		elseif (strlen($cm_email) > 100)
		{
			echo 'ERR|邮箱地址长度超出限制。|cm_email';
		}
		elseif (strlen($cm_url) > 100)
		{
			echo 'ERR|网址长度超出限制。|cm_url';
		}
		elseif ($cm_content == '')
		{
			echo 'ERR|请填写内容。|cm_content';
		}
		elseif (strlen($cm_content) > 1000)
		{
			echo 'ERR|内容长度超出限制。|cm_content';
		}
		else
		{
			$array = array
			(
				'cm_cid'	=> $cm_cid,
				'cm_ctitle'	=> $cm_ctitle,
				'cm_toid'	=> $cm_toid,
				'cm_topid'	=> $cm_topid,
				'cm_name'	=> $cm_name,
				'cm_toname'	=> $cm_toname,
				'cm_email'	=> $cm_email,
				'cm_url'	=> str_ireplace('http://', '', $cm_url),
				'cm_content'=> $cm_content,
				'cm_ip'		=> $cm_ip,
				'cm_iparea'	=> $cm_iparea,
				'cm_check'	=> 1,
				'cm_time'	=> time(),
				'cm_update' => time()
			);
			$D->insert('T[comment]', $array);
			$D->query('update T[content] set ct_talks=ct_talks+1 where ct_id='.$cm_cid);
			if ($cm_topid) $D->update('T[comment]', array('cm_update' => time()), array('cm_id' => $cm_topid));
			echo 'YES|提交成功，更新列表...';
		}
		break;
	
	case 'gbook.insert':
		$gb_toid	= intval($A->strPost('gb_toid'));
		$gb_topid	= intval($A->strPost('gb_topid'));
		$gb_name	= strip_tags(trim($A->strPost('gb_name')));
		$gb_toname	= strip_tags(trim($A->strPost('gb_toname')));
		$gb_email	= strip_tags(trim($A->strPost('gb_email')));
		$gb_url		= strip_tags(trim($A->strPost('gb_url')));
		$gb_content	= strip_tags(trim($A->strPost('gb_content')));
		$gb_ip      = $A->getUserIP();
		$gb_iparea  = $A->loadLibrary('iparea')->dataMini($gb_ip);
		
		if (strlen($gb_toname) > 100)
		{
			echo 'ERR|发生意外，请刷新页面后重试。';
		}
		elseif ($gb_name == '')
		{
			echo 'ERR|请填写称呼。|gb_name';
		}
		elseif (strlen($gb_name) > 36)
		{
			echo 'ERR|称呼长度超出限制。|gb_name';
		}
		elseif (strlen($gb_email) > 100)
		{
			echo 'ERR|邮箱地址长度超出限制。|gb_email';
		}
		elseif (strlen($gb_url) > 100)
		{
			echo 'ERR|网址长度超出限制。|gb_url';
		}
		elseif ($gb_content == '')
		{
			echo 'ERR|请填写内容。|gb_content';
		}
		elseif (strlen($gb_content) > 1000)
		{
			echo 'ERR|内容长度超出限制。|gb_content';
		}
		else
		{
			$array = array
			(
				'gb_toid'	=> $gb_toid,
				'gb_topid'	=> $gb_topid,
				'gb_name'	=> $gb_name,
				'gb_toname'	=> $gb_toname,
				'gb_email'	=> $gb_email,
				'gb_url'	=> str_ireplace('http://', '', $gb_url),
				'gb_content'=> $gb_content,
				'gb_ip'		=> $gb_ip,
				'gb_iparea'	=> $gb_iparea,
				'gb_check'	=> 1,
				'gb_time'	=> time(),
				'gb_update' => time()
			);
			$D->insert('T[gbook]', $array);
			if ($gb_topid) $D->update('T[gbook]', array('gb_update' => time()), array('gb_id' => $gb_topid));
			echo 'YES|提交成功，更新列表...';
		}
		break;
		
	default:
		echo 'ERR|无效请求';
		break;
}
?>