<?php

//include_once("models_create.php");
include_once("../minimax.php");
include_once("../magento.php");
include_once("../Products/stocks.php");

$token_minimax = get_minimax_token();
$stocks = get_stocks($token_minimax,$org);

function init($mysqli,$token_minimax,$stocks){

  insert_tokens_minimax($mysqli, $token_minimax);
  insert_minimax_items($mysqli, $stocks);
}


//insert tokens gathered for authentication from minimax into db
function insert_tokens_minimax($mysqli, $token_minimax){

  if($token_minimax->scope == "minimax.si"){
    $scope = $token_minimax->scope;
    $accessToken = $token_minimax->access_token;
    $tokenType = $token_minimax->token_type;
    $expiresIn = $token_minimax->expires_in;
    $refreshToken = $token_minimax->refresh_token;

    $insert = "INSERT INTO minimax_tokens (scope,access_token,token_type,expires_in,refresh_token) VALUES ('$scope','$accessToken','$tokenType','$expiresIn','$refreshToken')";

    $diff = $mysqli->query("SELECT COUNT(1) FROM minimax_tokens WHERE scope='minimax.si'");

    $update = "UPDATE minimax_tokens SET access_token = '$accessToken', token_type = '$tokenType', expires_in = '$expiresIn', refresh_token = '$refreshToken' WHERE scope = $scope";

    
    if($mysqli->query($insert) === true){
      echo "Values inserted into minimax_tokens table successfully.";
    }
    else if($diff->num_rows == 1){
      $mysqli->query($update);
      echo "<br>Token update successfull!<br>";
    }
    else{
      echo "ERROR: Could not able to execute $insert. " . $mysqli->error;
    }
    
  }
  else{
    echo "Scope of this token is on some other API not MINIMAX.SI";
  }

}



//insert to db minimax items and stocks
function insert_minimax_items($mysqli, $stocks){
  
  try{
    
    foreach($stocks["Rows"] as $rows)
    {
 
        $itemID = $rows["Item"]["ID"];
        $itemCode = $rows["ItemCode"];
        $itemName = $rows["ItemName"];
        $itemPrice = $rows["SellingPrice"];
        $itemQty = $rows["Quantity"];

        // query for insert into db 
        $insert = "INSERT INTO minimax_items (item_id,item_name,code,price,stock) VALUES ('$itemID','$itemName','$itemCode','$itemPrice','$itemQty')";
   
        //does record with those credentials already exists in db 
        $diff = $mysqli->query("SELECT * FROM minimax_items WHERE item_id='$itemID'");
  
        // query to update db 
        $update = "UPDATE minimax_items SET stock = '$itemQty' WHERE item_id='$itemID'";


        if($mysqli->query($insert) === true){
          echo "Values inserted into minimax_items table successfully.<br>";
        }
        else if($diff->num_rows > 0){
           $mysqli->query($update);
           echo "Updated item:" .$itemName. " with stock: ".$itemQty." successfull!<br>";
         }
        else{
          echo "ERROR: Could not able to execute $insert. " . $mysqli->error;
        }
        
        
    }
    echo '<br>Data stock insert successfull!<br><br>';

 }
 catch(Exception $e)
 {
     echo 'Data stock insert not successfull! Error: ',  $e->getMessage(), "\n";
 }
  
}



  //insert orders from magento to db 
function insert_orders($mysqli, $mag_order_id, $minimax_order_id, $date, $status, $total){

    // query for insert into db 
    $insert = "INSERT INTO orders (mag_order_id,minimax_order_id,order_date,order_status,total) VALUES ('$mag_order_id','$minimax_order_id','$date','$status','$total')";   
   

   $diff = $mysqli->query("SELECT COUNT(1) FROM orders WHERE mag_order_id='$mag_order_id'");

   // query to update db 
   $update = "UPDATE orders SET order_status = '$status' WHERE mag_order_id = $mag_order_id";
  
  
    if($mysqli->query($insert) === true){
      echo "Orders from Magento inserted into orders table successfully.<br>";
    }
    else if($diff->num_rows == 1){
      $mysqli->query($update);
      echo "Update successfull!<br>";
    }
    else{
      echo "ERROR: Could not able to execute $insert. " . $mysqli->error;
    }
  
}



//call all functions at once
init($mysqli,$token_minimax,$stocks);
  


?>
