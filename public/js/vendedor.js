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

    if(flag){
        Swal.fire("No se asignaron cantidades");
        return;
    }
    let nombre = $('#nombre').val();
    let tel = $('#tel').val();
    let direccion = `${$('#jr').val()} ${$('#mz').val()} ${$('#lt').val()} ${$('#piso').val()} ${$('#pisos').val()} / ${$('#urb').val()}`;
    $('#tocart').text($('#tocar').val());
    $('#telt').text(tel);
    $('#telt').attr('href', 'tel:+51'+tel);
    $('#colorT').text($('#color').val());
    $('#nombreCliente').text(nombre);
    $('#direccionT').text(direccion);
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

function formatNumber(number) {
    return number.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

input1.addEventListener('input', function() {
    const totalAmount = $('#totalAmount').val();
    const input1 = document.getElementById('input1');
    const input2 = document.getElementById('input2');
    const input3 = document.getElementById('input3');
    let value = parseFloat(input1.value);
    // Validar que el valor no sea mayor al monto total
    if (value > totalAmount) {
        alert("El valor no puede ser mayor al monto total de " + totalAmount);
        input1.value = totalAmount;
        value = totalAmount;
    }

    // Calcular el resto del monto y dividirlo entre los otros dos inputs
    let remainingAmount = totalAmount - value;
    let dividedAmount = remainingAmount / 2;

    input2.value = dividedAmount.toFixed(2);
    input3.value = dividedAmount.toFixed(2);
});