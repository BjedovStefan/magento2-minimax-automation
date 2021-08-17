
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(-1);


    //Magento admin credentials 

    $usr = [
        'username' => 'magento-username',
        'password' => 'magento-password'
    ];

    $ch = curl_init('https://your-domain/index.php/rest/V1/integration/admin/token');

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($usr));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Content-Length: '. strlen(json_encode($usr))]);

    $token = curl_exec($ch);

    $tokens = json_decode($token);

 

?>