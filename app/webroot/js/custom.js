var base_url = window.location.origin + "/messageApp/";

// UPLOAD IMAGE ON UPDATE PROFILE PAGE
$("#imgFile").change(function (e) {
	if (this.files && this.files[0]) {
		var img = document.querySelector('img');  // $('img')[0]
		img.src = URL.createObjectURL(this.files[0]); // set src to blob url
	}
});


// SEE MORE MESSAGE
$(document).ready(function(){
	$(document).on('click', '#show-more', function(){
		var lastMsg = $(this).data("msg");
		var recipient = document.getElementById("searchMessage").value;

		$("#show-more").html("Loading...");
		$.ajax({
			url: base_url+"get-messages",
			method: "POST",
			data: {lastMsgId: lastMsg, recipientName: recipient},
			dataType: "text",
			success: function(data){
				var resData = data;
				if (resData != '') {
					$('#show-more').remove();
					$('.messages-con').append(resData);
				} else {
					$("#show-more").html("No message available");
				}
			}
		});
	});

});


// REPLY MESSAGE
$(document).ready(function(){
	$("#reply-msg-form").submit(function (e) {
		var recipient = document.getElementById("reply-button").value;
		var msgContent = document.getElementById("msg-content").value;
		var convocodeVal = document.getElementById("convocode").value;
		$.ajax({
			url: base_url+"reply-message",
			method: "POST",
			data: {to_id: recipient, content: msgContent, convocode: convocodeVal},
			dataType: "text",
			success: function(data){
				var resData = data;
				if (resData != '') {
					$('.message-details-con').append(resData);
					$("#msg-content").val("");
					$('.message-details-con').animate({scrollTop: $('.message-details-con').prop("scrollHeight")}, 200);
				}
			}
		});
		e.preventDefault();
	});
});


// SEE OLDER MESSAGES DETAILS
$(document).ready(function(){
	$(document).on('click', '#see-older-msg', function(e){
		var recipientId = $('.message-details-con').data("recipient");
		var lastMsgId = $("#see-older-msg").data("msgid");
		$(this).html("Loading...");
		$.ajax({
			url: base_url+"get-message-paginate",
			method: "POST",
			data: {recipient: recipientId, lastMsgId: lastMsgId},
			dataType: "text",
			success: function(data){
				var resData = data;
				if (resData != '') {
					$("#see-older-msg").remove();
					$('.message-details-con').prepend(resData);
				} else {
					$("#see-older-msg").remove();
				}
			}
		});
		e.preventDefault();
	});
});


// DELETE CONVERSATION MESSAGE
$(document).ready(function(){
	$(document).on('click', '.delete-msg', function(e){
		var convocodeId = $(this).data("convocode");
		$.ajax({
			url: base_url+"delete-message",
			method: "POST",
			data: {convocode: convocodeId},
			dataType: "text",
			success: function(data){
				if(data == "Success"){
					$("#"+convocodeId).fadeOut(300, function() {
						$("#"+convocodeId).remove();
					});
				} else {
					alert("Error Occured!");
				}
			}
		});
		e.preventDefault();
	});
});



// DELETE SINGLE MESSAGE
$(document).ready(function(){
	$(document).on('click', '#delete-single-msg', function(e){
		var msgToDelete = $(this).data("msgid");
		$.ajax({
			url: base_url+"delete-single-message",
			method: "POST",
			data: {msgid: msgToDelete},
			dataType: "text",
			success: function(data){
				if(data == "Success"){
					$("#"+msgToDelete).fadeOut(300, function() {
						$("#"+msgToDelete).remove();
					});
				} else {
					alert("Error Occured!");
				}
			}
		});
		e.preventDefault();
	});
});


// SEARCH MESSAGE
$(document).ready(function(){
	$("#searchMessage").change(function (e) {
		var search = document.getElementById("searchMessage").value;
		$.ajax({
			url: base_url+"search-message",
			method: "POST",
			data: {searchValue: search},
			dataType: "text",
			success: function(data){
				var resData = data;
				if (resData != '') {
					$('.messages-con').html(resData);
				}
			}
		});
		e.preventDefault();
	});
});


//REGISTER FORM
$(document).ready(function(){
	$("#UserRegistrationForm").submit(function (e) {
		var formdata = $(this).serialize();
		$.ajax({
			url: base_url+"register-user",
			method: "POST",
			data: formdata,
			dataType: "text",
			success: function(data){
				if (data != '') {
					$('.error-messages').html(data);
				} else {
					window.location.href = base_url + "thank-you";
				}
			}
		});
		e.preventDefault();
	});
});
