<?php
$user="root";
$pass ="";
$host ="localhost";
$name ="salmonera";

$conn = mysqli_connect($host,$user,$pass,$name);

if (!$conn){
    die("No se pudo conectar". mysqli_connect_error());
}else{
    echo"Conexion completa";
}
$email=$_POST["email"];
$password=$_POST["password"];

$_QUERY = mysqli_query($conn,"SELECT * FROM usuario WHERE EMAILUSUARIO='".$email."' AND CONSTRASEÑAUSUARIO='".$password."'");
$nr=mysqli_num_rows($query);
if($nr==1){
    header("Location:pag 2 del front.html");
}else if ($nr==0){
    echo"Datos incorrectos";
}
?>