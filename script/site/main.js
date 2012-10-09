
function browser() { br=false;
	var ua=navigator.userAgent.toLowerCase(); 
	if ((ua.indexOf("opera") > -1) && (ua.indexOf("msie") > -1)) 	  	   { br="Opera"; }
	if (ua.indexOf("opera/9") > -1)                                        { br="Opera"; }
	if (ua.indexOf("netscape/7") > -1)                                     { br="Netscape"; }
	if (ua.indexOf("netscape/8") > -1)                                     { br="Netscape"; }
	if (ua.indexOf("firefox") > -1)                                        { br="Firefox"; }
	if (ua.indexOf("msie") > -1)                                           { br="IE"; }
	return br;
}

function getUrlPrice(u){
	var url="http://"+$("#sh-site").val()+"/";
	if (u.type!=undefined) { url+="type:"+u.type+"/"; } else { url+="type:"+"price/"; }
	if (u.page!=undefined) { url+="page:"+u.page+"/"; } else { url+="page:"+$("#sh-page").val()+"/"; }
	if (u.group!=undefined) { url+="group:"+u.group+"/"; } else { url+="group:"+$("#sh-group").val()+"/"; }
	if (u.portion!=undefined) { url+="portion:"+u.portion+"/"; } else { url+="portion:"+$("#sh-portion").val()+"/"; }
	if (u.first!=undefined) { url+="first:"+u.first+"/"; } else { url+="first:"+$("#sh-first").val()+"/"; }
	if (u.search!=undefined) { url+="search:"+u.search+"/"; } else { url+="search:"+$("#sh-search").val()+"/"; }
	if (u.act!=undefined) { url+="act:"+u.act+"/"; } else { url+="act:"+$("#sh-act").val()+"/"; }
	if (u.id!=undefined) { url+="id:"+u.id+"/"; } else { url+="id:"+$("#sh-id").val()+"/"; }
	if (u.sort!=undefined) { url+="sort:"+u.sort+"/"; } else { url+="sort:"+$("#sh-sort").val()+"/"; }
	if (u.grad!=undefined) { url+="grad:"+u.grad+"/"; } else { url+="grad:"+$("#sh-grad").val()+"/"; }
	if (u.presence!=undefined) { url+="presence:"+u.presence+"/"; } else { url+="presence:"+$("#sh-presence").val()+"/"; }	
	if (u.count!=undefined) { url+="count:"+u.count+"/"; }
	return url;
}

var sendAjax=function(title,href,e){
	if (browser()!="IE") {
		if (history!=undefined) {
			history.replaceState({page:href},title,href);
			e.preventDefault();
			document.title=title;
			$.ajax({
				url:href,
				type:'GET', dataType:'json',
				success:function(s){
					if (s.ans!=undefined) { alert(s.ans); } 
					if (s.table!=undefined) { 	if (s.table!="") {
							$("#table-data").html(s.table);
					} } 
					if (s.lists!=undefined) { 	if (s.lists!="") {
							$("#table-list").html(s.lists);
					} } 
					if (s.steps!=undefined) { 	if (s.steps!="") {
							$("#steps-wrap").html(s.steps);
					} } 
					if (s.shadow!=undefined) { if (s.shadow!="") {
							$("#shadow-wrap").html(s.shadow);
					} } 
					getAjax();
				},
				error:function(XMLHttpRequest,textStatus,error){ alert(textStatus); }
			});
		}
	}
}

var getAjax=function(){
	$(function(){
		$("[link-method=ajax]").on("click",function(e){
			sendAjax("Maestro",$(this).attr("href"),e);
			if ($(this).attr("link-action")=="portion") {
				$("[link-action=portion]").removeClass("portion-link-active").addClass("portion-link");
				$(this).addClass("portion-link-active");
			}
			if ($(this).attr("link-action")=="group") {
				$("[link-action=group]").removeClass("group-tree-active");
				$(this).addClass("group-tree-active");
				$("#search-value").val("Поиск по всем товарам...");
			}
		});
	});
}

$(function(){
	getAjax();
});


function mountFormSubmit(){
	return false;
}

function enterFormSubmit(){
	$(function(){
		var email=$("#users-enter-email").val();
		var password=$("#users-enter-password").val();
		$.ajax({
			url:'http://www.maestro18.ru/type:user/act:enter/email:'+email+'/password:'+password,
			type:'GET', dataType:'json',
			success:function(s){ 
				if (s.ans!=undefined) { alert(s.ans); }
				if (s.check!=undefined) {
					if (s.check=="TRUE") {
						location.href="http://www.maestro18.ru";
					}
				}
				if (s.e!=undefined) {
					if (s.e!="") {
						$("#users-enter-password").val("");
						$("#users-enter-error").html(s.e);
					}	
				}
			},
			error:function(XMLHttpRequest,textStatus,error){ alert(textStatus); }
		});
	});
	return false;
}

function searchFormSubmit(){
	location.href=getUrlPrice({
		page:0,
		first:0,
		search:$("#search-value").val(),
		act:'search',
		id:0
	});
	return false;
}

function articleFormSubmit() {
	location.href=getUrlPrice({
		act:'add',
		count:$("#article-quant-need").val()
	});
	return false;
}

function articleBtnAddOver() {
	$(function(){
		$("#btn-article-add").removeClass("btn-article-add-disable").addClass("btn-article-add-active");
	});
}

function articleBtnAddLeave(){
	$(function(){
		$("#btn-article-add").removeClass("btn-article-add-active").addClass("btn-article-add-disable");
	});
}

$(function(){
	if (window!=top){
		top.location.replace("http://www.maestro18.ru");
	}	
});

$(function(){
	$("#presence-checkbox").on("click",function(){
		if ($(this).is(":checked")) { check="true"; } else { check="false"; }
		location.href=getUrlPrice({
			page:0,
			first:0,
			act:'presence',
			id:0,
			presence:check
		});
	});
});

$(function(){
	$(".toolbar-button").on("mouseover",function(){
		$(this).removeClass("toolbar-button-disable").addClass("toolbar-button-active");
	});
});

$(function(){
	$(".toolbar-button").on("mouseleave",function(){
		$(this).removeClass("toolbar-button-active").addClass("toolbar-button-disable");
	});
});

$(function(){
	$(".ico-nav").on("mouseover",function(){
		$(this).fadeTo('fast',0.8);
	});
	$(".ico-nav").on("mouseleave",function(){
		$(this).fadeTo('fast',1);
	});
});



$(function(){
	$("#search-value").on("focus",function(){
		$("div#search-wrap").removeClass("search-wrap").addClass("search-wrap-active");
		$(this).animate({'width':'300px',},"slow").removeClass("input-search").addClass("input-search-active");
		if ($(this).val()=="Поиск по всем товарам...") { $(this).val(""); }
	});
	$("#search-value").on("blur",function(){
		$("div#search-wrap").removeClass("search-wrap-active").addClass("search-wrap");
		$(this).animate({'width':'250px',},"slow").removeClass("input-search-active").addClass("input-search");
		if ($(this).val()=="") { $(this).val("Поиск по всем товарам..."); }
	});
});

$(function(){
	$("input").on("focus",function(){
		$(this).select();
	});
});


$(function(){
	$(".orders-table-input input").on("keyup",function(){
		var input=$(this);
		var part=input.data("part");
		var count=input.val();
		$.ajax({
			url:'http://www.maestro18.ru/type:order/act:editquant/id:'+part+'/count:'+count,
			type:'GET', dataType:'json',
			success:function(s){ 
				if (s.ans!=undefined) { alert(s.ans); }
				if (s.count!=undefined) { 
					if (s.count>0) {
						input.val(s.count); 
					}
				}
				if (s.summ!=undefined) { 
					if (s.summ>0) {
						$("[summ_part_id="+part+"]").html(s.summ);
					}
				}
				if (s.total!=undefined) { 
					if (s.total>0) {
						$(".orders-table-total .orders-table-summ").html(s.total);
					}
				}
			},
			error:function(XMLHttpRequest,textStatus,error){ alert(textStatus); }
		});
	});
});


$(function(){
	$(".orders-table-delete").on("click",function(event){
		event.preventDefault();
		var part=$(this).data("part");
		$.ajax({
			url:'http://www.maestro18.ru/type:order/act:delline/id:'+part,
			type:'GET', dataType:'json',
			success:function(s){
				if (s.ans!=undefined) { alert(s.ans); }
				if (s.bar!=undefined) {
					if (s.bar!="") {
						$("#bar-wrap").html(s.bar);
					}
				}
				if (s.partId!=undefined) {
					if (s.partId>0) {
						$("[tr_part_id="+s.partId+"]").hide();
					}
				}
				if (s.total!=undefined) {
					if (s.total>0) {
						$(".orders-table-total .orders-table-summ").html(s.total);
					}
				}
				if (s.count!=undefined) { 
					if (s.count<1) {
						$("#orders-box").hide();
						$(".orders-link-mount").hide();
						$(".orders-message").html('<div>Ваша корзина пуста</div>');
					} else {
						$("#orders-box").show();
						$(".orders-link-mount").show();
						$(".orders-message").empty();
					}
				}
			},
			error:function(XMLHttpRequest,textStatus,error){ alert(textStatus); }
		});
	});
});

$(function(){
	$(".orders-mount-input input").on("keyup",function(event){
		var type=$(this).data("type");
		var value=$(this).val();
		$.ajax({
			url:'http://www.maestro18.ru/type:order/act:check/key:'+type+'/value:'+value,
			type:'GET', dataType:'json',
			success:function(s){
				if (s.ans!=undefined) { alert(s.ans); }
				if (s.e!=undefined) {
					$("[check_hint="+type+"]").html(s.e);
				}
				if (s.eT!=undefined) {
					if (s.eT=="red") {
						$("[check_hint="+type+"]").removeClass("orders-mount-hint-silver").addClass("orders-mount-hint-red");
					} else {
						$("[check_hint="+type+"]").removeClass("orders-mount-hint-red").addClass("orders-mount-hint-silver");
					}
				}
				if (s.check!=undefined) {
					if (s.check=="TRUE") {
						$("[check_icon="+type+"]").removeClass("orders-mount-check-false").addClass("orders-mount-check-true");
					} else {
						$("[check_icon="+type+"]").removeClass("orders-mount-check-true").addClass("orders-mount-check-false");
					}
				}
			},
			error:function(XMLHttpRequest,textStatus,error){ alert(textStatus); }
		});
	});
});

$(function(){
	$(".orders-mount-area textarea").on("blur",function(event){
		var value=$(this).val();
		$.ajax({
			url:'http://www.maestro18.ru/type:order/act:checkComment/value:'+value,
			type:'GET', dataType:'json',
			success:function(s){
				if (s.ans!=undefined) { alert(s.ans); }
			},
			error:function(XMLHttpRequest,textStatus,error){ alert(textStatus); }
		});
	});
});

$(function(){
	$(".orders-link-send").on("click",function(event){
		event.preventDefault();
		var firstname=$("[data-type=firstname]").val();
		var lastname=$("[data-type=lastname]").val();
		var phone=$("[data-type=phone]").val();
		var email=$("[data-type=email]").val();
		$.ajax({
			url:'http://www.maestro18.ru/type:order/act:send/firstname:'+firstname+'/lastname:'+lastname+'/phone:'+phone+'/email:'+email+'',
			type:'GET', dataType:'json',
			success:function(s){ 
				if (s.ans!=undefined) { alert(s.ans); }
				if (s.e!=undefined) {
					if (s.e!="") {						
						$(".orders-message").html('<div>'+s.e+'</div>');
					} else {
						$(".orders-message").empty();
					}
				}
				if (s.check!=undefined) { 
					if (s.check=="TRUE") {
						$(".orders-message").removeClass("orders-message-error").addClass("orders-message-good");
						$("#orders-box").empty();
						$(".orders-link-send").hide();
					} else {
						$(".orders-message").removeClass("orders-message-good").addClass("orders-message-error");
					}
				}
				if ((s.eT!=undefined) && (s.eL!=undefined)) {
					if ((s.eT!="") && (s.eL!="")) {
						$("[check_icon="+s.eT+"]").removeClass("orders-mount-check-true").addClass("orders-mount-check-false");
						$("[check_hint="+s.eT+"]").html(s.eL).removeClass("orders-mount-hint-silver").addClass("orders-mount-hint-red");
					}
				}
				if (s.bar!=undefined) {
					if (s.bar!="") {
						$("#bar-wrap").html(s.bar);
					}
				}
				if (s.reg!=undefined) {
					if (s.reg!="") {
						$("#orders-box").append(s.reg);
					}
				}

			},
			error:function(XMLHttpRequest,textStatus,error){ alert(textStatus); }
		});
	});
});




