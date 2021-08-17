<?php

include_once("../minimax.php");
include_once("../magento.php");
include_once("org_minimax.php");
include_once("items.php");


$token_minimax = get_minimax_token();
$org = get_organisation($token_minimax);

$items = get_items_database($mysqli);

function get_stocks($token_minimax,$org){

 $url = "https://moj.minimax.si/SI/API/api/orgs/".$org."/stocks";

 $request = array(
    'http' => array(
        'method'=> 'GET',
        'header'=> 'Authorization: Bearer ' . $token_minimax->access_token,
        'timeout'=> 100000
        )
    );
if (!$response = file_get_contents($url, false, stream_context_create($request))) {
    die('orgs error');
}

$stocks = json_decode($response, true);

return $stocks;

}



//update all product qty's in magento from minimax
function update_magento_stocks($token,$items){

    $headers = array('Content-Type:application/json','Authorization:Bearer '.$token);

    $ch = curl_init();

    foreach($items as $item){
        
        try {
            $apiProdUrl = "https://your-domain/rest/V1/products/".$item["code"];
            
            $product = [ 
                "product" => [ 
                    "sku" => $item["code"],
                    "extension_attributes"=> [ 
                        "stock_item"=> [ 
                            "qty"=> $item["stock"],
                            "is_in_stock"=> 1
                        ]
                    ]
                ]
                ];
    
    
        $product_string = json_encode($product);
    
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $apiProdUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $product_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
     
        $response = json_decode($response, TRUE);
        curl_close($ch);
    
        echo "Item with sku: ". $item["code"] . " updated into magento!";
    
        }
        catch(Exception $e){
            echo 'Caught exception: ',  $e->getMessage(), "\n";
           }

    }

    
   


}

?>