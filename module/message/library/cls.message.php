<?php
/***
 * 名称：内容模块类
 * Joe 2012.11
 * www.fcontex.com
*/

final class message 
{
	public $app;
	public $db;
	
	function __construct($database=NULL)
	{
		$this->app = FCApplication::sharedApplication();
		$this->db  = $database ? $database : FCApplication::sharedDataBase();
	}
	
	/* 调用二级评论 */
	public function getComments($topid)
	{
		return $this->db->fetchAll($this->db->query('select * from T[comment] where cm_topid = '.$topid.' order by cm_id asc'));
	}
	
	/* 调用二级留言 */
	public function getGbook($topid)
	{
		return $this->db->fetchAll($this->db->query('select * from T[gbook] where gb_topid = '.$topid.' order by gb_id asc'));
	}
}
?>