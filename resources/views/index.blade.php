@extends('layout.main')

@section('content')
<section class="forms">
    <div class="container-fluid">
        {{-- Título y bienvenida --}}
        <div class="row">
            <div class="col-md-12">
                <h2>Bienvenido, {{ Auth::user()->name }}!</h2>
                <p>Este es el resumen de los documentos electrónicos generados del mes de {{ $currentMonth }}</p>
                <p>Fecha actual: {{ $currentDate }}</p>
            </div>
        </div>

        {{-- Resumen rápido --}}
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Total Facturas</h5>
                        <p class="card-text">{{ $total_facturas }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Facturas No Autorizadas</h5>
                        <p class="card-text">{{ $total_no_autorizadas }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Facturas Autorizadas</h5>
                        <p class="card-text">{{ $total_facturas_autorizadas }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabla con detalles --}}
        <div class="row">
            <div class="col-md-12">
                <h4>Detalles de las Empresas</h4>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Empresa</th>
                            <th>No Autorizadas</th>
                            <th>Autorizadas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($detalles as $key => $detalle)
                            <tr>
                                <td>{{ ++$key }}</td>
                                <td>{{ $detalle['empresa']}}</td>
                                <td>{{ $detalle['no_autorizadas'] }}</td>
                                <td>{{ $detalle['autorizadas'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
