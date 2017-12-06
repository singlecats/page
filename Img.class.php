<?php
class Img{
	public $width=100;
	public $height=30;
	public $codeLength=4;
	public $fontSize=5;
	public $line=false;
	public $pixel=false;
	protected $handle;
	protected $code;
	public function __construct($option){
		foreach ($option as $k => $v) {
			$this->$k=$v;
		}
	}
	public function showCode(){
		$this->createCode();
		$this->createImg();
	}
	protected function createImg(){
		$this->createBaseImg();	
		$handle=$this->handle;
		$fontSize=$this->fontSize;				
		//验证码
		$code=$this->code;
		$width=($this->width/strlen($code));
		$offset=($width-imagefontwidth($fontSize))/2;
		for($i=0;$i<strlen($code);$i++){
			$fontColor=imagecolorallocate($handle, rand(0,120), rand(0,120), rand(0,120));
			imagechar($handle, $fontSize, $i*$width+$offset,rand(2,$this->height-imagefontheight($fontSize)), $code{$i}, $fontColor);
		}
		if($this->pixel){
			//设置干扰元素 点
			for($i=0;$i<100;$i++){
				//首先我们设置点的颜色
				$pointcolor = imagecolorallocate($handle,rand(50,200),rand(50,200),rand(50,200));
				 //设置点放在图像的什么位置上
				imagesetpixel($handle,rand(1,$this->width),rand(1,$this->height),$pointcolor);
			 }
		}
		//杂线
		if($this->line){
			for($i=0;$i<4;$i++){
				$otherColor=imagecolorallocate($handle,rand(80,220),rand(80,220),rand(80,220));
				imageline($handle, rand(0,$this->width), rand(0,$this->height), rand(0,$this->width), rand(0,$this->height), $otherColor);
			}
		}
		// 通知浏览器输出的是图像（png类型）
	    header('Content-Type: image/png');
	    // 输出到浏览器
	    imagepng($handle);
	    // 释放图像资源
	    imagedestroy($handle);
	}
	protected function createBaseImg(){
		$width=$this->width;
		$height=$this->height;
		//创建画布
		$this->handle=imagecreatetruecolor($width, $height);
		//分配颜色
		$backgroundColor=imagecolorallocate($this->handle,252,248,227);
		
		// 对画布背景填充颜色
		imagefill($this->handle, 0, 0, $backgroundColor);
	}
	protected function createCode(){
		$str='123456789abcdefghijklmnpqrstABCDEFGHIJKLMNPQRST';
		$this->code=substr(str_shuffle($str), 0,$this->codeLength+1);
		$_SESSION['code']=md5($this->code);
	}
}
$option=['width'=>100,'height'=>30,'color'=>'red','line'=>true,'pixel'=>true];
$img=new Img($option);
$img->showCode();
// $img->createCode();