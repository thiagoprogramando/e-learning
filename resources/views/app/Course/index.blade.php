@extends('app.layout')
@section('content')

    <div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="text-center mb-6">
                        <h4 class="mb-2">Cadastro de Curso</h4>
                        <p>Preencha os dados!</p>
                    </div>
                    <form action="{{ route('created-course') }}" method="POST" class="row" enctype="multipart/form-data">
                        @csrf
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="file" name="thumbnail" class="form-control" placeholder="Imagem de Capa" tabindex="0" id="thumbnail" value="{{ old('thumbnail') }}"/>
                                <label for="thumbnail">Imagem de Capa</label>
                            </div>
                        </div>
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
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" name="value" class="form-control money" placeholder="Valor" tabindex="0" id="value" value="{{ old('value') }}" oninput="maskValue(this)"/>
                                <label for="value">Valor</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="number" name="duration" class="form-control money" placeholder="Duração" tabindex="0" id="duration" value="{{ old('duration') }}"/>
                                <label for="duration">Duração (horas)</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="time" class="form-select" tabindex="0" id="time">
                                    <option value="monthly" selected>Mensal</option>
                                    <option value="semi-annual">Semestral</option>
                                    <option value="annual">Anual</option>
                                    <option value="lifetime">Vitalício</option>
                                </select>
                                <label for="time">Escolha um Tipo:</label>
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
                    <form action="{{ route('courses') }}" method="GET" class="row">
                        @csrf
                        <div class="col-sm-12 col-md-12 col-lg-12">
                          <div class="form-floating form-floating-outline mb-4">
                            <input type="text" name="title" class="form-control" placeholder="Título | Descrição" tabindex="0" id="title" value="{{ old('title') }}"/>
                            <label for="title">Título | Descrição</label>
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

    <div class="col-12 col-sm-12 col-md-12 col-lg-12">

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

        <small class="text-light fw-medium">Cursos disponíveis</small>
        <div class="accordion mt-4" id="accordionWithIcon">
            @foreach ($courses as $course)
                <div class="accordion-item">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-{{ $course->uuid }}" aria-expanded="false"><i class="ri-presentation-line ri-20px me-2"></i>{{ $course->title }}</button>
                    </h2>
                    <div id="accordionWithIcon-{{ $course->uuid }}" class="accordion-collapse collapse" style="">
                        <div class="accordion-body">
                            <div class="row">
                                <div class="col-12 col-sm-12 col-md-5 col-lg-5 border-start border-secondary">
                                    <p class="text-muted">{{ $course->description }}</p>
                                    <form action="{{ route('deleted-course', ['uuid' => $course->uuid]) }}" method="POST" class="btn-group confirm">
                                        <button type="button" data-bs-toggle="modal" data-bs-target="#lessonModal{{ $course->uuid }}" class="btn btn-sm btn-outline-dark" >ADICIONAR AULAS</button>
                                        <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#updateModal{{ $course->uuid }}">EDITAR</button>
                                        <button type="submit" @disabled(Auth::user()->role !== 'admin') class="btn btn-sm btn-outline-dark">EXCLUIR</button>
                                    </form>
                                    <hr>
                                    Professor: <strong>{{ $course->teacher->name }}</strong><br>
                                    Criador: <strong>{{ $course->creator->name }}</strong><br>
                                    Assunto: <strong>{{ $course->subject->title ?? 'Nenhum' }}</strong><br>
                                </div>
                                <div class="col-12 col-sm-12 col-md-7 col-lg-7 border-start border-info">
                                    <small class="text-light fw-medium">Aulas disponíveis</small>
                                    <div class="accordion mt-4" id="accordionWithIcon">
                                        @foreach ($course->lessons as $lesson)
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
                                                                <form action="{{ route('deleted-lesson-course', ['id' => $lesson->id]) }}" method="POST" class="btn-group confirm">
                                                                    <a href="{{ route('lesson', ['uuid' => $lesson->uuid]) }}" class="btn btn-sm btn-outline-dark">EDITAR/ADICIONAR CONTEÚDO</a>
                                                                    <button type="submit" @disabled(Auth::user()->role !== 'admin') class="btn btn-sm btn-outline-dark">REMOVER DO CURSO</button>
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
                                        @endforeach
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="updateModal{{ $course->uuid }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content p-5">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="modal-body p-0">
                                <div class="text-center mb-6">
                                    <h4 class="mb-2">Edição de Curso</h4>
                                    <p>Preencha os dados!</p>
                                </div>
                                <form action="{{ route('updated-course', ['uuid' => $course->uuid]) }}" method="POST" class="row" enctype="multipart/form-data">
                                    @csrf
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="file" name="thumbnail" class="form-control" placeholder="Imagem de Capa" tabindex="0" id="thumbnail"/>
                                            <label for="thumbnail">Imagem de Capa</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="text" name="title" class="form-control" placeholder="Nome | Título" tabindex="0" id="title" value="{{ old('title', $course->title) }}"/>
                                            <label for="title">Nome | Título</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <textarea name="description" id="description" class="form-control h-px-100" placeholder="Descrição" name="description">{{ old('description', $course->description) }}</textarea>
                                            <label for="description">Descrição</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-lg-3">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="text" name="value" class="form-control money" placeholder="Valor" tabindex="0" id="value" value="{{ old('value', $course->value) }}" oninput="maskValue(this)"/>
                                            <label for="value">Valor</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-lg-3">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <input type="number" name="duration" class="form-control" placeholder="Duração" tabindex="0" id="duration" value="{{ old('duration', $course->duration) }}"/>
                                            <label for="duration">Duração (horas)</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-lg-3">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="time" class="form-select" tabindex="0" id="time">
                                                <option value="monthly" {{ old('time', $course->time) == 'monthly' ? 'selected' : '' }}>Mensal</option>
                                                <option value="quarterly" {{ old('time', $course->time) == 'quarterly' ? 'selected' : '' }}>Trimestral</option>
                                                <option value="semi-annual" {{ old('time', $course->time) == 'semi-annual' ? 'selected' : '' }}>Semestral</option>
                                                <option value="annual" {{ old('time', $course->time) == 'annual' ? 'selected' : '' }}>Anual</option>
                                                <option value="lifetime" {{ old('time', $course->time) == 'lifetime' ? 'selected' : '' }}>Vitalício</option>
                                            </select>
                                            <label for="time">Escolha um Tipo:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-3 col-lg-3">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="is_published" class="form-select" tabindex="0" id="is_published">
                                                <option value="1" {{ old('is_published', $course->is_published) == '1' ? 'selected' : '' }}>Publicado</option>
                                                <option value="0" {{ old('is_published', $course->is_published) == '0' ? 'selected' : '' }}>Não Publicado</option>
                                            </select>
                                            <label for="is_published">Escolha um Tipo:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="teacher_id" class="form-select" tabindex="0" id="teacher_id">
                                                <option value="  " {{ !old('teacher_id', $course->teacher_id) ? 'selected' : '' }}>Todos</option>
                                                @foreach ($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ old('teacher_id', $course->teacher_id) == $teacher->id ? 'selected' : '' }}>{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="teacher_id">Escolha um Professor:</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-12 col-md-6 col-lg-6">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="created_by" class="form-select" tabindex="0" id="created_by">
                                                <option value="  " {{ !old('created_by', $course->created_by) ? 'selected' : '' }}>Todos</option>
                                                @foreach ($creators as $creator)
                                                    <option value="{{ $creator->id }}" {{ old('created_by', $course->created_by) == $creator->id ? 'selected' : '' }}>{{ $creator->name }}</option>
                                                @endforeach
                                            </select>
                                            <label for="created_by">Escolha um Criador:</label>
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

                <div class="modal fade" id="lessonModal{{ $course->uuid }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-simple">
                        <div class="modal-content p-3">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            <div class="modal-body p-0">
                                <div class="text-center mb-6">
                                    <h4 class="mb-2">ADICIONAR AULAS</h4>
                                    <p>Escolha os dados!</p>
                                </div>
                                <form action="{{ route('created-lesson-course', ['id' => $course->id]) }}" method="POST" class="row">
                                    @csrf
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="form-floating form-floating-outline mb-4">
                                            <select name="lesson_id[]" class="select2 form-select select2-hidden-accessible" tabindex="0" id="lesson_id{{ $course->id }}" multiple>
                                                <option disabled selected>Escolha as Aulas:</option>
                                                @foreach ($lessons as $lesson)
                                                    <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                                @endforeach
                                            </select>
                                            <label for="lesson_id{{ $course->id }}">Escolha as Aulas:</label>
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
            @endforeach
        </div>
    </div>
@endsection