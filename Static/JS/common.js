/**
 *分页时候取得分页的相关信息，并且调用分页功能进行分页
 */
$(document).ready(function() {
    var pagecount = parseInt($('#pagecount').val());
    var pagesize = parseInt($('#pagesize').val());
    var currentpage =  parseInt($('#currentpage').val());
    var showCount =  parseInt($('#showCount').val());

    var controller =  $('#controller').val();
    var method =  $('#method').val();

    multi(pagecount,pagesize,currentpage,showCount,controller,method);

    $("#showPage ").val(showCount); //用于显示select的选中事件
});

/**
 * 分页共同函数
 * @param count
 * @param page_num
 * @param page
 * @param showCount
 * @param controller
 * @param method
 */
function multi(count,page_num,page,showCount,controller,method){
//    alert(count+':'+page_num+':'+page+':'+showCount+':'+controller+':'+method)
    if($("#pagination")){
        var pagecount = count;
        var pagesize = page_num;
        var currentpage = page
        var counts,pagehtml="";
        var showCounts = 5;
        var count = showCount;
        if(pagecount%pagesize==0){
            counts = parseInt(pagecount/pagesize);
        }else{
            counts = parseInt(pagecount/pagesize)+1;
        }
        //只有一页内容
        if(pagecount<=pagesize){
            pagehtml="";
        }
        //大于一页内容
        if(pagecount>pagesize){

            //追加 显示条数select
            pagehtml+= '<li>&nbsp<a>   显示   <select id = "showPage">';

            pagehtml+= '<option value="5">5</option>';
            pagehtml+= '<option value="10">10</option>';
            pagehtml+= '<option value="15">15</option>';
            pagehtml+= '<option value="20">20</option>';

            pagehtml+= '</select>    条</a></li>';

            if(currentpage>1){
                pagehtml+= '<li><a href="admin.php?controller='+controller+'&method='+method+'&page='+(currentpage-1)+'&showCount='+count+'">   上一页</a></li>';
            }
            if((currentpage - showCounts) > 0){
                pagehtml+= '<li><a href="admin.php?controller='+controller+'&method='+method+'&page='+(1)+'&showCount='+count+'">...'+(1)+'</a></li>';
            }
            for(var i=0;i<counts;i++){
                if(i>=(currentpage - showCounts) && i<(currentpage + showCounts)){
                    if(i==currentpage-1){
                        pagehtml+= '<li class="active"><a href="admin.php?controller='+controller+'&method='+method+'&page='+(i+1)+'&showCount='+count+'">'+(i+1)+'</a></li>';
                    }else{
                        pagehtml+= '<li><a href="admin.php?controller='+controller+'&method='+method+'&page='+(i+1)+'&showCount='+count+'">'+(i+1)+'</a></li>';
                    }
                }
            }
            if((currentpage + showCounts)<counts){
                pagehtml+= '<li><a href="admin.php?controller='+controller+'&method='+method+'&page='+(counts)+'&showCount='+count+'">...'+(counts)+'</a></li>';
                //pagehtml+= '<li><a>...</a></li>';
            }
            if(currentpage<counts){
                pagehtml+= '<li><a href="admin.php?controller='+controller+'&method='+method+'&page='+(currentpage+1)+'&showCount='+count+'">下一页</a></li>';
            }

            pagehtml+= '<li><a>共'+counts+'页</a></li>';

            pagehtml+= '<li>&nbsp<a> 跳转到第<select id = "turnToPage">';

            for(var i=0;i<counts;i++){
                pagehtml+= '<option>'+(i+1)+'</option>';
            }
            pagehtml+= '</select>页</a></li>';
            pagehtml+= '<li>&nbsp<button type="button" class="btn btn-primary btn-sm" id = "turnToPageBtn">确定</button></li>';
        }
        $("#pagination").html(pagehtml);
    };
    $('#turnToPageBtn').click(function(){
        var thisPage  =  $("#turnToPage").val();
        var thisCount  =  $("#showPage").val();

        if(thisCount > count){
            thisCount = count
        }

        window.location.href='admin.php?controller='+controller+'&method='+method+'&page='+thisPage+'&showCount='+thisCount;
    })
};

/**
 * 根据id删除对应的数据
 * @param id
 * @param controller
 * @param method
 * @returns {boolean}
 */
function isDelete(id,controller,method){
    if(confirm("确认删除吗？")){
        $.ajax({
            url:'./admin.php?controller='+controller+'&method='+method
            ,type:"POST"
            ,data:{
                "id":id
            }
            ,dataType: "json"
            ,success:function(json){
                alert(json.msg);
                location.reload();
            }
            ,error:function(xhr){
                alert('PHP页面有错误！'+xhr.responseText);
            }
        });
    }else{
        return false;
    }
};