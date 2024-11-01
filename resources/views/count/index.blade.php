@extends('layout.main')

@section('content')
<section class="forms">

    <div class="container-fluid">

        <div class="row mb-4">
            <div class="col-12">
                <div class="card border border-light-subtle">

                    <div class="card-header bg-white text-secondary d-flex align-items-center justify-content-between border border-light-subtle">
                        <h4>Filtros</h4>
                    </div>

                    <div class="card-body">
                        <form method="POST" id="formFilter" enctype="multipart/form-data">

                            <div class="row">

                                <div class="col-md-3 mb-3">
                                    <label for="company-select" class="form-label">Tipo</label>
                                    <select id="tipo" name="tipo" class="form-control" data-live-search="true" data-live-search-normalize="true">
                                        <option value="All">Todo</option>
                                        <option value="FC">Factura</option>
                                        <option value="NC">Nota de Credito</option>
                                        <option value="RT">Retencion</option>
                                        <option value="GR">Guia de Remision</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="company-select" class="form-label">Empresa</label>
                                    <select id="company_id" name="company_id" class="form-control" data-live-search="true" data-live-search-normalize="true">
                                        <option value="0">Seleccione Empresa</option>
                                        @foreach ($company_list as $company)
                                            <option value="{{ $company->id }}">{{ $company->empresa }} ({{ $company->ruc }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="status-select" class="form-label">Estado</label>
                                    <select id="status" name="status" class="form-control" data-live-search="true" data-live-search-normalize="true" title="SELECCIONAR">
                                        <option value="no_autorizada">No Autorizada</option>
                                        <option value="autorizada">Autorizada</option>
                                    </select>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="date-range" class="form-label">Rango de Fechas</label>
                                    <input type="text" name="date_range" id="date-range" class="form-control">
                                </div>
                                
                            </div>

                            <div class="row">
                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                                </div>
                            </div>

                        </form>
                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card border border-light-subtle">

                    <div class="card-header bg-white text-secondary d-flex align-items-center justify-content-between border border-light-subtle">
                        <h4>Conteo</h4>
                        <button type="button" class="btn btn-primary" onclick="refreshTable()"><i class="bi bi-arrow-clockwise"></i> Refrescar Tabla</button>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="report-table" class="table order-list w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Tipo</th>
                                        <th>Ruc</th>
                                        <th>Mensaje</th>
                                        <th>Clave</th>
                                        <th>No Documento</th>
                                        <th>Fecha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <div id="viewInfo" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">

            <div class="modal-content">
                
                <div class="modal-header">
                    <h4 id="exampleModalLabel" class="modal-title">Detalles</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body">

                    <div class="row">

                        <div class="col-12">

                            <div class="table-responsive">

                                <table id="info_table" class="table table-sm table-hover w-full">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tipo</th>
                                            <th>Estado</th>
                                            <th>Mensaje</th>
                                            <th>Clave de acceso</th>
                                            <th>No Factura</th>
                                            <th>Fecha</th>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="viewXmlModal" tabindex="-1" role="dialog" aria-labelledby="viewXmlModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewXmlModalLabel">Contenido XML</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form method="post" enctype="multipart/form-data" id="updateXml">

                        <div class="row">

                            <div class="col-12 mb-3">
                                <div class="form-group">
                                    <label for="xmlTextarea">XML</label>
                                    <textarea id="xmlTextarea" name="xml" class="form-control" rows="15" placeholder="Aquí se mostrará el contenido del XML..."></textarea>
                                </div>
                            </div>
    
                            <input type="hidden" name="key" id="xmlKey">
                            <input type="hidden" name="id" id="companyXmlId">
    
                            <div class="col-12 d-flex justify-content-between">
                                <button type="button" class="btn btn-secondary" id="copyXmlBtn">Copiar XML</button>
                                <button type="submit" class="btn btn-primary">Enviar</button>
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
        rangeDate();
        dataTable();
    });

    $('#formFilter').on('submit', function(e) {
        e.preventDefault();
        $('#report-table').DataTable().ajax.reload();
    });

    $('#updateXml').submit(function (e) { 
        e.preventDefault();
        var formData    = new FormData($(this)[0]);
        updateXml(formData)
    });

    $('#copyXmlBtn').on('click', function() {
        var xmlTextarea = $('#xmlTextarea');
        xmlTextarea.select();

        try {
            document.execCommand('copy');
            alert('El XML ha sido copiado al portapapeles.');
        } catch (err) {
            alert('No se pudo copiar el contenido.');
        }
    });


    function rangeDate() {
        $('#date-range').daterangepicker({
            locale: {
                format: 'DD/MM/YYYY',
                separator: " - ",
                applyLabel: "Aplicar",
                cancelLabel: "Cancelar",
                fromLabel: "Desde",
                toLabel: "Hasta",
                customRangeLabel: "Personalizado",
                daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
            },
            maxDate: moment(),
        });
    }

    function dataTable() {
        $('#report-table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                url: "{{ route('filterTable') }}",
                type: "POST",
                data: function (d) {
                    d.company_id    = $('#company_id').val();
                    d.status        = $('#status').val();
                    d.tipo          = $('#tipo').val();
                    d.date_range    = $('#date-range').val();
                },
                error: function(xhr, status, error) {
                    console.error("Error en la solicitud:", error);
                    alert("Ha ocurrido un error. Por favor, inténtelo de nuevo.");
                }
            },
            "columns": [
                {"data": "key"},
                {"data": "tipo"},
                {"data": "ruc"},
                {"data": "message"},
                {"data": "clave"},
                {"data": "no_documento"},
                {"data": "fecha"},
                {"data": "options"},
            ],
            order: [['1', 'asc']],
            'language': {
                'lengthMenu': '_MENU_ Records per page',
                "info": 'Showing _START_ - _END_ (_TOTAL_)',
                "search": 'Search',
                'paginate': {
                    'previous': 'Previous',
                    'next': 'Next'
                }
            },
            'columnDefs': [
                {
                    "orderable": false,
                    'targets': [0, 2, 3, 4, 5, 6, 7]
                },
            ],
            'lengthMenu': [[10, 25, 50, 100], [10, 25, 50, 100]],
        });
    }

    function viewXml(id, key) {
        $.ajax({
            type: "POST",
            url: "{{ route('getXml', ['id' => '__ID__', 'key' => '__KEY__']) }}".replace('__ID__', id).replace('__KEY__', key),
            success: function(response) {
                if (response.xml) {
                    $('#companyXmlId').val(response.id);
                    $('#xmlTextarea').val(response.xml);
                    $('#xmlKey').val(response.key);
                    $('#viewXmlModal').modal('show');
                } else {
                    Swal.fire({
                        icon: 'info',
                        title: 'Sin Resultados',
                        text: response.message || 'No se encontró el XML.',
                        showConfirmButton: true,
                        allowOutsideClick: false,
                    });
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON && xhr.responseJSON.error ? xhr.responseJSON.error : 'Ocurrió un error inesperado.';
                Swal.fire({
                    icon: 'error',
                    title: "Proceso Fallido",
                    text: message,
                    showConfirmButton: true,
                    allowOutsideClick: false,
                });
            }
        });
    }

    function newAccessKey(id, key) {
        $.ajax({
            type: "POST",
            url: "{{ route('newAccessKey', ['id' => '__ID__', 'key' => '__KEY__']) }}"
                .replace('__ID__', id)
                .replace('__KEY__', key),
            success: function(response) {
                if (response.status) {
                    // Copiar la clave generada al portapapeles
                    navigator.clipboard.writeText(response.key)
                        .then(() => {
                            Swal.fire({
                                icon: 'success',
                                title: "Clave Generada",
                                text: 'La nueva clave de acceso es: ' + response.key + ' (copiada al portapapeles)',
                                showConfirmButton: true,
                                allowOutsideClick: false,
                            });
                        })
                        .catch(err => {
                            Swal.fire({
                                icon: 'warning',
                                title: "Clave Generada",
                                text: 'La nueva clave de acceso es: ' + response.key + '. No se pudo copiar automáticamente al portapapeles. Intenta copiarla manualmente.',
                                showConfirmButton: true,
                                allowOutsideClick: false,
                            });
                        });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: "Proceso Fallido",
                        text: 'Ocurrió un problema: ' + response.message,
                        showConfirmButton: true,
                        allowOutsideClick: false,
                    });
                }
            },
            error: function(xhr) {
                var message = xhr.responseJSON && xhr.responseJSON.message 
                    ? xhr.responseJSON.message 
                    : 'Ocurrió un error inesperado.';
                Swal.fire({
                    icon: 'error',
                    title: "Proceso Fallido",
                    text: message,
                    showConfirmButton: true,
                    allowOutsideClick: false,
                });
            }
        });
    }

    function resent(id, key) {
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espera un momento',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('company.resent', ['id' => '__ID__', 'key' => '__KEY__']) }}"
                        .replace('__ID__', id)
                        .replace('__KEY__', key),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Incluye el token CSRF
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Proceso Exitoso',
                                text: response.message,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timer: 1500
                            }).then(() => {
                                refreshTable();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Proceso Fallido',
                                text: response.message,
                                showConfirmButton: true,
                                allowOutsideClick: false,
                            });
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message 
                            ? xhr.responseJSON.message 
                            : 'Ocurrió un error inesperado.';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Error',
                            text: errorMessage,
                            showConfirmButton: true,
                            allowOutsideClick: false,
                        });
                    }
                });
            }
        });
    }

    function updateXml(formData) {
        Swal.fire({
            title: 'Procesando...',
            text: 'Por favor espera un momento',
            icon: 'info',
            showConfirmButton: false,
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('company.updateXml') }}",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            $('#viewXmlModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Proceso Exitoso',
                                text: response.message,
                                showConfirmButton: false,
                                allowOutsideClick: false,
                                timer: 1500
                            }).then(() => {
                                refreshTable();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Proceso Fallido',
                                text: response.message,
                                showConfirmButton: true,
                                allowOutsideClick: false,
                            });
                        }
                    },
                    error: function (xhr) {
                        var errorMessage = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : 'Ocurrió un error inesperado.';
                        Swal.fire({
                            icon: 'warning',
                            title: 'Error',
                            text: errorMessage,
                            showConfirmButton: true,
                            allowOutsideClick: false,
                        });
                    }
                });
            }
        });
    }

    function refreshTable() {
        var table = $('#report-table').DataTable();
        table.ajax.reload();
    }
    
</script>
@endpush