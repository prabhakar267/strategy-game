#Strategy Game

This event is a strategy game, based on the TV series **Game of Thrones**. 

The objective of the game is to maximize the Area/Land (kingdom) under the control of each House. This is done by battling the other Houses using the Army units under the control of each House. More army units can be purchased from Army pits by using Gold (Money), a certain amount of which has been allotted to each House at the start of the game.

####Instructions to run:
  1. Save connection.inc.sample as connect.inc.php and change variables according to your database settings. 
  2. Register yourself as a player before you can begin playing. (/register.php)
  3. Login and you are good to go. 
  4. The points can be viewed by clicking on the leaderboard.

####Rules of the game:
  In each round, you can 
       1. Attack another House OR
        2. Approach another House for an Alliance OR
        3. Buy soldiers from the Army Pit OR
        4. Do nothing
    If you want to take a loan, you can approach the Iron Bank of Braavos as well. This would be in addition to one of the above four choices.
    
####FAQs

    **HOW IS THE OUTCOME OF AN ATTACK DECIDED?**
    In a single battle, the House having greater army wins over the other house. If multiple houses attack the same house, the House with the largest effective army wins, while only the losing House suffers damage.

   **WHAT HAPPENS WHEN A HOUSE GETS DEFEATED IN AN ATTACK?**
    The defeated House gets left with 50% of the army, 50% of the land and 50% of the money it had prior to the attack.

   **WHAT IS AN ALLIANCE?**
   An Alliance works like a barter system. You give something from your resources (L, M and A) and ask for something else in exchange. The two allying Houses thus agree to a certain deal they made and that alliance only stands till the house which made the proposal attacks or defends. After the decision is made and the work is done, the alliance is culminated and the Houses are free to make an alliance with someone else or again with each other in the next round.

   **WHAT IF I LOSE MOST OF MY ARMY OR I HAVE VERY LOW ARMY?**
   Houses can buy soldiers from army pits. Different fighters will have different impact on army level.
   There are three categories of soldiers: Unsullied, Second Sons, and Sell Swords. Unsullied are the best quality soldiers, followed by the second sons, then the sell swords.
   Cost of Each Type of Soldier:
    - 10 Sell swords = 10M
    - Second Sons = 10M for 1.
    - Unsullied = 20M for 1
    
   **WHAT IS THE USE OF IRON BANK OF BRAAVOS AND WHAT ARE ITS TERMS AND CONDITIONS?**
   The Iron Bank of Braavos works as a money lender. Houses can take a loan twice in whole stage. Interest rate at which they have to repay the loan amount is 25%. Loan must be repaid within 3 rounds. If a House fails to repay the loan amount, then Braavos attacks that House, and it loses 80% of its resources.


  

