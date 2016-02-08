/* 
* @Author: Prabhakar Gupta
* @Date:   2016-02-04 18:31:17
* @Last Modified by:   Prabhakar Gupta
* @Last Modified time: 2016-02-08 16:17:22
*/

var LOAN_FORM = '<input type="number" class="form-control" placeholder="Enter the amount of loan you want from bank here" name="loan_amount" required>';


/**
 * update the input field for current move number
 * @return void
 */
function update_move_number(){
	var input_field = $("#move_number"),
		url = config_path_ajax + "admin/current_level.php";

	$.ajax({
		url : url,
		success : function(data){
			var repsonse_json = jQuery.parseJSON(data);
			input_field.val(repsonse_json.level);
		},
	});
}


/**
 * to get the outstanding trade requests for the users
 * @return void
 */
function get_requests_for_user(){
	var user_id = $("#user_id").val(),
		requests_div = $("#requests_for_me"),
		url = config_path_ajax + "admin/get_trades.php"
			+ "?mode=0"
			+ "&user_id=" + user_id;
	

	requests_div.html('');
	$.ajax({
		url : url,
		success : function(data){
			// console.log(data);
			var repsonse_json = jQuery.parseJSON(data);

			if(repsonse_json.length > 0){
				for(request in repsonse_json){
					var current_request = repsonse_json[request];

					var html = '<div class="col-md-12 request" id="' + current_request['trade_log_id'] + '">from : ' + current_request['name'] + '<br>army offered : <span>' + current_request['army_offered'] + '</span><br>money offered : <span>' + current_request['money_offered'] + '</span><br>land offered : <span>' + current_request['land_offered'] + '</span><br>army demanded : <span>' + current_request['army_demanded'] + '</span><br>money demanded : <span>' + current_request['money_demanded'] + '</span><br>land demanded : <span>' + current_request['land_demanded'] + '</span><br><br><button class="btn btn-success trade_button" data-flag="1">accept</button><button class="btn btn-danger trade_button" data-flag="0">reject</button></div>';

					requests_div.append(html);
				}
			} else {
				requests_div.html("No requests.");
			}
		},
	});
}

/**
 * to get the all trade requests initiated by the users
 * @return void
 */
function get_requests_from_user(){
	var user_id = $("#user_id").val(),
		requests_div = $("#requests_from_me"),
		url = config_path_ajax + "admin/get_trades.php"
			+ "?mode=1"
			+ "&user_id=" + user_id;
	

	requests_div.html('');
	$.ajax({
		url : url,
		success : function(data){
			// console.log(data);
			var repsonse_json = jQuery.parseJSON(data);
			console.log(repsonse_json);
			if(repsonse_json.length > 0){
				for(request in repsonse_json){
					var current_request = repsonse_json[request],
						status = (current_request['status'] > 0) ? 'Accepted' : 'Rejected';

					var html = '<div class="col-md-12 request" id="' + current_request['trade_log_id'] + '">to : ' + current_request['name'] + '<br>army offered : <span>' + current_request['army_offered'] + '</span><br>money offered : <span>' + current_request['money_offered'] + '</span><br>land offered : <span>' + current_request['land_offered'] + '</span><br>army demanded : <span>' + current_request['army_demanded'] + '</span><br>money demanded : <span>' + current_request['money_demanded'] + '</span><br>land demanded : <span>' + current_request['land_demanded'] + '</span><br>' + status;

					requests_div.append(html);
				}
			} else {
				requests_div.html("No requests.");
			}
		},
	});
}


$(document).on("click",".trade_button",function(){
	var button = $(this),
		flag = button.data("flag"),
		log_id = parseInt(button.parents(".request").attr("id")),
		url = config_path_ajax + "admin/do_trade.php"
			+ "?flag=" + flag
			+ "&log=" + log_id;

	$.ajax({
		url : url,
		success : function(data){
			get_requests_for_user();
		},
	});
});


$(document).ready(function(){
	update_move_number();

	get_requests_for_user();
	get_requests_from_user();

	$("#move_select_input").change(function() {
		var move_select_input_form = $("#move_select_input"),
			type_of_move = parseInt(move_select_input_form.val()),
			additional_info_div = $("#additional_info");

		console.log(type_of_move);

		additional_info_div.html('');
		switch(type_of_move){
			case 0 :
				// we have some kids or some real strategists out there playing
				// user is passing his move with 100% of his army on defence
				break;
			case 1 :
				// user wants to take a loan from bank
				// make him enter the amount he wants
				
				// additional_info
				additional_info_div.html(LOAN_FORM);
				break;
			case 2 :
				// this is some real shit, user wants to attack
				// make him enter the % of army he is going to put on defence
				
				additional_info_div.load("templates_included/move_attack.php");
				break;
			case 3 :
				// trader bitch, he is trading with some other team
				// make him enter the resources he wants to give
				
				additional_info_div.load("templates_included/move_trade.php");
				break;
		}
	});
});