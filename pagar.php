<?php
include 'global/config.php';    //datos de la base de datos para su funcionamiento
include 'global/conexion.php';  //conexion con la base de datos 
include 'carrito.php';
include 'templates/cabecera.php';
?>

<?php
    if($_POST){
        $total=0;
        $SID=session_id();
        $Correo=$_POST['email'];
            //SE CREO TABLA TBL DETALLE VENTA DONDE SE INGRESA LA INFORMACION QUE EL CLIENTE SOLICITA TODO LAS INFORMACION PROVIENE DEL CARRITO DE COMPRAS

        foreach($_SESSION['CARRITO'] as $indice =>$producto){ 
            $total=$total+($producto['PRECIO']*$producto['CANTIDAD']);
        }
            $sentencia = $pdo->prepare("INSERT INTO `tblventas`
            (`ID`, `ClaveTransaccion`, `PaypalDatos`, `Fecha`, `Correo`, `Total`, `status`)
            VALUES (NULL, :ClaveTransaccion, '', NOW(), :Correo, :Total, 'pendiente');");
            
            $sentencia->bindParam(":ClaveTransaccion", $SID);
            $sentencia->bindParam(":Correo", $Correo);
            $sentencia->bindParam(":Total", $total);
            
            $sentencia->execute();
            $idVenta=$pdo->lastInsertId();

                foreach($_SESSION['CARRITO'] as $indice =>$producto){ 

                    $sentencia = $pdo->prepare("INSERT INTO `tbldetalleventa` (`ID`, `IDVENTA`, `IDPRODUCTO`, `PRECIOUNITARIO`, `CANTIDAD`, `DESCARGADO`) 
                    VALUES (NULL,:IDVENTA,:IDPRODUCTO,:PRECIOUNITARIO,:CANTIDAD, '0');");

                    $sentencia->bindParam(":IDVENTA", $idVenta);
                    $sentencia->bindParam(":IDPRODUCTO", $producto['ID']);
                    $sentencia->bindParam(":PRECIOUNITARIO", $producto['PRECIO']);
                    $sentencia->bindParam(":CANTIDAD", $producto['CANTIDAD']);
                    
                    $sentencia->execute();
                }
       // echo "<h3>".$total."</h3>";
    } 
    
?>
<!-- Include the PayPal JavaScript SDK -->



<div class="jumbotron text-center">
    <h1 class="display-4">!Paso Final ¡</h1>
    
    <hr class="my-4">

        <p class="lead"> Estas a punto de pagar con paypal la cantidad de:
            <h4>$<?php echo number_format($total,2); ?> </h4>
        </p>
           


        <!DOCTYPE html>
<html lang="en">

<head>
    <!-- Add meta tags for mobile and IE -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title> PayPal Checkout Integration | Button Styles </title> 
</head>

<body>
    <!-- Set up a container element for the button -->
    <div id="paypal-button-container"></div>

    <!-- Include the PayPal JavaScript SDK -->
    <script src="https://www.paypal.com/sdk/js?client-id=AZend4Nde71mK7Udkhc1EKt6oS149OINFrVWw0fxvVEAMgii3KX2CQypdmpKO2UwoBGsVyViojR02Uvj&currency=USD"></script>
    <script>
        // Render the PayPal button into #paypal-button-container
        paypal.Buttons({

            style: {
                color:  'blue',
                shape:  'pill',
                label:  'pay',
                height: 40
            },
            
            // Call your server to set up the transaction
            createOrder: function(data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: "100" // Monto de la transacción
                        }
                    }]
                });
            },

          // Manejar la aprobación de la transacción
          onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    console.log(details);
                    alert("Transacción exitosa por " + details.payer.name.given_name);
                });
            },

            // Manejar cancelación de la transacción
            onCancel: function(data) {
                alert("Pago cancelado");
            },

            // Manejar errores
            onError: function(err) {
                console.error("Error en la transacción:", err);
                alert("Error procesando el pago. Intenta de nuevo.");
            }
        }).render('#paypal-button-container');
    </script>
</body>

</html>
    
              
        <p> Los productos serán enviados una vez que se procese el pago<br/>
             <strong> ¡Cualquier dudad o consulta comuniquese por los siguientes <br/>medios! email:rosaleschavezj50@gmail.com, TEL:7081-3771 </strong>
         </p>
    </hr>
</div>




<?php include 'templates/pie.php';?>