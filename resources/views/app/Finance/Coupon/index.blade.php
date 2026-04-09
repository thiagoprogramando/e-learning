@extends('app.layout')
@section('content')

    <div class="row mb-5 mt-5">
        <div class="col-12 col-sm-12 col-md-12 col-lg-12">
            <div class="card shadow-none bg-transparent border border-secondary mb-3">
                <div class="card-body text-secondary">
                    <h5 class="card-title text-secondary">CUPONS</h5>
                    <p class="card-text">Cupons e promoções.</p>
                </div>
            </div>

            <div class="card shadow-none bg-transparent border border-secondary mb-3">
                <div class="card-body text-secondary">
                    <div class="nav-align-top mb-3">
                        <ul class="nav nav-pills flex-column flex-md-row gap-2 gap-lg-0">
                            <li class="nav-item">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreate" class="nav-link active waves-effect waves-light"><i class="ri-add-circle-line me-2"></i>Adicionar</button>
                            </li>
                            <li class="nav-item">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalFilter" class="nav-link waves-effect waves-light"><i class="ri-filter-3-line me-2"></i>Filtrar</button>
                            </li>
                        </ul>
                    </div>

                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>CÓDIGO</th>
                                    <th class="text-center">VALOR/PORCENTAGEM</th>
                                    <th class="text-center">SITUAÇÃO</th>
                                    <th class="text-center">OPÇÕES</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach($coupons as $coupon)
                                    <tr>
                                        <td> {{ $coupon->id }} </td>
                                        <td class="text-info"> <a onclick="onClip('{{ $coupon->code }}')">{{ $coupon->code }}</a> </td>
                                        <td class="text-center"> 
                                            {{ $coupon->value > 0 ? 'R$ ' . number_format($coupon->value, 2, ',', '.') : number_format($coupon->percentage, 2, ',', '.') .'%' }} <br>
                                            <span class="text-muted">{{ $coupon->quanty != null ? 'Disponível: '.$coupon->quanty : 'Ilimitado' }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-label-{{ $coupon->statusBgLabel() }}">
                                                {{ $coupon->statusLabel() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('deleted-coupon', ['id' => $coupon->id]) }}" method="POST" class="delete">
                                                @csrf
                                                <a onclick="onClip('{{ $coupon->code }}')" class="btn btn-outline-info"><i class="ri-file-copy-line"></i></a>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $coupon->id }}" class="btn btn-outline-warning"><i class="ri-edit-box-line"></i></button>
                                                <button type="submit" class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalUpdate{{ $coupon->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-simple">
                                            <div class="modal-content p-3">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <div class="modal-body p-0">
                                                    <div class="text-center mb-6">
                                                        <h4 class="mb-2">CUPOM</h4>
                                                        <p>Atualize os dados!</p>
                                                    </div>
                                                    <form action="{{ route('updated-coupon', ['id' => $coupon->id]) }}" method="POST">
                                                        @csrf
                                                        <div class="row g-2">
                                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline mb-2">
                                                                    <select name="course_id" class="form-select" tabindex="0" id="course_id">
                                                                        <option value="  " selected>Todos os cursos</option>
                                                                        @foreach ($courses as $course)
                                                                            <option value="{{ $course->id }}" @selected($course->id == $coupon->course_id)>{{ $course->title }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="course_id">Escolha um curso:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline mb-2">
                                                                    <select name="user_id" class="form-select" tabindex="0" id="user_id">
                                                                        <option value="  " selected>Todos os Perfis</option>
                                                                        @foreach ($users as $user)
                                                                            <option value="{{ $user->id }}" @selected($user->id == $coupon->user_id)>{{ $user->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="user_id">Escolha um Perfil:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div id="inputValue" class="form-floating form-floating-outline">
                                                                    <input type="text" name="value" class="form-control money" placeholder="Valor" oninput="maskValue(this)" value="{{ $coupon->value }}">
                                                                    <label>Valor (R$)</label>
                                                                </div>
                                                                <div id="inputPercentage" class="form-floating form-floating-outline d-none">
                                                                    <input type="number" name="percentage" class="form-control" placeholder="Porcentagem" min="0" max="100" step="1" oninput="limitPercentage(this)" value="{{ $coupon->percentage }}">
                                                                    <label>Porcentagem (%)</label>
                                                                </div>
                                                                <div class="form-check form-switch mb-2">
                                                                    <input class="form-check-input" type="checkbox" id="typeDiscount">
                                                                    <label class="form-check-label" for="typeDiscount"> Porcentagem (%) </label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline mb-4">
                                                                    <select name="status" class="form-select" tabindex="0" id="status">
                                                                        <option value="  " selected>Escolha um Status</option>
                                                                        <option value="active" @selected($coupon->status == 'active')>Ativo</option>
                                                                        <option value="inactive" @selected($coupon->status == 'inactive')>Inativo</option>
                                                                    </select>
                                                                    <label for="status">Status:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="number" name="quanty" id="quanty" class="form-control" placeholder="Quantidade" value="{{ $coupon->quanty }}"/>
                                                                    <label for="quanty">Quantidade</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mt-3">
                                                                <button type="submit" class="btn btn-success">Atualizar</button>
                                                                <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="text-center mb-6">
                        <h4 class="mb-2">CUPOM</h4>
                        <p>Preencha os dados!</p>
                    </div>
                    <form action="{{ route('created-coupon') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline mb-2">
                                <select name="course_id" class="form-select" tabindex="0" id="course_id">
                                    <option value="  " selected>Todos os cursos</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                                <label for="course_id">Escolha um curso:</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline mb-2">
                                <select name="user_id" class="form-select" tabindex="0" id="user_id">
                                    <option value="  " selected>Todos os Perfis</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="user_id">Escolha um Perfil:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div id="inputValue" class="form-floating form-floating-outline">
                                <input type="text" name="value" class="form-control" placeholder="Valor" oninput="maskValue(this)">
                                <label>Valor (R$)</label>
                            </div>
                            <div id="inputPercentage" class="form-floating form-floating-outline d-none">
                                <input type="number" name="percentage" class="form-control" placeholder="Porcentagem" min="0" max="100" step="1" oninput="limitPercentage(this)">
                                <label>Porcentagem (%)</label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="typeDiscount">
                                <label class="form-check-label" for="typeDiscount"> Porcentagem (%) </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="status" class="form-select" tabindex="0" id="status">
                                    <option value="  " selected>Escolha um Status</option>
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                                <label for="status">Status:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <input type="number" name="quanty" id="quanty" class="form-control" placeholder="Quantidade"/>
                                <label for="quanty">Quantidade</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mt-3">
                            <button type="submit" class="btn btn-success">Gerar</button>
                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-simple">
            <div class="modal-content p-3">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body p-0">
                    <div class="text-center mb-6">
                        <h4 class="mb-2">FILTRAR</h4>
                        <p>Filtre os dados da Pesquisa!</p>
                    </div>
                    <form action="{{ route('coupons') }}" method="GET" class="row g-2">
                        @csrf
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Código"/>
                                <label for="search">Código</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline mb-2">
                                <select name="status" class="form-select" tabindex="0" id="status">
                                    <option value="  " selected>Escolha um Status</option>
                                    <option value="active">Ativo</option>
                                    <option value="inactive">Inativo</option>
                                </select>
                                <label for="status">Status:</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline mb-2">
                                <select name="course_id" class="form-select" tabindex="0" id="course_id">
                                    <option value="  " selected>Todos os cursos</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                                <label for="course_id">Escolha um curso:</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <div class="form-floating form-floating-outline mb-2">
                                <select name="user_id" class="form-select" tabindex="0" id="user_id">
                                    <option value="  " selected>Todos os Perfis</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="user_id">Escolha um Perfil:</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mt-3">
                            <button type="submit" class="btn btn-success">Filtrar</button>
                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const switchInput = document.getElementById('typeDiscount');
            const inputValueWrapper = document.getElementById('inputValue');
            const inputPercentageWrapper = document.getElementById('inputPercentage');
            const inputValue = inputValueWrapper.querySelector('input');
            const inputPercentage = inputPercentageWrapper.querySelector('input');

            switchInput.addEventListener('change', function () {
                if (this.checked) {
                    inputValueWrapper.classList.add('d-none');
                    inputPercentageWrapper.classList.remove('d-none');
                    inputValue.disabled = true;
                    inputPercentage.disabled = false;
                } else {
                    inputValueWrapper.classList.remove('d-none');
                    inputPercentageWrapper.classList.add('d-none');
                    inputValue.disabled = false;
                    inputPercentage.disabled = true;
                }
            });
            if (switchInput.checked) {
                inputValue.disabled = true;
                inputPercentage.disabled = false;
            } else {
                inputValue.disabled = false;
                inputPercentage.disabled = true;
            }
        });

        function limitPercentage(input) {
            let value = parseFloat(input.value);
            if (isNaN(value)) return;
            if (value > 100) input.value = 100;
            if (value < 0) input.value = 0;
        }
    </script>
@endsection