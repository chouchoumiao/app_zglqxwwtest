<?php
class commonController{
	protected function delInfoByID($model,$method){
		if(!isset($_POST["id"])){
			$arr['success'] = 0;
			$arr['msg'] = '无法取得id！';
			return $arr;
		}
		$id = intval(addslashes($_POST["id"]));

		if(M($model)->$method($id)){
			$arr['success'] = 1;
			$arr['msg'] = '删除成功！';
		}else{
			$arr['success'] = 0;
			$arr['msg'] = '删除失败';
		}
		return $arr;
	}
}