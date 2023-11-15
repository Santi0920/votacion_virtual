<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Votaciones | Coopserp</title>
    <link href="ResourcesAll/Bootstrap/Bootstrap.css" rel="stylesheet">
    <link href="css/login.css" rel="stylesheet">
    <link rel="shortcut icon" href="img/logoo.png" type="img/png">
    <script src="ResourcesAll/Sweetalert/sweetalert2.js"></script>
    <link rel="stylesheet" href="ResourcesAll/Sweetalert/sweetalert2.css">
    <script src="ResourcesAll/fontawesome/fontawesome.js"></script>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
  </head>
<body class="bg-light">
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
  <section>
    <div class="row g-0">
      <div class="col-lg-7 d-none d-lg-block">
        <!-- corousel -->
        <div id="carouselExampleIndicators" class="carousel slide">
          
          
          <div class="carousel-inner">
            <div class="carousel-item imagen-1 min-vh-100 active">
            <div class="overlay"></div>
            </div>
            <div class="carousel-item imagen-2 min-vh-100">
            </div>
          </div>
        
        </div>
      </div>
      <div class="col-lg-5" id="id">
        <div class="px-lg-5 pt-lg-4 pb-lg-3 p-4">
          <!-- regresar -->
          <div class="contenedor">
          </div>
          <p style="font-size: 35px; font-weight: bold" class="text-center">SOFTWARE VOTACIONES MANUALES</p>
          <img src="img/CoopserpPH.png" class="img-fliud mx-auto d-block mb-4 " style="height: 10rem; width: 30rem">
          
        </div>
        
        <!-- Formulario -->
        <div class="px-lg-5 py-lg-4 p-4">
          <form action="" method="post">
             @csrf
            <div class="mb-4">
              <label for="exampleInputEmail1" class="form-label fw-bold fs-5" style="color: #005E56;">Correo Electrónico</label>
              <input type="text" class="form-control fs-6" name="email" id="email" placeholder="Email" aria-describedby="emailHelp" required>
            </div>
            <div class="mb-4">
              <label for="exampleInputPassword1" class="form-label fw-bold fs-5" style="color: #005E56;">Contraseña</label>
              <input type="password" class="form-control bg-light-x fs-6" name="password" id="password" placeholder="Contraseña" required>
            </div>
            @error('message')
            <div>
              <script>
                Swal.fire
                  ({
                      icon: 'error',
                      title: "Error!\n Email o Contraseña incorrectos!",
                      text: '',
                      confirmButtonColor: '#005E56'
                
                  });  
              </script>
              </div>
            @enderror

            <div class="mt-2 fs-5 finalizar">
              <a href="#" class="form-text text-nuted text-decoration-none text-seconday fw-semibold " style="cursor:not-allowed">¿Has olvidado tu contraseña?</a>
            </div>
        
          <button type="submit" class="btn fw-bold fs-5 text-light mt-4 w-100" name="Sesion" style="background-color: #005E56;" value="" id="Sesion">INGRESAR</button>
          </form>
          
        </div>
      </div>
    </div>
  </section>


<!-- script bootstrap -->
<script src="ResourcesAll/Bootstrap/bootstrap.js"></script>
<link rel="stylesheet" href="ResourcesAll/Sweetalert/sweetalert2.css">
</body>
</html>