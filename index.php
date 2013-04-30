<?php
/***
 * 名称：主页
 * Joe 2012.03.06
 * www.fcontex.com
*/
include 'kernel/startup.php';

/*$res = $D->query('select ct_id, ct_content from fc_content where ct_content like "%fcattached%"');
while ($rst = $D->fetch($res))
{
	$D->update(
		'fc_content',
		array('ct_content' => $A->strSQL(preg_replace('/(href="\/\?system\/file\/.+?" rel="nofollow">.+?)<\/a>/i', '$1<span></span></a>', $rst['ct_content']))),
		array('ct_id' => $rst['ct_id']));
}
var_dump($D->count($res));exit();*/

if ($A->site['site_domainlock'] && $_SERVER['HTTP_HOST']!=$A->site['site_domain'])
{
	header('HTTP/1.1 301 Moved Permanently');
	header("location: http://".$A->site['site_domain'].'/'.($R->query ? $R->getQueryPrefix().$R->query : ''));
}
else $R->parse();
?>