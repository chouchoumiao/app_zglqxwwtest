<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/app_zglqxwwtest/Common/include.php');

$action = addslashes($_GET["action"]);
$weixinID = addslashes($_GET['weixinID']);
if(!isset($weixinID)){
    echo "<script>alert('当前公众号信息取得失败');history.back();</Script>";
    exit;
}

//FOR question
//如果获取到操作标识，进行录入或者修改操作
if($action=="update"){
    
      
    //获取表单传入数据
	$old_question_id = addslashes($_POST["question_id"]);
	$page = addslashes($_POST["page"]);
	$question_subject = addslashes($_POST["question_subject"]);
	$questionClass = addslashes($_POST["questionClass"]);
	$question_img = addslashes($_POST["question_img"]);
	//图片上传保存操作
	$filename = 'up_img';
	$files = $_FILES[$filename];
	$fileSize = $files['size'];
	if ($fileSize > 0){
		$name= '/Answer/answer-'.time().'.jpg';
		$form_data =$files['tmp_name'];
		$s2 = new SaeStorage();
		$img = new SaeImage();
		$img_data = file_get_contents($form_data);//获取本地上传的图片数据
		$img->setData($img_data);
		
        $attr=$img->getImageAttr();
        if($attr[0]>1000)
        {
          $img->resize(1000);  
        }
        
		$img->improve();       //提高图片质量的函数
		$new_data = $img->exec(); // 执行处理并返回处理后的二进制数据
		$s2->write('weixincourse',$name,$new_data);//将public修改为自己的storage 名称
		$url= $s2->getUrl('weixincourse',$name);//将public修改为自己的storage 名称echo "文件名：".$name."<br/>"
	}else{
		if($question_img == ""){
			$url = "imgPath error";
		}else{
			$url = $question_img;
		}	
	}
	//传入数据过滤
	
	$question_img=$url;
	$question_optionsA = addslashes($_POST["question_optionsA"]);
	$question_optionsB = addslashes($_POST["question_optionsB"]);
	$question_optionsC = addslashes($_POST["question_optionsC"]);
	$question_optionsD = addslashes($_POST["question_optionsD"]);
	$question_true = addslashes($_POST["question_true"]);
    
    if(($question_optionsC == "") && ($question_true == "C")){
        $warning = "提交失败，原因：C选项没有设置，所以不能选择C为正确答案";
        echoWarning($warning);
        exit;
    }
    if(($question_optionsD == "") && ($question_true == "D")){
        $warning = "提交失败，原因：D选项没有设置，所以不能选择D为正确答案";
        echoWarning($warning);
        exit;
    }
    
    //默认参数
    $nowtime=date("Y/m/d H:i:s",time());
    //如果是修改
    if($old_question_id){
        $sql = "update question_tb
                set question_subject='$question_subject',
                question_img ='$question_img' ,
                question_optionsA='$question_optionsA',
                question_optionsB='$question_optionsB',
                question_optionsB='$question_optionsB',
                question_optionsC='$question_optionsC',
                question_optionsD='$question_optionsD',
                question_true='$question_true',
                question_class_title='$questionClass',
                editTime = '$nowtime'
                where question_id=$old_question_id
                and WEIXIN_ID = $weixinID";
    }else{
        //新增
   		$sql = "insert into question_tb
                (WEIXIN_ID,question_subject,question_img,question_optionsA,
                 question_optionsB,question_optionsC,question_optionsD,
			     question_true,question_class_title,createtime,status)
			    values ($weixinID,'$question_subject','$question_img',
			            '$question_optionsA','$question_optionsB',
                        '$question_optionsC','$question_optionsD',
                        '$question_true','$questionClass','$nowtime',1)";
    }
    $errorno = SaeRunSql($sql);
    if($errorNo != 0){
        $msg = "设置失败！";
    }else{
        $msg = "设置成功！";
    }
    echoInfo($msg);
    exit;
}


if($action == "mainInfo"){
    
    $questionShowCount = addslashes($_POST["questionShowCount"]);
    $questionID = addslashes($_POST["questionID"]);
    $questionClass = addslashes($_POST["questionClass"]);
    $arr['classinfo'] = "";
    $arr['Despinfo'] = "";     
    
    if($questionID){
        $sql = "select * from question_master
                where MASTER_ID = $questionID
                and WEIXIN_ID = $weixinID";
        $masterInfo = getLineBySql($sql);
        if($masterInfo){
            $question_win_integral = $masterInfo['QUESTION_WIN_INTEGRAL'];
            $question_win_comment = $masterInfo['QUESTION_WIN_COMMENT'];
            
            //修正bug 取出的数据不是数据或者为空的情况下,返回值为空 20150925
            if($question_win_integral){
                $classinfo = json_decode($question_win_integral);
            }else{
                $classinfo = "";
            }
            if($question_win_comment){
                $Despinfo = json_decode($question_win_comment);
            }else{
                $Despinfo = "";
            }
            $arr['classinfo'] = $classinfo;
            $arr['Despinfo'] = $Despinfo;            
        }
    }

    //取得当前总题目的数目
    $sql= "select COUNT(*) from question_tb
           where status = 1
           AND WEIXIN_ID = $weixinID
           AND question_class_title = '$questionClass'";
    
    $qusetionCount = getVarBySql($sql);

    if($questionShowCount>$qusetionCount){
        $arr['success'] = "NoEnoughDate";
        $arr['count'] = $qusetionCount;
        $arr['msg'] = "请注意，题库中有效题目只有".$qusetionCount."条，设置时最大只能设为该值！";
        
    }else{
        $arr['success'] = "OK";
    }
    echo json_encode($arr);	
    exit;
}

if($action == "detailInfo"){

    $NowDate = date("Y-m-d",time());
    $NowDateTime  = date("Y-m-d H:i:s",time());
    
    $questionShowCount = addslashes($_POST["questionShowCount"]);
    $questionWinCount = addslashes($_POST["questionWinCount"]);
    $questionTitle = addslashes($_POST["questionTitle"]);
    $questionSatus = addslashes($_POST["questionSatus"]);
    $question_beginDate = addslashes($_POST["question_beginDate"]);
    $question_endDate = addslashes($_POST["question_endDate"]);
    $questionClass = addslashes($_POST["questionClass"]);
    $maxTimes = addslashes($_POST["maxTimes"]);
    $questionID = addslashes($_POST["questionID"]);
    
    //addslashes函数不能再数组中使用 20150925
    $count = $_POST["count"];
    $integral = $_POST["integral"];
    $comment = $_POST["comment"];
    
    $newCount = json_encode($count);
    $newIntegral = json_encode($integral);
    $newComment = getPreg_replace($comment);
    
    if(!$questionID){
        //新增
        //当前新追加活动的状态为开启的情况下，也就是有效，这时需要判断是否原来记录中是否存在有效的记录。（原则有效活动只能是一条）
        if(($questionSatus == 1) || ($questionSatus == "1") ){
            //取得刚刚追加的答题设置内容的ID
            $sql = "select max(MASTER_ID) from question_master
                    where QUESTION_BEGIN_DATE <='$NowDate'
                    and '$NowDate' <= QUESTION_END_DATE
                    and WEIXIN_ID = $weixinID
                    and QUESTION_SATUS = 1";
            $getOldData = getVarBySql($sql);
            if($getOldData){
                $arr['success'] = "exist";
                $arr['msg'] = "当前存在有效的活动，如要再追加新的活动请删除原活动";
                
                echo json_encode($arr);	
                exit;
            }
        }
        
        $sql = "insert into question_master
                        (WEIXIN_ID,QUESTION_TITLE,QUESTION_SHOW_COUNT,
                         QUESTION_BEGIN_DATE,QUESTION_END_DATE,QUESTION_CLASS,
                         QUESTION_MAXTIMES,QUESTION_WIN_COUNT,QUESTION_WIN_INTEGRAL,
                         QUESTION_WIN_COMMENT,QUESTION_INSERT_DATETIME,QUESTION_SATUS)
                 values ('$weixinID','$questionTitle',$questionShowCount,
                         '$question_beginDate','$question_endDate','$questionClass',
                         $maxTimes,'$newCount','$newIntegral','$newComment',
                         '$NowDateTime',$questionSatus)";
    }else{
        
        $sql = "update question_master
                set QUESTION_TITLE='$questionTitle',
                    QUESTION_SHOW_COUNT=$questionShowCount,
                    QUESTION_BEGIN_DATE='$question_beginDate',
                    QUESTION_END_DATE='$question_endDate',
                    QUESTION_CLASS='$questionClass',
                    QUESTION_MAXTIMES='$maxTimes',
                    QUESTION_WIN_COUNT='$newCount',
                    QUESTION_WIN_INTEGRAL='$newIntegral',
                    QUESTION_WIN_COMMENT='$newComment',
                    QUESTION_EDIT_TIME='$NowDateTime',
                    QUESTION_SATUS = $questionSatus
                where MASTER_ID = $questionID
                AND WEIXIN_ID = $weixinID";
    }
    
    $errono = SaeRunSql($sql);
    if($errono != 0){
        $arr['success'] = "InsertNG";
        $arr['msg'] = "设置失败！".$sql;
        
        echo json_encode($arr);	
        exit;
    }
    $arr['success'] = "OK";
    $arr['msg'] = "设置成功！";
    
}

echo json_encode($arr);