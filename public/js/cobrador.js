let tabla = $('#datatable').DataTable();

function mostrar(){
    $("#exampleModal").modal('show');
}
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