<?php

/**
 * Created by wujiayu.
 * User: Administrator
 * Date: 2016/4/26
 * Time: 16:35
 */
class commonModel
{

    /**
     * ȡ�÷�ҳ��Ϣ
     * private
     * @return array
     */
    protected function getMulti(){
        if(!isset($_GET["page"])){
            $page = 1;
        }else{
            $page=intval(addslashes($_GET["page"]));
        }
        if(isset($_GET['showCount'])){
            $showCount = intval(addslashes($_GET['showCount']));
        }else{
            $showCount = 5;
        }
        return array(
            'page' => $page,
            'showCount' =>$showCount,
            'from_record' =>($page - 1) * $showCount  //���㿪ʼ�ļ�¼���
        );
    }

}