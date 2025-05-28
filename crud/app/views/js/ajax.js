const form_ajax=document.querySelectorAll(".FormularioAjax");//seleccionamos todos los formularios que tengan la clase FA 

//recorremos los elementos seleccionados,
// formularios--se le asociara un evento para cuando se envie 
//  se define funcion anonima para prevenir evento por defecto en submit/envio 
form_ajax.forEach(formularios =>{
    formularios.addEventListener("submit",function (e) {
        e.preventDefault();

        //sweet alert
        Swal.fire({
            title: 'Estas seguro?',
            text: 'Quieres realizar la acción solicitada',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, hazlo !',
            cancelButtonText: 'No, Cancela !'
        }).then((result) => {
            if (result.isConfirmed) {
                // Ejecutar AJAX
                let data= new FormData(this);
                let method=this.getAttribute("method");
                let action=this.getAttribute("action");

                let encabezado= new Headers();
                let config={
                    method:method,
                    headers:encabezado,
                    mode:'cors',
                    cache:'no-cache',
                    body: data
                };

                fetch(action,config)
                .then(response =>response.json())
                .then(response =>{
                    return alertas_ajax(response);
                });



                }
            });

    });   
});

function alertas_ajax(alerta){
    if(alerta.tipo=="simple"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        });

    }else if(alerta.tipo=="recargar"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                location.reload();
            }
        });

    }else if(alerta.tipo=="limpiar"){

        Swal.fire({
            icon: alerta.icono,
            title: alerta.titulo,
            text: alerta.texto,
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if(result.isConfirmed){
                document.querySelector(".FormularioAjax").reset();
            }
        });

    }else if(alerta.tipo=="redireccionar"){
        window.location.href=alerta.url;
    }
}