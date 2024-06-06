$(document).ready(function() {
    $('#search-icon').click(function() {
        $('#search-container').toggle();
        if ($('#search-container').is(':visible')) {
            $('#search-input').focus();
        }
    });

    function filtrarTabla() {
        var textoBusqueda = $("#search-input").val().toLowerCase();
        $("#tabla-datos tbody tr").each(function() {
            var textoFila = $(this).text().toLowerCase();
            // Mostrar o ocultar la fila según si coincide con el texto de búsqueda
            $(this).toggle(textoFila.indexOf(textoBusqueda) > -1);
        });

        // Verificar si se encontraron filas después del filtrado
        var filasMostradas = $("#tabla-datos tbody tr:visible").length;
        if (filasMostradas === 0) {
            // No se encontraron coincidencias, mostrar mensaje
            Swal.fire("No se encontraron coincidencias");
        }
    }

    // Escuchar el evento input en el campo de búsqueda
    $("#search-input").on("input", function() {
        filtrarTabla();
    });

    $(".cardscroll").draggable({
        helper: "clone"
    });

    // Hacer el contenedor de vendedores como una zona de soltado
    $("#contenedorVendedores").droppable({
        accept: ".cardscroll",
        drop: function(event, ui) {
            var $card = $(ui.helper).clone();
            $card.removeClass("ui-draggable-dragging").draggable({
                helper: "clone"
            });
            $(this).append($card);
            resetearElemento();
        }
    });

    $("#contenedorVendedores .cardm span").append('<a href="#" class="eliminar">x</a>');

    function resetearElemento() {
        $("#contenedorVendedores").children().last().removeClass().removeAttr("style");
        $("#contenedorVendedores").children().last().addClass("cardm");
        $("#contenedorVendedores").children().last().css("margin-bottom", "12px");
        $("#contenedorVendedores .cardm:last-child span").append('<a href="#" class="eliminar">x</a>');
        let nuevoDiv = $(
            '<div class="cardm"><input type="number" class="form-control" inputmode="numeric"></div>');
        // Adjuntar el evento change al nuevo input de tipo número
        nuevoDiv.find("input[type='number']").on("change", function() {
            calcularSuma();
        });
        $("#totalCampo, #totalVendido, #totalSobrante").before(nuevoDiv);
    }

    $("#contenedorVendedores").on("click", ".eliminar", function() {
        var elementoAEliminar = $(this).closest('.cardm');
        elementoAEliminar.remove();
    });

    // Función para calcular la suma de los valores de todos los inputs
    function calcularSumaCampo() {
        var suma = 0;
        $(".campo input[type='number']").each(function() {
            var valor = parseFloat($(this).val());
            if (!isNaN(valor)) {
                suma += valor;
            }
        });
        // Actualizar el valor del input dentro de totalCampo con la suma
        $("#totalCampo input[type='number']").val(suma);
    }

    // Escuchar el evento change en los inputs de tipo número
    $(".campo input[type='number']").on("change", function() {
        calcularSumaCampo();
    });

    // Función para calcular la suma de los valores de todos los inputs
    function calcularSumaVendidos() {
        var suma = 0;
        $(".vendidos input[type='number']").each(function() {
            var valor = parseFloat($(this).val());
            if (!isNaN(valor)) {
                suma += valor;
            }
        });
        // Actualizar el valor del input dentro de totalCampo con la suma
        $("#totalVendido input[type='number']").val(suma);
    }

    // Escuchar el evento change en los inputs de tipo número
    $(".vendidos input[type='number']").on("change", function() {
        calcularSumaVendidos();
    });

    // Función para calcular la suma de los valores de todos los inputs
    function calcularSumaSobrantes() {
        var suma = 0;
        $(".sobrantes input[type='number']").each(function() {
            var valor = parseFloat($(this).val());
            if (!isNaN(valor)) {
                suma += valor;
            }
        });
        // Actualizar el valor del input dentro de totalCampo con la suma
        $("#totalSobrante input[type='number']").val(suma);
    }

    // Escuchar el evento change en los inputs de tipo número
    $(".sobrantes input[type='number']").on("change", function() {
        calcularSumaSobrantes();
    });
});