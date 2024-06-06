<!DOCTYPE html>
<html lang="en">

<head>
    <title>LOTES CONSERGEN BRAMILEX</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--===============================================================================================-->
    <link rel="icon" type="image/png" href="{{ asset('img/icons/favicon.ico') }}" />
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css"> --}}
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="vendor/animate/animate.css"> --}}
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css"> --}}
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css"> --}}
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css"> --}}
    <!--===============================================================================================-->
    {{-- <link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css"> --}}
    <!--===============================================================================================-->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.css') }}">
    <!--===============================================================================================-->
</head>

<body style="background-color: #666666;">
    <div class="limiter">
        <div class="container-login100">
            <div class="wrap-login100">
                <form method="POST" action="{{ route('login') }}" class="login100-form">
                    @csrf
                    <div style="display: flex; justify-content: center; align-items: center; margin-bottom: 10px;">
                        <!-- <img src="./assets/img/logo.jpg" alt="" style="width: 250px;"> -->
                    </div>
                    <span class="login100-form-title p-b-43">
                        Inicio de sesi&oacute;n
                    </span>
                    <div class="wrap-input100">
                        <input class="input100" type="text" id="usuario" name="usuario" required>
                        <span class="focus-input100"></span>
                        <label class="label-input100" for="username">Usuario</label>
                    </div>
                    <div class="wrap-input100">
                        <input class="input100" type="password" id="password" name="password" required>
                        <span class="focus-input100"></span>
                        <label class="label-input100" for="password">Contraseña</label>
                    </div>
                    <div class="container-login100-form-btn">
                        <button type="submit" class="login100-form-btn">Login</button>
                    </div>

                </form>
                <div class="login100-more" style="background-image: url('{{ asset('img/login.jpg') }}');">
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if ($errors->has('usuario'))
            Swal.fire({
                icon: "error",
                title: "Credenciales Incorrectas",
                text: "Usuario o contraseña incorrectas.",
            });
        @endif
    </script>
</body>

</html>
