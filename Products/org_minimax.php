<?php

//-------------------------------------------Get organisation from MINIMAX via API----------------------------------//


include_once("../minimax.php");

$token = get_minimax_token();

function get_organisation($token){

    $request = array(
        'http' => array(
            'method'=> 'GET',
            'header'=> 'Authorization: Bearer ' . $token->access_token,
            'timeout'=> 100000
            )
        );
    if (!$response = file_get_contents('https://moj.minimax.si/SI/api/api/currentuser/orgs', false, stream_context_create($request))) {
        die('orgs error');
    }
    
    $orgs = json_decode($response, true);
    

    //Organization in minimax.si ID
    try
    {
        foreach($orgs["Rows"] as $key){
            if($key["Organisation"]["Name"] == "ORG-ID")
            $organisation = ($key["Organisation"]["ID"]);
        }
       
    }
    catch(Exception $e)
    {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    
   return $organisation;

}



?>
