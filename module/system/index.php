<?php
/***
 * 名称：系统模块
 * Alan 2012.03
 * www.fcontex.com
*/
if (defined('SYSTEM_INCLUDE'))
{
	switch ($R->event)
	{
		case 'file':
			$id = intval($A->strDeCode($R->param(2)));
			
			$rst = $D->fetch($D->query('select at_filename, at_filenewname, at_dir from T[attached] where at_id='.$id));
			if (empty($rst))
			{
				$R->print404();
			}
			$fname = PATH_STORE.$A->system['uploadDir'].'/'.$rst['at_dir'].$rst['at_filenewname'];
			if (!file_exists($fname))
			{
				$R->print404();
			}
			$fsize = filesize($fname);
			if ($fsize/1024/1024 > $A->system['uploadSize'])
			{
				$R->print404();
			}
			$range = 0;
			if(!empty($_SERVER['HTTP_RANGE']))
			{
				list($range) = explode('-', (str_replace('bytes=','',$_SERVER['HTTP_RANGE'])));
			}
			if (($length = $fsize-intval($range)) <= 0) $length = 0;
			header('HTTP/1.1 206 Partial Content');
			header("Content-Type: application/octet-stream");
			header("Content-Disposition: attachment; filename=".$rst['at_filename']);
			header('Content-Length: '.$length);
			header('Content-Range: bytes='.$range.'-'.($fsize-1).'/'.($fsize));
			$fp = fopen($fname, "rb");
			fseek($fp, $range);
			echo fread($fp, $fsize);
			fclose($fp);
			flush();ob_flush();
			$D->query('update T[attached] set at_hits=at_hits+1 where at_id='.$id);
			break;
		
		default:
			$R->cacheopen = TRUE;
			$T->display('404');
			break;
	}
}
else
{
	include '../../kernel/startup.php';
	
	switch ($A->strGet('mode'))
	{
		case 'menus':
			if (!$U->hasRights('system.login')) exit();
			$T->show('menus');
			break;
		case 'site':
			if (!$U->hasRights('system.site.select'))
			{
				$A->hasRightsErr();
			}
			$T->show('config.site');
			break;
		case 'logs':
			if (!$U->hasRights('system.logs.select'))
			{
				$A->hasRightsErr();
			}
			$T->show('logs');
			break;
		case 'cache':
			if (!$U->hasRights('system.cache'))
			{
				$A->hasRightsErr();
			}
			$T->show('cache');
			break;
		case 'modules':
			if (!$U->hasRights('system.modules.select'))
			{
				$A->hasRightsErr();
			}
			$T->show('modules');
			break;
		case 'files.upload':
			if (!$U->hasRights('system.file.upload'))
			{
				$A->hasRightsErr();
			}
			$T->show('files.upload');
			break;
		case 'files.url':
			if (!$U->hasRights('system.file.upload'))
			{
				$A->hasRightsErr();
			}
			$T->show('files.url');
			break;
		case 'files.server':
			if (!$U->hasRights('system.file.upload'))
			{
				$A->hasRightsErr();
			}
			$T->show('files.server');
			break;
		case 'file':
			if (!$U->hasRights('system.file.select'))
			{
				$A->hasRightsErr();
			}
			$T->show('file.select');
			break;
		case 'config.skins':
			if (!$U->hasRights('system.skins'))
			{
				$A->hasRightsErr();
			}
			$T->show('config.skins');
			break;
		case 'config.themes':
			if (!$U->hasRights('system.themes'))
			{
				$A->hasRightsErr();
			}
			$T->show('config.themes');
			break;
		case 'login.status';
			$T->show('login.status');
			break;
		default:
			$T->show('start');
			break;
	}
}
?>