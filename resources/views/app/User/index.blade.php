@extends('app.layout')
@section('content')

    <div class="col-12">
        <div class="kanban-add-new-board">
            <label class="kanban-add-board-btn" for="kanban-add-board-input" data-bs-toggle="modal" data-bs-target="#filterModal">
                <i class="ri-filter-line"></i>
                <span class="align-middle">Filtrar</span>
            </label>
        </div>

        <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
            <form action="{{ route('users') }}" method="GET">
                @csrf
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="exampleModalLabel1">Dados da Pesquisa</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="name" id="name" class="form-control" placeholder="Nome:"/>
                                        <label for="name">Nome:</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="email" name="email" id="email" class="form-control" placeholder="E-mail:"/>
                                        <label for="email">E-mail:</label>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-12 col-md-6 col-lg-6 mb-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" name="cpfcnpj" id="cpfcnpj" class="form-control cpfcnpj" placeholder="CPF/CNPJ:" oninput="maskCpfCnpj(this)"/>
                                        <label for="cpfcnpj">CPF/CNPJ:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="status" class="form-select" tabindex="0" id="status">
                                            <option value="  " selected>Todos</option>
                                            <option value="active">Ativo</option>
                                            <option value="inactive">Inativo</option>
                                        </select>
                                        <label for="status">Escolha um Status:</label>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6 col-lg-6">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <select name="role" class="form-select" tabindex="0" id="role">
                                            <option value="  " selected>Todos</option>
                                            <option value="admin">Administradores</option>
                                            <option value="instructor">Instrutores</option>
                                            <option value="student">Estudantes</option>
                                        </select>
                                        <label for="role">Escolha um Perfil:</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer btn-group">
                            <button type="button" class="btn btn-outline-dark" data-bs-dismiss="modal"> Fechar </button>
                            <button type="submit" class="btn btn-success">Filtrar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
        <div class="card demo-inline-spacing">
            <div class="list-group p-0 m-0">
                @foreach ($users as $user)
                    <div class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer waves-effect waves-light">
                        <img src="{{ $user->avatar ? asset('storage/'.$user->avatar) : asset('assets/img/avatars/man.png') }}" alt="Perfil {{ $user->name }}" class="rounded-circle me-3" width="40">
                        <div class="w-100">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="user-info">
                                    <h6 class="mb-1 fw-normal">{{ $user->name }}</h6>
                                    <div class="d-flex align-items-center">
                                        <div class="user-status me-2 d-flex align-items-center">
                                            <span class="badge badge-dot bg-dark me-1"></span>
                                            <small>{{ $user->maskCpfCnpj() }}</small>
                                        </div>
                                        <div class="user-status me-2 d-flex align-items-center">
                                            <span class="badge badge-dot bg-info me-1"></span>
                                            <small>{{ $user->email }}</small>
                                        </div>
                                        <small class="text-muted ms-1" title="{{ $user->maskRole() }}">{{ $user->maskRole() }}</small>
                                    </div>
                                </div>
                                <form action="{{ route('deleted-user', ['uuid' => $user->uuid]) }}" method="POST" class="add-btn delete">
                                    @csrf
                                    <a href="{{ route('user', ['uuid' => $user->uuid]) }}" class="btn btn-success text-white btn-sm"><i class="ri-menu-search-line"></i></a>
                                    <button type="submit" class="btn btn-danger btn-sm"><i class="ri-delete-bin-line"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>  
                @endforeach
            </div>

            <div class="card-footer d-flex justify-content-center">
                {{ $users->links() }}
            </div>
        </div>
    </div>

@endsection