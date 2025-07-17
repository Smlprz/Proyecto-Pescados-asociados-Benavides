// Constantes de peso (kg) por etapa de desarrollo
const PESOS = {
    alevin: 0.0005,    // 0.5 gramos
    juvenil: 0.05,     // 50 gramos
    adulto: 3.5        // 3.5 kg
};

// Variables globales para seguimiento
let calculosCompletados = false;
let ultimoValorMuerteesp = null;

// Función principal para calcular todos los valores
function calcularValores() {
    try {
        // Obtener valores de los inputs
        const cosecha = parseFloat(document.getElementById('cosecha').value) || 0;
        const temp = parseFloat(document.getElementById('temperatura').value) || 8;
        const oxigeno = parseFloat(document.getElementById('oxigeno').value) || 10;
        
        // Validar cosecha
        if (cosecha <= 0) {
            document.getElementById('resultados-calculados').style.display = 'none';
            document.getElementById('muerteesp').value = '';
            calculosCompletados = false;
            return;
        }
        
        // 1. CALCULAR BIOMASA (alevines)
        const biomasa = calcularBiomasa(cosecha);
        
        // 2. CALCULAR MORTALIDAD ESPERADA
        const mortalidadBase = calcularMortalidad(cosecha, temp, oxigeno);
        
        // 3. MOSTRAR RESULTADOS
        mostrarResultados(biomasa, mortalidadBase, cosecha);
        
        // Actualizar campo oculto para el formulario
        document.getElementById('muerteesp').value = mortalidadBase.toFixed(1);
        ultimoValorMuerteesp = mortalidadBase.toFixed(1);
        calculosCompletados = true;
        
        console.log("Cálculos completados. Mortalidad:", mortalidadBase.toFixed(1));
    } catch (error) {
        console.error("Error en cálculo:", error);
        calculosCompletados = false;
    }
}

// Calcular biomasa inicial en kg
function calcularBiomasa(cosecha) {
    return cosecha * PESOS.alevin;
}

// Algoritmo para calcular mortalidad esperada
function calcularMortalidad(cosecha, temp, oxigeno) {
    let mortalidadBase = 15; // 15% base para alevines
    
    // Ajustar por temperatura (óptima: 8-12°C)
    if (temp < 6 || temp > 14) mortalidadBase += 10;
    else if (temp >= 6 && temp <= 10) mortalidadBase += 5;
    
    // Ajustar por oxígeno (óptimo: 8-12 mg/L)
    if (oxigeno < 6) mortalidadBase += 15;
    else if (oxigeno < 8) mortalidadBase += 5;
    
    // Ajustar por densidad (número de alevines)
    if (cosecha > 50000) mortalidadBase += 3; // Alta densidad
    if (cosecha < 20000) mortalidadBase -= 2; // Baja densidad
    
    // Limitar entre 5% y 40%
    return Math.max(5, Math.min(40, mortalidadBase));
}

// Mostrar resultados en la interfaz
function mostrarResultados(biomasa, mortalidad, cosecha) {
    const resultadosDiv = document.getElementById('resultados-calculados');
    
    if (cosecha > 0) {
        resultadosDiv.style.display = 'block';
        document.getElementById('biomasa-calculada').textContent = biomasa.toFixed(4) + ' kg';
        document.getElementById('mortalidad-calculada').textContent = mortalidad.toFixed(1) + '%';
        document.getElementById('muertes-esperadas').textContent = Math.round(cosecha * (mortalidad/100)) + ' peces';
    } else {
        resultadosDiv.style.display = 'none';
    }
}

// Validación del formulario
function configurarEventos() {
    const form = document.getElementById('formJaula');
    
    if (!form) {
        console.error("Formulario no encontrado");
        return;
    }

    // Configurar eventos de entrada para campos numéricos
    const camposNumericos = ['cosecha', 'temperatura', 'oxigeno'];
    camposNumericos.forEach(id => {
        const campo = document.getElementById(id);
        if (campo) {
            campo.addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, '');
                calcularValores();
            });
        }
    });

    // Evento submit mejorado
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validar campos requeridos
        const camposRequeridos = ['jaulaid', 'centro', 'cosecha', 'temperatura', 'oxigeno'];
        let formularioValido = true;

        camposRequeridos.forEach(campo => {
            const input = document.getElementById(campo);
            if (!input || !input.value) {
                input.classList.add('error');
                formularioValido = false;
            } else {
                input.classList.remove('error');
            }
        });

        if (!formularioValido) {
            alert('Por favor complete todos los campos requeridos.');
            return;
        }

        // Verificar cálculos
        if (!calculosCompletados || !ultimoValorMuerteesp) {
            calcularValores(); // Forzar cálculo
            setTimeout(() => {
                if (ultimoValorMuerteesp) {
                    this.submit(); // Reintentar envío
                } else {
                    alert('Error en los cálculos. Por favor revise los datos ingresados.');
                }
            }, 100);
            return;
        }

        // Verificar valor de muerteesp
        const muerteesp = document.getElementById('muerteesp').value;
        if (!muerteesp || isNaN(muerteesp) || muerteesp < 5 || muerteesp > 40) {
            alert('El cálculo de mortalidad esperada no es válido. Por favor verifique los datos.');
            return;
        }

        // Si todo está bien, enviar formulario
        this.submit();
    });

    // Quitar marca de error al corregir
    document.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value !== '') {
                this.classList.remove('error');
            }
        });
    });
}

// Inicialización cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Calcular valores iniciales
    calcularValores();
    
    // Configurar eventos
    configurarEventos();
    
    // Forzar cálculo cada segundo como respaldo (solo para desarrollo)
    const intervaloDebug = setInterval(() => {
        if (!calculosCompletados) {
            calcularValores();
        } else {
            clearInterval(intervaloDebug);
        }
    }, 1000);
});