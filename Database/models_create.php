<?php

include("Conn.php");


function token_minimax($mysqli){

    $sql = "CREATE TABLE minimax_tokens(
        scope varchar(64) NOT NULL PRIMARY KEY ,
        access_token text NOT NULL,
        token_type VARCHAR(64) NOT NULL,
        expires_in VARCHAR(64) NOT NULL,
        expires_at BigInt NOT NULL,
        refresh_token text NOT NULL
    )";
    
    //check if table exits in database
    $result = $mysqli->query("SHOW TABLES LIKE 'minimax_tokens'");
    
    if($mysqli->query($sql) === true){
        echo "Table minimax_tokens created successfully./n";
    } else if($result->num_rows == 1){
        echo "Table minimax_tokens already exists!";
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
     
    // Close connection
    $mysqli->close();


}


function minimax_credentials($mysqli){

    $sql = "CREATE TABLE  minimax_credentials(
        client_id varchar(64) NOT NULL PRIMARY KEY,
        client_secret VARCHAR(64) NOT NULL,
        username VARCHAR(64) NOT NULL,
        pass VARCHAR(64) NOT NULL
    )";
    
    //check if table exits in database
    $result = $mysqli->query("SHOW TABLES LIKE 'minimax_credentials'");
    
    if($mysqli->query($sql) === true){
        echo "Table minimax_credentials created successfully.";
    } else if($result->num_rows == 1){
        echo "Table minimax_credentials already exists!";
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
     
    // Close connection
    $mysqli->close();


}


function orders($mysqli){

    $sql = "CREATE TABLE orders(
        mag_order_id int NOT NULL PRIMARY KEY,
        minimax_order_id int,
        order_date date NOT NULL,
        order_status VARCHAR(64) NOT NULL,
        total float NOT NULL
    )";
    
    //check if table exits in database
    $result = $mysqli->query("SHOW TABLES LIKE 'orders'");
    
    if($mysqli->query($sql) === true){
        echo "Table orders created successfully.";
    } else if($result->num_rows == 1){
        echo "Table orders already exists!";
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
     
    // Close connection
    $mysqli->close();


}

orders($mysqli);

function minimax_items($mysqli){

    $sql = "CREATE TABLE minimax_items(
        item_id int NOT NULL PRIMARY KEY,
        item_name NVARCHAR(100) NOT NULL, 
        code VARCHAR(64) NOT NULL,
        price float NOT NULL,
        stock int NOT NULL
    ) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci   ";
    
    //check if table exits in database
    $result = $mysqli->query("SHOW TABLES LIKE 'minimax_items'");
    
    if($mysqli->query($sql) === true){
        echo "Table minimax_items created successfully.";
    } else if($result->num_rows == 1){
        echo "Table minimax_items already exists!";
    } else{
        echo "ERROR: Could not able to execute $sql. " . $mysqli->error;
    }
     
    // Close connection
    $mysqli->close();


}




?>
