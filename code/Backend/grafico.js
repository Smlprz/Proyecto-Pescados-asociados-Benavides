document.addEventListener("DOMContentLoaded", function () {
    // Consumir el JSON generado por informe.php
    fetch('informe.php')
        .then(response => {
            if (!response.ok) throw new Error("HTTP error " + response.status);
            return response.json();
        })
        .then(data => {
            const ctx = document.getElementById('graficoMuertes').getContext('2d');

            new Chart(ctx, {
                type: 'line',  // Cambia por 'bar' si prefieres barras
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Muertes',
                        data: data.data,
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 2,
                        fill: false,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Cantidad de muertes'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Fecha'
                            }
                        }
                    }
                }
            });
        })
        .catch(error => {
            console.error('Error al cargar los datos:', error);
        });
});