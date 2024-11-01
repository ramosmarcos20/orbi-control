<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js" integrity="sha384-oP9a5E/dX76pQv9SCcF/N7xG+gU3e1C1k0F94Dvm1sdMRTPJt5wF/Wy7JJARjK6t2" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/orbiErp-1.jpg') }}">
    <title>Orbi Login</title>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card p-4 shadow-lg" style="width: 22rem;">
            <h3 class="text-center mb-4">Iniciar Sesión</h3>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre de Usuario</label>
                    <div class="input-group">
                        <span class="input-group-text" id="basic-addon1"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Tu nombre de usuario" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="password" placeholder="********" required>
                    </div>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Ingresar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
