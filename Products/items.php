<?php

include_once("../magento.php");
include_once("insert.php");
include_once("../Database/Conn.php");

//Check if the product with SKU exists in Magento

function check_if_exists($token,$id){

    $product_url = 'https://your-domain/rest/V1/products/'.$id;

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $product_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8", "Authorization: Bearer " . $token));

    $result = curl_exec($ch);

    $orders = json_decode($result);

    $sku = $orders->sku;

    //check and print what is there and what is not
    if(isset($sku)){
      return true;
    }
    else{
        return false;
    }
    
  
   
}


//get all minimax items from database 
function get_items_database($mysqli){

  $sql = $mysqli->query("SELECT * FROM minimax_items");

  $list = array();

  if ($sql->num_rows > 0) {
    // output data of each row
    while($result = $sql->fetch_assoc()) {
     
      $list[] = $result;
     
    }
  } else {
    echo "0 results";
  }
 
  $object = (object)$list;

  return $object;
}

 $res = get_items_database($mysqli);
 

//Call insert_product_magento function to insert or update qty of product 
function insert_update_items_magento($res,$token){
  
  if($res !== null){
    foreach($res as $row){
      if(check_if_exists($token,$row["code"]) == false){
        
        insert_product_magento($token,$row["code"], $row["item_name"], $row["price"], $row["stock"]);
      }    
    
    }
  }
  else {
    echo "No items in database to update";
  }

}


//insert item that doesn't exist in magento but exists in database from minimax
insert_update_items_magento($res,$tokens);


?>