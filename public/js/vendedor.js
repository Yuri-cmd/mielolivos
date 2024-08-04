var canvas = document.getElementById('signature-pad');
var signaturePad = new SignaturePad(canvas);

document.getElementById('clear').addEventListener('click', function() {
    signaturePad.clear();
});
$('#siguiente').click(function() {
    
    let productos = $('.valor');
    let list = "";
    let total = 0;
    let precio = 0;
    let flag = true;
    let cantidad, stock;
    let flagStock = false;
    $(productos).each(function (index, element) {
        if($(element).val() > 0){
            precio = parseFloat($(element).attr("data-precio")) * $(element).val();
            cantidad =  parseFloat($(element).val());
            stock = parseFloat($(element).attr("data-stock"));
            // if(cantidad > stock){
            //     Swal.fire(`No puede asignar ${cantidad} ya que su stock para ${$(element).attr("data-nombre")} es de ${stock}`);
            //     flagStock = true;
            // }
            list += `<li><span>${$(element).val()} ${$(element).attr("data-nombre")}</span> <span>S/${precio}</span></li>`; 
            total += parseFloat($(element).attr("data-precio")) * $(element).val();
            flag = false;
        }
    }); 

    // if(flagStock){
    //     return;
    // }

    if(flag){
        Swal.fire("No se asignaron cantidades");
        return;
    }
    let nombre = $('#nombre').val();
    let tel = $('#tel').val();
    let direccion = `${$('#jr').val()} ${$('#mz').val()} ${$('#lt').val()} ${$('#piso').val()} ${$('#pisos').val()} / ${$('#pisos').val()}`;
    $('#tocart').text($('#tocar').val());
    $('#telt').text(tel);
    // $('#telt').attr('href', 'tel:+51'+tel);
    $('#colorT').text($('#color').val());
    $('#nombreCliente').text(nombre);
    $('#mzT').html($('#mz').val() ? 'Mz. '+$('#mz').val() : '-');
    $('#jrT').html($('#jr').val() ? 'Jr/Calle. '+$('#jr').val() : '-');
    $('#lotT').html($('#lt').val() ? 'Lt. '+$('#lt').val() : '-');
    $('#urbT').html($('#urb').val() ? 'Urb. '+$('#urb').val() : '-');
    $('#pisoT').html('Piso: '+($('#piso').val() ? $('#piso').val() : '-'));
    $('#pisosT').html('N&deg;Pisos: '+($('#pisos').val() ? $('#pisos').val() : '-'));
    if(nombre == ""){
        Swal.fire("Falta ingresar nombre");
        return;
    }
    if(tel == ""){
        Swal.fire("Falta ingresar telefono");
        return;
    }
    $(".list-unstyled").html(list);
    $("#total").text('S/'+ formatNumber(total));
    $('#totalAmount').val(total);
    
    $('#exampleModal').modal('show');
});

$('#telt').click(function (){
    let tel = $('#tel').val();
    let html = `<div style="display: flex; justify-content: space-around">
        <div>
            <a type="button" class="btn btn-primary" href="tel:+51${tel}">Llamar</a>
        </div>
        <div>
            <a type="button" class="btn btn-secondary" onclick="enviarMensajeWhatsApp(${tel})">Whastapp</a>
        </div>
    </div>`;
    Swal.fire({
        title: "Acciones",
        text: "Llamar o enviar whastapp",
        html: html,
        icon: "warning",
        showCancelButton: false,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "cerrar"
    }).then((result) => {
        if (result.isConfirmed) {
          
        }
    });
})

$('#submit').click(function() {
    if (signaturePad.isEmpty()) {
        Swal.fire("Por favor, dibuje su firma.");
        return;
    }

    var signatureData = signaturePad.toDataURL('image/png');
    
    let nombre = $('#nombre').val();
    let tel = $('#tel').val();
    let jr = $('#jr').val();
    let mz = $('#mz').val();
    let lt = $('#lt').val();
    let piso = $('#piso').val();
    let pisos = $('#pisos').val();
    let urb = $('#urb').val();
    let tocar = $('#tocar').val();
    let color = $('#color').val();
    let datosVenta = {
        nombre: nombre,
        tel: tel,
        jr:jr,
        mz: mz,
        lt: lt,
        piso: piso,
        pisos: pisos,
        urb: urb,
        tocar: tocar,
        color: color
    }
    let idGrupo = $(this).attr('data-idgrupo');
    let detalle = [];
    let productos = $('.valor');
    $(productos).each(function (index, element) {
        detalle.push({
            cantidad: $(element).val(),
            id: $(element).attr("data-id"), 
            precio: $(element).attr("data-precio"), 
            nombre: $(element).attr("data-nombre"), 
        });
    });
    let cuotas = {
        cuota1: $('#input1').val(),
        cuota2: $('#input2').val(),
        cuota3: $('#input3').val(),
    } 
    $.post(urlSaveVenta, {_token: token, datosVenta:datosVenta, detalle: detalle, cuotas: cuotas, signatureData: signatureData,idGrupo: idGrupo},
        function (data, textStatus, jqXHR) {
            Swal.fire({
                title: "Se guardo Correctamente",
                text: "¿Desea enviar recibo?",
                icon: "success",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Enviar",
                cancelButtonText: "No, enviar"
            }).then((result) => {
                if (result.isConfirmed) {
                    enviarMensajeWhatsApp(tel, data);
                    Swal.fire({
                        title: "Enviado!",
                        text: "Se envio correctamente",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        } else if (result.isDenied) {
                            location.reload();
                        }
                    });
                }
            });
        },
    );
    
});

$('#contado').click(function () { 
    
    let productos = $('.valor');
    let list = "";
    let total = 0;
    let precio = 0;
    let flag = true;
    let cantidad, stock;
    let flagStock = false;

    $(productos).each(function (index, element) {
        if($(element).val() > 0){
            precio = parseFloat($(element).attr("data-precio")) * $(element).val();
            cantidad =  parseFloat($(element).val());
            stock = parseFloat($(element).attr("data-stock"));
            if(cantidad > stock){
                Swal.fire(`No puede asignar ${cantidad} ya que su stock para ${$(element).attr("data-nombre")} es de ${stock}`);
                flagStock = true;
            }
            list += `<li><span>${$(element).val()} ${$(element).attr("data-nombre")}</span> <span>S/${precio}</span></li>`; 
            total += parseFloat($(element).attr("data-precio")) * $(element).val();
            flag = false;
        }
    }); 

    if(flagStock){
        return;
    }

    $(".list-unstyled").html(list);
    $("#totalc").text('S/'+ formatNumber(total));
    $('#totalAmountc').val(total);
    if(flag){
        Swal.fire("No se asignaron cantidades");
        return;
    }
    $('#ContadoModal').modal('show');
});

$('#submitContado').click(function() {
    let datosVenta = {
        nombre: '',
        tel: '',
        jr: '',
        mz: '',
        lt: '',
        piso: '',
        pisos: '',
        urb: '',
        tocar: '',
        color: ''
    }
    let idGrupo = $(this).attr('data-idgrupo');
    let detalle = [];
    let productos = $('.valor');
    $(productos).each(function (index, element) {
        detalle.push({
            cantidad: $(element).val(),
            id: $(element).attr("data-id"), 
            precio: $(element).attr("data-precio"), 
            nombre: $(element).attr("data-nombre"), 
        });
    });
    let cuotas = {};
    $.post(urlSaveVenta, {_token: token, datosVenta:datosVenta, detalle: detalle, cuotas: cuotas, tipo: 'contado', idGrupo: idGrupo},
        function (data, textStatus, jqXHR) {
            Swal.fire({
                title: "Guardo!",
                text: "Se guardo correctamente",
                icon: "success"
            }).then((result) => {
                if (result.isConfirmed) {
                    location.reload();
                } else if (result.isDenied) {
                    location.reload();
                }
            });
        },
    );
    
});

function enviarMensajeWhatsApp(telefono, id) {
    var mensaje = encodeURIComponent('Hola, le hacemos envio de su bolea, puede revisarlo entrando a la siguiente url. http://mielolivos.test/vendedor/venta/'+id);
    window.open('https://wa.me/' + telefono + '?text=' + mensaje, '_blank');
}

function enviarMensajeWhatsApp(telefono){
    window.open('https://wa.me/' + telefono, '_blank');
}

function formatNumber(number) {
    return number.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function calculateRemaining() {
    const totalAmount = parseFloat(document.getElementById('totalAmount').value) || 0;
    const input1 = parseFloat(document.getElementById('input1').value) || 0;
    const input2 = parseFloat(document.getElementById('input2').value) || 0;
    const input3 = document.getElementById('input3');
    const input3Container = document.getElementById('input3-container');
    const input3ContainerFecha = document.getElementById('input3ContainerFecha');

    let sum = input1 + input2;
    
    if (sum > totalAmount) {
        alert("La suma de los valores no puede ser mayor al monto total de " + totalAmount);
        // Reset values to prevent exceeding totalAmount
        document.getElementById('input1').value = "";
        document.getElementById('input2').value = "";
        input3.value = "";
        input3Container.style.display = 'none';
        return;
    }

    let remainingAmount = totalAmount - sum;
    input3.value = remainingAmount.toFixed(2);

    // Show or hide input3 based on whether input2 is the remaining amount or not
    if (remainingAmount > 0 && input2 !== remainingAmount) {
        input3Container.style.display = 'block';
        input3ContainerFecha.style.display = 'block';
    } else {
        input3Container.style.display = 'none';
        input3ContainerFecha.style.display = 'none';
    }
}

function updateInputs() {
    const input1 = document.getElementById('input1').value;
    
    if (input1) {
        calculateRemaining();
    }
}

document.getElementById('input1').addEventListener('input', updateInputs);
document.getElementById('input2').addEventListener('input', calculateRemaining);