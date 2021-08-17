<?php

include("customer.php");

function get_order_data($orderid,$token){

    $order_url = 'https://your-domain/rest/V1/orders/'.$orderid;
    $headers = array('Content-Type:application/json','Authorization:Bearer '.$token);
   
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $order_url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json; charset=utf-8", "Authorization: Bearer " . $token));

    $result = curl_exec($ch);

    $magentoOrder = json_decode($result, true);

       if (!isset($magentoOrder['extension_attributes']['shipping_assignments']))
        return null;

       $shippingAssignments = $magentoOrder['extension_attributes']['shipping_assignments'];
       foreach ($shippingAssignments as $shippingAssignment) {
          if (isset($shippingAssignment['shipping']['address'])) 
          {
               $city = $shippingAssignment->city;
               $email = $shippingAssignment->email;
               $firstname = $shippingAssignment->firstname;
               $lastname = $shippingAssignment->lastname;
               $postcode = $shippingAssignment->postcode;
               $street = $shippingAssignment->street;
               $fullname = $firstname . " " . $lastname;

               new_customer($fullname,$street,$postcode,$city,$email);
          }
          else
          {
              echo "Something wrong!";
          }
       
       }
   


}

get_order_data(000000061,$token); //test




?>