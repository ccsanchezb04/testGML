@extends('layouts.app')

@section('content')
<div class="card" id="config">
    <div class="card-body">
        <h5 class="card-title">Configuración</h5>
        <div class="container">
            <div class="row">
                <div class="col-md-offset-4 col-md-4">
                    <form novalidate autocomplete="off" class="was-validated" id="configForm">
                        <div class="form-group">
                            <label for="configEmailAdmin">Correo Admin</label>
                            <input type="text" class="form-control" id="configEmailAdmin" name="config[admin_email]"
                                placeholder="Ingrese Correo del administrador">
                        </div>
                        <button type="button" class="btn btn-primary" id="btnSaveConfig">Guardar <i class="fa-solid fa-floppy-disk"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    
@endsection