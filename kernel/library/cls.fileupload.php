<?php
 /**
  * 上传类...
  * 
  * @author Joe
  * @category  
  * @package fileUpload
  */
class fileupload 
{
    //文件域input名称
    public $fileInput;
    
    //原始文件名
    public $fileName;
    
    //原始文件路径
    public $filePath;
    
	//新的文件目录
	public $fileNewDir;
	
    //新的文件路径
    public $fileNewPath;
    
    //新的文件名称
    public $fileNewName;
    
    //原始文件大小
    public $fileSize;
    
    //原始文件后辍
    public $fileSuffix;
    
    //上传文件大小限制 M
    public $fileLimitSzie;
    
    //上传文件类型限制
    public $fileLimitSuffix;
    
    //上传的文件是否为图片
    public $isImage;
    
    //初始化变量
    function __construct ()
    {
		$A = FCApplication::sharedApplication();
		
        //文件域input名称
        $this->fileInput   = 'fileInput';
        //原始文件名
        $this->fileName    = isset($_FILES[$this->fileInput]) ? $_FILES[$this->fileInput]['name'] : '';
        //原始文件路径
        $this->filePath    = isset($_FILES[$this->fileInput]) ? $_FILES[$this->fileInput]['tmp_name'] : '';
        //新的文件路径
        $this->fileNewPath = $A->system['uploadPath'];
		//新文件目录
		$this->fileNewDir = 'content';
        //新的文件名称
        $this->fileNewName = rand(100, 99999).time();
        //原始文件大小
        $this->fileSize    = isset($_FILES[$this->fileInput]) ? $_FILES[$this->fileInput]['size'] : 0;
        //原始文件后辍
        $this->fileSuffix  = isset($_FILES[$this->fileInput]) ? $A->fileSuffix($_FILES[$this->fileInput]['name']) : '';
        //上传文件大小限制 M
        $this->fileLimitSzie = $A->system['uploadSize'];
        //上传文件类型限制
        $this->fileLimitSuffix = $A->system['uploadSuffix']['image'];
        //上传文件是否为图片
        $this->isImage = $A->isImage($this->fileSuffix) ? 1 : 0;
    }
    
    /**
     * 处理上传
     * 
     * @access upload
     * @return array()
     */
    public function upload()
    {
		$A = FCApplication::sharedApplication();
		
        //返回值
        $message = array
		(
            'error'         => '',
            'isImage'       => $this->isImage,
			'fileDir'		=> '',
            'fileNewName'   => '', 
            'fileName'      => $this->fileName,
            'fileWidth'     => '',
            'fileHeight'    => '',
            'fileSize'      => $this->fileSize, //字节
            'fileSuffix'    => $this->fileSuffix,
			'fileType'		=> isset($_FILES[$this->fileInput]['type']) ? $_FILES[$this->fileInput]['type'] : ''
        );
        if (!isset($_FILES[$this->fileInput]))
        {
            $message['error'] = '没有选择文件';
        }
        elseif (!file_exists($this->fileNewPath))
        {
            $message['error'] = '保存文件路径错误，PATH：'.$this->fileNewPath;
        }
        elseif (!in_array($this->fileSuffix, explode(',', $this->fileLimitSuffix)))
        {
            $message['error'] = '文件类型不正确';
        }
        elseif (($this->fileSize/1024/1024) > $this->fileLimitSzie)
        {
            $message['error'] = '上传文件大小超出限制，最大支持：'.$this->fileLimitSzie.'M';
        }
        elseif ($_FILES[$this->fileInput]['error'] != 0)
        {
            $message['error'] = '上传文件出错，代号：'.$_FILES[$this->fileInput]['error'];
        }
        else 
        {
			//新建目录
			$this->fileNewPath .= $this->fileNewDir.'/';
			if (!file_exists($this->fileNewPath))
			{
				@mkdir($this->fileNewPath, 0777);
			}
			$dir1 = date('Ym');
			$this->fileNewPath .= $dir1.'/';
			if (!file_exists($this->fileNewPath))
			{
				@mkdir($this->fileNewPath, 0777);
			}
			$dir2 = date('d');
			$this->fileNewPath .= $dir2.'/';
			if (!file_exists($this->fileNewPath))
			{
				@mkdir($this->fileNewPath, 0777);
			}
			$message['fileDir'] = $this->fileNewDir.'/'.$dir1.'/'.$dir2.'/';
            //组成完整文件路径
            $this->fileNewPath .= $this->fileNewName.'.'.$this->fileSuffix;
            if (@move_uploaded_file($this->filePath,$this->fileNewPath))
			{
				$message['fileNewName'] = $this->fileNewName.'.'.$this->fileSuffix;
			}
			else
			{
				$message['error'] = '上传文件失败，检查是否有权限。';
			}
            //如果是图片文件
            if ($message['isImage'])
            {
                list($width, $height, $type, $attr) = getimagesize($this->fileNewPath);
                $message['fileWidth']  = $width;
                $message['fileHeight'] = $height;
				$message['fileIcon'] = $A->fileIcon($this->fileSuffix);
            }
        }
        return $message;
    }
}
?>