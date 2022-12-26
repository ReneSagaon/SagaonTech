<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require('PHPMailer/src/PHPMailer.php');
require('PHPMailer/src/Exception.php');
require('PHPMailer/src/SMTP.php');
require('fpdf/fpdf.php');
require('password.php');

class PDF extends FPDF{
    //Encabezado
    function Header(){
        // Logo
        $this->Image(__DIR__.'/img/logo.png',25,-8,34);
        // Arial bold 15
        $this->SetFont('Arial','B',15);
        // Movernos a la derecha
        $this->Cell(79);
        // Título
        // Salto de línea
        $this->Ln(12);
    }
    
    // Pie de página
    function Footer()
    {
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0, 10, $this->PageNo().'/{nb}', 0, 0, 'C');
    }

}

function sendEmail($nombre, $apellido, $eMail, $sku){
    
    $query = "SELECT * FROM `productos`;";
    $products = getTable($query);
    $productData = filterSKU2($products, $sku);
    
    @$pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->SetFont('Times','',12);
    $pdf->Image(__DIR__.'/img/waterMark.jpg', 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());
    
    $message  = "Estimado ".$nombre;
    if($apellido != "-"){
        $message .= " ".$apellido;
    }
    $message .= ". <br> En el archivo adjunto dentro de este correo se encuentra
        la informacion solicitada acerca del producto ".$productData['nombre'];
    
    /* Formating PDF */
    $dato = date("d/m/y");
    $pdf->Cell(140);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,6,'Fecha: '.$dato,10,1);

    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',12);
    $nombreTmp = "Estimad@ ".$nombre;
    if($apellido != "-"){
        $nombreTmp .= " ".$apellido;
    }
    $pdf->Cell(0,10,utf8_decode($nombreTmp),10,1);
    
    $pdf->Ln(5);
    $pdf->Cell(15);
    $pdf->SetFont('Arial','',11.18);
    $pdf->Cell(0,0,utf8_decode("En base a su solicitud pongo a tu disposición la cotización de:"),0,1);
    
    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,15,utf8_decode($productData['nombre']),10,1);

    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,6,utf8_decode("Descripción:"),10,1);
    
    $pdf->Cell(15);
    $pdf->SetFont('Arial','',12);
    $pdf->MultiCell(160, 5, utf8_decode($productData['descripcion']), 0);
    
    $imgLink = __DIR__.'/img/'.$productData['imagen'];
    $pdf->Cell(0,0,$pdf->Image($imgLink,85,185,75),10,1);
    
    $pdf->Ln(200);//CAMBIO para espacio entre la tabla de informacion y las imagenes
    $pdf->Cell(15);
    $pdf->Ln(5);
    $pdf->Cell(15);
    $pdf->SetFont('Arial','B',12);
    $pdf->Image(__DIR__.'/img/waterMark.jpg', 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());
    $pdf->Cell(0,6,utf8_decode("Cotización"),10,1);
    
    $total = (float)$productData['precio'];
    $iva = $total - ($total / 1.16);
    $iva = round($iva,2);
    $precio = $total - $iva;
    
    
    
    $pdf->Cell(15);
    $pdf->MultiCell(160,9,utf8_decode("Concepto:  ".$productData['nombre']),1);
    $pdf->Cell(15);
    $pdf->MultiCell(160,9,utf8_decode("Precio:  $".$precio." MXN"),1);
    $pdf->Cell(15);
    $pdf->MultiCell(160,9,utf8_decode("IVA:  $".$iva." MXN"),1);
    $pdf->Cell(15);
    $pdf->MultiCell(160,9,utf8_decode("Unidades:  1"),1);
    $pdf->Cell(15);
    $pdf->MultiCell(160,9,utf8_decode("Total:  $".$total." MXN"),1);
    $pdf->Ln(5);

    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(15);
    $pdf->Cell(0,8,utf8_decode("Datos de pago:"),10,1);


    $pdf->SetFont('Arial','I',12);
    $pdf->Cell(15);
    $pdf->Cell(0,6,utf8_decode("BANCO Banamex"),10,1);

    $pdf->SetFont('Arial','',12);
     $pdf->Cell(15);
    $pdf->Cell(0,6,utf8_decode("Beneficiario: César Daniel Sagaón Bustos"),10,1);

    $pdf->SetFont('Arial','',12);
     $pdf->Cell(15);
    $pdf->Cell(0,6,utf8_decode("RFC: SABC911120AA2"),10,1);

    $pdf->SetFont('Arial','',12);
     $pdf->Cell(15);
    $pdf->Cell(0,6,utf8_decode("Sucursal: 7008"),10,1);

    $pdf->SetFont('Arial','',12);
     $pdf->Cell(15);
    $pdf->Cell(0,6,utf8_decode("Cuenta: 1589311"),10,1);

    $pdf->SetFont('Arial','',12);
     $pdf->Cell(15);
    $pdf->Cell(0,6,utf8_decode("CLABE: 002045700815893118 "),10,1);

    $pdf->Ln(5);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(15);

    $pdf->MultiCell(160,5,utf8_decode("Una vez realizado el pago favor de ".
                                    "enviar a ventas@sagaon.tech una foto o ".
                                    "scanner del recibo legible en formato PDF, ".
                                    "JPG o PNG.Espero que esta información sea".
                                    "de utilidad y poder contribuir al".
                                    "beneficio de su empresa. Quedo a sus ".
                                    "ordenes para cualquier duda o aclaración."),0);
    $pdf->Ln(2);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(15);
    $pdf->Cell(0,15,utf8_decode("Sagaon Tech."),10,1);
    $pdf->Ln(4);
    $pdf->SetFont('Arial','',12);
    $pdf->Cell(15);
    /* ---------------- End of formating PDF ------------  */
    
    $myPDF = $pdf->Output('', 'S');
    
    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 0;                                        // Disable verbose debug output
        $mail->isSMTP();                                             // Set mailer to use SMTP
        $mail->Host       = 'cotizacion.sagaon.tech';                      // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
        $mail->Username   = 'ventas@cotizacion.sagaon.tech';                    // SMTP username
        $mail->Password   = MAIL_PASSWORD;                                     // SMTP password
        $mail->SMTPSecure = 'tls';                                   // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                     // TCP port to connect to

        //Recipients
        $mail->setFrom('ventas@cotizacion.sagaon.tech', 'Sagaon Tech');
        $mail->addReplyTo('ventas@sagaon.tech', 'Sagaon Tech');
        $mail->addAddress($eMail, $nombre);     // Add a recipient
        $mail->addAddress("ventas@sagaon.tech", "Ventas");
        $mail->addAddress("c@sagaon.tech", "Cesar");

        // Content
        $mail->isHTML(true);                                 // Set email format to HTML
        $mail->Subject = 'Cotizacion SagaonTech';
        $mail->Body    = $message;
        
        $mail->addStringAttachment($myPDF, 'cotizacionSagaonTech.pdf');

        $mail->send();
        return "1";
    } catch (Exception $e) {
        return "0, Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}

function filterSKU2($array, $sku){
    $found = 0;
    foreach($array as $row){
        if($row['sku'] == $sku){
            return $row;
        }
    }
    return $found;
}

/*
@$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
$pdf->Image('img/WaterMark.png', 0, 0, $pdf->GetPageWidth(), $pdf->GetPageHeight());

$pdf->Cell(100,10,"Hola Mundo",0,0,'c');
$pdf->Cell(100,10,strval($pdf->GetPageWidth())."---".strval($pdf->GetPageHeight()),
    0,0,'c');

//$myPDF = $pdf->Output('', 'S');
$pdf->Output();

$to = "casmin137137@gmail.com";
$subject = "Mail Prueba";

$message = "<h1> Hi There! </h1> <p> Thanks for testing </p>";

$headers = "From: ventas@sagaon.tech" . "\r\n" .
    "X-Mailer: PHP/" . phpversion() . "\r\n" .
    "Content-type: text/html\r\n";

// $flag = mail($to, $subject, $message, $headers);
$flag = false;

/*if( $flag == true ){
    echo "Mail Sent";
}else{
    echo "Mail NOT Sent";
}*/

// Instantiation and passing `true` enables exceptions
/*
$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->SMTPDebug = 2;                                        // Disable verbose debug output
        $mail->isSMTP();                                             // Set mailer to use SMTP
        $mail->Host       = 'cotizacion.sagaon.tech';                      // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                    // Enable SMTP authentication
        $mail->Username   = 'ventas@cotizacion.sagaon.tech';                    // SMTP username
        $mail->Password   = 'sagaonTech123';                                     // SMTP password
        $mail->SMTPSecure = 'tls';                                   // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = 587;                                     // TCP port to connect to

        //Recipients
        $mail->setFrom('ventas@cotizacion.sagaon.tech', 'Sagaon Tech');
        $mail->addReplyTo('ventas@sagaon.tech', 'First Last');
        $mail->addAddress('casmin137137@gmail.com', 'Rene');     // Add a recipient

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = 'Prueba de GMail';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    
    //$mail->addStringAttachment($myPDF, 'myFile.pdf');

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

*/


?>