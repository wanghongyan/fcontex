<?php
/***
 * 名称：数据分页类
 * Joe, 2012.03
 * http://www.fcontex.com/
*/

final class pageturnner
{
	private $db = NULL;
	
	public $size = 10;  //页大小
	public $page = 1;   //当前页
	
	public $radius = 2;  //翻页条半径
	public $linker = ''; //翻页链接
	public $turnner = ''; //翻页条
	
	public $prev = '';  //上一页链接
	public $next = '';  //下一页链接
	
	private $total = 0;  //总记录数
	private $pages = 0;  //总页数
	
	private $pageStart = 0;  //起始页
	private $pageStop  = 0;  //结束页
	
	public $style = 'Default';
	
	function __construct($database=NULL)
	{
		$this->db = $database ? $database : FCApplication::sharedDataBase();
	}
	
	//分页数据处理
	public function parse($index, $field, $table, $where='', $order='')
	{
		//翻页链接
		$strQuery = preg_replace(array('/page=[^&]+?&/i','/&+$/i'), '', $_SERVER['QUERY_STRING'].'&');
		if (!$this->linker)
		{
			$this->linker = $strQuery ? '?'.$strQuery.'&page={p}' : '?page={p}';
			if (isset($_GET['page'])) $this->page = intval($_GET['page']);
		}
		/*else
		{
			$pos = strpos($this->linker, '{p}');
			$this->page = substr('?'.$strQuery, $pos);
			$len = strlen($this->linker) - ($pos+3);
			$this->page = substr($this->page, 0, strlen($this->page)-$len);
		}*/
		
		//总记录数
		$sql = 'select count('. $index .') as total from '.$table;
		if ($where) $sql .= ' where '. $where;
		$rst = $this->db->fetch($this->db->query($sql));
		$this->total = $rst['total'];
		
		//总页数
		$this->pages = ceil($this->total/$this->size);
		
		//当前分页
		if ($this->page === 'last') $this->page = $this->pages;
		if (!is_numeric($this->page)) $this->page = 1;
		if ($this->page > $this->pages) $this->page = $this->pages;
		if ($this->page < 1) $this->page = 1;
		$this->page = intval($this->page);
		
		//分页数据
		$sql = 'select '. $field .' from '.$table;
		if ($where) $sql .= ' where '. $where;
		if ($order) $sql .= ' order by '. $order;
		$sql .= ' limit '. ($this->page-1) * $this->size . ', '. $this->size;
		$result = $this->db->query($sql);
		
		//以半径算出始终显示页码数
		$pageNum = $this->radius * 2 + 1;
		
		//开始页码
		$this->pageStart = $this->page - $this->radius;
		
		//结束页码
		$this->pageStop = ($this->page + $this->radius) < $pageNum ? $pageNum : $this->page + $this->radius;
		$this->pageStop = $this->pageStop > $this->pages ? $this->pages : $this->pageStop;
		
		//补齐页码
		$this->pageStart = ($this->pageStop - $this->page) < $this->radius ? $this->pageStart - ($this->radius - ($this->pageStop - $this->page)) : $this->pageStart;
		$this->pageStart = $this->pageStart < 1 ? 1 : $this->pageStart;
		/*if ($this->pageStart <= 0)
		{
			$this->pageStop += abs($this->pageStart);
			$this->pageStart = 1;
		}
		if ($this->pageStop > $this->pages)
		{
			$this->pageStop = $this->pages;
		}*/
		
		$styleCurrent = 'style' . $this->style;
		$this->$styleCurrent();
		
		return $result;
	}
	
	//默认翻页条模板
	private function styleDefault()
	{
		//翻页条开始
		$this->turnner = '<div class="turnner" id="turnner">';
				
		//首页
		if ($this->pageStart > 1)
		{
			$this->turnner .= '<a title="首页" href="'. str_replace("{p}","1",$this->linker) .'">1..</a>';
		}
		
		//上一页
		if ($this->page == 1)
		{
			$this->prev = '';
			$this->turnner .= '<a title="上一页" href="javascript:void(0)">«</a>';
		}
		else
		{
			$this->prev = str_replace("{p}",($this->page-1),$this->linker);
			$this->turnner .= '<a title="上一页" href="'. $this->prev .'">«</a>';
		}
		
		//第_页
		for ($p=$this->pageStart; $p<=$this->pageStop; $p++)
		{
			if ($p == $this->page)
			{
				$this->turnner .= '<a title="第'. $p .'页" class="c">'. $p .'</a>';
			}
			else
			{
				$this->turnner .= '<a title="第'. $p .'页" href="'. str_replace('{p}',$p,$this->linker) .'">'. $p .'</a>';
			}
		}
				
		//下一页
		if ($this->page == $this->pages)
		{
			$this->next = '';
			$this->turnner .= '<a title="下一页" href="javascript:void(0)">»</a>';
		}
		else
		{
			$this->next = str_replace("{p}",($this->page+1),$this->linker);
			$this->turnner .= '<a title="下一页" href="'. $this->next .'">»</a>';
		}
		
		//末页
		if ($this->pageStop < $this->pages)
		{
			$this->turnner .= '<a title="末页" href="'. str_replace('{p}',$this->pages,$this->linker) .'">..'. $this->pages .'</a>';
		}
		//统计
		$this->turnner .= '<span><label class="total">'.$this->size.'条/页</label> 共<label class="total">'. $this->total .'条</label></span>';
		
		//翻页条结束
		$this->turnner .= '</div>';
	}

	//简化版翻页条
	private function styleSimple()
	{
		//翻页条开始
		$this->turnner = '<div class="turnner" id="turnner">';
		
		//上一页
		if ($this->page == 1)
		{
			$this->prev = '';
			$this->turnner .= '<a title="上一页" href="javascript:void(0)">«</a>';
		}
		else
		{
			$this->prev = str_replace("{p}",($this->page-1),$this->linker);
			$this->turnner .= '<a title="上一页" href="'. $this->prev .'">«</a>';
		}

		//首页
		if ($this->pageStart > 1)
		{
			$this->turnner .= '<a title="首页" href="'. str_replace("{p}","1",$this->linker) .'">1..</a>';
		}
		
		//第_页
		for ($p=$this->pageStart; $p<=$this->pageStop; $p++)
		{
			if ($p == $this->page)
			{
				$this->turnner .= '<a title="第'. $p .'页" class="c">'. $p .'</a>';
			}
			else
			{
				$this->turnner .= '<a title="第'. $p .'页" href="'. str_replace('{p}',$p,$this->linker) .'">'. $p .'</a>';
			}
		}		
		
		//下一页
		if ($this->page == $this->pages)
		{
			$this->next = '';
			$this->turnner .= '<a title="下一页" href="javascript:void(0)">»</a>';
		}
		else
		{
			$this->next = str_replace("{p}",($this->page+1),$this->linker);
			$this->turnner .= '<a title="下一页" href="'. $this->next .'">»</a>';
		}

		//末页
		if ($this->pageStop < $this->pages)
		{
			$this->turnner .= '<a title="末页" href="'. str_replace('{p}',$this->pages,$this->linker) .'">..'. $this->pages .'</a>';
		}

		//翻页条结束
		$this->turnner .= '</div>';
	}
}
?>