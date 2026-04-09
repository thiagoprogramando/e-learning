@extends('app.layout')
@section('content')

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}"/>

    <div class="col-12 col-sm-12 col-md-7 col-lg-7">
        <div class="accordion mt-4" id="accordionWithIcon">
            <div class="accordion-item active">
                <h2 class="accordion-header d-flex align-items-center">
                    <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionWithIconX" aria-expanded="true"><i class="ri-add-circle-line"></i> Adicionar Bloco</button>
                </h2>
                <div id="accordionWithIconX" class="accordion-collapse collapse show" style="">
                    <div class="accordion-body">
                        <form action="{{ route('created-block', ['uuid' => $lesson->uuid]) }}" method="POST" class="row" enctype="multipart/form-data">
                            @csrf
                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                <div class="form-floating form-floating-outline mb-4 mt-3">
                                    <input type="text" name="title" class="form-control" placeholder="Título" tabindex="0" id="title"/>
                                    <label for="title">Título</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                                <div class="form-floating form-floating-outline mb-4 mt-3">
                                    <select name="type" class="form-select" tabindex="0" id="type">
                                        <option value="text" selected>Texto</option>
                                        <option value="video">Video</option>
                                        <option value="audio">Áudio</option>
                                        <option value="image">Imagem</option>
                                        <option value="embed">Embed (iframe)</option>
                                    </select>
                                    <label for="type">Escolha um Tipo:</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-2 col-lg-2">
                                <div class="form-floating form-floating-outline mb-4 mt-3">
                                    <input type="number" name="order" class="form-control" placeholder="Ordem" tabindex="0" id="order"/>
                                    <label for="order">Ordem</label>
                                </div>
                            </div>
                            <div class="col-12 col-sm-12 col-md-12 col-lg-12">
                                {{-- TEXTO --}}
                                <div id="block-text" class="block-type">
                                    <div class="full-editor"></div>
                                    <textarea name="content_text" id="text" hidden></textarea> 
                                </div>
                                {{-- VIDEO --}}
                                <div id="block-video" class="block-type d-none">
                                    <div class="form-floating form-floating-outline mb-4 mt-3">
                                        <input type="file" name="content_video" class="form-control" id="video" accept="video/*"/>
                                        <label for="video">Video</label>
                                    </div>
                                </div>
                                {{-- AUDIO --}}
                                <div id="block-audio" class="block-type d-none">
                                    <div class="form-floating form-floating-outline mb-4 mt-3">
                                        <input type="file" name="content_audio" class="form-control" id="audio" accept="audio/*"/>
                                        <label for="audio">Áudio</label>
                                    </div>
                                </div>
                                {{-- IMAGE --}}
                                <div id="block-image" class="block-type d-none">
                                    <div class="form-floating form-floating-outline mb-4 mt-3">
                                        <input type="file" name="content_image" class="form-control" id="image" accept="image/*"/>
                                        <label for="image">Imagem</label>
                                    </div>
                                </div>
                                {{-- EMBED --}}
                                <div id="block-embed" class="block-type d-none">
                                    <div class="form-floating form-floating-outline mb-4">
                                        <textarea name="content_embed" id="embed" class="form-control h-px-100"></textarea>
                                        <label for="embed">Código Embed (iframe)</label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mb-4 mt-3">
                                <button type="submit" class="btn btn-success">Adicionar</button>
                                <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @foreach ($blocks as $block)
                <div class="accordion-item">
                    <h2 class="accordion-header d-flex align-items-center">
                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-{{ $block->id }}" aria-expanded="false"><i class="ri-presentation-line ri-20px me-2"></i>{{ '#'.$block->id.' - '.$block->title }}</button>
                    </h2>
                    <div id="accordionWithIcon-{{ $block->id }}" class="accordion-collapse collapse" style="">
                        <div class="accordion-body">
                            <div class="row">
                                @switch($block->type)
                                    {{-- TEXTO --}}
                                    @case('text')
                                        <div class="col-12">
                                            {!! $block->content !!}
                                        </div>
                                    @break
                                    {{-- VIDEO --}}
                                    @case('video')
                                        <div class="col-12">
                                            <video controls class="w-100">
                                                <source src="{{ $block->content }}" type="video/mp4">
                                                Seu navegador não suporta vídeo.
                                            </video>
                                        </div>
                                    @break
                                    {{-- IMAGE --}}
                                    @case('image')
                                        <div class="col-12 text-center">
                                            <img src="{{ $block->content }}" class="img-fluid rounded">
                                        </div>
                                    @break
                                    {{-- AUDIO --}}
                                    @case('audio')
                                        <div class="col-12">
                                            <audio controls class="w-100">
                                                <source src="{{ $block->content }}">
                                                Seu navegador não suporta áudio.
                                            </audio>
                                        </div>
                                    @break
                                    {{-- EMBED --}}
                                    @case('embed')
                                        <div class="col-12">
                                            <div class="ratio ratio-16x9">
                                                {!! $block->content !!}
                                            </div>
                                        </div>
                                    @break
                                @endswitch
                                <form action="{{ route('deleted-block', ['id' => $block->id]) }}" method="POST" class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mb-4 mt-3 confirm">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Excluir</button>
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
                <h5 class="card-title text-center">Tickets</h5>
                <div class="btn-group mb-3">
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreatedSubject" class="btn btn-outline-dark"><i class="ri-add-circle-line"></i>Filtrar</button>
                </div>

                <div class="demo-inline-spacing mt-4">
                    <div class="list-group">
                        {{-- @foreach ($subjects as $subject)
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
                        @endforeach  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const select = document.getElementById('type');
            const blocks = document.querySelectorAll('.block-type');

            function toggleBlocks(type) {
                blocks.forEach(block => block.classList.add('d-none'));

                const active = document.getElementById('block-' + type);
                if (active) {
                    active.classList.remove('d-none');
                }
            }

            toggleBlocks(select.value);
            select.addEventListener('change', function () {
                toggleBlocks(this.value);
            });

            const fullToolbar = [
                [{ font: [] }, { size: [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ script: 'super' }, { script: 'sub' }],
                [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
                [{ direction: 'rtl' }],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ];

            window.editor = new Quill('.full-editor', {
                bounds: '.full-editor',
                placeholder: 'Digite o conteúdo...',
                modules: {
                formula: true,
                toolbar: fullToolbar
                },
                theme: 'snow'
            });

            window.editor.on('text-change', function () {
                document.getElementById('text').value = window.editor.root.innerHTML;
            });
        });
    </script>
@endsection