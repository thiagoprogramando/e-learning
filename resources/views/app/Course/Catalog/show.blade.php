@extends('app.layout')
@section('content')

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-academy-details.css') }}"/>

    <div class="row g-8">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center flex-wrap mb-6 gap-1">
                        <div class="me-1">
                            <h5 class="mb-0">{{ $course->title }}</h5>
                            <p class="mb-0">Prof. <span class="fw-medium text-heading"> {{ $course->teacher->name ?? '' }} </span></p>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="ri-error-warning-line text-danger ri-24px mx-4" data-bs-toggle="modal" data-bs-target="#ticketModal"></i>
                        </div>
                    </div>
                    <div class="card academy-content shadow-none">
                        <div class="card-body pt-3">
                            @if (isset($lesson))
                                @foreach ($lesson->blocks as $item)
                                    @switch($item->type)
                                        @case('text')
                                            <div class="p-2 mb-5" id="{{ $item->id }}">
                                                {!! $item->content !!}
                                            </div>
                                            @break
                                        @case('video')
                                            <div class="p-2 mb-5" id="{{ $item->id }}">
                                                <div class="cursor-pointer">
                                                    <video class="w-100" poster="https://cdn.plyr.io/static/demo/View_From_A_Blue_Moon_Trailer-HD.jpg" id="plyr-video-player" playsinline controls>
                                                        <source src="{{ asset($item->content) }}" type="video/mp4"/>
                                                    </video>
                                                </div>
                                            </div>
                                            @break
                                        @case('image')
                                            <div class="card bg-dark border-0 text-white mb-5" id="{{ $item->id }}">
                                                <img class="card-img" src="{{ asset($item->content) }}" alt="{{ $item->title }}">
                                            </div>
                                            @break
                                        @case('audio')
                                            <div class="p-2 mb-5" id="{{ $item->id }}">
                                                <h5 class="card-header">{{ $item->title }}</h5>
                                                <div class="card-body">
                                                    <audio class="w-100" id="plyr-audio-player" controls>
                                                        <source src="{{ asset($item->content) }}" type="audio/mp3">
                                                    </audio>
                                                </div>
                                            </div>
                                            @break
                                        @default
                                            <div>---</div>
                                            @break  
                                    @endswitch
                                @endforeach
                            @else
                                <h5>Sobre o Curso</h5>
                                <p class="mb-0">
                                    {{ $course->description }}
                                </p>
                                <hr class="my-6">
                                <h5>Detalhes</h5>
                                <div class="d-flex flex-wrap row-gap-2">
                                    <div class="me-12">
                                        <p class="text-nowrap mb-2"><i class="ri-group-line ri-20px me-2"></i>Atividades: Sim</p>
                                        <p class="text-nowrap mb-0"><i class="ri-pages-line ri-20px me-2"></i>Prova: Sim</p>
                                        </div>
                                        <div>
                                        <p class="text-nowrap mb-2">
                                            <i class="ri-video-upload-line ri-20px me-2 ms-50"></i>Aulas: {{ $course->lessons->count() }}
                                        </p>
                                        <p class="text-nowrap mb-0">
                                            <i class="ri-time-line ri-20px me-2"></i>Duração: {{ $course->duration }}/horas
                                        </p>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-outline-dark mt-5 mb-5">ESCOLHA UMA AULA NO MENU PARA INICIAR <i class="ri-arrow-right-line ri-16px lh-1 scaleX-n1-rtl"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-8 col-lg-4">
            <div class="accordion stick-top accordion-custom-button" id="courseContent">
                @foreach ($course->lessons as $index => $lesson)
                    <div class="accordion-item @if ($index == 0) active @endif mb-0">
                        <div class="accordion-header border-bottom-0" id="headingOne">
                            <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#chapterOne" aria-expanded="true" aria-controls="chapterOne">
                                <span class="d-flex flex-column">
                                    <span class="h5 mb-0">{{ $lesson->title }}</span>
                                    <span class="text-body fw-normal">{{ $lesson->blocks->count() }} Blocos</span>
                                </span>
                            </button>
                        </div>
                        <div id="chapterOne" class="accordion-collapse collapse @if ($index == 0) show @endif" data-bs-parent="#courseContent">
                            @foreach ($lesson->blocks as $index => $block)
                                <div class="accordion-body py-4 border-top">
                                    <div class="d-flex align-items-center mb-4">
                                        <a href="{{ route('ava', ['course' => $course->uuid, 'lesson' => $lesson->uuid]) }}#{{ $block->id }}" class="text-decoration-none">
                                            <span class="mb-0 h6">{{ ($index + 1).'. '.$block->title  }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="content-backdrop fade"></div>
    </div>

    <div class="modal fade" id="ticketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="text-center mb-6">
                        <h4 class="mb-2">TICKET</h4>
                        <p>Preencha os dados!</p>
                    </div>
                    <form action="{{ route('created-ticket') }}" method="POST" class="row">
                        @csrf
                        <input type="hidden" name="course_id" value="{{ $course->id }}">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="comment" id="comment" class="form-control h-px-100" placeholder="Problemas | Dúvidas | Sugestões" name="comment">{{ old('comment') }}</textarea>
                                <label for="comment">Problemas | Dúvidas | Sugestões</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="lesson_id" class="form-select" tabindex="0" id="lesson_id">
                                    <option value="  " selected>Todos</option>
                                    @foreach ($course->lessons as $index => $lesson)
                                        <option value="{{ $lesson->id }}">{{ $lesson->title }}</option>
                                    @endforeach
                                </select>
                                <label for="lesson_id">Escolha uma Aula/Tópico:</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                            <button type="submit" class="btn btn-success">Enviar</button>
                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/app-academy-course-details.js') }}"></script>
@endsection