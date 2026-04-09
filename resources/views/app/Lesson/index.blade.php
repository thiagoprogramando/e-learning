@extends('app.layout')
@section('content')


    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Cadastro de Aula</h4>
                        <p>Preencha os dados!</p>
                    </div>
                    <form action="{{ route('created-lesson') }}" method="POST" class="row">
                        @csrf
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="title" class="form-control" placeholder="Nome | Título" tabindex="0" id="title" value="{{ old('title') }}"/>
                                <label for="title">Nome | Título</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="description" id="description" class="form-control h-px-100" placeholder="Descrição" name="description">{{ old('description') }}</textarea>
                                <label for="description">Descrição</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="teacher_id" class="form-select" tabindex="0" id="teacher_id">
                                    <option value="  " selected>Todos</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                                <label for="teacher_id">Escolha um Professor:</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="created_by" class="form-select" tabindex="0" id="created_by">
                                    <option value="  " selected>Todos</option>
                                    @foreach ($creators as $creator)
                                        <option value="{{ $creator->id }}">{{ $creator->name }}</option>
                                    @endforeach
                                </select>
                                <label for="created_by">Escolha um Criador:</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="subject_id" class="form-select" tabindex="0" id="subject_id">
                                    <option value="  " selected>Todos</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                                    @endforeach
                                </select>
                                <label for="subject_id">Escolha um Assunto:</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                            <button type="submit" class="btn btn-success">Cadastrar</button>
                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="filterModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Filtros</h4>
                        <p>Escolha os dados!</p>
                    </div>
                    <form action="{{ route('lessons') }}" method="GET" class="row">
                        @csrf
                        <div class="col-sm-12 col-md-12 col-lg-12">
                          <div class="form-floating form-floating-outline mb-4">
                            <input type="text" name="title" class="form-control" placeholder="Título | Descrição" tabindex="0" id="title" value="{{ old('title') }}"/>
                            <label for="title">Título | Descrição</label>
                          </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="subject_id" class="form-select" tabindex="0" id="subject_id">
                                    <option value="  " selected>Todos</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->title }}</option>
                                    @endforeach
                                </select>
                                <label for="subject_id">Escolha um Assunto:</label>
                            </div>
                        </div>
                        @if (Auth::user()->role == 'admin')
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="teacher_id" class="form-select" tabindex="0" id="teacher_id">
                                        <option value="  " selected>Todos</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="teacher_id">Escolha um Professor:</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-floating form-floating-outline mb-4">
                                    <select name="created_by" class="form-select" tabindex="0" id="created_by">
                                        <option value="  " selected>Todos</option>
                                        @foreach ($creators as $creator)
                                            <option value="{{ $creator->id }}">{{ $creator->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="created_by">Escolha um Criador:</label>
                                </div>
                            </div>
                         @endif
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                            <button type="submit" class="btn btn-success">Pesquisar</button>
                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-7 col-lg-7">

        <div class="nav-align-top mb-3">
            <ul class="nav nav-pills flex-column flex-md-row gap-2 gap-lg-0">
                <li class="nav-item">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#createModal" @disabled(Auth::user()->role !== 'admin') class="nav-link active waves-effect waves-light"><i class="ri-add-circle-line me-2"></i>Adicionar</button>
                </li>
                <li class="nav-item">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#filterModal" class="nav-link waves-effect waves-light"><i class="ri-filter-3-line me-2"></i>Filtrar</button>
                </li>
            </ul>
        </div>

        <div class="accordion mt-4" id="accordionWithIcon">
            @foreach ($lessons as $lesson)
                <div class="accordion-item">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-{{ $lesson->uuid }}" aria-expanded="false"><i class="ri-presentation-line ri-20px me-2"></i>{{ $lesson->title }}</button>
                    </h2>
                    <div id="accordionWithIcon-{{ $lesson->uuid }}" class="accordion-collapse collapse" style="">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-5 col-lg-5 border-start border-secondary">
                                    <small class="text-muted">{{ $lesson->description }}</small>
                                </div>
                                <div class="col-12 col-sm-12 col-md-7 col-lg-7 border-start border-info">
                                    <form action="{{ route('deleted-lesson', ['uuid' => $lesson->uuid]) }}" method="POST" class="btn-group confirm">
                                        <a href="{{ route('lesson', ['uuid' => $lesson->uuid]) }}" class="btn btn-outline-dark">CONTEÚDO</a>
                                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#updateModal{{ $lesson->uuid }}">EDITAR</button>
                                        <button type="submit" @disabled(Auth::user()->role !== 'admin') class="btn btn-outline-dark">EXCLUIR</button>
                                    </form>
                                    <hr>
                                    Professor: <strong>{{ $lesson->teacher->name ?? 'Nenhum' }}</strong><br>
                                    Criador: <strong>{{ $lesson->creator->name ?? 'Nenhum' }}</strong><br>
                                    Assunto: <strong>{{ $lesson->subject->title ?? 'Nenhum' }}</strong><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="updateModal{{ $lesson->uuid }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-simple">
                        <div class="modal-content p-3">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="modal-body p-0">
                                <div class="text-center mb-6">
                                    <h4 class="mb-2">Edição de Aula</h4>
                                    <p>Preencha os dados!</p>
                                </div>
                                <form action="{{ route('updated-lesson', ['uuid' => $lesson->uuid]) }}" method="POST" class="row">
                                    @csrf
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="text" name="title" class="form-control" placeholder="Nome | Título" tabindex="0" id="title" value="{{ $lesson->title }}"/>
                                            <label for="title">Nome | Título</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <textarea name="description" id="description" class="form-control h-px-100" placeholder="Descrição" name="description">{{ $lesson->description }}</textarea>
                                            <label for="description">Descrição</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="teacher_id" class="form-select" tabindex="0" id="teacher_id">
                                                <option value="  " selected>Sem Associação</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ $lesson->teacher_id == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="teacher_id">Escolha um Professor:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="created_by" class="form-select" tabindex="0" id="created_by">
                                                <option value="  " selected>Escolha um Criador</option>
                                                @foreach ($creators as $creator)
                                                    <option value="{{ $creator->id }}" {{ $lesson->created_by == $creator->id ? 'selected' : '' }}>{{ $creator->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="created_by">Escolha um Criador:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="subject_id" class="form-select" tabindex="0" id="subject_id">
                                                <option value="  " selected>Sem Associação</option>
                                                @foreach ($subjects as $subject)
                                                    <option value="{{ $subject->id }}" {{ $lesson->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->title }}</option>
                                                @endforeach
                                            </select>
                                            <label for="subject_id">Escolha um Assunto:</label>
                                        </div>
                                    </div>
                                    <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                                        <button type="submit" class="btn btn-success">Atualizar</button>
                                        <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-12 col-sm-12 col-md-5 col-lg-5">
        <div class="card shadow-none bg-transparent border border-secondary mb-3">
            <div class="card-body text-secondary">
                <h5 class="card-title text-center">Assuntos</h5>
                <div class="btn-group mb-3">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreatedSubject" class="btn btn-outline-dark" @disabled(Auth::user()->role !== 'admin')><i class="ri-add-circle-line"></i> Novo Assunto</button>
                </div>

                <div class="demo-inline-spacing mt-4">
                    <div class="list-group">
                        @foreach ($subjects as $subject)
                            <div class="list-group-item list-group-item-action d-flex align-items-center cursor-pointer waves-effect">
                                <div class="w-100">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div class="user-info">
                                        <h6 class="mb-1 fw-normal">{{ $subject->title }}</h6>
                                            <div class="d-flex align-items-center">
                                                <div class="user-status me-2 d-flex align-items-center">
                                                    <small>{{ $subject->description }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <form action="{{ route('deleted-subject', ['id' => $subject->id]) }}" method="POST" class="confirm btn-group btn-group-sm">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $subject->id }}">
                                            <button type="button" class="btn btn-sm btn-outline-dark btn-sm waves-effect waves-light" @disabled(Auth::user()->role !== 'admin') data-bs-toggle="modal" data-bs-target="#modalUpdatedSubject{{ $subject->id }}"><i class="ri-edit-box-line me-2"></i></button>
                                            <button type="submit" class="btn btn-sm btn-outline-dark btn-sm waves-effect waves-light" @disabled(Auth::user()->role !== 'admin')><i class="ri-delete-bin-line me-2"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal fade" id="modalUpdatedSubject{{ $subject->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-simple">
                                    <div class="modal-content p-3">
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        <div class="modal-body p-0">
                                            <div class="text-center mb-6">
                                                <h4 class="mb-2">ALTERAÇÃO DE DADOS</h4>
                                                <p>Preencha os dados!</p>
                                            </div>
                                            <form action="{{ route('updated-subject', ['id' => $subject->id]) }}" method="POST" class="row g-2">
                                                @csrf
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <input type="text" name="title" class="form-control" placeholder="Nome | Título" tabindex="0" id="title" value="{{ $subject->title }}"/>
                                                        <label for="title">Nome | Título</label>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12 col-md-12 col-lg-12">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <textarea name="description" id="description" class="form-control h-px-100" placeholder="Descrição" name="description">{{ $subject->description }}</textarea>
                                                        <label for="description">Descrição</label>
                                                    </div>
                                                </div>
                                                <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mt-3">
                                                    <button type="submit" class="btn btn-success">Confirmar</button>
                                                    <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach 
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreatedSubject" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="text-center mb-6">
                        <h4 class="mb-2">DADOS DO ASSUNTO</h4>
                        <p>Preencha os dados!</p>
                    </div>
                    <form action="{{ route('created-subject') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="title" class="form-control" placeholder="Nome | Título" tabindex="0" id="title" value="{{ old('title') }}"/>
                                <label for="title">Nome | Título</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="description" id="description" class="form-control h-px-100" placeholder="Descrição" name="description">{{ old('description') }}</textarea>
                                <label for="description">Descrição</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mt-3">
                            <button type="submit" class="btn btn-success">Confirmar</button>
                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection