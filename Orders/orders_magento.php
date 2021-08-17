<?php

include_once("../magento.php");
include_once("../Products/items.php");
include_once("../Database/models_insert.php");


//GET ALL ORDERS AND THEIR INFO FROM MAGENTO 2 
function get_order_data($token){

    $ch = curl_init("https://your-domain/index.php/rest/V1/orders?searchCriteria=increment_id");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8", "Authorization: Bearer " . $token));
    
    $result = curl_exec($ch);
    
    $orders = json_decode($result);

    return $orders;
   
}


//get all orders from magento and store into variable 
$order = get_order_data($tokens);


//INSERT SELECTED ORDER FIELDS FROM MAGENTO 2 TO PMA.OMNIA DATABASE
function insert_orders_in_db($order,$mysqli){

    foreach ($order->items as $result) {

        try{
            $mag_order_id = $result->entity_id;
            $date = $result->created_at;
            $status = $result->status;
            $total = $result->base_grand_total;
            $minimax_order_id = null;
    
            
            insert_orders($mysqli, $mag_order_id, $minimax_order_id, $date, $status, $total);
        }
        catch(Exception $e){
            echo 'Caught exception at insert_orders_in_db: ',  $e->getMessage(), "\n";
        }
       
    }

}

insert_orders_in_db($order,$mysqli);

?>