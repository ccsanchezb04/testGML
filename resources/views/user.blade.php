@extends('layouts.app')

@section('content')
    <div class="card" id="users">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-10">
                    <h5 class="card-title">Usuario</h5>
                </div>
                <div class="col-sm-2">
                    <button class="btn btn-warning btn-sm float-right" id="btnAdd" data-toggle="modal"
                        data-target="#modalUser">
                        Agregar <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            </div>
            <table class="table table-striped" id="usersTable">
                <thead>
                    <th>Cédula</th>
                    <th>Usuario</th>
                    <th>Celular</th>
                    <th>Email</th>
                    <th>Categoria</th>
                    <th>Acciones</th>
                </thead>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalUser" role="dialog" aria-labelledby="modalUserLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalUserLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form autocomplete="off" novalidate class="was-validated" id="usersForm">
                        {{--  Nombres, apellidos, cédula, email, país, dirección y celular --}}
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="userName">Nombre(s):</label>
                                    <input type="text" class="form-control onlyText" id="userName" name="user[name]"
                                        placeholder="Ingrese Nombre(s)" minlength="5" maxlength="100" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="userLastName">Apellidos(s):</label>
                                    <input type="text" class="form-control onlyText" id="userLastName"
                                        name="user[last_name]" placeholder="Ingrese Apellidos(s)" minlength="5"
                                        maxlength="100" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="userIdNumber">Cédula:</label>
                                    <input type="text" class="form-control" id="userIdNumber" name="user[id_number]"
                                        placeholder="Ingrese Cédula" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="userPhoneNumber">Celular:</label>
                                    <input type="text" class="form-control onlyNumber" id="userPhoneNumber"
                                        name="user[phone_number]" placeholder="Ingrese Celular" minlength="5"
                                        maxlength="180" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="userEmail">Correo Electrónico:</label>
                                    <input type="email" class="form-control" id="userEmail" name="user[email]"
                                        placeholder="Ingrese Correo Electrónico" maxlength="150" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="userAddress">Dirección:</label>
                                    <input type="text" class="form-control" id="userAddress" name="user[address]"
                                        placeholder="Ingrese Dirección" maxlength="180" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="userCountry">País:</label>
                                    {{-- <input type="text"  id="userIdNumber" name="" placeholder="Ingrese Cédula"> --}}
                                    <select class="form-control" name="user[country]" id="userCountry" required></select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="userCategoryId">Categoria:</label>
                                    {{-- <input type="text" class="form-control" id="userPhoneNumber" name="user[category_id]" placeholder="Ingrese Celular"> --}}
                                    <select class="form-control" name="user[category_id]" id="userCategoryId"
                                        required></select>
                                    <input type="hidden" name="user[id]" id="userId">
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
        /*
         * Faltan acciones crud (Dependen de validateFomr) 
         */
        $(document).ready(function() {
            initDatatable();
            getCountries();
            getCategories();

            $('#usersForm select').select2({
                placeholder: "Seleccione una opción",
                allowClear: true,
                theme: "bootstrap"
            });

            $('#usersForm select').val('').trigger('change');
        });

        $('#btnAdd').click(function(e) {
            $('#modalUser #modalUserLabel').text('Agregar Usuario');
            cleanForm();
            $('#action').val('add');
            $("#btnSave").show();
            $("#usersForm input").prop('readonly', false);
            $("#usersForm select").prop('disabled', false);
        });

        $('#btnSave').click(function(e) {
            e.preventDefault();
            let validate = formValidate('#usersForm');

            let dataSend = $('#usersForm').serialize();

            if (validate) {
                axios.post('/users/save', dataSend)
                    .then((response) => {
                        console.log(response.data);
                        if (response.data.status) {
                            swal({
                                title: "Éxito!",
                                text: response.data.msm,
                                icon: "success",
                            });

                            $("#modalUser").modal('hide');
                            cleanForm();
                            $('#usersTable').DataTable().destroy();
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
                    })
            }
        });

        $("#userIdNumber").focusout(function(e) {
            validateUser("idNumber", $(this).val());
        });

        $("#userEmail").focusout(function(e) {
            validateUser("email", $(this).val());
        });

        function getCountries() {
            let countries = [];
            axios.get('https://restcountries.com/v3.1/subregion/South America')
                .then((response) => {
                    $('#userCountry').empty();
                    countries = response.data;
                    let selectCountry = '';

                    countries.sort(
                        (p1, p2) => (p1.name.common < p2.name.common) ? -1 : 0
                    );

                    $.each(countries, (indexInArray, valueOfElement) => {
                        selectCountry += '<option value="' + valueOfElement.cca2 + '">' + valueOfElement
                            .name
                            .common + '</option>';
                    });

                    $('#userCountry').append(selectCountry);
                }).catch((error) => {
                    console.log(error);
                    swal({
                        title: "Error!",
                        text: "No se pudo cargar la información solicitada",
                        icon: "error",
                    });
                });
        }

        function getCategories() {
            let categories = '';
            axios.get('/categories/all')
                .then((response) => {
                    if (response.data.status) {
                        $('#userCategoryId').empty();
                        categories = response.data.data;
                        let selectCategory = '';

                        $.each(categories, (indexInArray, valueOfElement) => {
                            selectCategory += '<option value="' + valueOfElement.id + '">' +
                                valueOfElement
                                .name + '</option>';
                        });

                        $('#userCategoryId').append(selectCategory);
                    } else {
                        swal({
                            title: "Error!",
                            text: "No se pudo cargar la información solicitada",
                            icon: "error",
                        });
                    }
                }).catch((error) => {
                    console.log(error);
                    swal({
                        title: "Error!",
                        text: "No se pudo cargar la información solicitada",
                        icon: "error",
                    });
                });
        }

        function actionUser(element) {

            let action = $(element).data('action');
            let idUser = $(element).data('id');

            cleanForm();
            $('#modalUser').modal('show');

            if (action == "edit") {
                $('#modalUser #modalUserLabel').text('Editar Usuario');
                $('#action').val('edit');
                $("#btnSave").show();
                $("#usersForm input").prop('readonly', false);
                $("#usersForm select").prop('disabled', false);
            }

            if (action == "view") {
                $('#modalUser #modalUserLabel').text('Ver Usuario');
                $('#action').val('view');
                $("#btnSave").hide();
                $("#usersForm input").prop('readonly', true);
                $("#usersForm select").prop('disabled', true);
            }

            let infoUser = '';

            axios.get('/users/getUserById', {
                    params: {
                        "idUser": idUser
                    }
                })
                .then((response) => {
                    if (response.data.status) {
                        infoUser = response.data.data;

                        $.each(infoUser, (indexInArray, valueOfElement) => {
                            console.log(valueOfElement);
                            $("[name='user[" + indexInArray + "]']").val(valueOfElement);
                        });

                        $('#usersForm select').trigger('change');

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

        function deleteUser(element) {
            let idUser = $(element).data('id');

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
                        axios.get('/users/delete', {
                                params: {
                                    "idUser": idUser
                                }
                            })
                            .then((response) => {
                                if (response.data.status) {
                                    swal({
                                        title: "Éxito!",
                                        text: response.data.msm,
                                        icon: "success",
                                    });

                                    $('#usersTable').DataTable().destroy();
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

        function validateUser(field, value) {
            axios.get('/users/validateUser', {
                    params: {
                        "field": field,
                        "dataSend": value
                    }
                })
                .then((response) => {
                    console.log(response.data);
                    if (response.data.status) {
                        if (response.data.count > 0) {
                            let valMsm = (field == 'idNumber') ? 'esta Cédula' : 'este Correo Electrónico';
                            swal({
                                title: "Alerta!",
                                text: "Ya hay un usuario registrado con " + valMsm,
                                icon: "error",
                            });

                            $("#btnSave").prop('disabled', true);
                        } else {
                            $("#btnSave").prop('disabled', false);
                        }
                    } else {
                        swal({
                            title: "Error!",
                            text: "No se pudo cargar la información solicitada",
                            icon: "error",
                        });
                    }
                })
                .catch((error) => {
                    console.log(error);
                    swal({
                        title: "Error!",
                        text: "No se pudo cargar la información solicitada",
                        icon: "error",
                    });
                });
        }

        function initDatatable() {
            $('#usersTable').DataTable({
                "language": {
                    "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
                },
                processing: true,
                serverSide: false,
                pagination: false,
                ajax: '/users/all',
                columns: [{
                        data: 'id_number'
                    },
                    {
                        data: 'complete_name'
                    },
                    {
                        data: 'phone_number'
                    },
                    {
                        data: 'email'
                    },
                    {
                        data: 'category_user'
                    },
                    {
                        data: 'id',
                        render: function(data, type, row) {
                            let btnUpdate =
                                '<button type="button" class="btn btn-sm btn-primary btnEdit" id="btnEdit-' +
                                row.id + '" data-id="' + row.id +
                                '" data-action="edit" onclick="actionUser(this)"><i class="fa-solid fa-pen-to-square"></i> Editar</button>';
                            let btnView =
                                '<button type="button" class="btn btn-sm btn-success btnView" id="btnView-' +
                                row.id + '" data-id="' + row.id +
                                '" data-action="view" onclick="actionUser(this)"><i class="fa-solid fa-eye"></i> Ver</button>';
                            let btnDelete =
                                '<button type="button" class="btn btn-sm btn-danger btnDelete" id="btnDelete-' +
                                row.id + '" data-id="' + row.id +
                                '" onclick="deleteUser(this)"><i class="fa-solid fa-trash"></i> Borrar</button>';
                            return btnUpdate + ' ' + btnView + ' ' + btnDelete;
                        }
                    },
                ]
            });
        }

        function cleanForm() {
            $("#usersForm input").val('');
            $("#usersForm select").val('').trigger('change');
        }
    </script>
@endsection
