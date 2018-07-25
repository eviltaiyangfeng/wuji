// JavaScript Document
/*
$(window).keypress(function(event){
		alert(event.keyCode);
		})
*/
	 //PUBLIC
	function ajax(url,data){
		var date = $.ajax({
		 url : url,
		 type : 'post',
		 data : data,
		 async : false,
		 success:function(date){
			 return date;
			 }
		 })
		 date=date.responseText;
		 date=eval('('+date+')');
		 return date;
		}
		
		
		function hint(val){
			$('.public_hint').html(val).stop(true).animate({opacity:'1',top:'50%'},0).animate({opacity:'0'},2500).animate({top:'-1000px'},0);
		}
		
		$("#file_box").on("change", ".load", function() {
           load($(this).attr("id"),'/add/upimg');
        });

        function load(file_id,url,imgsrc){
		jQuery.ajaxFileUpload({ 
          url : url,  
          secureuri : false,  
          fileElementId : file_id,  
          dataType : 'json',  
          //data : {src : $('.m_b_hand .img').attr('src')},
          success : function(data){ 
          		//console.warn(data);
              if(data.files ==''){
                hint('操作超时,请刷新后重试');
              }else{
				imgsrc.attr("src",data.files);
              }
			 }
  		   }) 
	     }	

$(function(){
	
		
		
		
	})
