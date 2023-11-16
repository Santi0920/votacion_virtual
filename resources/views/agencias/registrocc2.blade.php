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

@if (session("correcto2"))
  <div>
  <script>
    Swal.fire
      ({
          icon: 'success',
          title: "{{session('correcto')}}",
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
          confirmButtonColor: '#005E56'
    
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
    <!-- Coopserp.com-->
    
    <!-- Botón que aparece al reducir pantalla--> 
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
    </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
  <!-- Foto Coopserp--> 
  <img src="img/CoopserpPH.png" alt="Coopserp.icono" width="150px" height="60px" id="data" class="navbar-brand mb-2 mt-2" style="filter: drop-shadow(0 2px 0.8px white);">
    
  <ul class="navbar-nav me-auto mb-lg-0 header">  
    <!-- Consultar cedula--> 
    <li class="nav-item" style="margin-left: 30px">
      <a href="https://www.coopserp.com/consulta_cedula/" style="text-decoration: none" target="__blank">
        <span class="nav-link active text-white fw-bold" type="button" style="font-size: 25px">
          CONSULTA
        </span>
      </a>
    </li>      
    <!-- DataCreditos-->       
    <div class="dropdown nav-item" style="margin-left: 30px">
      <li class="nav-link active text-white fw-bold" type="button">
        <a href="/entrance" class="text-light" style="text-decoration: none; font-size: 25px">REGISTRO</a> 
       </li>

    </div>


  </ul>
  
  <span class="mx-4 text-white" style="font-size: 25px;"><img style="height: 2.5rem" class="mx-1" src="img/perfil.png">Bienvenid@ <strong>{{ auth()->user()->name }}</strong></span>
  <a onclick="return csesion()" href="{{route('login.destroy')}}"><button class="btn btn-light"><b style="font-size: 25px;">Cerrar Sesión</b></button></a>

 
</div>
</div>
</nav>



<div class="container-fluid row p-4">
<form action="{{route('cc.createagency')}}" class="col 3 m-3" method="POST" enctype= "multipart/form-data" onsubmit="return validateForm()">
  <h2 class="p-2 text-secondary text-center"><b>REGISTRO GENERAL DE VOTANTES</b></h2>
  
 @csrf
  
 


<datalist id="agencia">
  <option value="Medellín"></option><option value="Cali"></option><option value="Barranquilla"></option><option value="Cartagena"></option><option value="Jamundí"></option><option value="San Andrés"></option><option value="CaliBC"></option><option value="Palmira"></option><option value="Buga"></option><option value="Buenaventura"></option><option value="Tuluá"></option><option value="Sevilla"></option><option value="Caicedonia"></option><option value="La Unión"></option>
  <option value="Roldanillo"></option><option value="Cartago"></option><option value="Zarzal"></option><option value="S Quilichao"></option><option value="Yumbo"></option><option value="Pasto"></option><option value="Popayán"></option><option value="Ipiales"></option><option value="Leticia"></option><option value="Soacha"></option><option value="Pereira"></option><option value="Manizales"></option><option value="Monteria"></option><option value="Sincelejo"></option>
  <option value="Valledupar"></option><option value="Villavicencio"></option><option value="Santa Marta"></option><option value="Duitama"></option><option value="Bogotá Norte"></option><option value="Pasto"></option><option value="Bogotá Centro"></option><option value="Bogotá Elemento"></option><option value="Bogotá TC"></option><option value="Tunja"></option><option value="Ibagué"></option><option value="Bucaramanga"></option><option value="Cúcuta"></option><option value="Zipaquirá"></option>
  <option value="Armenia"></option><option value="Neiva"></option><option value="Riohacha"></option><option value="Yopal"></option><option value="Facatativá"></option><option value="Girardot">
</datalist>

  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="cedula" class="form-label fw-semibold">CÉDULA<span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control" name="Cedula" id="Cedula" required autocomplete="off">
</div>
   
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">PRIMER APELLIDO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Priapellido" id="Priapellido" required autocomplete="off">
  </div>

  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">SEGUNDO APELLIDO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Segapellido" id="Segapellido" required autocomplete="off">
  </div>
 
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">NOMBRE <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Nombre" id="Nombre" autocomplete="off">
  </div>


  
  <div class="mb-3 w-100" title="Este campo es obligatorio" style="position: absolute; margin-top: -8000px">
    <label for="exampleInputEmail1" class="form-label fw-semibold">SANGRE <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control" autocomplete="off"> 
  </div>


  <div class="mb-3 w-100" title="Este campo es obligatorio" >
    <label for="exampleInputEmail1" class="form-label fw-semibold">GENERO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Genero" id="Genero" required autocomplete="off"> 
  </div>


  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">AÑO NACIMIENTO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Anionaci" id="Anionaci" required autocomplete="off"> 
  </div>

  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">MES NACIMIENTO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Mesnaci" id="Mesnaci" required autocomplete="off"> 
  </div>

  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">DIA NACIMIENTO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Dianaci" id="Dianaci" required autocomplete="off"> 
  </div>

  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">TIPO SANGRE <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="TipoSangre" id="TipoSangre" required autocomplete="off"> 
  </div>

  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">AGENCIA <span class="text-danger" style="font-size:20px;">*</span></label>
    <input list="agencia" type="text" class="form-control " name="Agencia" id="Agencia" required autocomplete="off">
    <div id="agenciaError" style="color: red;" class="fw-bold"></div>
  </div>

  <datalist id="agencia">
    <option value="Medellín"></option><option value="Cali"></option><option value="Barranquilla"></option><option value="Cartagena"></option><option value="Jamundí"></option><option value="San Andrés"></option><option value="CaliBC"></option><option value="Palmira"></option><option value="Buga"></option><option value="Buenaventura"></option><option value="Tuluá"></option><option value="Sevilla"></option><option value="Caicedonia"></option><option value="La Unión"></option>
    <option value="Roldanillo"></option><option value="Cartago"></option><option value="Zarzal"></option><option value="S Quilichao"></option><option value="Yumbo"></option><option value="Pasto"></option><option value="Popayán"></option><option value="Ipiales"></option><option value="Leticia"></option><option value="Soacha"></option><option value="Pereira"></option><option value="Manizales"></option><option value="Monteria"></option><option value="Sincelejo"></option>
    <option value="Valledupar"></option><option value="Villavicencio"></option><option value="Santa Marta"></option><option value="Duitama"></option><option value="Bogotá Norte"></option><option value="Pasto"></option><option value="Bogotá Centro"></option><option value="Bogotá Elemento"></option><option value="Bogotá TC"></option><option value="Tunja"></option><option value="Ibagué"></option><option value="Bucaramanga"></option><option value="Cúcuta"></option><option value="Zipaquirá"></option>
    <option value="Armenia"></option><option value="Neiva"></option><option value="Riohacha"></option><option value="Yopal"></option><option value="Facatativá"></option><option value="Girardot">
  </datalist>

<script>
  var agenciaInput = document.getElementById('Agencia');
  var agenciaError = document.getElementById('agenciaError');

  agenciaInput.addEventListener('input', function() {
    var agencia = agenciaInput.value.trim();
    var opcionesAgencia = document.getElementById('agencia').options;
    var valorValido = false;

    for (var i = 0; i < opcionesAgencia.length; i++) {
      if (agencia.toLowerCase() === opcionesAgencia[i].value.toLowerCase()) {
        valorValido = true;
        break;
      }
    }

    if (valorValido) {
      agenciaError.innerHTML = '';
    } else {
      agenciaError.innerHTML = 'Seleccione una opción de la lista';
    }
  });

  agenciaInput.setAttribute('maxlength', '20');

  function validateForm() {
    var agenciaInput = document.getElementById('Agencia');
      var agenciaError = document.getElementById('agenciaError');

      var agencia = agenciaInput.value.trim();
      var opcionesAgencia = document.getElementById('agencia').options;
      var valorValido = false;

      for (var i = 0; i < opcionesAgencia.length; i++) {
        if (agencia.toLowerCase() === opcionesAgencia[i].value.toLowerCase()) {
          valorValido = true;
          break;
        }
      }

      if (!valorValido) {
        Swal.fire({
          icon: 'error',
          title: '¡Error!',
          text: 'En el campo Agencia seleccionar una opción de la lista!',
          confirmButtonColor: '#005E56'
        });
        agenciaError.innerHTML = 'Seleccione una opción de la lista';
        return false;
      }
  }
    
</script>


  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">CUENTA ASOCIADO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="number" class="form-control " name="Cuenta" id="Cuenta" required autocomplete="off">
  </div>

  <div class="text-center">
    <button onclick="return confirmar()" id="agregar" type="submit" class="btn btn-primary w-50" name="btnregistrar" value="ok" style="background-color: #005E56;" name="registrar">Registrar</button>
    </div>







    </form>
    {{-- FECHA --}}
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-9">
      <div class="">
        <form action="" method="post">
        <div class="" style="margin-top: 8px; margin-right: -14px;">

      <h2 class="p-3 mb-0 text-secondary text-end"><b><span class="text-warning" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">SOFTWARE VOTACIONES</span> - <span id="fechaActual"></span></b></h2>
    </div>
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
    <div style="overflow: auto;" class="">
        <table id="personas" class="table-responsive hover table table-striped shadow-lg mt-4 table-bordered table-hover col-md-1 p-1">
          <thead class="" style="background-color: #005E56;">
            <tr class="text-white">
              <th class="" scope="col"></th>
              <th class="" scope="col">AGENCIA</th>
              <th class="" scope="col">CÉDULA</th>
              <th class="" scope="col">CUENTA</th>
              <th class="" scope="col"></th>
              <th class="" scope="col">NOMBRE</th>
              <th class="" scope="col">GENERO</th>
              <th class="" scope="col"></th>
              <th class="" scope="col">SANGRE</th>
              <th class="" scope="col">FECHA/HORA ENTRADA</th>
              <th class="" scope="col">VOTAR</th>
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
        <script>
 

 var table = $('#personas').DataTable({
  "ajax": "{{ route('datatable.agency') }}",

  "columns": [
    {data: 'ID'},
    {data: 'Agencia'},
    {data: 'Cedula'},
    {data: 'Cuenta'},
    {
      data: null,
      render: function (data, type, row) {

        return data.Priapellido + ' ' + data.Segapellido;
      },
      title: 'APELLIDOS' 
    },
    {data: 'Nombre'},
    {data: 'Genero'},
    {
      data: null,
      render: function (data, type, row) {
        return data.Anionaci + '/' + data.Mesnaci + '/' + data.Dianaci;
      },
      title: 'FECHA NACIMIENTO' 
    },
    {data: 'TipoSangre'},
    {data: 'Fecha'},
    {    data: null,
      render: function(data, type, row) {
        var id = row.ID;
        var url = "{{ route('votarcandidatoid.agency', ':id') }}"; 
        var today = new Date().toISOString().split('T')[0];
        url = url.replace(':id', id);

        if(row.Voto == 0){
        var mostrarCandidato = `<div class="text-center"><a href="" id="modalLink_${id}" type="submit" class="btn btn-small btn-warning edit-button edit" data-bs-toggle="modal" data-bs-target="#modalEditar_${id}" data-id="${id}" style="margin-right: "><i class="fa-solid fa-ticket"></i></a></div>
        <div class="modal fade" id="modalEditar_${id}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                      <div class="modal-content">
                        <button type="button" data-bs-dismiss="modal" class="btn-close p-3" aria-label="Close"></button>
                        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                          <h1 class="modal-title text-center" id="modificar">No. TARJETÓN</h1>
                        </div>
                        <hr>
                        <div class="modal-body">
                          <form action="`+url+`" class="text-center" method="POST" enctype="multipart/form-data" id="formulario" onclick="digitar()">
                            @csrf

                    <label for="numero" class="form-label me-2 fw-semibold" style="font-size:40px">Ingrese el numero de tarjetón:</label>

                    <div class="mb-3 d-flex align-items-center justify-content-center">
                        <input type="text" class="form-control me-2 text-center w-50" id="numero" name="NoTarjeton" style="font-size:25px" required maxlength="2">
                        <button type="button" class="btn btn-success" style="background-color:#005E56; color:white; font-size:25px" onclick="borrar()">←</button>
                    </div>
                    <div class="d-grid gap-3">
                        <div>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(7)">7</button>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(8)">8</button>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(9)">9</button>
                        </div>
                        
                        <div>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(4)">4</button>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(5)">5</button>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(6)">6</button>
                        </div>
                        <div>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(1)">1</button>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(2)">2</button>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(3)">3</button>
                        </div>
                        <div>
                            <button type="button" class="btn" style="background-color:#005E56; color:white; font-size:40px; width: 100px;" onclick="agregarNumero(0)">0</button>
                        </div>
                        <div>
                        <button onclick="return confirmar()" id="btnBuscar" type="submit" class="btn fw-semibold" style="background-color:#005E56; color:white; font-size:40px;">BUSCAR</button>
                    </div>
                    </div>

               </div>

                          </form>
                        </div>
                      </div>
                    </div>
                  </div>`;
                }else{
                  var mostrarCandidato = `
                  <div class="text-center">
                    <a onclick="yavoto(event)" href="#" class="btn btn-small btn-warning edit-button edit" data-bs-toggle="modal" style="margin-right: ">
                      <i class="fa-solid fa-ticket"></i>
                    </a>
                  </div>`;
                
                }

  return mostrarCandidato;

}
  }
],
  "order": [[0, 'desc']],

  "lengthMenu": [[1], [1]],
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



        

function csesion(){
            var respuesta=confirm("¿Estas seguro que deseas cerrar sesión?")
            return respuesta
          }









function agregarNumero(numero) {
    let inputNumero = document.getElementById('numero');
    if (inputNumero.value.length < 2) {
        inputNumero.value += numero;
    }
}


function borrar() {
    let inputNumero = document.getElementById('numero');
    inputNumero.value = inputNumero.value.slice(0, -1);
}

function yavoto(e) {
  e.preventDefault();
  Swal.fire({
    icon: 'warning',
    title: '<strong>¡EL ASOCIADO YA VOTÓ!</strong>',
    html: '',
    confirmButtonColor: '#005E56',
    timer: 5000
  });
}

</script>


        
        
    </div>
    
    </div>
    
</div>    


    @endsection
    
 