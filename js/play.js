/* 
* @Author: Prabhakar Gupta
* @Date:   2016-02-04 18:31:17
* @Last Modified by:   Prabhakar Gupta
* @Last Modified time: 2016-02-06 13:28:38
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

$(document).ready(function(){
	update_move_number();

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