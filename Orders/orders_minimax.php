<?php

include_once("../Database/Conn.php");
include_once("../magento.php");
include_once("../minimax.php");
include_once("../Products/items.php");
include_once("../Products/org_minimax.php");


$minimax_token = get_minimax_token();
$org = get_organisation($token);


///////////////////// ID'S FROM DB /////////////////////
function get_completed($mysqli){


    $comp_arr = array();

    $sql = ("SELECT * from orders where minimax_order_id = '' and order_status='complete'");
    $result = $mysqli->query($sql);

    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
           
            $comp_arr[] = $row["mag_order_id"];
        }
    }
    else{
        echo "get_completed not working";
    }


    return $comp_arr;
}


$id_db = get_completed($mysqli);
/////////////////////////////////////////////////////////////


//////GET ORDER FROM MAGENTO 2 WITH SELECTED ID /////////////
function get_order_data_by_id($token,$id){

    $ch = curl_init("https://your-domain/index.php/rest/V1/orders/".(int)$id);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8", "Authorization: Bearer " . $token));
    
    $result = curl_exec($ch);
    
    $order = json_decode($result);
  
    return $order;
   
  }
  
//////////////////////////////////////////////////



/////////SORTING ORDER DATA FROM MAGENTO 2 ////////////
 function generate_order($order,$mysqli){

    $orders = get_items_database($mysqli);

  
    $level = ($order->extension_attributes->shipping_assignments);
    foreach($level as $i){
              $address = $i->shipping->address;
                
                    $adr = $address->street[0];
                    $postal = $address->postcode;
                    $city = $address->city;
                    
          }

          
    foreach($order->items as $i){ 
        foreach ($orders as $e){
            if ($e["code"] == $i->sku){
                //print_r($e["code"] ." ". $i->sku);
              
                      $ITid  = $e["item_id"];
                      $ITname = $i->name;
                      $ITcode = $i->sku;
                      $qty = $i->qty_ordered;
                      $cena = $i->price_incl_tax;
                      
                          
            } 
        }
    }



    $data = array(
        "OrderId" => $order->increment_id,
        "ReceivedIssued" => "P",
        "Year" => 0,
        "Number" => null,
        "Date" => date("Y-m-d H:i:s"),
            "Customer" => array(
                "ID" => 8451445,
                "Name" => null,
                "ResourceUrl" => null
            ),
        "CustomerName" => $order->customer_firstname." ". $order->customer_lastname,
        "CustomerAddress" => $adr,
        "CustomerPostalCode" => $postal,
        "CustomerCity" => $city,

        "CustomerCountry" => array(
            "ID" => 192,
            "Name" => null,
            "ResourceUrl" => null
        ),
        "CustomerCountryName" => null,
          "Analytic" => null,
         "DueDate" => null,
          "Reference" => $order->entity_id,
          "Currency" => array(
            "ID" => 7,
            "Name" => null,
            "ResourceUrl" => null
          ),
          "Notes" => null,
          "Document" => null,
          "DateConfirmed" => null,
          "DateCompleted" => null,
          "DateCanceled" => null,
          "Status" => null,
          "DescriptionAbove" => null,
          "DescriptionBelow" => null,
          "ReportTemplate" => array(
            "ID" => null,
            "Name" => null,
            "ResourceUrl" => null
          ),

          "OrderRows" => [array(
            "OrderRowId" => null,
            "Order" => null,
            "Item" => array(
                "ID" => $ITid,
                "Name" => null,
                "ResourceUrl" => null,
            ),
            "ItemName" => $ITname,
            "ItemCode" => $ITcode,
            "Description" => null,
            "Quantity" => $qty,
            "Price" => $cena,
            "UnitOfMeasurement" => "kos",
            "RecordDtModified" => "2020-01-07T12:20:00+02:00",
            "RowVersion" => null,
            "_links" => null,
        ) ],

        "RecordDtModified" => date("Y-m-d H:i:s"),
            "RowVersion" => null,
            "_links" => null

        );
 
     return $data;
}
////////////////////////////////////////////////////////


///////////////////INSERT ORDERS FROM DB TO MINIMAX ////////////
function insert_order_minimax($token,$minimax_token,$id_db,$mysqli,$org){

    $api_endpoint = 'https://moj.minimax.si/SI/API/api/orgs/'.$org.'/orders';


    foreach($id_db as $m2_id){

        $headers = array('Content-Type:application/json','Authorization:Bearer '.$minimax_token->access_token);
        $ch = curl_init();

      try{
           $generated = generate_order(get_order_data_by_id($token,$m2_id),$mysqli);
           
               $product_string = json_encode($generated, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
               
               $ch = curl_init();
               curl_setopt($ch,CURLOPT_URL, $api_endpoint);
               curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
               curl_setopt($ch, CURLOPT_POSTFIELDS, $product_string);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
               curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
               curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
               $response = curl_exec($ch);
             
               $response = json_decode($response, TRUE);
               curl_close($ch);
               

               echo "<br>Order from db Database-name with ID: 60". $m2_id . " inserted into minimax!<br>";  //test
             

      }
      catch(Exception $e){
        echo 'Caught exception: ',  $e->getMessage(), "\n";
      }
    }


}


insert_order_minimax($tokens,$minimax_token,$id_db,$mysqli,$org);


?>