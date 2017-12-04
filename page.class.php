<?php

class page{
	public $totalPage;
	public $perPage;
	public $url;
	public $showPage=5;
	public $nowPage;
	public $html;
	public function __construct($totalPage,$perPage,$showPage='',$url=''){
		$this->totalPage=$totalPage;
		$this->perPage=$perPage;
		if($showPage){
			$this->showPage=$showPage;
		}
		$this->url=$url?$url:$_SERVER['PHP_SELF'].'?';
	}
	public function showPage(){		
		$urlArr=$_SERVER['QUERY_STRING'];
		parse_str($urlArr,$queryArr);
		// print_r($queryArr);
		$this->nowPage=isset($queryArr['page'])?$queryArr['page']:1;
		unset($queryArr['page']);
		$this->creatPage($queryArr);
		return $this->html;
	}
	public function creatPage($queryArr){
		$totalPage=$this->totalPage;
		$perPage=$this->perPage;
		$nowPage=$this->nowPage;
		$showPage=$this->showPage;
		$url=$this->url;
		if(count($queryArr)){
			$url.=http_build_query($queryArr);
		}
		$start=($nowPage>=ceil($showPage/2))?$nowPage-ceil($showPage/2)+1:1;
		$end=($nowPage>ceil($showPage/2))?$nowPage+$showPage-ceil($showPage/2)+1:$showPage+1;
		$end=$end>$totalPage?$totalPage+1:$end;
		$start=($end-$start+1)<=$showPage?$totalPage-$showPage+1:$start;
		$html='';
		if($nowPage>1){
			$html.='<a style="padding:5px;text-decoration: none;margin:5px 5px;border:1px solid #e1e2e3;" href="'.$url.'&page='.($nowPage-1).'">上一页</a>';
		}
		for($j=$start;$j<$end;$j++){
			if($nowPage==$j){
				$html.='<span style="background-color:red;padding:5px;margin:5px 5px;border:1px solid #e1e2e3;">'.$j.'</span>';
				continue;
			}
			$html.='<a style="padding:5px;text-decoration: none;margin:5px 5px;border:1px solid #e1e2e3;" href='.$url.'&page='.$j.'>'.$j.'</a>';
		}
		if($nowPage<$totalPage){
			$html.='<a style="padding:5px;text-decoration: none;margin:5px 5px;border:1px solid #e1e2e3;" href="'.$url.'&page='.($nowPage+1).'">下一页</a>';
		}
		$this->html=$html;
	}
}
$page=new page(20,2,12);
$html=$page->showPage();
echo $html;