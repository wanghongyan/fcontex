<?php 
/**
 * iparea.php 根据ip地址获取ip所在地区
 */
class iparea
{
	public $fp = NULL;		//定义文件指针
	public $func;			//处理的方法
	private $offset;		
	private $index;
	
	/**
 	 * 构造函数
 	 * 
 	 */
	public function __construct()
	{
		if(file_exists(PATH_STORE.'ipdata/mini.Dat'))
		{
			$this->func = 'dataMini';
			$this->fp = fopen(PATH_STORE.'ipdata/mini.Dat', 'rb');
			$this->offset = unpack('Nlen', fread($this->fp, 4));
			$this->index  = fread($this->fp, $this->offset['len'] - 4);
		}
	}
	
	/**
 	 * 使用mini.Dat ip数据包获取地区
 	 * @param  string $ip IP地址
 	 * @ return string/null
 	 */
	public function dataMini($ip)
	{
		$ipdot = explode('.', $ip);
		$ipdot[0] = (int)$ipdot[0];
		$ipdot[1] = isset($ipdot[1]) ? (int)$ipdot[1] : 0;
		$ip    = pack('N', ip2long($ip));
		$length = $this->offset['len'] - 1028;
		$start  = unpack('Vlen', $this->index[$ipdot[0] * 4] . $this->index[$ipdot[0] * 4 + 1] . $this->index[$ipdot[0] * 4 + 2] . $this->index[$ipdot[0] * 4 + 3]);
		for($start = $start['len'] * 8 + 1024; $start < $length; $start += 8)
		{
			if($this->index{$start} . $this->index{$start + 1} . $this->index{$start + 2} . $this->index{$start + 3} >= $ip)
			{
				$this->index_offset = unpack('Vlen', $this->index{$start + 4} . $this->index{$start + 5} . $this->index{$start + 6} . "\x0");
				$this->index_length = unpack('Clen', $this->index{$start + 7});
				break;
			}
		}
		fseek($this->fp, $this->offset['len'] + $this->index_offset['len'] - 1024);
		if($this->index_length['len'])
		{
			return CHARSET == 'utf-8' ? iconv('gbk', 'utf-8', str_replace('- ', '', fread($this->fp, $this->index_length['len']))) : str_replace('- ', '', fread($this->fp, $this->index_length['len']));
		}
		else
		{
			return 'Unknown';
		}
	}

	private function close()
	{
		@fclose($this->fp);
	}
}
?>