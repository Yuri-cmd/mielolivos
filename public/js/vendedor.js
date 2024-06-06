document.addEventListener("DOMContentLoaded", function() {
    var canvas = document.getElementById('signature-pad');
    var signaturePad = new SignaturePad(canvas);

    document.getElementById('clear').addEventListener('click', function() {
        signaturePad.clear();
    });
});

$('#submit').click(function() {
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
            Swal.fire({
                title: "Enviado!",
                text: "Se envio correctamente",
                icon: "success"
            });
        }
    });
});