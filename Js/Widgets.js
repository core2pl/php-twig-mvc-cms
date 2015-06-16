function clock() {
	var d = new Date();
	var time = d.getHours()+":"+d.getMinutes()+":"+d.getSeconds();
	$('.clock').html(time);
}
window.setInterval("clock()", 1000);

function refreshL() {
	$.ajax({
				
		url : "/ajax/list",
		data: {
		},
		type : "get",
		success: function(data){
			$(".page_block").html(data);
		}
				
	});
}

function refreshA() {
	$.ajax({
				
		url : "/ajax/listAdmin",
		data: {
		},
		type : "get",
		success: function(data){
			$(".page_block").html(data);
		}
				
	});
}

function refreshList() {
	window.setInterval("refreshL()", 30000);
}

function adminList() {
	window.setInterval("refreshA()", 30000);
}

function refresh() {
	$.ajax({
				
		url : "/ajax/refresh",
		data: {
		},
		type : "post",
		success: function(){
		}
				
	});
}
window.setInterval("refresh()", 30000);