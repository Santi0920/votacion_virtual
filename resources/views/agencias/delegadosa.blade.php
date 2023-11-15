@extends('layouts.base')

@section('content')


@if (session("correcto"))
  <div>
  <script>
    Swal.fire
      ({
          icon: 'success',
          title: "{{session('correcto')}}",
          text: '',
          confirmButtonColor: '#005E56',
          timer: 3000
    
      });  
  </script>
  </div>
@endif

@if (session("alert"))
  <div>
  <script>
    Swal.fire
      ({
          icon: 'warning',
          title: "{{session('alert')}}",
          text: '',
          confirmButtonColor: '#005E56'
    
      });  
  </script>
  </div>
@endif

@if (session("incorrecto"))
<div>
  <script>
    Swal.fire
      ({
          icon: 'error',
          title: "{{session('incorrecto')}}",
          text: '',
          confirmButtonColor: '#005E56',
          timer: 10000
    
      });  
  </script>
  </div>
@endif

@error('message')
<div>
<script>
  Swal.fire
    ({
        icon: 'error',
        title: "Error al registrar!\n{{$message}}",
        text: '',
        confirmButtonColor: '#005E56'
  
    });  
</script>
</div>
@enderror

<!-- NAV DE LISTA-->
<a name="arriba"></a>
<nav class="navbar navbar-expand-lg bg-body-secondary p-0" id="Menu">
    <div class="container-fluid menu-bar" style="" > 
      
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
      </button>
  
    <div class="collapse navbar-collapse" id="navbarSupportedContent">

    <img src="img/CoopserpPH.png" alt="Coopserp.icono" width="150px" height="60px" id="data" class="navbar-brand mb-2 mt-2" style="filter: drop-shadow(0 2px 0.8px white);">
      
    <ul class="navbar-nav me-auto mb-lg-0 header">            
      <div class="dropdown nav-item" style="margin-left: 30px">
        <a href="/entrance" style="text-decoration: none"><li class="nav-link active text-white fw-bold" type="button" style="font-size: 25px">
          INGRESO
         </li></a>
  
      </div>
      <li class="nav-item" style="margin-left: 30px;">
        <a class="nav-link active text-white" aria-current="page" href="/delegadosa" id="data" style="font-size: 25px">CANDIDATOS</a>
      </li>
    

      <li class="nav-item" style="margin-left: 30px">
      <a href="{{ route('imprimir2')}}" class="nav-link active text-white" aria-current="page" href="#" id="data" style="font-size: 25px" target="__blank">GENERAR PDF <img src="img/pdf.png" style="margin-left: 10px; height: 3.0rem"> </a>
    </li>
  
    </ul>
    
    <span class="mx-4 text-white" style="font-size: 25px;"><img style="height: 2.5rem" class="mx-1" src="img/perfil.png">Bienvenid@ <strong>{{ auth()->user()->name }}</strong></span>
    <a onclick="return cerrarsesion()" href="{{route('login.destroy')}}"><button class="btn btn-light"><b style="font-size: 25px;">Cerrar Sesión</b></button></a>
  
   
  </div>
  </div>
  </nav>


<style>
  .formato-ayuda {
    color: gray;
    font-style:inherit;
  }
  </style>
    </form>
    <br>
    {{-- FECHA --}}
    <div class="col-11" style="margin-left:3.5%">
      <div class="">
        <form action="" method="post">
        <div class="" style="margin-top: 0px; margin-right: -14px;">

      </b></h2>
    </div>
    <h2 class="p-2 mb-0 text-secondary text-start"><b><span class="text-warning" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">SOFTWARE VOTACIONES</span> - CANDIDATOS - <span class="text-end" id="fechaActual"></b></span></h2>
    <script>
     function obtenerFechaActual() {
        const fecha = new Date();
        const meses = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        const mes = meses[fecha.getMonth()];
        const dia = fecha.getDate();
        const anio = fecha.getFullYear();
        let horas = fecha.getHours();
        let amPm = 'AM';
        
        // AM/PM
        if (horas > 12) {
            horas -= 12;
            amPm = 'PM';
        } else if (horas === 0) {
            horas = 12;
        }
    
        const minutos = fecha.getMinutes();
        const segundos = fecha.getSeconds();
        
        
        return `${mes} ${dia}, ${anio} - ${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${amPm}`;
    }
    
    
    function actualizarFechaActual() {
        const elementoFecha = document.getElementById('fechaActual');
        elementoFecha.textContent = `${obtenerFechaActual()}`;
    }
    
    
    setInterval(actualizarFechaActual, 1000);
    </script>
    
    
        </form>  
      </div>
      <div style="overflow: auto;" class="p-3 table-responsive">
        <table id="personas" class=" hover table table-striped shadow-lg mt-2 table-bordered table-hover col-md-4" style="font-size: 25px">
          <thead class="" style="background-color: #005E56;">
            <tr class="text-white">
              <th class="" scope="col" style="width: 20px">#</th>
              <th class="" scope="col" style="width: 100px">POSICION</th>
              <th class="" scope="col" style="width: 100px">AGENCIA</th>
              <th class="" scope="col" style="width: 20px">TARJETON</th>
              <th class="" scope="col" >NOMBRE</th>
              <th class="" scope="col">APELLIDOS</th>
              <th class="" scope="col" style="width: 30px"></th>
            </tr> 
          </thead> 
          <tbody class="table-group-divider">
           
            
          </tbody>


          
        </table>

        


          </div>
        </div>
        <script src="ResourcesAll/dtables/jquery-3.5.1.js"></script>
        <script src="ResourcesAll/dtables/jquerydataTables.js"></script>
        <script src="ResourcesAll/dtables/dataTablesbootstrap5.js"></script>
        <script src="ResourcesAll/dtables/dtable1.min.js"></script>
        <script src="ResourcesAll/dtables/botonesdt.min.js"></script>
        <script src="ResourcesAll/dtables/estilobotondt.min.js"></script>
        <script src="ResourcesAll/dtables/botonimprimir.min.js"></script>
        <script src="ResourcesAll/dtables/imprimir2.min.js"></script>
        
        <script>



 var table = $('#personas').DataTable({
  "ajax": "{{ route('datatable.agency2') }}",
  "columns": [
    {data: 'ID'},
    {data: 'Posicion'},
    {data: 'AgenciaD'},
    {data: 'NoTarjeton'},
    {data: 'Nombre'},
    {data: 'Apellidos'},
    {    
        data: null,
      render: function(data, type, row) {
        var id = row.ID;
        var url = "{{route ('update.delegatevotesagency', ':id') }}"; 
        var today = new Date().toISOString().split('T')[0];
        url = url.replace(':id', id);
        
        
        var html = '';

      var editButton = `<a title="VOTOS" href="" id="modalLink_${id}" type="submit" class="btn btn-small btn-warning edit-button edit" data-bs-toggle="modal" data-bs-target="#modalEditar_${id}" data-id="${id}" style="margin-right: "><i class="fa-solid fa-ticket"></i></a>
      <div class="modal fade" id="modalEditar_${id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
          <div class="modal-content">
            <button type="button" data-bs-dismiss="modal" class="btn-close p-3" aria-label="Close"></button>
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <h1 class="modal-title text-center" id="modificar">AGREGAR VOTOS</h1>
            </div>
            <hr>
            <div class="modal-body">
              <form action="`+url+`" class="text-center" method="POST" enctype="multipart/form-data" id="formulario" onsubmit="return validateForm2()">
              @csrf
              <!-- Resto del contenido del modal -->
              <div class="mb-3">
                <label for="" id="" class="form-label fw-bold fs-4" value="" style="margin-left: -280px">VOTOS TOTALES</label>
                <input type="number" class="form-control" name="Votos" id="Votos" value=""  placeholder="a ${row.Nombre} ${row.Apellidos}" required>
              </div>


                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                  <button type="submit" name="editar" id="btnGuardar" class="btn btn-primary" style="background-color: #005E56;" onclick="return confirmar()">Guardar</button>


                </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>`;

  return editButton;

}
  }
  ],

  "lengthMenu": [[5], [5]],
  "language": {
    "lengthMenu": "Mostrar _MENU_ registros por página",
    "zeroRecords": "No existe!",
    "info": "Mostrando la página _PAGE_ de _PAGES_",
    "infoEmpty": "No hay registros disponibles",
    "infoFiltered": "(Filtrado de _MAX_ registros totales)",
    "search": "Buscar:",
    "paginate": {
      "next": "Siguiente",
      "previous": "Anterior"
    }
  }
});
//   responsive: "true",
//         dom: 'Bfrtilp',       
//         buttons:[ 
// 			{
// 				extend:    'excelHtml5',
// 				text:      '<i class="fas fa-file-excel"></i> ',
// 				titleAttr: 'Exportar a Excel',
// 				className: 'btn btn-success btn-lg'
// 			},
// 			{
// 				extend:    'print',
// 				text:      '<i class="fa fa-print"></i> ',
// 				titleAttr: 'Imprimir',
// 				className: 'btn btn-info btn-lg'
// 			}
//       ]	


function showUnauthorizedMessage() {
  Swal.fire({
    icon: 'error',
    title: '¡Permiso no autorizado!',
    text: 'No tienes permiso para realizar esta acción.',
    confirmButtonColor: '#005E56'
  });
  
  return false;
}




function confirmar() {
    var respuesta = confirm("POR FAVOR INGRESAR LOS VOTOS TOTALES DEL CANDIDATO. ADVERTENCIA!!! NO SE PODRÁ REALIZAR CAMBIOS DESPUÉS DE REGISTRARLOS.");
    return respuesta;
}

    
          function csesion(){
            var respuesta=confirm("¿Estas seguro que deseas cerrar sesión?")
            return respuesta
          }
        </script>
        

        
    </div>
    
    </div>
    
</div>    



    @endsection
    
 