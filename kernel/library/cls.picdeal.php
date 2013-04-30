<?php
 /**
  * 处理图片类...
  * [缩略图， 水印]
  * @author Joe
  * @category  
  * @package picDeal
  */
class picdeal 
{
    
	//原图片地址
    public $picPath;
    
    //原图片名称, 自动获取
    public $picName;
    
    //新图片保存路径
    public $picNewPath = '';
    
    //新图片命名规则
    public $picNewName = '';
    
    //缩略图宽
    public $picWidth;
    
    //缩略图高
    public $picHeight;
    
    function __construct ()
    {
        
    }
	
    /**
     * 生成缩略图方法
     * 
     * @author Joe
     * @category Project
     * @copyright Copyright(c) 2012 
     * @newWidth  缩略图宽度
     * @newHeight 缩略图高度
     * @color     颜色值 - 十六进制
     */
    public function thumb($newWidth = 200, $newHeight = 200, $bgColor = 'FFFFFF')
    {
		$A = FCApplication::sharedApplication();
		
        $this->picName    = basename($this->picPath);
        $this->picNewName = $newWidth.'_'.$newHeight.$this->picName;
        $this->picNewPath = $this->picNewPath == '' ? str_replace($this->picName, '', $this->picPath).$this->picNewName : $this->picNewPath;
        
        //判断文件是否存在并是图片
        if (!file_exists($this->picPath) || !$A->isImage($A->fileSuffix($this->picPath)))
        {
            return FALSE;
        }
        //获取图像属性
        list($width, $height, $type, $attr) = getimagesize($this->picPath);
        //判断以宽或高为基准压缩
        if ($width > $height)
        {
            $this->picWidth = $width < $newWidth ? $width : $newWidth;
            $this->picHeight= $width < $newWidth ? $height : intval(($newWidth/$width)*$height);
            /* if ($this->picHeight > $newHeight){
                $this->picHeight = $newHeight;
                $this->picWidth  = intval(($newHeight/$height)*$width);
            }	 */
        }
        else 
        {
            $this->picWidth = $height > $newHeight ? intval(($newHeight / $height) * $width) : $width;
            $this->picHeight= $height > $newHeight ? $newHeight : $height;
        }
        //设置坐标
		$leftTop  = ($newHeight - $this->picHeight) / 2;
		$leftLeft = ($newWidth - $this->picWidth) / 2;

        //R G B 转换 十六进制转换十进制
        $R = hexdec(substr($bgColor, 0, 2));
        $G = hexdec(substr($bgColor, 2, 2));
        $B = hexdec(substr($bgColor, 4, 2));
        
        //创建新图像
        $pic = @imagecreatetruecolor($newWidth, $newHeight);
        //设置背景颜色
        $RBG = imagecolorallocate($pic, $R, $G, $B);
        imagefill($pic, 0, 0, $RBG);
        
        switch ($type)
        {
            case 1:
                $IMG = imagecreatefromgif($this->picPath);
                imagecopyresampled($pic, $IMG, $leftLeft, $leftTop, 0, 0, $this->picWidth, $this->picHeight, $width, $height);
                imagegif($pic, $this->picNewPath);
            break;
            
            case 2:
                $IMG = imagecreatefromjpeg($this->picPath);
                imagecopyresampled($pic, $IMG, $leftLeft, $leftTop, 0, 0, $this->picWidth, $this->picHeight, $width, $height);
                imagejpeg($pic, $this->picNewPath);
            break;
            
            case 3:
				imagecolortransparent($pic, $RBG); 
                $IMG = imagecreatefrompng($this->picPath);
                imagecopyresampled($pic, $IMG, $leftLeft, $leftTop, 0, 0, $this->picWidth, $this->picHeight, $width, $height);
                imagepng($pic, $this->picNewPath);
            break;
        }
       if (isset($IMG)) imagedestroy($IMG);
        return TRUE;
    }
}
?>