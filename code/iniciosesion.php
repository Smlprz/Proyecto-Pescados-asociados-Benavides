<?php
include("conexion.php");

$email=$_POST["email"];
$password=$_POST["password"];

if(isset($_POST["usuario"])){
    $_QUERY = mysqli_query($conn,"SELECT * FROM usuario WHERE EMAILUSUARIO='$email' AND CONTRASEÑAUSUARIO='$password'");
    $nr=mysqli_num_rows($_QUERY);
    if($nr==1){
    echo"Datos Correctos";
    header("Location:pag 2 del front.html");
    }else{
    echo"Datos incorrectos";
    }
}
?>