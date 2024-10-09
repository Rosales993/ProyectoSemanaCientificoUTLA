<?php
session_start();

$mensaje = "";    
if (isset($_POST['btnAccion'])) {
    switch($_POST['btnAccion']) {
        case "Agregar":
            if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {
                $ID = openssl_decrypt($_POST['id'], COD, KEY);
                //$mensaje .= "OK ID correcto: " . $ID . "<br/>";
            } else {
                $mensaje .= "Upps... ID incorrecto.<br/>";
                break; // Si hay error, termina aquí
            }

            if (is_string(openssl_decrypt($_POST['nombre'], COD, KEY))) {
                $NOMBRE = openssl_decrypt($_POST['nombre'], COD, KEY);
                //$mensaje .= "Ok NOMBRE: " . $NOMBRE . "<br/>";
            } else {
                $mensaje .= "Upps... algo pasa con el nombre.<br/>";
                break; // Si hay error, termina aquí
            }

            if (is_numeric(openssl_decrypt($_POST['cantidad'], COD, KEY))) {
                $CANTIDAD = openssl_decrypt($_POST['cantidad'], COD, KEY);
               // $mensaje .= "OK CANTIDAD: " . $CANTIDAD . "<br/>";
            } else {
                $mensaje .= "Upps... algo pasa con la cantidad.<br/>";
                break; // Si hay error, termina aquí
            }

            if (is_numeric(openssl_decrypt($_POST['precio'], COD, KEY))) {
                $PRECIO = openssl_decrypt($_POST['precio'], COD, KEY);
               // $mensaje .= "Ok precio: " . $PRECIO . "<br/>";
            } else {
                $mensaje .= "Upps... algo pasa con el precio.<br/>";
                break; // Si hay error, termina aquí
            }

            if(!isset($_SESSION['CARRITO'])){
                $producto = array(
                    'ID' => $ID,
                    'NOMBRE' => $NOMBRE,
                    'CANTIDAD' => $CANTIDAD,
                    'PRECIO' => $PRECIO
                );
                $_SESSION['CARRITO'][0] = $producto;
                $mensaje = "Producto agregado al carrito";
            } else {

                $idProductos=array_column($_SESSION['CARRITO'],"ID");
                if(in_array($ID,$idProductos)){
                    echo "<script>alert('El producto ya fue seleccionado...');</script>";
                }else{
                $NumeroProductos = count($_SESSION['CARRITO']);
                $producto = array(
                    'ID' => $ID,
                    'NOMBRE' => $NOMBRE,
                    'CANTIDAD' => $CANTIDAD,
                    'PRECIO' => $PRECIO
                     );
                    $_SESSION['CARRITO'][$NumeroProductos] = $producto;
                    $mensaje = "Producto agregado al carrito";
                }
            }
            //$mensaje=print_r( $_SESSION,true);

            
            break;

        case "Eliminar":
            if (is_numeric(openssl_decrypt($_POST['id'], COD, KEY))) {
                $ID = openssl_decrypt($_POST['id'], COD, KEY);

                foreach ($_SESSION['CARRITO'] as $indice => $producto) {
                    if ($producto['ID'] == $ID) {
                        unset($_SESSION['CARRITO'][$indice]);
                        $mensaje .= "Producto eliminado del carrito.<br/>";
                        break; // Sale del foreach después de eliminar el producto
                    }
                }
            } else {
                $mensaje .= "Upps... ID incorrecto: " . $ID . "<br/>";
            }
            break;
    }
}
?>
