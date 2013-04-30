<?php
/***
 * 名称：内容模块动作处理程序
 * Joe 2012.07
 * www.fcontex.com
*/

include '../../../kernel/startup.php';

$C = $A->loadLibrary('content');

switch ($mode = $A->strGet('mode'))
{
	case 'count':
		$id = intval($A->strGet('id'));
		$atts = $A->strGet('atts');
		$json = '{';
		if ($id)
		{
			$res = $D->query('select ct_hits, ct_talks from T[content] where ct_id = '.$id);
			$rst = $D->fetch($res);
			$D->update('T[content]', array('ct_hits' => $rst['ct_hits']+1), array('ct_id' => $id));
			$hits = $rst['ct_hits']+1;
			$talks = $rst['ct_talks'];
			$json .= 'hits:'.($rst['ct_hits']+1).',talks:'.$rst['ct_talks'];
		}
		if (preg_match('/^[0-9]+?(,[0-9]+?)*$/', $atts))
		{
			$res = $D->query('select at_hits from T[attached] where at_id in('.$atts.')');
			$str = ',atts:['; $dot = '';
			while ($rst = $D->fetch($res))
			{
				$str .= $dot.$rst['at_hits'];
				$dot = ',';
			}
			$json .= $str.']';
		}
		echo $json.'}';
		break;
	default:
		echo 'ERR|无效请求。';
		break;
}
?>