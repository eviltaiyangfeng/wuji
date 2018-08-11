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
		
	function ajaxpic(url,formid) {  
     var formData = new FormData($(formid)[0]); 
	 var data=$.ajax({  
          url:url,  
          type:'POST',  
          data: formData,  
          async: false,  
          cache: false,  
          contentType: false,  
          processData: false,  
          success: function (data) {
			  return data
			  } 
     })
	 data=data.responseText;
	 data=eval('('+data+')');
	 return(data); 
	}	
	/*hint--start*/	
	//$.hint.hinta("消息",'提示','1000');
	//$.hint.hintb('消息','提示');
	//$.hint.hintc("消息",'提示',function(){alert(111);});
	(function () {
		$.hint = {
		hinta: function (title, msg , time) {
		  var ahtml='';
		  ahtml+='<div class="p_mb p_mb_hint_a" style="display:block; background:rgba(0,0,0,0.1);">';
		  ahtml+='<div class="hint_a hint"><p class="p1 p_color_back"> '+title+'</p><p class="p2"> '+msg+' </p></div></div>';
		  $('body').append(ahtml);
		  setTimeout(hint_a_close,time);
		  },
		hintb: function (title, msg) {
		  var bhtml='';
		  bhtml+='<div class="p_mb p_mb_hint_b" style="display:block;"><div class="hint_b hint">';
		  bhtml+='<p class="p1 p_color_back">'+title+' <i class="hint_close"><span></span></i></p><p class="p2">'+msg+'</p>';
		  bhtml+='<a href="javascript:;" class="p_color_back hint_two"> 确定</a></div></div>';
		  $('body').append(bhtml);
		  btnNo();
		},
		hintc: function (title, msg, callback) {
			var chtml='';	
			chtml+='<div class="p_mb p_mb_hint_c" style="display:block;"><div class="hint_c hint">';
			chtml+='<p class="p1 p_color_back">'+title+' <i class="hint_close"><span></span></i></p><p class="p2"> '+msg+' </p>';
			chtml+='<a href="javascript:;" class="p_color_backa"> 确定</a>';
			chtml+='<a href="javascript:;" class="p_color_backb"> 取消</a></div></div>';
			$('body').append(chtml);

			btnOk(callback);
			btnNo();
		},
		hintpic: function (title, msg , time) {
		  var ahtml='';
		  ahtml+='<div class="p_mb p_mb_hint_a" style="display:block; background:rgba(0,0,0,0.1);">';
		  ahtml+='<div class="hint_a hint"><p class="p1 p_color_back"> '+title+'</p><p class="p2"> '+msg+' </p></div></div>';
		  $('body').append(ahtml);
		  setTimeout(hint_pic_close,time);
		  }
		
	}
	 function hint_a_close(){
		 $('.p_mb_hint_a').remove();
		 location.reload();
		 }
	 function hint_pic_close(){
		 $('.p_mb_hint_a').remove();
		 } 
	  
	  var btnOk = function (callback) {
		$(".p_mb_hint_c .p_color_backa").click(function () {
		  $(".p_mb_hint_c").remove();
		  callback();
		});
	  }
	  
	
	 
	  var btnNo = function () {
		$(".p_mb_hint_b .hint_two").click(function () {
		  $(".p_mb_hint_b").remove();
		});
		
		$(".p_mb_hint_c .p_color_backb").click(function () {
		if($('.p_mb_hint_c img')){
			  ajax('/Cl/dellsimg',{imgsrc:$('.p_mb_hint_c img').attr('src')});
			  }	
		$(".p_mb_hint_c").remove();
		  
		  
		});
		$('.p_mb .hint .hint_close').click(function(){
			$(this).parents('.p_mb').remove();
			
		})
		
	  }
		
	 })();