let baseUrl = window.location.protocol + '//' + window.location.host;

// let tabla = $('#datatable').DataTable({
//     responsive: true,
//     "ajax": {
//         "url": baseUrl+'/almacen/etc/controller/producto.php?s=get', // Ruta del script PHP en el servidor que obtiene los datos de la tabla productos
//         "dataSrc": ""
//     },
//     "columns": [
//         { "data": "fecha_compra" },
//         { "data": "nombre" },

//         {
//             "data": null,
//             "render": function(data, type, row) {
//                 arr = JSON.parse(row.liquidacion);
//                 return `<button onclick="verImagenes(1,'${arr}')" class="px-4 py-2 text-white rounded-md" style="background-color: rgb(6 182 212);"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
//                 <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
//                 <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
//               </svg></button>`;
//             }
//         },
//         { "data": "molino" },
//         { "data": "codigo" },
//         { "data": "sacos" },
//         { "data": "cliente" },
//         { "data": "tnl" },
//         { "data": "moneda" },
//         { "data": "precio" },
//         { "data": "fecha_retiro" },
//         { "data": "blendis" },
//         {
//             "data": null,
//             "render": function(data, type, row) {
//                 arr = JSON.parse(row.imagen);
//                 return `<button onclick="verImagenes(2,'${arr}')" class="px-4 py-2 text-white rounded-md" style="background-color: rgb(6 182 212);"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-fill" viewBox="0 0 16 16">
//                 <path d="M10.5 8a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0"/>
//                 <path d="M0 8s3-5.5 8-5.5S16 8 16 8s-3 5.5-8 5.5S0 8 0 8m8 3.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7"/>
//               </svg></button>`;
//             }
//         },
//         {
//             "data": null,
//             "render": function(data, type, row) {
//                 return `<button onclick="verReporte(${row.id})" class="px-4 py-2 text-white rounded-md" style="background-color: rgb(34 197 94);"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-check-fill" viewBox="0 0 16 16">
//                 <path d="M9.293 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.707A1 1 0 0 0 13.707 4L10 .293A1 1 0 0 0 9.293 0M9.5 3.5v-2l3 3h-2a1 1 0 0 1-1-1m1.354 4.354-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708.708"/>
//               </svg></button>`;
//             }
//         },
//         {
//             "data": null,
//             "render": function(data, type, row) {
//                 return `<button onclick="editar(${row.id})" class="bg-blue-500 text-white rounded-md" style="padding:8px"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
//                 <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
//                 <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
//               </svg></button> <button onclick="eliminar(${row.id})" class="bg-red-500 text-white rounded-md" style="padding:8px"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
//               <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"/>
//             </svg></button> <button onclick="cambiar(${row.id}, ${row.estado})" class="bg-green-500 text-white rounded-md" style="padding:8px"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
//             <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
//           </svg></button>`;
//             }
//         }
//     ]
// });
$(document).ready(function() {
    $('#submit').click(function() {
        let username = $('#username').val();
        let password = $('#password').val();

        if (username === '' || password === '') {
            Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Por favor complete todos los campos.",
              });
              
        } else {
            $.post('./etc/controller/auth.php', {
                username:username,
                password:password
            }, function(response) {
                let rsp =  JSON.parse(response);
                if(rsp.response){
                    if(rsp.rol == 2){
                        window.location.href = './etc/view/vendedor.php'; 
                    }else{
                        window.location.href = './etc/view/principal.php';
                    }
                }else{
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Usuario o contraseña incorrecta",
                      });
                }
            });
        }
    });
        
});

