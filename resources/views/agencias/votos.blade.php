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
          <!-- Consultar cedula--> 
    <ul class="navbar-nav me-auto mb-lg-0 header">            
      <li class="nav-item" style="margin-left: 30px">
        <a href="https://www.coopserp.com/consulta_cedula/" style="text-decoration: none" target="__blank">
          <span class="nav-link active text-white fw-bold" type="button" style="font-size: 25px">
            CONSULTA
          </span>
        </a>
      </li>

      <div class="dropdown nav-item" style="margin-left: 30px">
        <li class="nav-link active text-white fw-bold" type="button">
          <a href="/entrance" class="text-light" style="text-decoration: none; font-size: 25px">REGISTRO</a> 
         </li>
  
      </div>
  
      <div class="nav-item" style="margin-left: 0px">
        <li class="nav-item" >
        <a href="{{ route('imprimir2')}}" class="nav-link active text-white" aria-current="page" href="#" id="data" style="font-size: 25px" target="__blank">GENERAR PDF <img src="img/pdf.png" style="margin-left: 10px; height: 3.0rem"> </a>
      </li>
      </div>
  
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
    <h2 class="p-2 mb-0 text-secondary text-start"><b><span class="text-warning" style="text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);"></span>  <span class="text-end" id="fechaActual"></b></span></h2>
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
    <div>

        <div class=" mt-3 mb-5">
            <div class="d-flex justify-content-center">
                <form id="calculadoraForm" class="text-center" method="POST" action="{{ route('votarcandidato.agency', 'id') }}"> 
                    @csrf
                    <label for="numero" class="form-label me-2 fw-semibold" style="font-size:40px">Ingrese el numero de tarjetón:</label>

                    <div class="mb-3 d-flex align-items-center justify-content-center">
                        <input type="text" class="form-control me-2 text-center w-50" id="numero" name="NoTarjeton" style="font-size:25px" required readonly>
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
                        <button type="submit" class="btn fw-semibold" style="background-color:#005E56; color:white; font-size:40px;">BUSCAR</button>
                    </div>
                    </div>
                </form>
                

            </div>
        </div>



    </div>

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
            let inputNumero = document.getElementById('numero');
            let inputResultado = document.getElementById('resultado');
        
            function agregarNumero(numero) {
              if (inputNumero.value.length < 2) 
              {
                inputNumero.value += numero;
              }
            }
        

        
            function limpiar() {
                inputNumero.value = '';
                inputResultado.value = '';
            }
        
            function borrar() {
                inputNumero.value = inputNumero.value.slice(0, -1);
            }
        
        </script>

    

    <script>
          function csesion(){
            var respuesta=confirm("¿Estas seguro que deseas cerrar sesión?")
            return respuesta
          }
        </script>
        

        
    </div>
    
    </div>
    
</div>    



    @endsection
    
 