/**
 * Created by Administrator on 14-11-16.
 */
$(document).ready(function(){

    $('#menu').tendina({
        openCallback: function(clickedEl) {
            clickedEl.addClass('opened');
        },
        closeCallback: function(clickedEl) {
            clickedEl.addClass('closed');
        }
    });

});
$(function(){

    $("#ad_setting").click(function(){
        $("#ad_setting_ul").show();
    });
    $("#ad_setting_ul").mouseleave(function(){
        $(this).hide();
    });
    $("#ad_setting_ul li").mouseenter(function(){
        $(this).find("a").attr("class","ad_setting_ul_li_a");
    });
    $("#ad_setting_ul li").mouseleave(function(){
        $(this).find("a").attr("class","");
    });
});

function getWeiID(){
    var weixinID =  $("#weiIDSelect").val();

    $.ajax({
        url:"admin.php?controller=admin&method=changeWeixinID"//��Ϊ��Ķ�̬ҳ
        ,type:"POST"
        ,data:{"weixinID":weixinID}//����json.js��⽫json����ת��Ϊ��Ӧ��JSON�ṹ�ַ���
        ,dataType: "json"
        ,success:function(data){
            alert(data.msg);
            self.location='admin.php?controller=admin&method=index';
        }
        ,error:function(xhr){alert('PHPҳ���д���'+xhr.responseText);}
    });
};