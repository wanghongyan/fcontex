<?php
/***
 * 名称：内容模块类
 * Joe 2012.11
 * www.fcontex.com
*/

final class content 
{
	public $app;
	public $db;
	
	function __construct($database=NULL)
	{
		$this->app = FCApplication::sharedApplication();
		$this->db  = $database ? $database : FCApplication::sharedDataBase();
	}
	
	/* 调用内容 */
	public function getContents($find='*', $where='ct_type=0 and ct_check=1', $order='ct_fixed desc, ct_inserttime desc', $limit=0)
	{
		$sql = 'select '.$find.' from T[content] where '.$where.' order by '.$order;
		if ($limit) $sql .= ' limit '.$limit;
		return $this->db->fetchAll($this->db->query($sql));
	}
	
	/* 调用单篇内容 */
	public function getContent($find='*', $where='ct_type=0 and ct_check=1')
	{
		return $this->db->fetch($this->db->query('select '.$find.' from T[content] where '.$where));
	}
	
	/* 调用指定日志标签 */
	public function getContentTags($id)
	{
		return $this->db->fetchAll($this->db->query('select * from T[tags] where tg_cid like "%,'.$id.',%"'));
	}
	
	/* 调用标签 */
	public function getTags($where='', $limit='')
	{
		$where = $where == '' ? ' where 1 = 1' : ' where '.$where;
		$sql = 'select * from T[tags] '.$where.' order by tg_id desc '.($limit ? ' '.$limit : '');
		return $this->db->fetchAll($this->db->query($sql));
	}
	
	/* 调用分类 */
	public function getCategories($pid=-1)
	{
		$sql = 'select * from T[category]';
		if ($pid != -1)
		{
			$sql .= ' where cg_pid = '.$pid;
		}
		$sql .= ' order by cg_order asc, cg_id asc';
		return $this->db->fetchAll($this->db->query($sql));
	}
	
	/* 调用单条分类信息 */
	public function getCategory($id)
	{
		$query = $this->db->query('select * from T[category] where cg_id = '.$id.' order by cg_order asc');
		return $this->db->fetch($query);
	}
	
	
	/* 获取指定用户信息 */
	public function getUser($uid, $find='us_name, us_username, us_id')
	{
		$query = $this->db->query('select '.$find.' from T[user] where us_id = '.intval($uid));
		return $this->db->fetch($query);
	}
	
	/* 获取导航 */
	public function getNavigate()
	{
		$query = $this->db->query('select * from T[navigate] where nv_check = 1 order by nv_order asc');
		$data = array();
		while ($rst = $this->db->fetch($query))
		{
			$data[] = $rst;
		}
		return $data;
	}
}
?>