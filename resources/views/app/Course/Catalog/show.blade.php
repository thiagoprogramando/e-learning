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
                    <div class="card academy-content shadow-none border">
                        <div class="card-body pt-3">
                            @switch($block->type)

                                @case('text')
                                    <div class="p-2 mb-5">
                                        {!! $block->content !!}
                                    </div>
                                @break

                                @case('video')
                                    <div class="p-2 mb-5">
                                        <video class="w-100" controls>
                                            <source src="{{ asset($block->content) }}" type="video/mp4"/>
                                        </video>
                                    </div>
                                @break

                                @case('image')
                                    <div class="card bg-dark border-0 text-white mb-5">
                                        <img class="card-img" src="{{ asset($block->content) }}" alt="{{ $block->title }}">
                                    </div>
                                @break

                                @case('audio')
                                    <div class="p-2 mb-5">
                                        <h5>{{ $block->title }}</h5>
                                        <audio class="w-100" controls>
                                            <source src="{{ asset($block->content) }}" type="audio/mp3">
                                        </audio>
                                    </div>
                                @break

                                @default
                                    <div>---</div>
                            @endswitch
                            <div class="text-end">
                                @if($nextBlock)
                                    <a href="{{ route('ava', ['course' => $course->uuid, 'lesson' => $nextLesson->uuid ?? $lesson->uuid, 'block'  => $nextBlock->id]) }}" class="btn btn-outline-success">
                                        Próxima
                                    </a>
                                @else
                                    <button class="btn btn-outline-secondary" disabled>
                                        Fim do curso
                                    </button>
                                @endif
                            </div>
                            <hr class="my-6">
                            <h5>Detalhes do Curso</h5>
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
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-12 col-md-8 col-lg-4">
            <div class="accordion stick-top accordion-custom-button" id="courseContent">
                @foreach ($course->lessons as $index => $lessonList)
                    <div class="accordion-item {{ $block->lesson_id == $lessonList->id ? 'active' : '' }}">
                        <div class="accordion-header border-bottom-0">
                            <button type="button" class="accordion-button {{ $block->lesson_id == $lessonList->id ? '' : 'collapsed' }}" data-bs-toggle="collapse" data-bs-target="#chapter{{ $index }}" aria-expanded="{{ $block->lesson_id == $lessonList->id ? 'true' : 'false' }}">
                                
                                <span class="d-flex flex-column">
                                    <span class="h5 mb-0">{{ $lessonList->title }}</span>
                                    <span class="text-body fw-normal">
                                        {{ ($index + 1).' / '.$lessonList->blocks->count() }}
                                    </span>
                                </span>
                            </button>
                        </div>

                        <div id="chapter{{ $index }}" class="accordion-collapse collapse {{ $block->lesson_id == $lessonList->id ? 'show' : '' }}" data-bs-parent="#courseContent">
                            <div class="accordion-body py-4 border-top">
                                @foreach ($lessonList->blocks as $lessonIndex => $blockItem)
                                    <div class="form-check d-flex align-items-center mb-4">
                                        <input class="form-check-input" type="checkbox" id="defCheck{{ $blockItem->id }}" data-url="{{ route('ava', ['course' => $course->uuid, 'lesson' => $lessonList->uuid, 'block' => $blockItem->id]) }}" {{ ($block->id == $blockItem->id || in_array($blockItem->id, $viewedBlocks ?? [])) ? 'checked' : '' }}/>
                                        <label for="defCheck{{ $blockItem->id }}" class="form-check-label ms-4">
                                            <span class="mb-0 h6">
                                                {{ ($lessonIndex + 1).'. '.$blockItem->title }}
                                            </span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
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
                        <input type="hidden" name="lesson_id" value="{{ $lesson->id }}">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-4">
                                <textarea name="comment" id="comment" class="form-control h-px-100" placeholder="Problemas | Dúvidas | Sugestões" name="comment">{{ old('comment') }}</textarea>
                                <label for="comment">Problemas | Dúvidas | Sugestões</label>
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
    <script>
        document.querySelectorAll('.form-check-input').forEach(input => {
            input.addEventListener('change', function() {
                const url = this.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            });
        });
    </script>
@endsection