<?php

//---------------------------------------------Insert products directly to magento 2 via API----------------------------------//

include_once("../magento.php");

function insert_product_magento($token,$itemSKU, $itemName, $sellingPrice, $qty){

    $apiProdUrl = "https://your-domain/rest/V1/products/";

    $headers = array('Content-Type:application/json','Authorization:Bearer '.$token);

    $ch = curl_init();

    try {

        $product = [ 
            "product" => [ 
                "sku" => $itemSKU,
                "name"=> $itemName,
                "price"=> $sellingPrice,
                "status"=> 1,
                "type_id"=> "simple",
                "weight"=> 0,
                "attribute_set_id"=> 4,
                "extension_attributes"=> [ 
                    "stock_item"=> [ 
                        "qty"=> $qty,
                        "is_in_stock"=> 1
                    ]
                ]
            ]
            ];


    $product_string = json_encode($product);

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $apiProdUrl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $product_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
 
    $response = json_decode($response, TRUE);
    curl_close($ch);

    echo "Item with sku: ". $itemSKU . " inserted into magento!";

    }
   catch(Exception $e){
    echo 'Caught exception: ',  $e->getMessage(), "\n";
   }
       



}



?>