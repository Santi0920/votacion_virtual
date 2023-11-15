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
  <div class="container-fluid menu-bar"> 
    <!-- Coopserp.com-->
    
    <!-- Botón que aparece al reducir pantalla--> 
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <!-- Foto Coopserp--> 
      <img src="img/CoopserpPH.png" alt="Coopserp.icono" width="150px" height="60px" id="data" class="navbar-brand mb-2 mt-2" style="filter: drop-shadow(0 2px 0.8px white);">
        <!-- Consultar cedula--> 
      <ul class="navbar-nav me-auto mb-lg-0 header">        
        <li class="nav-item" style="margin-left: 30px">
          <a href="https://www.coopserp.com/consulta_cedula/" style="text-decoration: none" target="__blank">
            <span class="nav-link active text-white fw-bold" type="button" style="font-size: 25px">
              CONSULTA
            </span>
          </a>
        </li>
        <li class="nav-item" style="margin-left: 30px">
          <a href="/" style="text-decoration: none">
            <span class="nav-link active text-white fw-bold" type="button" style="font-size: 25px">
              REGISTRO
            </span>
          </a>
        </li>

        <li class="nav-item" style="margin-left: 0px">
          <a class="nav-link active text-white" aria-current="page" href="/delegados" id="data" style="font-size: 25px">CANDIDATOS</a>
        </li>

        <li class="nav-item dropdown" style="margin-left: 0px">
          <a class="nav-link active text-white dropdown-toggle" aria-current="page" href="#" id="data" style="font-size: 25px">
            GENERAR PDFS 
          </a>
          <ul class="dropdown-menu" style="background-color: #005E56;">
            <li class="nav-item" style="margin-left: 0px">
              <a href="{{ route('imprimir.1')}}" class="nav-link active text-white" id="data" style="font-size: 25px" target="__blank">
                PDF 1 
                <img src="img/pdf.png" style="margin-left: 10px; height: 3.0rem">
              </a>
            </li>
  
            <li class="nav-item" style="margin-left: 0px">
              <a href="{{ route('imprimir.2')}}" class="nav-link active text-white" id="data" style="font-size: 25px" target="__blank">
                PDF 2 
                <img src="img/pdf.png" style="margin-left: 10px; height: 3.0rem">
              </a>
            </li>
  
            <li class="nav-item" style="margin-left: 0px">
              <a href="{{ route('imprimir.3')}}" class="nav-link active text-white" id="data" style="font-size: 25px" target="__blank">
                PDF 3
                <img src="img/pdf.png" style="margin-left: 10px; height: 3.0rem">
              </a>
            </li>
  
            <li class="nav-item" style="margin-left: 0px">
              <a href="{{ route('imprimir.4')}}" class="nav-link active text-white" id="data" style="font-size: 25px" target="__blank">
                PDF 4 
                <img src="img/pdf.png" style="margin-left: 10px; height: 3.0rem">
              </a>
            </li>

            <li class="nav-item" style="margin-left: 0px">
              <a href="{{ route('imprimir.5')}}" class="nav-link active text-white" id="data" style="font-size: 25px" target="__blank">
                PDF 5 
                <img src="img/pdf.png" style="margin-left: 10px; height: 3.0rem">
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
    <span class="mx-4 text-white" style="font-size: 25px;"><img style="height: 2.5rem" class="mx-1" src="img/perfil.png">Bienvenid@ <strong>{{ auth()->user()->name }}</strong></span>
  <a onclick="return cerrarsesion()" href="{{route('login.destroy')}}"><button class="btn btn-light"><b style="font-size: 25px;">Cerrar Sesión</b></button></a>
  </div>
  
</nav>


<!-- Modal para agregar consultante -->
<form action="{{route ('rol.store')}}" class="text-center" id="" method="POST" enctype="multipart/form-data" onsubmit="validateForm3()">
  @csrf
    <div class="container">
         <div class="agregar">
            <a href="datacredito.php"  type="button" class="" data-bs-toggle="modal" data-bs-target="#exampleModal2"><i class="fa-solid fa-plus icono"></i></a>
        </div>
    </div>
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <button type="button" data-bs-dismiss="modal" class="btn-close p-3" aria-label="Close"></button>
                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                    
                    <h1 class="modal-title text-center fw-semibold" id="modificar">AGREGAR ROL</h1>

                 </div>
                <hr>

                <div class="modal-body">
    
                
                <div>
                <table class="table w-100" id="usuarios">
                    <thead class="" style="background-color: #005E56;">
                      <tr class="text-white">
                        
                        <th scope="">USUARIO</th>
                        <th>ROL</th>

 

                      </tr>
                      </thead>
                    

                      <tbody>
                    
                      </tbody>
                </table>
                </div>
               <hr>
               
            <!--Label1-->  
            <div class="mb-3 mt-3">
                <label for="label" id="consul1" class="form-label fw-bold" value="">NOMBRE COMPLETO</label>
                <input type="text" class="form-control" name="name" id="name" required>
                <div id="nameError" style="color: red;" class="fw-bold"></div>
            </div>
            <!--VALIDACION CAMPO USUARIO--> 
            <script>
            var usernameInput = document.getElementById('name');
            var usernameError = document.getElementById('nameError');

            usernameInput.addEventListener('keyup', function() {
              var username = usernameInput.value.trim();

              if (/^[a-zA-Z\sñÑ]+$/u.test(username)) {
                usernameError.innerHTML = '';
              } else {
                usernameError.innerHTML = 'El nombre de usuario solo debe contener letras';
              }
            });
            usernameInput.setAttribute("maxlength", "20");

            </script>


     
            <!--Label2--> 
            <div class="mb-3">
                <label for="label" id="consul2" class="form-label fw-bold">CORREO ELECTRÓNICO</label>
                <input type="email" class="form-control" name="email" id="email" required>
                <div id="emailError" style="color: red;" class="fw-bold"></div>
            </div>
            <script>
              var correoInput = document.getElementById('email');
              var correoError = document.getElementById('emailError');
              var correo = correoInput.value.trim();

            correoInput.setAttribute("maxlength", "40");
            </script>

        <style>
          .password-toggle-icon {
              position: absolute;
              right: 10px;
              top: 50%;
              transform: translateY(-50%);
              cursor: pointer;
          }
        </style>
          <div class="mb-3">
            <label for="exampleInputEmail1" id="consul3" class="form-label fw-bold">CONTRASEÑA</label>
            <div style="position: relative;">
                <input type="password" class="form-control" name="password" id="password" required>
                <i class="password-toggle-icon fa fa-eye" onclick="togglePasswordVisibility('password')"></i>
            </div>
            <div id="contraseñaError" style="color: red;" class="fw-bold"></div>
          </div>
            <script>

            function togglePasswordVisibility(inputId) {
                    var input = document.getElementById(inputId);
                    var icon = input.nextElementSibling;
                    if (input.type === "password") {
                        input.type = "text";
                        icon.classList.remove("fa-eye");
                        icon.classList.add("fa-eye-slash");
                    } else {
                        input.type = "password";
                        icon.classList.remove("fa-eye-slash");
                        icon.classList.add("fa-eye");
                    }
                }
              var contraseñaInput = document.getElementById('password');
              var contraseñaError = document.getElementById('contraseñaError');

              contraseñaInput.addEventListener('keyup', function() {
                var contraseña = contraseñaInput.value.trim();

                if (/^(?=.*[a-zA-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,12}$/.test(contraseña)) {
                  contraseñaError.innerHTML = '';
                } else {
                  contraseñaError.innerHTML = 'La contraseña debe tener entre 8 y 12 caracteres y contener al menos una letra, un número y un símbolo';
                }
              });

              contraseñaInput.setAttribute("maxlength", "12");
            </script>



            <div class="mb-3">
              <label for="label" id="consul4" class="form-label fw-bold">CONFIRMAR CONTRASEÑA</label>
              <div style="position: relative;">
                  <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" required>
                  <i class="password-toggle-icon fa fa-eye" onclick="togglePasswordVisibility('password_confirmation')"></i>
              </div>
              <div id="ccontraseñaError" style="color: red;" class="fw-bold"></div>
          </div>
            <script>
              var ccontraseñaInput = document.getElementById('password_confirmation');
              var ccontraseñaError = document.getElementById('ccontraseñaError');
              var ccontraseñaSucces = document.getElementById('ccontraseñaSucces');
              ccontraseñaInput.addEventListener('keyup', function() {
              var contraseña = contraseñaInput.value.trim();
              var ccontraseña = ccontraseñaInput.value.trim();

              if (contraseña === ccontraseña) {
                ccontraseñaError.innerHTML = '';
                ccontraseñaSucces.innerHTML = 'Las contraseñas si coinciden!';
              } else {
                ccontraseñaError.innerHTML = 'Las contraseñas no coinciden!';
                ccontraseñaSucces.innerHTML = '';
              }
              });
            </script>

          <div class="mb-3 w-100">
            <label for="estado" class="form-label fw-bold" style="margin-left: -93%;">ROL<span class="text-danger" style="font-size:20px;"></label>
            <select class="form-control " name="rol" id="rol" required>
              <option value="">Seleccione una opción</option>
              <option value="Agencia">Agencia</option>
            </select>
          </div>

          <div class="mb-3 w-100">
            <label for="agenciaU" class="form-label fw-bold" style="margin-left: -84%;">AGENCIA<span class="text-danger" style="font-size:20px;"></label>
            <select class="form-control " name="agenciau" id="agenciau" required>
              <option value="">Seleccione una opción</option>
              <option value="Medellín">Medellín</option>
              <option value="Cali">Cali</option>
              <option value="Barranquilla">Barranquilla</option>
              <option value="Cartagena">Cartagena</option>
              <option value="Jamundí">Jamundí</option>
              <option value="San Andrés">San Andrés</option>
              <option value="CaliBC">CaliBC</option>
              <option value="Palmira">Palmira</option>
              <option value="Buga">Buga</option>
              <option value="Buenaventura">Buenaventura</option>
              <option value="Tuluá">Tuluá</option>
              <option value="Sevilla">Sevilla</option>
              <option value="Caicedonia">Caicedonia</option>
              <option value="La Unión">La Unión</option>
              <option value="Roldanillo">Roldanillo</option>
              <option value="Cartago">Cartago</option>
              <option value="Zarzal">Zarzal</option>
              <option value="S Quilichao">S Quilichao</option>
              <option value="Yumbo">Yumbo</option>
              <option value="Pasto">Pasto</option>
              <option value="Popayán">Popayán</option>
              <option value="Ipiales">Ipiales</option>
              <option value="Leticia">Leticia</option>
              <option value="Soacha">Soacha</option>
              <option value="Pereira">Pereira</option>
              <option value="Manizales">Manizales</option>
              <option value="Monteria">Monteria</option>
              <option value="Sincelejo">Sincelejo</option>
              <option value="Valledupar">Valledupar</option>
              <option value="Villavicencio">Villavicencio</option>
              <option value="Santa Marta">Santa Marta</option>
              <option value="Duitama">Duitama</option>
              <option value="Bogotá Norte">Bogotá Norte</option>
              <option value="Pasto">Pasto</option>
              <option value="Bogotá Centro">Bogotá Centro</option>
              <option value="Bogotá Elemento">Bogotá Elemento</option>
              <option value="Bogotá TC">Bogotá TC</option>
              <option value="Tunja">Tunja</option>
              <option value="Ibagué">Ibagué</option>
              <option value="Bucaramanga">Bucaramanga</option>
              <option value="Cúcuta">Cúcuta</option>
              <option value="Zipaquirá">Zipaquirá</option>
              <option value="Armenia">Armenia</option>
              <option value="Neiva">Neiva</option>
              <option value="Riohacha">Riohacha</option>
              <option value="Yopal">Yopal</option>
              <option value="Facatativá">Facatativá</option>
              <option value="Girardot">Girardot</option>              
            </select>
          </div>
        </div>



        <div class="text-center p-2">
        <button  type="submit" class=" btn btn-primary w-50" name="btnregistrar2" style="background-color: #005E56;">Registrar</button>
        </div>
            
            </div>
        </div>
    </div>
    </form>


<div class="container-fluid row p-4">
<form action="{{route('cc.create')}}" class="col 3 m-3" method="POST" enctype= "multipart/form-data" onsubmit="return validateForm()">
  <h2 class="p-2 text-secondary text-center"><b>REGISTRO GENERAL DE VOTANTES</b></h2>
  
 @csrf
  <div class="mb-3 w-100" title="Este campo es obligatorio">
   <label for="cedula" class="form-label fw-semibold">CÉDULA<span class="text-danger" style="font-size:20px;">*</span></label>
   <input type="text" class="form-control" name="Cedula" id="Cedula" required autocomplete="off" disabled>
  </div>
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">PRIMER APELLIDO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Priapellido" id="Priapellido" required autocomplete="off" disabled>
  </div>
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">SEGUNDO APELLIDO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Segapellido" id="Segapellido" required autocomplete="off" disabled>
  </div>
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">NOMBRE <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Nombre" id="Nombre" autocomplete="off" disabled>
  </div>
  
  
  
  <div class="mb-3 w-100" title="Este campo es obligatorio" style="position: absolute; margin-top: -8000px">
    <label for="exampleInputEmail1" class="form-label fw-semibold">SANGRE <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control" autocomplete="off" disabled> 
  </div>
  
  
  <div class="mb-3 w-100" title="Este campo es obligatorio" >
    <label for="exampleInputEmail1" class="form-label fw-semibold">GENERO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Genero" id="Genero" required autocomplete="off" disabled> 
  </div>
  
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">AÑO NACIMIENTO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Anionaci" id="Anionaci" required autocomplete="off" disabled> 
  </div>
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">MES NACIMIENTO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Mesnaci" id="Mesnaci" required autocomplete="off" disabled> 
  </div>
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">DIA NACIMIENTO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Dianaci" id="Dianaci" required autocomplete="off" disabled> 
  </div>
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">TIPO SANGRE <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="TipoSangre" id="TipoSangre" required autocomplete="off" disabled> 
  </div>
  
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="cedula" class="form-label fw-semibold">AGENCIA<span class="text-danger" style="font-size:20px;">*</span></label>
    <input list="agencia" type="text" class="form-control" name="Agencia" id="Agencia" required autocomplete="off" disabled>
 </div>
 
 <datalist id="agencia">
  <option value="Medellín"></option><option value="Cali"></option><option value="Barranquilla"></option><option value="Cartagena"></option><option value="Jamundí"></option><option value="San Andrés"></option><option value="CaliBC"></option><option value="Palmira"></option><option value="Buga"></option><option value="Buenaventura"></option><option value="Tuluá"></option><option value="Sevilla"></option><option value="Caicedonia"></option><option value="La Unión"></option>
  <option value="Roldanillo"></option><option value="Cartago"></option><option value="Zarzal"></option><option value="S Quilichao"></option><option value="Yumbo"></option><option value="Pasto"></option><option value="Popayán"></option><option value="Ipiales"></option><option value="Leticia"></option><option value="Soacha"></option><option value="Pereira"></option><option value="Manizales"></option><option value="Monteria"></option><option value="Sincelejo"></option>
  <option value="Valledupar"></option><option value="Villavicencio"></option><option value="Santa Marta"></option><option value="Duitama"></option><option value="Bogotá Norte"></option><option value="Pasto"></option><option value="Bogotá Centro"></option><option value="Bogotá Elemento"></option><option value="Bogotá TC"></option><option value="Tunja"></option><option value="Ibagué"></option><option value="Bucaramanga"></option><option value="Cúcuta"></option><option value="Zipaquirá"></option>
  <option value="Armenia"></option><option value="Neiva"></option><option value="Riohacha"></option><option value="Yopal"></option><option value="Facatativá"></option><option value="Girardot">
 </datalist>
  <div class="mb-3 w-100" title="Este campo es obligatorio">
    <label for="exampleInputEmail1" class="form-label fw-semibold">CUENTA ASOCIADO <span class="text-danger" style="font-size:20px;">*</span></label>
    <input type="text" class="form-control " name="Cuenta" id="Cuenta" required autocomplete="off" disabled> 
  </div>
  
  <div class="text-center">
    <button onclick="return confirmar()" id="agregar" type="submit" class="btn btn-primary w-50" name="btnregistrar" value="ok" style="background-color: #005E56;" name="registrar" disabled>Registrar</button>
  </div>
  






    </form>
    {{-- FECHA --}}
    <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12 col-xxl-9">
      <div class="">
        <form action="" method="post">
        <div class="" style="margin-top: 8px; margin-right: -14px;">

        <h2 class="p-3 mb-0 text-secondary text-end">
  <b><span class="text-warning" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);">SOFTWARE VOTACIONES</span></b><span class="fw-bold" id="fechaActual"></span>
</h2>


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
        
        
        return ` - ${mes} ${dia}, ${anio} - ${horas}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')} ${amPm}`;
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
  "ajax": "{{ route('datatable.cc') }}",

  "columns": [
    {data: 'ID', title: '#'},
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
    {data: 'Fecha'}
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


var table = $('#usuarios').DataTable({
  "ajax": "{{ route('usuarios') }}",

  "columns": [
    {data: 'name'},
    {data: 'rol'}

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



          // function confirmar(){
          //   var respuesta=confirm("AY MI MADRE")
          //   return respuesta
          // }
         
        </script>
        
        
    </div>
    
    </div>
    
</div>    


    @endsection
    
 