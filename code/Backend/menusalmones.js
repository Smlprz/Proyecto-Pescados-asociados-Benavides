function centro1(){
    window.location="pag 3 del front.html"
}
function jaula101(){
    window.location="pag 4 del front.html"
}
function atrasa2(){
    window.location="pag 2 del front.html"
}
function actualizardatos(){
    window.location="pag 5 del front.html"
}
function mostrardatos() {
  fetch('mostrar.php')
    .then(response => response.json())
    .then(data => {
      if (data && !data.error) {
        document.getElementById('biomasa').textContent = data.BIOMASA ?? 'n/d';
        document.getElementById('mortalidad_actual').textContent = data.MUERTEACT ?? 'n/d';
        document.getElementById('temperatura').textContent = data.TEMPERATURA ?? 'n/d';
        document.getElementById('oxigeno').textContent = data.OXIGENO ?? 'n/d';
        document.getElementById('mortalidad_esperada').textContent = data.mortalidad_esperada ?? 'n/d';
        document.getElementById('mortalidad_esperada').textContent = data.MUERTEESP ?? 'n/d';
      } else {
        alert('No se encontraron datos o ocurrió un error.');
      }
    })
    .catch(error => {
      console.error('Error al obtener los datos:', error);
      alert('Error al obtener los datos.');
    });
}
function formulariodatos(){
    document.getElementById('formDatos').addEventListener('submit', function(e) {
        e.preventDefault();
  
        const formData = new FormData(this);
        fetch('insertar.php', {
          method: 'POST',
          body: formData
        })
        .then(resp => resp.json())
        .then(data => {
          const respuesta = document.getElementById('respuesta');
          if (data.success) {
            respuesta.style.color = 'green';
            respuesta.textContent = data.message;
          } else {
            respuesta.style.color = 'red';
            respuesta.textContent = data.error;
          }
        })
        .catch(error => {
          document.getElementById('respuesta').style.color = 'red';
          document.getElementById('respuesta').textContent = 'Error en la solicitud.';
          console.error('Error:', error);
        });
      });
}
