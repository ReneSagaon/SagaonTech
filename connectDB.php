<?php

function mySQLConnect(){
    $servername = "localhost";
    $username = "sagacnzt_DBadmin";
    $password = "adminDB#001";
    $dbname = "sagacnzt_sagaonTech_productos";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn -> set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    return $conn;
}

function runQuery($query){
    $socket = mySQLConnect();
    $result = $socket->query($query);
}

function getTable($query){
    $socket = mySQLConnect();
    $result = $socket->query($query);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $table[] = $row;
        }
    }else{
        return "0";
    }
    
    if (count($table) < 1){
        echo "0";
    }
    return $table;
}

?>