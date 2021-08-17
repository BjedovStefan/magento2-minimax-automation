<?php

include("../minimax.php");

$token = get_minimax_token();

function create_minimax_customer($customer,$token){

    $api_endpoint = "https://moj.minimax.si/SI/api/api/orgs/ORG_ID/customers";
    
    $headers = array('Content-Type:application/json','Authorization:Bearer '.$token);
    
    $ch = curl_init();
    $customer_string = json_encode($customer);

    try{
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $api_endpoint);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $customer_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $response = curl_exec($ch);
     
        $response = json_decode($response, TRUE);
        print_r($response);
        curl_close($ch);
    }
    catch(Exception $e){
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    
}


function new_customer($name, $address, $postal_code, $city, $email){

    $customer = [ 
        "CustomerId"=> 1222,
        "Code"=> $email,
        "Name"=> $name,
        "Address"=> $address,
        "PostalCode"=> $postal_code,
        "City"=> $city,
        "Country"=> [ 
            "ID"=> 192,
            "Name"=> null,
            "ResourceUrl"=> null
        ],
        "CountryName"=> NULL,
        "TaxNumber"=> null,
        "RegistrationNumber"=> null,
        "VATIdentificationNumber"=> null,
        "SubjectToVAT"=> "N",
        "Currency"=> [ 
            "ID"=> 7,
            "Name"=> null,
            "ResourceUrl"=> null
        ],
        "ExpirationDays"=> 0,
        "RebatePercent"=> 0.0,
        "WebSiteURL"=> null,
        "EInvoiceIssuing"=> "N",
        "InternalCustomerNumber"=> null,
        "Usage"=> "D",
        "RecordDtModified"=> "2021-02-10T21:20:00",
        "RowVersion"=> null
    ];

   print_r($customer);
    //create_minimax_customer($customer,$token);

}


?>