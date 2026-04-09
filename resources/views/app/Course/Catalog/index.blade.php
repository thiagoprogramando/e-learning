@extends('app.layout')
@section('content')

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-academy.css') }}" />
    <style>
        .swal2-zindex {
            z-index: 2000 !important;
        }
    </style>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-5 mt-5">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="app-academy">
                <div class="card p-0 mb-6">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between p-0 pt-6">
                        <div class="app-academy-md-25 card-body py-0 pt-6 ps-12">
                            <img src="{{ asset('assets/img/illustrations/bulb-light.png') }}" class="img-fluid app-academy-img-height scaleX-n1-rtl" alt="Bulb in hand" height="90"/>
                        </div>
                        <div class="app-academy-md-50 card-body d-flex align-items-md-center flex-column text-md-center mb-6 py-6">
                            <span class="card-title mb-4 lh-lg px-md-12 h4 text-heading">
                                Educação, talentos e oportunidades <br/> de carreira. <span class="text-info text-nowrap">Tudo em um só lugar.</span>
                            </span>
                            <p class="mb-4 px-0 px-md-2">
                                Aprimore suas habilidades com os cursos e certificações online mais <br> confiáveis em <b class="text-info">M</b>arketing, <b class="text-info">T</b>ecnologia da <b class="text-info">I</b>nformação, <b class="text-info">P</b>reparatórios e <b class="text-info">I</b>diomas.
                            </p>
                            <form action="{{ route('catalog') }}" method="GET" class="d-flex align-items-center justify-content-between app-academy-md-80">
                                <input type="search" name="search" placeholder="Pesquise por um curso..." class="form-control form-control-sm me-4" value="{{ old('search', request('search')) }}" required/>
                                <button type="submit" class="btn btn-info btn-icon"><i class="ri-search-line ri-22px"></i></button>
                            </form>
                        </div>
                        <div class="app-academy-md-25 d-flex align-items-end justify-content-end">
                            <img src="{{ asset('assets/img/illustrations/pencil-rocket.png') }}" alt="pencil rocket" height="180" class="scaleX-n1-rtl"/>
                        </div>
                    </div>
                </div>

                <div class="card mb-6">
                    <div class="card-header d-flex flex-wrap justify-content-between gap-4">
                        <div class="card-title mb-0 me-1">
                            <h5 class="mb-0">Catálogo de Cursos</h5>
                            <p class="mb-0 text-body">Total 6 cursos disponíveis para você.</p>
                        </div>
                        <div class="d-flex justify-content-md-end align-items-center gap-6 flex-wrap">
                            {{-- <select class="form-select form-select-sm w-px-250">
                                <option value="all courses">All Courses</option>
                                <option value="ui/ux">UI/UX</option>
                                <option value="seo">SEO</option>
                                <option value="web">Web</option>
                                <option value="music">Music</option>
                                <option value="painting">Painting</option>
                            </select>

                            <div class="form-check form-switch mb-0">
                                <input type="checkbox" class="form-check-input" id="CourseSwitch" checked />
                                <label class="form-check-label text-nowrap mb-0" for="CourseSwitch">Hide completed</label>
                            </div> --}}
                        </div>
                    </div>
                    <div class="card-body mt-1">
                        <div class="row gy-6 mb-6">
                            @foreach ($courses as $course)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card p-2 h-100 shadow-none border rounded-3">
                                        <div class="rounded-4 text-center mb-5">
                                            <a href="app-academy-course-details.html">
                                                <img class="img-fluid" src="{{ $course->thumbnail ? asset($course->thumbnail) : asset('assets/img/pages/app-academy-tutor-1.png') }}" alt="tutor image 1"/>
                                            </a>
                                        </div>
                                        <div class="card-body p-3 pt-0">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <span class="badge rounded-pill bg-label-primary">Acesso {{ $course->timeLabel() }}</span>
                                                <p class="d-flex align-items-center justify-content-center fw-medium gap-1 mb-0"> 
                                                    R$ {{ number_format($course->value, 2, ',', '.') }}
                                                </p>
                                            </div>
                                            <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $course->uuid }}" class="h5">{{ $course->title }}</a>
                                            <p class="mt-1">{{ $course->description }}</p>
                                            <p class="d-flex align-items-center mb-1">
                                                <i class="ri-time-line ri-20px me-1"></i>{{ $course->duration }}/horas
                                            </p>
                                            <div class="progress bg-label-info mb-4" style="height: 8px">
                                                <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 100%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#detailModal{{ $course->uuid }}" class="w-100 btn btn-outline-secondary d-flex align-items-center"><i class="ri-refresh-line ri-16px align-middle me-2"></i><span>Detalhes</span></button>
                                                @if ($course->hasApprovedInvoiceForAuthenticatedUser())
                                                    <a href="{{ route('ava', ['course' => $course->uuid]) }}" class="w-100 btn btn-outline-success d-flex align-items-center"> 
                                                        <span class="me-2">Continue</span><i class="ri-arrow-right-line ri-16px lh-1 scaleX-n1-rtl"></i>
                                                    </a>
                                                @else
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#buyModal{{ $course->uuid }}" class="w-100 btn btn-info d-flex align-items-center"> 
                                                        <span class="me-2">Comprar</span><i class="ri-shopping-cart-line ri-16px lh-1"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="detailModal{{ $course->uuid }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-simple">
                                        <div class="modal-content p-3">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            <div class="modal-body p-0">
                                                <div class="text-center mb-6">
                                                    <h4 class="mb-2">DETALHES DO CURSO</h4>
                                                    <p>Análise Aulas, formas de pagamento e detalhes do curso</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        Professor/Monitor: <strong>{{ $course->teacher->name }}</strong><br>
                                                        Método de Pagamento: <strong>{{ $course->timeLabel() }}</strong><br>
                                                        Valor: <strong>R$ {{ number_format($course->value, 2, ',', '.') }}</strong><br>
                                                    </div>
                                                    <div class="col-12 mb-4">
                                                        <h5 class="text-center mb-2">Aulas</h5>
                                                        <div class="accordion" id="accordionWithIcon">
                                                            @foreach ($course->lessons as $lesson)
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header d-flex align-items-center">
                                                                        <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionWithIcon-{{ $lesson->uuid }}" aria-expanded="false"><i class="ri-presentation-line ri-20px me-2"></i>{{ $lesson->title }}</button>
                                                                    </h2>
                                                                    <div id="accordionWithIcon-{{ $lesson->uuid }}" class="accordion-collapse collapse" style="">
                                                                        <div class="accordion-body">
                                                                            <small class="text-muted">{{ $lesson->description }}</small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                                                        <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id="buyModal{{ $course->uuid }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-simple">
                                        <div class="modal-content p-3">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            <div class="modal-body p-0">
                                                <div class="text-center mb-6">
                                                    <h4 class="mb-2">FORMA DE PAGAMENTO</h4>
                                                    <p>Escolha a forma de pagamento que melhor se adapta às suas necessidades</p>
                                                </div>
                                                <form action="{{ route('buy-course', ['uuid' => $course->uuid]) }}" method="POST" class="row">
                                                    @csrf
                                                    <div class="col-sm-12 col-md-12 col-lg-12 mb-3">
                                                        <h3 class="mb-0"><strong>R$ {{ number_format($course->value, 2, ',', '.') }}</strong><small>/{{ $course->timeLabel() }}</small></h3>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                                        <div class="form-floating form-floating-outline mb-4">
                                                            <select name="payment_method" class="form-select" tabindex="0" id="payment_method" required>
                                                                <option value="PIX" selected>Escolha uma forma:</option>
                                                                <option value="PIX">PIX</option>
                                                                <option value="BOLETO">Boleto</option>
                                                                <option value="CREDIT_CARD">Cartão de Crédito</option>
                                                            </select>
                                                            <label for="payment_method">Escolha uma forma de pagamento:</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                                        <div class="coupon-alert"></div>
                                                        <div class="input-group mb-4">
                                                            <input type="text" name="coupon_code" class="form-control coupon_code" placeholder="CUPOM">
                                                            <input type="hidden" class="course_id" value="{{ $course->id }}">
                                                            <input type="hidden" class="user_id" value="{{ auth()->id() }}">
                                                            <button type="button" class="btn btn-outline-info valited-coupon">Validar</button>
                                                        </div>
                                                    </div>
                                                    <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4">
                                                        <button type="submit" class="btn btn-success">Comprar</button>
                                                        <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
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
    </div>

    <script id="f3j9kp">
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.valited-coupon').forEach(button => {
                button.addEventListener('click', function () {

                    const modal     = this.closest('.modal');
                    const code      = modal.querySelector('.coupon_code').value;
                    const courseId  = modal.querySelector('.course_id')?.value || null;
                    const userId    = modal.querySelector('.user_id')?.value || null;
                    const alertBox  = modal.querySelector('.coupon-alert');

                    fetch("{{ route('valited-coupon') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            code,
                            course_id: courseId,
                            user_id: userId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        alertBox.innerHTML = `
                            <div class="alert alert-${data.success ? 'success' : 'danger'} alert-dismissible fade show">
                                ${data.message}
                            </div>
                        `;
                    })
                    .catch(() => {
                        alertBox.innerHTML = `
                            <div class="alert alert-danger alert-dismissible fade show">
                                Erro ao validar cupom.
                            </div>
                        `;
                    });
                });
            });
        });
    </script>
@endsection