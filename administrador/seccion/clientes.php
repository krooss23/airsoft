<?php include("../template/cabecera.php"); ?>
<?php

$txtID=(isset($_POST['txtID']))?$_POST['txtID']:"";
$txtNombre=(isset($_POST['txtNombre']))?$_POST['txtNombre']:"";
$txtPassword=(isset($_POST['txtPassword']))?$_POST['txtPassword']:"";
$txtEmail=(isset($_POST['txtEmail']))?$_POST['txtEmail']:"";
$txtImagen=(isset($_FILES['txtImagen']['name']))?$_FILES['txtImagen']['name']:"";
$accion=(isset($_POST['accion']))?$_POST['accion']:"";

include("../config/bd.php");

switch($accion){
 
    //INSERT INTO `clientes` (`id`, `nombre`, `contraseña`, `correo`, `imagen`) VALUES (NULL, 'Pedro Porro', 'hola', 'hola@gmail.com', 'imagen.jpg');
  
  case "Agregar":
        $sentenciaSQL= $conexion->prepare("INSERT INTO clientes ( nombre,correo,imagen ) VALUES (:nombre,:correo,:imagen);");
        $sentenciaSQL->bindParam(':nombre',$txtNombre);
        $sentenciaSQL->bindParam(':correo',$txtEmail);


        $fecha= new DateTime();
        $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";

        $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

        if($tmpImagen!=""){

            move_uploaded_file($tmpImagen,"../../img/.$nombreArchivo");
        }

        $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
        $sentenciaSQL->execute();

        header("location:clientes.php");
        break;

     case "Modificar":

            $sentenciaSQL= $conexion->prepare("UPDATE clientes SET nombre=:nombre WHERE id=:id");
                $sentenciaSQL->bindParam(':nombre',$txtNombre);
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();

                $sentenciaSQL= $conexion->prepare("UPDATE clientes SET correo=:correo WHERE id=:id");
                $sentenciaSQL->bindParam(':correo',$txtEmail);
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();    

                if($txtImagen!=""){

                    $fecha= new DateTime();
                    $nombreArchivo=($txtImagen!="")?$fecha->getTimestamp()."_".$_FILES["txtImagen"]["name"]:"imagen.jpg";
                    $tmpImagen=$_FILES["txtImagen"]["tmp_name"];

                    move_uploaded_file($tmpImagen,"../../img/.$nombreArchivo");
                    

                    $sentenciaSQL= $conexion->prepare("SELECT imagen FROM clientes WHERE id=:id");
                    $sentenciaSQL->bindParam(':id',$txtID);
                    $sentenciaSQL->execute();
                    $cliente=$sentenciaSQL->fetch(PDO::FETCH_LAZY);
        
                   if(isset($cliente["imagen"]) &&($cliente["imagen"]!="imagen.jpg") ){
        
                    if(file_exists("../../img/".$cliente["imagen"])){
        
                        unlink("../../img/".$cliente["imagen"]);
        
                     }
                   }
        

                    $sentenciaSQL= $conexion->prepare("UPDATE clientes SET imagen=:imagen WHERE id=:id");
                    $sentenciaSQL->bindParam(':imagen',$nombreArchivo);
                    $sentenciaSQL->bindParam(':id',$txtID);
                    $sentenciaSQL->execute();    
                }

                header("location:clientes.php");
            break;

     case "Cancelar":
            header("location:clientes.php");
            break;

         case "Seleccionar":

                $sentenciaSQL= $conexion->prepare("SELECT * FROM clientes WHERE id=:id");
                $sentenciaSQL->bindParam(':id',$txtID);
                $sentenciaSQL->execute();
                $cliente=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

                $txtNombre=$cliente['nombre'];
                $txtEmail=$cliente['correo'];
                $txtImagen=$cliente['imagen'];
             
                
                break;
   
          case "Borrar":

            $sentenciaSQL= $conexion->prepare("SELECT imagen FROM clientes WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            $cliente=$sentenciaSQL->fetch(PDO::FETCH_LAZY);

           if(isset($cliente["imagen"]) &&($cliente["imagen"]!="imagen.jpg") ){

            if(file_exists("../../img/".$cliente["imagen"])){

                unlink("../../img/".$cliente["imagen"]);

             }
           }


            $sentenciaSQL= $conexion->prepare("DELETE  FROM clientes WHERE id=:id");
            $sentenciaSQL->bindParam(':id',$txtID);
            $sentenciaSQL->execute();
            
            header("location:clientes.php");
               break;
                      
     
       
}

$sentenciaSQL= $conexion->prepare("SELECT * FROM clientes");
$sentenciaSQL->execute();
$listaClientes=$sentenciaSQL->fetchAll(PDO::FETCH_ASSOC);




?>

<div class="col-md-5">

<div class="card">
    <div class="card-header">
        Datos cliente
    </div>

    <div class="card-body">

    <form method="POST" enctype="multipart/form-data" >

    <div class = "form-group">
    <label for="txtID">ID:</label>
    <input type="text" class="form-control" value="<?php echo $txtID; ?>" name="txtID" id="txtID" placeholder="ID" required readonly>
    </div>

    <div class = "form-group">
    <label for="txtNombre">Nombre:</label>
    <input type="text" class="form-control" value="<?php echo $txtNombre; ?>" name="txtNombre" id="txtNombre" placeholder="Nombre" required>
    </div>

    <div class = "form-group">
    <label for="txtPassword">Contraseña:</label>
    <input type="password" class="form-control"   name="txtPassoword" id="txtPassword" placeholder="Contraseña" required>
    </div>

    <div class = "form-group">
    <label for="txtEmail">Correo electronico:</label>
    <input type="email" class="form-control" value="<?php echo $txtEmail; ?>" name="txtEmail" id="txtEmail" placeholder="Correo electronico" required>
    </div>

    <div class = "form-group">
    <label for="txtImagen">Imagen</label>
    <input type="file" class="form-control" value="<?php echo $txtImagen; ?>" name="txtImagen" id="txtImagen" placeholder="nombre">
    </div>


    <div class="btn-group" role="group" aria-label="">
        <button type="submit" name="accion"<?php echo ($accion =="Seleccionar")?"disabled":"" ;?> value="Agregar" class="btn btn-success">Agregar</button>
        <button type="submit" name="accion"<?php echo ($accion!="Seleccionar")?"disabled":"" ;?> value="Modificar" class="btn btn-warning">Modificar</button>
        <button type="submit" value="Cancelar" class="btn btn-info">Cancelar</button>
    </div>


    </form>

         </div>

  
    </div>

    


</div>
<div class="col-md-7">


<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Imagen</th>
            <th>Correo</th>
            <th>acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($listaClientes as $cliente) { ?>
        <tr>
            <td><?php echo $cliente['id'] ?></td>
            <td><?php echo $cliente['nombre'] ?></td>
            <td><?php echo $cliente['imagen'] ?></td>
            <td><?php echo $cliente['correo'] ?></td>

            <td>
              
                
            <form  method="post">

                <input type="hidden" name="txtID" id="txtID" value="<?php echo $cliente['id'] ?>"/>

                <input type="submit" name="accion" value="Seleccionar" class="btn btn-primary"/>

                <input type="submit" name="accion" value="Borrar" class="btn btn-danger"/>
              
            </form>
            </td>
        </tr>
      <?php } ?>
    </tbody>
</table>

 
</div>


<?php include("../template/pie.php"); ?>








