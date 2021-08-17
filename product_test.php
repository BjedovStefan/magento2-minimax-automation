<?php

 include_once("magento.php");

function get_order_data($token){

    $ch = curl_init("https://your-domain/index.php/rest/V1/orders?searchCriteria=increment_id");
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8", "Authorization: Bearer " . $token));
    
    $result = curl_exec($ch);
    
    $orders = json_decode($result);

    print_r($orders) ;
   
}

get_order_data($tokens);


?>