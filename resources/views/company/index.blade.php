@extends('layout.main')

@section('content')
<section class="forms">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <div class="card border border-light-subtle">

                    <div class="card-header bg-white text-secondary d-flex align-items-center justify-content-between border border-light-subtle">
                        <h4>Empresas</h4>
                        <div class="form-group m-0">
                            <button type="button" class="btn btn btn-outline-primary btn-sm px-4" id="btnAdd">Agregar Empresa</button>
                        </div>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">
                            <table id="empresa-table" class="table order-list w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Ruc</th>
                                        <th>Nombre</th>
                                        <th>Accion</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($company_list as $key => $company)
                                        <tr data-id="{{ $company->id }}">
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $company->ruc }}</td>
                                            <td>{{ $company->empresa }}</td>
                                            <td>
                                                <button type="button" data-id="{{ $company->id }}" class="btn btn-light border btnEdit"><i class="bi bi-pen"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div id="createModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title">Agregar Empresa</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">

                    <form method="post" enctype="multipart/form-data" id="formInsert">
                        @csrf 
                        <div class="row">
                    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="ruc" class="form-label">RUC: </label>
                                    <input type="text" class="form-control" name="ruc" id="ruc" required>
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="empresa" class="form-label">Empresa: </label>
                                    <input type="text" class="form-control" name="empresa" id="empresa" required>
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="host" class="form-label">Host: </label>
                                    <input type="text" class="form-control" name="host" id="host" required>
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="base" class="form-label">Base de datos: </label>
                                    <input type="text" class="form-control" name="base" id="base" required>
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="bdusuario" class="form-label">Usuario DB: </label>
                                    <input type="text" class="form-control" name="bdusuario" id="bdusuario" required>
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="bdclave" class="form-label">Clave DB: </label>
                                    <input type="text" class="form-control" name="bdclave" id="bdclave" required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="own_db" id="own_db" @checked(true)>
                                        <label class="form-check-label" for="own_db">
                                            Propia Base de datos
                                        </label>
                                    </div>
                                </div>
                            </div>
                    
                        </div>
                    
                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="form-group">
                                    <button type="submit" class="btn btn-outline-primary btn-sm px-4">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title">Editar Usuario</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    
                    <form method="POST" enctype="multipart/form-data" id="formUpdate">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">  <!-- Spoofing de PUT -->
                        <div class="row">
        
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">RUC: </label>
                                    <input type="text" class="form-control" name="ruc" id="edit_ruc" @required(true)>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Empresa: </label>
                                    <input type="text" class="form-control" name="empresa" id="edit_empresa" @required(true)>
                                </div>
                            </div>
    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Host: </label>
                                    <input type="text" class="form-control" name="host" id="edit_host" @required(true)>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Base de datos: </label>
                                    <input type="text" class="form-control" name="base" id="edit_base" @required(true)>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Usuario DB: </label>
                                    <input type="text" class="form-control" name="bdusuario" id="edit_bdusuario" @required(true)>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Clave DB: </label>
                                    <input type="text" class="form-control" name="bdclave" id="edit_bdclave" @required(true)>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-6 col-sm-12 mt-3">
                                <div class="d-flex align-items-center">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="own_db" id="edit_own_db">
                                        <label class="form-check-label" for="edit_own_db">
                                            Propia Base de datos
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col-12 d-flex justify-content-end">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="company_id" @required(true)>
                                    <button type="submit" class="btn btn-outline-primary btn-sm px-4">Guardar</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
<script type="text/javascript">

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        $('#empresa-table').DataTable( {
            "order": [],
            'language': {
                'lengthMenu': '_MENU_ Filter',
                "info":      '<small>Showing _START_ - _END_ (_TOTAL_)</small>',
                "search":  'Search',
                'paginate': {
                        'previous': '<i class="dripicons-chevron-left"></i>',
                        'next': '<i class="dripicons-chevron-right"></i>'
                }
            },
            'columnDefs': [
                {
                    "orderable": false,
                    'targets': [0, 3]
                },
            ],
            'lengthMenu': [[10, 25, 50, -1], [10, 25, 50, "All"]],
        });
    });

    $('#btnAdd').click(function (e) { 
        e.preventDefault();
        $('#createModal').modal('show');
    });

    $('.btnEdit').click(function (e) { 
        e.preventDefault();
        var companyId = $(this).data('id');
        getData(companyId);
    });

    $('#formInsert').submit(function (e) { 
        e.preventDefault();
        var formData    = new FormData($(this)[0]);
        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Procesando...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                ajaxInsert(formData);
            }
        });
    });

    $('#formUpdate').submit(function (e) { 
        e.preventDefault();
        var formData    = new FormData($(this)[0]);
        var userId      = $('input[name="id"]').val();
        
        formData.append('_method', 'PUT');

        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Procesando...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                ajaxUpdate(formData, userId);
            }
        });
        
    });


    /* FUNCTIONS */
    function ajaxInsert(formData) {
        $.ajax({
            type: "POST",
            url: "{{ route('company.store') }}",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#createModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: "Proceso Exitoso",
                    text: response.message,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    for (let key in errors) {
                        errorMessage += errors[key].join(', ') + '\n'; // Une los mensajes de error
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error de Validación',
                        text: errorMessage,
                        confirmButtonText: 'Aceptar'
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Ocurrió un error al procesar tu solicitud.',
                        confirmButtonText: 'Aceptar'
                    });
                }
            }
        });
    }

    function getData(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('company.edit', ':id') }}".replace(':id', id),
            success: function (response) {
                $('input[name="id"]').val(response.id);
                $('#edit_ruc').val(response.ruc);
                $('#edit_empresa').val(response.empresa);
                $('#edit_host').val(response.host);
                $('#edit_base').val(response.base);
                $('#edit_bdusuario').val(response.bdusuario);
                $('#edit_bdclave').val(response.bdclave);
                $('#edit_own_db').prop('checked', response.own_db);
                $('#editModal').modal('show');
            },
            error: function (xhr) {
                let errorMessage = xhr.responseJSON.error;
                if (xhr.status === 404) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Error de consulta',
                        text: errorMessage,
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false,
                    });
                } else if (xhr.status === 500) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error del Servidor',
                        text: xhr.responseJSON.error,
                        confirmButtonText: 'Aceptar',
                        allowOutsideClick: false,
                    });
                }
            }
        });
    }

    function ajaxUpdate(formData, userId) { 
        $.ajax({
            type: "POST",
            url: "{{ route('company.update', ':id') }}".replace(':id', userId),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Proceso Exitoso',
                    text: response.message,
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    timer: 1500
                }).then(() => {
                    location.reload();
                });
            },
            error: function (xhr) {
                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let errorMessage = '';
                    for (let field in errors) {
                        errorMessage += errors[field].join(', ') + '<br>';
                    }
                    Swal.fire('Error de validación', errorMessage, 'error');
                } else if (xhr.status === 404) {
                    Swal.fire('Error', 'Empresa no encontrada.', 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON.error || 'Ocurrió un error al actualizar la empresa.', 'error');
                }
            }
        });
    }

    function eliminar(id) {
        Swal.fire({
            title: '¿Está seguro de eliminar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('company.destroy', ':id') }}".replace(':id', id), // Reemplazar con el ID de la empresa
                    data: {
                        _token: '{{ csrf_token() }}' // Incluir el token CSRF
                    },
                    success: function (response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Eliminado',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            location.reload(); // Recargar la página para reflejar los cambios
                        });
                    },
                    error: function (xhr) {
                        Swal.fire('Error', xhr.responseJSON.error || 'Ocurrió un error al eliminar la empresa.', 'error');
                    }
                });
            }
        });
    }

    function refreshTable() {
        var table = $('#info_table').DataTable();
        table.ajax.reload();
    }


</script>
@endpush

