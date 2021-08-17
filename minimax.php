<?php

function get_minimax_token(){


    //minimax.si account credentials 

    $params = array(
        'client_id'=> 'clientID',
        'client_secret'=> 'clientSecret',
        'grant_type'=> 'password',
        'username'=> 'username',
        'password'=> 'password',
        'scope' => 'minimax.si');
    
    $request = array(
        'http' => array(
            'method'=> 'POST',
            'header'=> array(
                'Content-type: application/x-www-form-urlencoded',
                ),
            'content'=> http_build_query($params),
            'timeout'=> 1000000
            )
        );
    
    if (!$response = file_get_contents('https://moj.minimax.si/SI/aut/oauth20/token', false, stream_context_create($request))) {        
        die('auth error');
    }
    
    $token = json_decode($response);

    return $token;
}


?>