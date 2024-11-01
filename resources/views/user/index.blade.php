@extends('layout.main')

@section('content')
<section class="forms">

    <div class="container-fluid">

        <div class="row">

            <div class="col-12">

                <div class="card border border-light-subtle">

                    <div class="card-header bg-white text-secondary d-flex align-items-center justify-content-between border border-light-subtle">
                        <h4>Usuario</h4>
                        <div class="form-group m-0">
                            <button type="button" class="btn btn btn-outline-primary btn-sm px-4" id="btnAdd">Agregar Usuario</button>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="user-table" class="table order-list w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Usuario</th>
                                        <th>Acción</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($user_list as $key => $user)
                                    <tr data-id="{{ $user->id }}">
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>
                                            <button type="button" data-user_id="{{ $user->id }}" class="btn btn-light border btnEdit"><i class="bi bi-pen"></i></button>
                                            @if ($user->id !== 1)
                                            <button type="button" class="btn btn-outline-danger" onclick="return eliminar({{ $user->id }})"><i class="bi bi-trash"></i></button>
                                            @endif
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

    <div id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title">Agregar Usuario</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">

                    <form method="post" enctype="multipart/form-data" id="insert_user">
                        @csrf 
                        <div class="row">
        
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Nombre de Usuario: </label>
                                    <input type="text" class="form-control" name="name" id="name" @required(true)>
                                </div>
                            </div>
    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Password: </label>
                                    <input type="password" class="form-control" name="password" id="password" @required(true)>
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

    <div id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade text-left">
        <div role="document" class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title">Editar Usuario</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">
                    
                    <form method="POST" enctype="multipart/form-data" id="update_user">
                        @csrf
                        <input type="hidden" name="_method" value="PUT">  <!-- Spoofing de PUT -->
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Nombre de Usuario: </label>
                                    <input type="text" class="form-control" name="name" id="edit_name" @required(true)>
                                </div>
                            </div>
                    
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label for="" class="form-label">Password: </label>
                                    <input type="text" class="form-control" name="password" id="edit_password">
                                </div>
                            </div>
                    
                            <div class="col-12 d-flex justify-content-end">
                                <div class="form-group">
                                    <input type="hidden" name="id" id="user_id" @required(true)>
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
    
    $(document).ready(function() {
        $('#user-table').DataTable( {
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
                    'targets': [0, 2]
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
        var userId = $(this).data('user_id');
        getData(userId);
    });

    $('form#insert_user').submit(function (e) { 
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

    $('form#update_user').submit(function (e) {  
        e.preventDefault();
        
        var formData    = new FormData($(this)[0]);
        var userId      = $('input[name="id"]').val();  // Obtener el ID del usuario desde el input hidden
        
        formData.append('_method', 'PUT');

        Swal.fire({
            position: 'center',
            icon: 'info',
            title: 'Procesando...',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                ajaxUpdate(formData, userId);  // Pasamos el ID como parámetro
            }
        });
    });


    /* FUNCIONES */
    function ajaxInsert(formData) {
        $.ajax({
            type: "POST",
            url: "{{ route('user.store') }}",
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
                let errorMessage = '';
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    for (const key in errors) {
                        errorMessage += errors[key].join(', ') + '\n';
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Errores de Validación',
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

    function getData(id) {
        $.ajax({
            type: "GET",
            url: "{{ route('user.edit', ':id') }}".replace(':id', id),
            success: function (response) {
                $('input[name="id"]').val(response.id);
                $('#edit_name').val(response.name); 
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
            url: "{{ route('user.update', ':id') }}".replace(':id', userId),
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                $('#editModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: "Proceso Exitoso",
                    text: response.success,
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
                    Swal.fire('Error', 'Usuario no encontrado.', 'error');
                } else {
                    Swal.fire('Error', xhr.responseJSON.error || 'Ocurrió un error al actualizar el usuario.', 'error');
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
                    url: "{{ route('user.destroy', ':id') }}".replace(':id', id), // Reemplazar con el ID de la empresa
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


</script>
@endpush
