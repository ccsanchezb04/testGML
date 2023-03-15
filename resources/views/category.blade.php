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

    <div class="modal fade" id="modalCategory" role="dialog" aria-labelledby="modalCategoryLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCategoryLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" novalidate class="was-validated" id="categoriesForm">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="categoryName">Nombre:</label>
                                    <input type="text" class="form-control" id="categoryName" name="category[name]"
                                        placeholder="Ingrese Categoría" required>
                                    <input type="hidden" name="category[id]" id="categoryId">
                                    <input type="hidden" id="action">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar <i
                            class="fa-solid fa-xmark"></i></button>
                    <button type="button" class="btn btn-primary" id="btnSave">Guardar <i
                            class="fa-solid fa-floppy-disk"></i></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            initDatatable();
        });

        $('#btnAdd').click(function(e) {
            $('#modalCategory #modalCategoryLabel').text('Agregar Categoría');
            cleanForm();
            $('#action').val('add');
            $("#btnSave").show();
            $("#categoriesForm input").prop('readonly', false);
            $("#categoriesForm select").prop('disabled', false);
        });

        $('#btnSave').click(function(e) {
            e.preventDefault();
            let validate = formValidate('#categoriesForm');

            let dataSend = $('#categoriesForm').serialize();

            if (validate) {
                axios.post('/categories/save', dataSend)
                    .then((response) => {
                        console.log(response.data);
                        if (response.data.status) {
                            swal({
                                title: "Éxito!",
                                text: response.data.msm,
                                icon: "success",
                            });

                            $("#modalCategory").modal('hide');
                            cleanForm();
                            $('#categoriesTable').DataTable().destroy();
                            initDatatable();
                        } else {
                            swal({
                                title: "Error!",
                                text: "No se puede procesar el registro",
                                icon: "error",
                            });
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                        swal({
                            title: "Error!",
                            text: "No se puede procesar el registro",
                            icon: "error",
                        });
                    });
            }
        });

        function actionCategory(element) {

            let action = $(element).data('action');
            let idCategory = $(element).data('id');

            cleanForm();
            $('#modalCategory').modal('show');

            if (action == "edit") {
                $('#modalCategory #modalCategoryLabel').text('Editar Usuario');
                $('#action').val('edit');
                $("#btnSave").show();
                $("#categoriesForm input").prop('readonly', false);
            }

            if (action == "view") {
                $('#modalCategory #modalCategoryLabel').text('Ver Usuario');
                $('#action').val('view');
                $("#btnSave").hide();
                $("#categoriesForm input").prop('readonly', true);
            }

            let infoCategory = '';

            axios.get('/categories/getCategoryById', {
                    params: {
                        "idCategory": idCategory
                    }
                })
                .then((response) => {
                    if (response.data.status) {
                        infoCategory = response.data.data;

                        $.each(infoCategory, (indexInArray, valueOfElement) => {
                            console.log(valueOfElement);
                            $("[name='category[" + indexInArray + "]']").val(valueOfElement);
                        });

                        $('#categoriesForm select').trigger('change');

                    } else {
                        swal({
                            title: "Error!",
                            text: "No se pudo cargar la información solicitada",
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    swal({
                        title: "Error!",
                        text: "No se pudo cargar la información solicitada",
                        icon: "error",
                    });
                });
        }

        function deleteCategory(element) {
            let idCategory = $(element).data('id');

            swal("¿Desea eliminar este registro?", {
                    buttons: {
                        cancel: {
                            text: "Cancelar",
                            value: false,
                            visible: true,
                            className: "",
                            closeModal: true,
                        },
                        confirm: {
                            text: "Borrar",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        }
                    },
                })
                .then((value) => {
                    if (value) {
                        axios.get('/categories/delete', {
                                params: {
                                    "idCategory": idCategory
                                }
                            })
                            .then((response) => {
                                if (response.data.status) {
                                    swal({
                                        title: "Éxito!",
                                        text: response.data.msm,
                                        icon: "success",
                                    });

                                    $('#categoriesTable').DataTable().destroy();
                                    initDatatable();
                                } else {
                                    swal({
                                        title: "Error!",
                                        text: "No se puede eliminar este registro",
                                        icon: "error",
                                    });
                                }
                            })
                            .catch((error) => {
                                console.log(error);
                                swal({
                                    title: "Error!",
                                    text: "No se puede eliminar este registro",
                                    icon: "error",
                                });
                            });
                    }
                });

        }

        function initDatatable() {
            $('#categoriesTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                processing: true,
                serverSide: false,
                pagination: false,
                ajax: '/categories/all',
                columns: [{
                        data: 'name'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            let btnUpdate =
                                '<button type="button" class="btn btn-sm btn-primary btnEdit" id="btnEdit-' +
                                row.id + '" data-id="' + row.id +
                                '" data-action="edit" onclick="actionCategory(this)"><i class="fa-solid fa-pen-to-square"></i> Editar</button>';
                            let btnView =
                                '<button type="button" class="btn btn-sm btn-success btnView" id="btnView-' +
                                row.id + '" data-id="' + row.id +
                                '" data-action="view" onclick="actionCategory(this)"><i class="fa-solid fa-eye"></i> Ver</button>';
                            let btnDelete =
                                '<button type="button" class="btn btn-sm btn-danger btnDelete" id="btnDelete-' +
                                row.id + '" data-id="' + row.id +
                                '" onclick="deleteCategory(this)"><i class="fa-solid fa-trash"></i> Borrar</button>';
                            return btnUpdate + ' ' + btnView + ' ' + btnDelete;
                        }
                    },
                ]
            });
        }

        function cleanForm() {
            $("#categoriesForm input").val('');
            $("#categoriesForm select").val('').trigger('change');
        }
    </script>
@endsection
