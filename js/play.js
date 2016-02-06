/* 
* @Author: Prabhakar Gupta
* @Date:   2016-02-04 18:31:17
* @Last Modified by:   Prabhakar Gupta
* @Last Modified time: 2016-02-06 12:22:39
*/

var LOAN_FORM = '<input type="number" class="form-control" placeholder="Enter the amount of loan you want from bank here" name="loan_amount" required>';

$(document).ready(function(){
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