<?php

session_start();
require_once('commonModel.class.php');
class hongbaoModel extends commonModel{

    private $_table;
    private $weixinID;

    public function __construct(){
        $this->weixinID = $_SESSION['weixinID'];
        $this->_table = 'hongbaoInfo';
    }

	public function getHongbaoInfoMod(){

        $_whereCount = "WEIXIN_ID = $this->weixinID AND hongbao_Status = 1";
        $_whereInfo = "WEIXIN_ID = $this->weixinID AND hongbao_Status = 1 order by hongbao_id DESC";

        $arr = parent::getList($this->_table,$_whereCount,$_whereInfo);
        if($arr['class_list']){
            return $arr;
        }else{
            return array();
        }
	}
}