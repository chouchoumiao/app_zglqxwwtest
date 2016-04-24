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
        url:"admin.php?controller=admin&method=changeWeixinID"//改为你的动态页
        ,type:"POST"
        ,data:{"weixinID":weixinID}//调用json.js类库将json对象转换为对应的JSON结构字符串
        ,dataType: "json"
        ,success:function(data){
            alert(data.msg);
            self.location='admin.php?controller=admin&method=index';
        }
        ,error:function(xhr){alert('PHP页面有错误！'+xhr.responseText);}
    });
};