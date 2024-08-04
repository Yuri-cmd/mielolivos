$('#estadistica').click(function (){
    $('#estadisticasModal').modal('show');
});

$('#buscarUsuario').on('input', function() {
    let query = $(this).val();

    if (query.length > 0) {
        $.ajax({
            url: urlBuscarUsuarios,
            type: 'GET',
            data: { query: query },
            success: function(data) {
                $('#resultList').empty();
                if (data.length > 0) {
                    data.forEach(function(usuario) {
                        $('#resultList').append('<li class="list-group-item">' + usuario.usuario + '</li>');
                    });
                } else {
                    $('#resultList').append('<li class="list-group-item">No se encontraron resultados</li>');
                }
            }
        });
    } else {
        $('#resultList').empty();
    }
});

$(document).on('click', '#resultList li', function() {
    let selectedText = $(this).text();
    $('#buscarUsuario').val(selectedText);
    $('#resultList').empty();
});

var lineChart = null; // Variable para almacenar el objeto Chart del Line Chart
var pieChart1 = null; // Variable para almacenar el objeto Chart del Pie Chart 1
var pieChart2 = null; // Variable para almacenar el objeto Chart del Pie Chart 2

$('#fechaRango').daterangepicker({
    locale: {
        format: 'YYYY-MM-DD',
        separator: ' a ',
        applyLabel: 'Aplicar',
        cancelLabel: 'Cancelar',
        fromLabel: 'Desde',
        toLabel: 'Hasta',
        customRangeLabel: 'Personalizado',
        daysOfWeek: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        firstDay: 1
    }
});

 // Manejar el clic en el botón de filtrar
 $('#btnFiltrar').click(function() {
    // Obtener el rango de fechas seleccionado
    var fechaInicio = $('#fechaRango').data('daterangepicker').startDate.format('YYYY-MM-DD');
    var fechaFin = $('#fechaRango').data('daterangepicker').endDate.format('YYYY-MM-DD');
    console.log('Filtrar por rango de fechas:', fechaInicio, 'a', fechaFin);

    // Destruir los gráficos existentes si ya se han creado
    destruirGraficos();

    // Actualizar los gráficos con el nuevo rango de fechas
    actualizarLineChart(fechaInicio, fechaFin);
    actualizarPieCharts();
});

// Función para destruir los gráficos existentes
function destruirGraficos() {
    if (lineChart) {
        lineChart.destroy();
    }
    if (pieChart1) {
        pieChart1.destroy();
    }
    if (pieChart2) {
        pieChart2.destroy();
    }
}

// Función para actualizar el Line Chart
function actualizarLineChart(fechaInicio, fechaFin) {
    // Implementa la lógica para actualizar el Line Chart con las fechas seleccionadas
    var ctx = document.getElementById('lineChart').getContext('2d');
    lineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: generarLabelsFechas(fechaInicio, fechaFin),
            datasets: [{
                label: 'Datos de ejemplo',
                data: generarDatosEjemplo(),
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

// Función para generar los días de la semana según el rango de fechas seleccionado
function generarLabelsFechas(fechaInicio, fechaFin) {
    var labels = [];
    var fechaActual = new Date(fechaInicio);
    var fechaFinal = new Date(fechaFin);
    while (fechaActual <= fechaFinal) {
        if (fechaActual.getDay() !== 0) {
            var diaSemana = obtenerNombreDiaSemana(fechaActual.getDay());
            var fechaFormato = fechaActual.getDate();
            labels.push(`${diaSemana}${fechaFormato}`);
        }
        fechaActual.setDate(fechaActual.getDate() + 1);
    }
    return labels;
}

// Función auxiliar para obtener el nombre del día de la semana
function obtenerNombreDiaSemana(numeroDia) {
    var diasSemana = ['LU', 'MT', 'MC', 'JV', 'VR', 'SB'];
    return diasSemana[numeroDia - 1];
}

// Función para generar datos de ejemplo para el Line Chart
function generarDatosEjemplo() {
    return [12, 19, 3, 5, 2, 8]; // Datos de ejemplo
}

// Función para actualizar los Pie Charts
function actualizarPieCharts() {
    // Implementa la lógica para actualizar los Pie Charts con las fechas seleccionadas
    var ctx1 = document.getElementById('pieChart1').getContext('2d');
    pieChart1 = new Chart(ctx1, {
        type: 'pie',
        data: {
            labels: ['Categoria 1', 'Categoria 2', 'Categoria 3'],
            datasets: [{
                label: 'Datos de ejemplo',
                data: [30, 20, 50], // Datos de ejemplo
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                borderWidth: 1
            }]
        }
    });

    var ctx2 = document.getElementById('pieChart2').getContext('2d');
    pieChart2 = new Chart(ctx2, {
        type: 'pie',
        data: {
            labels: ['Categoria A', 'Categoria B', 'Categoria C'],
            datasets: [{
                label: 'Datos de ejemplo',
                data: [40, 30, 30], // Datos de ejemplo
                backgroundColor: ['#4BC0C0', '#36A2EB', '#FFCE56'],
                borderWidth: 1
            }]
        }
    });
}