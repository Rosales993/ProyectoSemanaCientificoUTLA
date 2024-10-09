<?php
include 'global/config.php';    //datos de la base de datos para su funcionamiento
include 'global/conexion.php';  //conexion con la base de datos 
include 'carrito.php';
include 'templates/cabecera.php';
?>

            <br>
            <?php if($mensaje!="") ?>
                <div class="alert alert-success" role="alert" style="color: green;">

                <?php echo($mensaje);?>
                <a href="mostrarCarrito.php" class="badge badge-success" style="background-color: green; color: white; padding: 5px; border-radius: 5px;">Ver carrito</a>
                </div>
            <?php ?>
            <div class="row">
                <?php
                    //este campo se agrego destro del div class"row", para poder llamar los datos atravez de la base de datos, i implementando boostrap y un foreach
                    $sentencia=$pdo->prepare("SELECT * FROM `tblproductos` ORDER BY `Imagen` ASC");
                    $sentencia->execute();
                    $listaProductos=$sentencia->fetchAll(PDO::FETCH_ASSOC);
                    //print_r($listaProductos);
                ?>

                <?php   foreach($listaProductos as $producto){ ?>  
                    <div class="col-3">
                        <div class="card">
                            <!--se implemento "dat-toggle", "data-trigger","data-contemt" como parte de boostrap para realizar que la imagen tenga la descripcion de la base de datos -->
                            <!--tambien se modifico title 'Descripcion' para mostrar la descripcion al pasar el cursor sobre la imagen que esta bajo el cursor -->  
                            <img 
                            title="<?php echo $producto['Descripcion'];?>"
                            alt="<?php echo $producto['Nombre'];?>"
                            class="card-img-top" 
                            src= "<?php echo $producto['Imagen'];?>"             
                            data-toggle="popover" 
                            data-trigger="hover"
                            data-content="<?php echo $producto['Descripcion'];?>"
                            height="317px"
                            >


                            <div class="card-body">
                                <span><?php echo $producto['Nombre']?></span>
                                <h5 class="card-title">$<?php echo $producto['Precio']?></h5>
                                <p class="card-text">Descripción</p>
                                
                                <form action="" method="post">
                                    <!--solicitud al carrito de compras de lo que el usuario esta solicitando atravez del formulario dependiendo de la base de datos-->
                                    <input type="hidden" name="id" id="id" value="<?php echo openssl_encrypt( $producto['ID'],COD,KEY);?>">
                                    <input type="hidden" name="nombre" id="nombre" value="<?php echo openssl_encrypt( $producto['Nombre'],COD,KEY);?>">
                                    <input type="hidden" name="precio" id="precio" value="<?php echo openssl_encrypt( $producto['Precio'],COD,KEY);?>">
                                    <input type="hidden" name="cantidad" id="cantidad" value="<?php echo openssl_encrypt(1,COD,KEY);?>">

                                    <button class="btn btn-primary" 
                                    name="btnAccion" 
                                    value="Agregar" 
                                    type="submit"
                                    >
                                    <center> Agregar al carrito</center>
                                    </button>

                                </form>

                                
                            </div>
                        </div>
                        
                    </div>
                    
                <?php  }?> 
                    
            </div>
        </div>
    
    <script>
        //scrip de boostrap´"https://getbootstrap.com/docs/4.0/components/popovers/" 
        //para la implementacion de la descripcion de la base de datos al pasar el cursor en cualquier imagen
        $(function () {
                $('[data-toggle="popover"]').popover();
        });
    </script>
    
<?php
include 'templates/pie.php';
?>