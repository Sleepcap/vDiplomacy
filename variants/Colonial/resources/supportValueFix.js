/*
 * As Choices for TSR and Suez orders are updated later during the initialisation
 * of the orders, it can happen, that the initial setted order get lost.
 * (order.js line 326: values are set to "" if the choice is currently not available)
 * 
 * For move-orders, this shouldn't be a problem as there will be always a move-order available,
 * but for support's it may be problem. For the suez-order which cannot give a regular supportHold
 * it's definitely a problem
 */

function fixOrders(){
        MyOrders.map(
                function(order,i){
                        order.requirements.each(
                                function(r){
                                        if(order[r] != ordersData[i][r]) {
                                                order.updateValue(r, ordersData[i][r]); 
                                                order.reHTML(r);
                                        }
                                });
                        if( order.isChanged ){
                                order.setSelectsGreen();
                                order.checkComplete();
                        }
                });
}


