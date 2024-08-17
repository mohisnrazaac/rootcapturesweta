$(document).on("keyup",".user_list_search",function(){
    var s = $(this).val().toLowerCase();
    if(s==''){
        $('.user_list').show();
    }else{
        $('.user_list').hide();
        $('.user_list').each(function(e){
            //alert($(this).attr('user'));
            var name = $(this).attr('user').toLowerCase();
            if(name.indexOf(s)!= -1){
                $(this).show();
            }
        });
    }
    
}); 

$(document).on("keyup",".chat_search",function(){
    var s = $(this).val().toLowerCase();
    if(s==''){
        $('.chat_list').show();
    }else{
        $('.chat_list').hide();
        $('.chat_list').each(function(e){
            //alert($(this).attr('user'));
            var name = $(this).attr('chat').toLowerCase();
            if(name.indexOf(s)!= -1){
                $(this).show();
            }
        });
    }
    
});

$(document).on("keyup",".search_group_list",function(){
    var s = $(this).val().toLowerCase();
    if(s==''){
        $('.group_list').show();
    }else{
        $('.group_list').hide();
        $('.group_list').each(function(e){
            //alert($(this).attr('user'));
            var name = $(this).attr('group').toLowerCase();
            if(name.indexOf(s)!= -1){
                $(this).show();
            }
        });
    }
    
});

$(document).on("keyup",".addcontactsearch",function(){
    var s = $(this).val().toLowerCase();
    if(s==''){
        $('.search-contact-list').show();
    }else{
        $('.search-contact-list').hide();
        $('.search-contact-list').each(function(e){
            //alert($(this).attr('user'));
            var name = $(this).attr('username').toLowerCase();
            var email = $(this).attr('email').toLowerCase();
            var phone = $(this).attr('phone').toLowerCase();
            if(name.indexOf(s)!= -1 || email.indexOf(s)!= -1 || phone.indexOf(s)!= -1){
                $(this).show();
            }
        });
    }
    
});

$(document).on("keyup",".sharecontactsearch",function(){
    var s = $(this).val().toLowerCase();
    if(s==''){
        $('.search-share-list').show();
    }else{
        $('.search-share-list').hide();
        $('.search-share-list').each(function(e){
            //alert($(this).attr('user'));
            var name = $(this).attr('username').toLowerCase();
            var email = $(this).attr('email').toLowerCase();
            var phone = $(this).attr('phone').toLowerCase();
            if(name.indexOf(s)!= -1 || email.indexOf(s)!= -1 || phone.indexOf(s)!= -1){
                $(this).show();
            }
        });
    }
    
});

$(document).on("keyup",".forwardsearch",function(){
    var s = $(this).val().toLowerCase();
    if(s==''){
        $('.search-forward-list').show();
    }else{
        $('.search-forward-list').hide();
        $('.search-forward-list').each(function(e){
            //alert($(this).attr('user'));
            var name = $(this).attr('username').toLowerCase();
            var email = $(this).attr('email').toLowerCase();
            var phone = $(this).attr('phone').toLowerCase();
            if(name.indexOf(s)!= -1 || email.indexOf(s)!= -1 || phone.indexOf(s)!= -1){
                $(this).show();
            }
        });
    }
    
});

$(document).on("click",".select_user_status a",function(){
    var status = $(this).text();
    var html = ''
    if(status=="Offline"){
        html = '<i class="ri-record-circle-fill font-size-10 text-dark me-1 ms-0 d-inline-block"></i> '+status+' <i class="mdi mdi-chevron-down"></i>';
    }else if(status=="Busy"){
        html = '<i class="ri-record-circle-fill font-size-10 text-danger me-1 ms-0 d-inline-block"></i> '+status+' <i class="mdi mdi-chevron-down"></i>';
    }else if(status=="Away"){
        html = '<i class="ri-record-circle-fill font-size-10 text-warning text-away me-1 ms-0 d-inline-block"></i> '+status+' <i class="mdi mdi-chevron-down"></i>';
    }else {
        html = '<i class="ri-record-circle-fill font-size-10 me-1 ms-0  text-success d-inline-block"></i> '+status+' <i class="mdi mdi-chevron-down"></i>';
    } 

    $(".update_user_status").html(html);
}); 

$(document).on("click",".create_group",function(){
    var error = 0;

    if ($(".group_name").val == 0){
        $message = "Please enter a group name";
        error++;
    }

    if ($(".group_member:checkbox:checked").length == 0){
        $message = "Please select group members";
        error++;
    }
    
    if(error>0){
        $('.message_section').html('<div class="alert alert-danger" role="alert">'+$message+'</div>');
        setTimeout(function(){ $('.message_section').html(''); }, 2000);
    }else{
        var data = $(".create_group_form").serialize();
        $.ajax({
            type:"post",
            url:"chat-ajax.php",
            data:data,
            success:function(response){
                $('.message_section').html('<div class="alert alert-success" role="alert">Group Create successfully.</div>');
                setTimeout(function(){ 
                    $('.message_section').html('');
                    $(".addgroup_close").trigger("click");
                }, 3000);
                $(".create_group_form")[0].reset();
                $(".group_chat_list").html(response);
                
            }
        })
    }

});

$(document).on("click",".add_new_contact",function(){
    var error = 0;
    if ($(".add_contacts:checkbox:checked").length == 0){
        $message = "Please select users to add contact";
        error++;
    }
    
    if(error>0){
        $('.message_section').html('<div class="alert alert-danger" role="alert">'+$message+'</div>');
        setTimeout(function(){ $('.message_section').html(''); }, 2000);
    }else{
        var data = $(".contactsearchform").serialize();
        $.ajax({
            type:"post",
            url:"chat-ajax.php",
            data:data,
            success:function(response){
                $('.message_section').html('<div class="alert alert-success" role="alert">Add contacts successfully.</div>');
                setTimeout(function(){ 
                    $('.message_section').html('');
                    $(".addcontact_close").trigger("click");
                }, 3000);
                $(".contactsearchform")[0].reset();
                $(".userexistcontact").html(response);
                
            }
        })
    }
});

$(document).on("click",".share_new_contact",function(){
    var error = 0;
    if ($(".share_contacts:checkbox:checked").length == 0){
        $message = "Please select users to share contact";
        error++;
    }
    
    if(error>0){
        $('.message_section').html('<div class="alert alert-danger" role="alert">'+$message+'</div>');
        setTimeout(function(){ $('.message_section').html(''); }, 2000);
    }else{
        var data = $(".contactshareform").serialize();
        $.ajax({
            type:"post",
            url:"chat-ajax.php",
            data:data,
            success:function(response){
                $('.message_section').html('<div class="alert alert-success" role="alert">Share contacts successfully.</div>');
                setTimeout(function(){ 
                    $('.message_section').html('');
                    $(".customModel").hide();
                    $(".customModel").removeClass("show");
                }, 3000);
                $(".contactshareform")[0].reset();
                
            }
        })
    }
});

$(document).on("click",".action_close",function(){
    $(".customModel").hide();
    $(".customModel").removeClass("show");
});

$(document).on("click",".action_remove",function(){
    var data = $(".actioncontactform").serialize();
    $.ajax({
        type:"post",
        url:"chat-ajax.php",
        data:data,
        success:function(response){
            $('.message_section').html('<div class="alert alert-success" role="alert">Action complete successfully.</div>');
            setTimeout(function(){ 
                $('.message_section').html('');
                $("#actionContact-exampleModal").hide();
                $("#actionContact-exampleModal").removeClass("show");
            }, 3000);
            $(".userexistcontact").html(response);
        }   
    })
});

$(document).on("click",".confirm_remove",function(){
    var data = $(".confirm_form").serialize();
    $.ajax({
        type:"post",
        url:"chat-ajax.php",
        data:data,
        success:function(response){
            $('.message_section').html('<div class="alert alert-success" role="alert">Chat delete successfully.</div>');
            setTimeout(function(){ 
                $('.message_section').html('');
                $("#confirm-exampleModal").hide();
                $("#confirm-exampleModal").removeClass("show");
            }, 3000);
            $(".livechat_container").html(response);
        }   
    })
});

$(document).on("click",".forward_msg_button",function(){
    var error = 0;
    if ($(".forward_contacts:checkbox:checked").length == 0){
        $message = "Please select users to forward message";
        error++;
    }
    
    if(error>0){
        $('.message_section').html('<div class="alert alert-danger" role="alert">'+$message+'</div>');
        setTimeout(function(){ $('.message_section').html(''); }, 2000);
    }else{
        var data = $(".forwardmsgform").serialize();
        $.ajax({
            type:"post",
            url:"chat-ajax.php",
            data:data,
            success:function(response){
                $('.message_section').html('<div class="alert alert-success" role="alert">Message forward successfully.</div>');
                setTimeout(function(){ 
                    $('.message_section').html('');
                    $("#forwardmsg-exampleModal").hide();
                    $("#forwardmsg-exampleModal").removeClass("show");
                }, 3000);
            }   
        })
    }
    
});

$(document).on("click",".chat-send-websocket",function(e){
    event.preventDefault();
    sendMessageData();

    //$('#messages_area').scrollTop($('#messages_area')[0].scrollHeight);
});

$(document).on('keypress','.chat-websocket',function (e) {
    var key = e.which;
    if(key == 13){
        sendMessageData();
    }
});

var conn = new WebSocket('wss://rootcapture.com/socket');
conn.onopen = function(e) {
    console.log("Connection established!");
};

conn.onerror    = function(ev){ 
    console.log("Connection error!");
    //window.open('bin/server.php', '_blank'); 
    $.ajax({
        type:'get',
        url:'start-server.php',
        success:function(){

        }
    })
}; 

conn.onmessage = function(e) {

    var data = JSON.parse(e.data);
    var li = $(".loggedUserId").val();
    var gi = $(".chat_group_id").val();
    var html_data =  '';
    if(data.from == 'Me'){
        html_data = $(".medatacontent").html();
    }else{
        if(data.gi>0 && data.gi==gi){
            html_data = $(".otherdatacontent").html();
        }else if(li==data.ri){
            html_data = $(".otherdatacontent").html();
        }
    }
    if(html_data!=''){
        html_data = html_data.replace(/{{MESSAGE}}/g,data.msg);
        html_data = html_data.replace(/{{SHORTNAME}}/g,"V");
        html_data = html_data.replace(/{{MESSAGEID}}/g,5);
        html_data = html_data.replace(/{{USERNAME}}/g,data.sn);
        html_data = html_data.replace(/{{DATE}}/g,data.dt);
    }
    
    $('.websocket-chat-box').append(html_data);

    $(".chat-websocket").val("");
}; 

function actionContact(action='',id=''){
    var message = '';
    if(action=='remove_contact'){
        message = "Do you really want to remove this contact?";
    }else if(action=='block_contact'){
        message = "Do you really want to block this contact?";
    }
    $(".confirm_message").html(message);
    $(".action_type").val(action);
    $(".action_id").val(id);
    $("#actionContact-exampleModal").show();
    $("#actionContact-exampleModal").addClass("show");
}

function shareContact(name='',id=''){
    $(".share-user").show();
    $(".share-user"+id).hide();
    $(".sharename").html(name);
    $(".shared_user").val(name);
    $("#shareContact-exampleModal").show();
    $("#shareContact-exampleModal").addClass("show");
}

function openContactChat(contact_id,loggedUserId){
    $.ajax({
        type:"post",
        url:"chat-ajax.php",
        data:{action:"contact_chat",contact_id:contact_id},
        success:function(response){
            var res = response.split('|');           
            
            var pusher = new Pusher('afc846898302db567944', {
                authEndpoint: '/livechat/auth.php',
                auth: {
                          params: {
                              channelName: res[0],
                              id:loggedUserId
                          }
                      },
                cluster: 'ap2'
              });
      
            var channel = pusher.subscribe(res[0]);

            $(".livechat_container").html(res[1]);
        }   
    })
}

function openGroupChat(group_id){
    $.ajax({
        type:"post",
        url:"chat-ajax.php",
        data:{action:"group_chat",group_id:group_id},
        success:function(response){
            $(".livechat_container").html(response);
        }   
    })
}

function showUserProfile(){
    $(".user-profile-sidebar").toggle();
}

function closeSidebar(){
    $(".user-profile-sidebar").hide();
}

function archiveChat(user_id=''){
    $.ajax({
        type:"post",
        url:"chat-ajax.php",
        data:{action:"archive_chat",contact_id:user_id},
        success:function(response){
            //$(".livechat_container").html(response);
        }   
    });
}

function muteChat(user_id='',status=''){
    $.ajax({
        type:"post",
        url:"chat-ajax.php",
        data:{action:"mute_chat",contact_id:user_id,status:status},
        success:function(response){
            $(".livechat_container").html(response);
        }   
    });
}

function deleteChat(user_id=''){
    var message = '';
    message = "Do you really want to remove this chat?";
    $(".confirm_message").html(message);
    $(".action_type").val("delete_chat");
    $(".conatct_user_id").val(user_id);
    $("#confirm-exampleModal").show();
    $("#confirm-exampleModal").addClass("show");
}  

function deleteMessage(chatid='',contact_id=''){
    $.ajax({
        type:"post",
        url:"chat-ajax.php",
        data:{action:"delete_message",chatid:chatid,contact_id:contact_id},
        success:function(response){
            $(".livechat_container").html(response);
        }   
    })
}


function copyMessage(text='') {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val(text).select();
  document.execCommand("copy");
  $temp.remove();
}

function sendMessageData(){
    var user_id = 5;
    var msg = $('.chat-websocket').val();
    var si = $('.login_user_id').val();
    var sn = $('.login_user_name').val();
    var ri = $('.receiver_user_id').val();
    var rn = $('.receiver_user_name').val();
    var gi = $('.chat_group_id').val();

    var data = {
        userId : si,
        si : si,
        si : si,
        sn : sn,
        ri : ri,
        rn : rn,
        gi : gi,
        msg : msg
    };

    conn.send(JSON.stringify(data));
}