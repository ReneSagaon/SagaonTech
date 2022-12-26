<?php

header("Content-Type: text/html;charset=utf-8");

require("../sendMail.php");
require("../connectDB.php");

if($_POST['command'] == "formatTable"){
    if( isset($_POST['data']) ){
        $data = json_decode($_POST['data'], true);
        $lengthData = count($data);
        $lengthData1 = count($data[0]);
        $header = array_shift($data);
        $returnText = printArray($data);
        echo $returnText;
    }else{
        echo "0";
    }

}else if($_POST['command'] == "getProduct"){
    if( isset($_POST['sku']) ){
        $sku = $_POST['sku'];
        $query = "SELECT * FROM `productos`;";
        $products = getTable($query);
        if(count($products) < 1){
            echo "0, aqui quedo";
        }
        $productData = filterSKU($products, $sku);
        if( is_array($productData) ){
            $text = "1-//-".utf8_decode($productData['nombre'])." - ".$productData['modelo']."-//-".nl2br($productData['descripcion']).
                    "-//-".$productData['imagen']."-//-".$productData['precio'];
            echo $text;
        }else{
            echo "0, No hay conexion con DB";
        }
    }else{
        echo "0, SKU incorrect";
    }

}else if($_POST['command'] == "sendMail"){
    if( isset($_POST['sku']) and isset($_POST['nombre']) and isset($_POST['mail'])
        and isset($_POST['telefono']) and isset($_POST['apellido']) ){
        $result = sendEmail($_POST['nombre'], $_POST['apellido'], $_POST['mail'], $_POST['sku']);
        if ($result == "1"){
            saveLogs($_POST['nombre'], $_POST['apellido'], $_POST['mail'],
                $_POST['telefono'], $_POST['sku']);
            echo "1-//-El e-mail se envio exitosamente";
        }else{
            echo "0-//-e-mail no enviado, correo invalido";
        }
    }else{
        echo "0";
    }

}else{
    echo "0, Not valid value";
}

function printArray($array){
    $text = "<table border='1' style='width:100%'>
                <tr>
                    <th> SKU </th>
                    <th> Nombre </th>
                    <th> Modelo </th>
                    <th> Descripcion </th>
                    <th> Imagen </th>
                    <th> Precio </th>
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


function filterSKU($array, $sku){
    $found = 0;
    foreach($array as $row){
        if($row['sku'] == $sku){
            return $row;
        }
    }
    return $found;
}

function saveLogs($nombre, $apellido, $mail, $telefono, $sku){
    $query = "INSERT INTO `logs` (`nombre`, `apellido`, `correo`, `telefono`, `extraField`)
            VALUES('".$nombre."', '".$apellido."', '".$mail."', '".$telefono."', '".$sku."')";
    runQuery($query);
}

?>