@extends('layouts.app')

@section('content')
<div class="card" id="categories">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-10">
                <h5 class="card-title">Categoría</h5>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-warning btn-sm float-right" id="btnAdd" data-toggle="modal"
                    data-target="#modalCategory">
                    Agregar <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
        <table class="table table-striped" id="categoriesTable">
            <thead>
                <th>Categoria</th>
                <th>Acciones</th>
            </thead>
        </table>
    </div>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function () {
            initDatatable();
        });

        function initDatatable() {
            $('#categoriesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                processing: true,
                serverSide: false,
                pagination: false,
                ajax: '/categories/all',
                columns: [
                    {
                        data: 'name'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            let btnUpdate =
                                '<button type="button" class="btn btn-sm btn-primary btnEdit" id="btnEdit-' +
                                row.id + ' data-id="' + row.id +
                                '"><i class="fa-solid fa-pen-to-square"></i> Editar</button>';
                            let btnView =
                                '<button type="button" class="btn btn-sm btn-success btnView" id="btnView-' +
                                row.id + ' data-id="' + row.id +
                                '"><i class="fa-solid fa-eye"></i> Ver</button>';
                            let btnDelete =
                                '<button type="button" class="btn btn-sm btn-danger btnDelete" id="btnDelete-' +
                                row.id + ' data-id="' + row.id +
                                '"><i class="fa-solid fa-trash"></i> Borrar</button>';
                            return btnUpdate + ' ' + btnView + ' ' + btnDelete;
                        }
                    },
                ]
            });
        }
    </script>
@endsection