function login()
{
    
let user=document.getElementById("email").value;
let pass=document.getElementById("password").value;

if(user=="ejemplo@gmail.com" && pass=="contraseña")
{
    window.location="pag 2 del front.html";
}
else
{
    alert("Datos de inicio incorrectos")
}
}
