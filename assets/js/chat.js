$(document).ready(function(){
	var edit_id = 0;
	var group_id = 0;
	var su = 0;
	var url      = window.location.href;
	if(url.indexOf("chat.php") != -1){
		setInterval(function(){
			// updateUserList();	
			updateUserChat();
			updateUnreadMessageCount();	
		}, 4000);	
		setInterval(function(){
			// showTypingStatus();
			updateUnreadMessageCount();			
		}, 5000);
	}
	
	$(document).on("click", '#profile_name', function(event) { 	
        console.log('hi');
		$("#status-options").toggleClass("active");
	});

	$(document).on("click", '#status-options ul li', function(event) { 	
		$("#profile_name").removeClass();
		$("#status-online").removeClass("active");
		$("#status-away").removeClass("active");
		$("#status-busy").removeClass("active");
		$("#status-offline").removeClass("active");
		$(this).addClass("active");
		var available_status = 0;
		if($("#status-online").hasClass("active")) {
			$("#profile_name span").addClass("loggedin");
			$("#profile_name span").removeClass("loggedout");
			available_status = 0;
		} else if ($("#status-offline").hasClass("active")) {
			$("#profile_name span").addClass("loggedout");
			$("#profile_name span").removeClass("loggedin");
			available_status = 1;
		} else {
			$("#profile_name span").removeClass();
		};
		$("#status-options").removeClass("active");
		$.ajax({
			url:"chat.php",
			method:"POST",
			data:{action:'available_status',available_status:available_status},
			dataType: "json",
			success:function(response){
				
			}
		});
	});
	

	$(document).on('click', '.bubble_three_dot .delete', function(){		
		var to_chat_id = $(this).attr('id');
		if (confirm("Confirm to delete this") == true) {
		    $.ajax({
				url:"chat.php",
				method:"POST",
				data:{to_chat_id:to_chat_id, action:'delete_chat'},
				dataType: "json",
				success:function(response){
					var resp = $.parseJSON(response);
					$('#conversation').html(resp.conversation);
				}
			});
		}
		
	});	

	$(document).on('click', '.bubble_three_dot .delete_temp', function(){		
		var to_chat_id = $(this).attr('id');
		var partner1 = $(".ppartner1").val();
		var partner2 = $(".ppartner2").val();
		if (confirm("Confirm to delete this") == true) {
			$.ajax({
				url:"chat.php",
				method:"POST",
				data:{to_chat_id:to_chat_id, partner1:partner1, partner2:partner2, action:'delete_chat_temp'},
				dataType: "json",
				success:function(response){
					$('#conversation_thread').html(response.conversation);
				}
			});
		}
	});	

	$(document).on('click', '.bubble_three_dot .restore', function(){		
		var to_chat_id = $(this).attr('id');
		var partner1 = $(".ppartner1").val();
		var partner2 = $(".ppartner2").val();
		if (confirm("Confirm to restore this") == true) {
			$.ajax({
				url:"chat.php",
				method:"POST",
				data:{to_chat_id:to_chat_id, partner1:partner1, partner2:partner2, action:'restore_chat'},
				dataType: "json",
				success:function(response){
					$('#conversation_thread').html(response.conversation);
				}
			});
		}
	});	


	$(document).on('click', '.bubble_three_dot .edit', function(){		
		edit_id = $(this).attr('id');
		var message = $(this).attr('message');

		if(edit_id>0){
			var cname="pmsg";
			if($(this).hasClass("groupmsg")) {
				cname = 'gmsg';
			}
			$(".editchatbox").remove();
			$(this).parent('div').before('<div class="editchatbox">'+
	            '<input type="text" class="mail-edit-box form-control mb-1" value="'+message+'" placeholder="Message">'+
	            '<span class="closechat"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="20" width="20" fill="#ff0000"><path d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm121.6 313.1c4.7 4.7 4.7 12.3 0 17L338 377.6c-4.7 4.7-12.3 4.7-17 0L256 312l-65.1 65.6c-4.7 4.7-12.3 4.7-17 0L134.4 338c-4.7-4.7-4.7-12.3 0-17l65.6-65-65.6-65.1c-4.7-4.7-4.7-12.3 0-17l39.6-39.6c4.7-4.7 12.3-4.7 17 0l65 65.7 65.1-65.6c4.7-4.7 12.3-4.7 17 0l39.6 39.6c4.7 4.7 4.7 12.3 0 17L312 256l65.6 65.1z"/></svg></span>&nbsp;'+
	            '<span class="savechat '+cname+'"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" height="20" width="20" fill="#00ab55"><path d="M504 256c0 136.967-111.033 248-248 248S8 392.967 8 256 119.033 8 256 8s248 111.033 248 248zM227.314 387.314l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.249-16.379-6.249-22.628 0L216 308.118l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.249 16.379 6.249 22.628.001z"/></svg></span>'+                                                                 
	        '</div>');
		}
	});	

	$(document).on('click', '.user-list-box .person', function(){		
		if($(this).hasClass("thread")) {
			$('.person').removeClass('active');
			$(this).addClass('active');
			var partner1 = $(this).data('partner1');
			var partner2 = $(this).data('partner2');
			var tun = $(this).data('tousername');
			$(".ppartner1").val(partner1);
			$(".ppartner2").val(partner2);
			$(".current-chat-user-name .threadname").html(tun);
			showChatThread(partner1,partner2);
		}else if($(this).hasClass("group")) {
			$('.person').removeClass('active');
			$(this).addClass('active');
			var group = $(this).data('group');
			group_id = group;
			var tun = $(this).data('tousername');
			
			$(".current-chat-user-name .groupname").html(tun);
			showChatGroup(group);
		}else{
			$('.person').removeClass('active');
			$(this).addClass('active');
			var to_user_id = $(this).data('touserid');
			su = to_user_id;
			var tun = $(this).data('tousername');
			$(".current-chat-user-name .personalname").html(tun);
			showUserChat(to_user_id);
			$(".chat").attr('id', 'person'+to_user_id);
			$(".mail-write-box").attr('id', 'chatMessage'+to_user_id);
	        //$('#person'+to_user_id+']').addClass('active-chat');
		}	
	});	


	$('.mail-write-box').on('keydown', function(event) {
		if(event.key === 'Enter') {
			if($(this).hasClass("ingroup")) {
				sendGroupMessage(group_id);
				updateGroupChat();
			}else{
				var to_user_id = $(this).attr('id');
				to_user_id = to_user_id.replace(/chatMessage/g, "");
				sendMessage(to_user_id);
				updateUserChat();
			}
		}
	});

	/*$('.mail-edit-box').on('keyup', function(event) {
		if(event.key === 'Enter') {
			var to_user_id = $(this).attr('id');
			to_user_id = to_user_id.replace(/chatMessage/g, "");
			updateMessage(to_user_id,edit_id);
			updateUserChat();
		}else{
			if($(this).val()==''){
				$(".mail-write-box").show();
				$(".mail-edit-box").hide();
			}
		}
	});*/

	$(document).on('click',".closechat", function(event) {
		$(".editchatbox").remove();
	});

	$(document).on('click',".savechat", function(event) {
		//$(".editchatbox").remove();
	
		var message = $(".mail-edit-box").val();
	
		if(message==''){
			$(".editchatbox").remove();
		}else{
		
			if($(this).hasClass("gmsg")) {
				
				updateGroupMessage(group_id,edit_id);
			}else{
				updateMessage(su,edit_id);
			}	
			$(".editchatbox").remove();
		}
	});

	$(document).on('click',".mute_notification", function(event) {
		var id = $('.person.active').attr('data-touserid');
		var title = $(this).attr("title");
		$(".mute_notification").toggle();
		$.ajax({
			url:"chat.php",
			method:"POST",
			data:{id:id,title:title, action:'mute_chat'},
			dataType: "json",
			success:function(response){
				
			}
		});
	});
});

function showUserChat(to_user_id){
	$.ajax({
		url:"chat.php",
		method:"POST",
		data:{to_user_id:to_user_id, action:'show_chat'},
		dataType: "json",
		success:function(response){
			$('#conversation').html(response.conversation);	
			$('#unread_'+to_user_id).html('');
			if(response.mute==1){
				$(".unmute_icon").show();
				$(".mute_icon").hide();
			}else{
				$(".unmute_icon").hide();
				$(".mute_icon").show();
			}
		}
	});
}

function showChatThread(partner1='',partner2=''){
	$.ajax({
		url:"chat.php",
		method:"POST",
		data:{partner1:partner1, partner2:partner2, action:'show_chat_thread'},
		dataType: "json",
		success:function(response){
			$('#conversation_thread').html(response.conversation);	
		}
	});
}

function showChatGroup(group){
	$.ajax({
		url:"chat.php",
		method:"POST",
		data:{group:group,action:'show_chat_group'},
		dataType: "json",
		success:function(response){
			$('#conversation_group').html(response.conversation);	
		}
	});
}

function sendMessage(to_user_id) {
	message = $(".mail-write-box").val();
	$('.mail-write-box').val('');
	if($.trim(message) == '') {
		return false;
	}
	$.ajax({
		url:"chat.php",
		method:"POST",
		data:{to_user_id:to_user_id, chat_message:message, action:'insert_chat'},
		dataType: "json",
		success:function(response) {
			$('#conversation').html(response.conversation);				
			$(".messages").animate({ scrollTop: $('.messages').height() }, "fast");
		}
	});	
}

function sendGroupMessage(group_id){
	message = $(".mail-write-box.ingroup").val();
	$('.mail-write-box.ingroup').val('');
	if($.trim(message) == '') {
		return false;
	}

	$.ajax({
		url:"chat.php",
		method:"POST",
		data:{group_id:group_id, chat_message:message, action:'insert_group_chat'},
		dataType: "json",
		success:function(response) {
			$('#conversation_group').html(response.conversation);				
			$(".messages").animate({ scrollTop: $('.messages').height() }, "fast");
		}
	});
}

function updateMessage(to_user_id=0,edit_id=0) {
	message = $(".mail-edit-box").val();
	if($.trim(message) == '') {
		return false;
	}
	$.ajax({
		url:"chat.php",
		method:"POST",
		data:{to_user_id:to_user_id,edit_id:edit_id, chat_message:message, action:'edit_chat'},
		dataType: "json",
		success:function(response) {
			$('#conversation').html(response.conversation);				
			$(".messages").animate({ scrollTop: $('.messages').height() }, "fast");
		}
	});	
}

function updateGroupMessage(group_id=0,edit_id=0) {

	message = $(".mail-edit-box").val();
	if($.trim(message) == '') {
		return false;
	}
	$.ajax({
		url:"chat.php",
		method:"POST",
		data:{group_id:group_id,edit_id:edit_id, chat_message:message, action:'edit_group_chat'},
		dataType: "json",
		success:function(response) {
			$('#conversation_group').html(response.conversation);				
			$(".messages").animate({ scrollTop: $('.messages').height() }, "fast");
		}
	});	
}

function updateUserChat() {
	$('.person.active').each(function(){
		var to_user_id = $(this).attr('data-touserid');
		$.ajax({
			url:"chat.php",
			method:"POST",
			data:{to_user_id:to_user_id, action:'update_user_chat'},
			dataType: "json",
			success:function(response){		
				$('#conversation').html(response.conversation);			
			}
		});
	});
}

function updateUnreadMessageCount() {
	$('.person.active').each(function(){
		var to_user_id = $(this).attr('data-touserid');
		$.ajax({
			url:"chat.php",
			method:"POST",
			data:{to_user_id:to_user_id, action:'update_unread_message'},
			dataType: "json",
			success:function(response){	
				if(response.count) {
					$('#unread_'+to_user_id).html(response.count);	
				}					
			}
		});
	});
}