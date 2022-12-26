<?php
require("connectDB.php");

function printArray($array){
    $text = "<table border='1' style='width:100%'>
                <tr>
                    <th> ID </th>
                    <th> Nombre </th>
                    <th> Apellido </th>
                    <th> Correo </th>
                    <th> Telefono </th>
                    <th> SKU </th>
                    <th> Fecha </th>
                </tr>";
    foreach($array as $row){
        $text .= "<tr>";
        foreach($row as $cell){
            $text .= "<td>".$cell."</td>";
        }
        $text .= "</tr>";
    }
    $text .= "</table>";
    return $text;
}

$query = "SELECT * FROM `logs`;";
$logs = getTable($query);

if(isset($_POST["name"]) and isset ($_POST["password"])){
    if($_POST["name"]  == "cesar" and $_POST["password"] == "sagaon.tech"){
        echo printArray($logs);
    }else{
        echo "Usuario Invalido";
    }
}else{
    echo "Usuario Invalido";
}


?>