/* 
* @Author: Prabhakar Gupta
* @Date:   2016-02-04 18:31:17
* @Last Modified by:   Prabhakar Gupta
* @Last Modified time: 2016-02-04 18:38:56
*/

$(document).ready(function(){
	$("#move_select_input").change(function() {
		var move_select_input_form = $("#move_select_input"),
			type_of_move = parseInt(move_select_input_form.val());

		console.log(type_of_move);

		switch(type_of_move){
			case 0 :
				// we have some kids or some real strategists out there playing
				// user is passing his move with 100% of his army on defence
				break;
			case 1 :
				// user wants to take a loan from bank
				// make him enter the amount he wants
				break;
			case 2 :
				// this is some real shit, user wants to attack
				// make him enter the % of army he is going to put on defence
				break;
			case 3 :
				// trader bitch, he is trading with some other team
				// make him enter the resources he wants to give
				break;
		}
		
	});
});