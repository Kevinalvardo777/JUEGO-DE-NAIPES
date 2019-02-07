<?php

header("Content-Type: application/json;charset=utf-8");
require '../../proyectos/instaticket/plugin/escpos-php/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

if ($_POST['opcion'] == "imprimirCodigo") {
    if (!empty($_POST['codigo'])) {
    $connector = new WindowsPrintConnector("LR2000");
    $printer = new Printer($connector);
$printer -> initialize();
    try {
        $img = EscposImage::load("img/LOGO INSTATICKET.png", FALSE);
        $printer->setJustification(Printer::JUSTIFY_CENTER);
        $printer->text("----------------------------------\n");
        $printer->setTextSize(2, 2);
        $printer->setFont(Printer::FONT_A);
        $printer->bitImage($img, Printer::IMG_DOUBLE_WIDTH);
        $printer->setTextSize(1, 1);
        $printer->text("----------------------------------\n");
        $printer->setTextSize(2, 2);
        $printer->text($_POST['nombreEvento'] . "\n");
        $printer->setTextSize(1, 1);
        $printer->text("TU ENTRADA A LA SUERTE\n");
        $printer->text($_POST['fechaActual'] . "\n");
        $printer->feed(2);
        $printer->text("PREMIO: " . $_POST['premioNombre'] . "\n");
        $printer->text("CODIGO: " . $_POST['codigo'] . "\n");
        $printer->barcode($_POST['codigo'], Printer::BARCODE_CODE39);
        $printer->feed(10);
        $printer->cut(Printer::CUT_FULL, 3, "LR2000");
        $printer->close();

        echo json_encode(array("data" => array("mensaje" => "Exitosa","tipo"=>"success")));
    } catch (Exception $e) {
        $printer->close();
        echo json_encode(array("data" => array("mensaje" => $e,"tipo"=>"error")));
    }
    } else {
        echo json_encode(array("data" => array("mensaje" => "No se pudo realizar la impresión faltan datos")));
    }
} else {
    echo json_encode(array("data" => array("mensaje" => "Ninguna seleccion","tipo"=>"error")));
}