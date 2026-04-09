@extends('app.layout')
@section('content')

    <div class="col-12 col-sm-12 col-md-8 offset-md-2 col-lg-8 offset-lg-2">
        <div class="card demo-inline-spacing">
            <div class="card-header align-items-center">
                <h5 class="card-action-title mb-0">Dados do Perfil</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('updated-user', ['uuid' => $user->uuid]) }}" method="POST" class="row">
                    @csrf
                    <div class="col-12 col-sm-12 col-md-12 col-lg-12 d-flex justify-content-center align-items-center flex-column text-center mb-3">
                        <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : asset('assets/img/avatars/man.png') }}" alt="Perfil de {{ Auth::user()->name }}" class="d-block w-px-100 h-px-100 rounded-4" id="change-photo-button" style="cursor: pointer;"/>
                    </div>
                    <div class="col-12 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" name="name" id="name" class="form-control" placeholder="Nome:" value="{{ $user->name }}"/>
                            <label for="name">Nome:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="email" name="email" id="email" class="form-control" placeholder="E-mail:" value="{{ $user->email }}"/>
                            <label for="email">E-mail:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" name="phone" id="phone" class="form-control phone" placeholder="Telefone:" value="{{ $user->phone }}" oninput="maskPhone(this)"/>
                            <label for="phone">Telefone:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-3 col-lg-3 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" name="cpfcnpj" id="cpfcnpj" class="form-control cpfcnpj" placeholder="CPF/CNPJ:" value="{{ $user->cpfcnpj }}" oninput="maskCpfCnpj(this)"/>
                            <label for="cpfcnpj">CPF/CNPJ:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="text" name="password" id="password" class="form-control" placeholder="Nova senha:"/>
                            <label for="password">Nova senha:</label>
                        </div>
                    </div>
                    <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-3">
                        <div class="form-floating form-floating-outline">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirme a nova senha:"/>
                            <label for="confirm_password">Confirme a nova senha:</label>
                        </div>
                    </div>
                    @if (Auth::user()->role == 'admin')
                        <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <div class="select2-primary">
                                    <select name="role" id="role" class="select2 form-select">
                                        <option value="admin" @selected($user->role == 'admin')>Administrando</option>
                                        <option value="instructor" @selected($user->role == 'instructor')>Instrutor</option>
                                        <option value="student" @selected($user->role == 'student')>Estudante</option>
                                    </select>
                                </div>
                                <label for="role">Permissões:</label>
                            </div>
                        </div>
                        <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-3">
                            <div class="form-floating form-floating-outline">
                                <div class="select2-primary">
                                    <select name="status" id="status" class="select2 form-select">
                                        <option value="active" @selected($user->status == 'active')>Ativo</option>
                                        <option value="inactive" @selected($user->status == 'inactive')>Inativo</option>
                                    </select>
                                </div>
                                <label for="status">Status:</label>
                            </div>
                        </div>
                    @endif
                    <div class="col-6 col-sm-12 col-md-6 col-lg-6 mb-3">
                        <button type="submit" class="btn btn-outline-success mt-2">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form action="{{ route('updated-user', ['uuid' => $user->uuid]) }}" method="POST" enctype="multipart/form-data" id="photo-upload-form" class="d-none">
        @csrf
        <input type="hidden" name="uuid" value="{{ $user->uuid }}">
        <input type="file" name="photo" id="photo-input" accept="image/*" onchange="document.getElementById('photo-upload-form').submit();">
    </form>

     <script>
        document.getElementById('change-photo-button').addEventListener('click', function() {
            document.getElementById('photo-input').click();
        });

        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('input[type="file"]').forEach(input => {
                input.addEventListener("change", function() {
                    this.closest("form").submit();
                });
            });
        });
    </script>

@endsection