@extends('app.layout')
@section('content')

    <div class="row mb-5 mt-5">
        <div class="col-12 col-sm-12 col-md-8 col-lg-8">
            <div class="card shadow-none bg-transparent border border-secondary mb-3">
                <div class="card-body text-secondary">
                    <h5 class="card-title text-secondary">Olá, {{ Auth::user()->maskName() }}</h5>
                    <p class="card-text">Acompanhe suas transações financeiras.</p>
                </div>
            </div>

            <div class="card shadow-none bg-transparent border border-secondary mb-3">
                <div class="card-body text-secondary">
                    <h5 class="card-title text-secondary">Faturas</h5>
                    <div class="nav-align-top mb-3">
                        <ul class="nav nav-pills flex-column flex-md-row gap-2 gap-lg-0">
                            @if (Auth::user()->role == 'admin')
                                <li class="nav-item">
                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalCreate" class="nav-link active waves-effect waves-light"><i class="ri-add-circle-line me-2"></i>Adicionar</button>
                            </li>
                            @endif
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
                                    <th>DESCRIÇÃO</th>
                                    <th>VALOR</th>
                                    <th>SITUAÇÃO</th>
                                    <th class="text-center">OPÇÕES</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @foreach($invoices as $invoice)
                                    <tr>
                                        <td class="text-info"> {{ $invoice->id }} </td>
                                        <td> {{ $invoice->payment_description }} </td>
                                        <td> {{ 'R$ ' . number_format($invoice->payment_value, 2, ',', '.') }} </td>
                                        <td>
                                            <span class="badge bg-label-{{ $invoice->paymentBgLabel() }}">
                                                {{ $invoice->paymentLabel() }}
                                            </span> <br>
                                            <small class="text-muted">{{ $invoice->payment_status === 'paid' ? 'Pago em: ' . $invoice->payment_paid_at->format('d/m/Y') : 'Vencimento: ' . $invoice->payment_due_date->format('d/m/Y') }}</small>
                                        </br>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('deleted-invoice', ['uuid' => $invoice->uuid]) }}" method="POST" class="delete">
                                                @csrf
                                                <a href="{{ $invoice->payment_url }}" class="btn btn-outline-success">
                                                    <span>Acessar</span>
                                                    <i class="ri-arrow-right-line ri-16px lh-1 scaleX-n1-rtl"></i>
                                                </a>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#modalUpdate{{ $invoice->uuid }}" class="btn btn-outline-warning"><i class="ri-edit-box-line"></i></button>
                                                <button type="submit" class="btn btn-outline-danger"><i class="ri-delete-bin-line"></i></button>
                                            </form>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="modalUpdate{{ $invoice->uuid }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-simple">
                                            <div class="modal-content p-3">
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                <div class="modal-body p-0">
                                                    <div class="text-center mb-6">
                                                        <h4 class="mb-2">FATURA</h4>
                                                        <p>Atualize os dados!</p>
                                                    </div>
                                                    <form action="{{ route('updated-invoice', ['uuid' => $invoice->uuid]) }}" method="POST">
                                                        @csrf
                                                        <div class="row g-2">
                                                            <div class="col-12">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" name="payment_description" id="payment_description" class="form-control" placeholder="Descrição" value="{{ $invoice->payment_description }}"/>
                                                                    <label for="payment_description">Descrição</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <select name="course_id" class="form-select" tabindex="0" id="course_id">
                                                                        <option value="  " selected>Nenhum curso selecionado</option>
                                                                        @foreach ($courses as $course)
                                                                            <option value="{{ $course->id }}" @selected($course->id == $invoice->course_id)>{{ $course->title }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="course_id">Escolha um curso:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <select name="user_id" class="form-select" tabindex="0" id="user_id">
                                                                        <option value="  " selected>Nenhum perfil selecionado</option>
                                                                        @foreach ($users as $user)
                                                                            <option value="{{ $user->id }}" @selected($user->id == $invoice->user_id)>{{ $user->name }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    <label for="user_id">Escolha um Perfil:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <select name="payment_method" class="form-select" tabindex="0" id="payment_method">
                                                                        <option value="PIX" selected>Escolha um Método</option>
                                                                        <option value="PIX" @selected($invoice->payment_method == 'PIX')>PIX</option>
                                                                        <option value="BOLETO" @selected($invoice->payment_method == 'BOLETO')>Boleto</option>
                                                                        <option value="CREDIT_CARD" @selected($invoice->payment_method == 'CREDIT_CARD')>Cartão de crédito</option>
                                                                    </select>
                                                                    <label for="payment_method">Método de Pagamento:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <select name="payment_status" class="form-select" tabindex="0" id="payment_status">
                                                                        <option value="  " selected>Escolha um Status</option>
                                                                        <option value="pending" @selected($invoice->payment_status == 'pending')>Pendente</option>
                                                                        <option value="paid" @selected($invoice->payment_status == 'paid')>Pago</option>
                                                                        <option value="canceled" @selected($invoice->payment_status == 'canceled')>Cancelado</option>
                                                                    </select>
                                                                    <label for="payment_status">Status:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="text" name="payment_value" id="payment_value" class="form-control money" oninput="maskValue(this)" placeholder="Valor" value="{{ $invoice->payment_value }}"/>
                                                                    <label for="payment_value">Valor</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <select name="payment_type" class="form-select" tabindex="0" id="payment_type">
                                                                        <option value="  " selected>Escolha um Tipo</option>
                                                                        <option value="revenue" @selected($invoice->payment_type == 'revenue')>Entrada</option>
                                                                        <option value="expense" @selected($invoice->payment_type == 'expense')>Saída</option>
                                                                    </select>
                                                                    <label for="payment_type">Tipo:</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="date" name="payment_due_date" id="payment_due_date" class="form-control" placeholder="Vencimento" value="{{ $invoice->payment_due_date ? \Carbon\Carbon::parse($invoice->payment_due_date)->format('Y-m-d') : '' }}"/>
                                                                    <label for="payment_due_date">Vencimento</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                                                                <div class="form-floating form-floating-outline">
                                                                    <input type="date" name="payment_paid_at" id="payment_paid_at" class="form-control" placeholder="Pagamento" value="{{ $invoice->payment_paid_at ? \Carbon\Carbon::parse($invoice->payment_paid_at)->format('Y-m-d') : '' }}"/>
                                                                    <label for="payment_paid_at">Pagamento</label>
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

        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
            <div class="card shadow-none bg-transparent border border-secondary mb-3">
                <div class="card-body text-secondary text-center">
                    <h5 class="card-title text-center">GRÁFICO</h5>
                    <canvas id="usageChart" height="120"></canvas>
                    <small class="text-muted">Total de: {{ $stats['total'] }}</small>
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
                        <h4 class="mb-2">FATURA</h4>
                        <p>Preencha os dados!</p>
                    </div>
                    <form action="{{ route('created-invoice') }}" method="POST" class="row g-2">
                        @csrf
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="payment_description" id="payment_description" class="form-control" placeholder="Descrição"/>
                                <label for="payment_description">Descrição</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <select name="course_id" class="form-select" tabindex="0" id="course_id">
                                    <option value="  " selected>Nenhum curso selecionado</option>
                                    @foreach ($courses as $course)
                                        <option value="{{ $course->id }}">{{ $course->title }}</option>
                                    @endforeach
                                </select>
                                <label for="course_id">Escolha um curso:</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <select name="user_id" class="form-select" tabindex="0" id="user_id">
                                    <option value="  " selected>Nenhum perfil selecionado</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                <label for="user_id">Escolha um Perfil:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <select name="payment_method" class="form-select" tabindex="0" id="payment_method">
                                    <option value="PIX" selected>Escolha um Método</option>
                                    <option value="PIX">PIX</option>
                                    <option value="BOLETO">Boleto</option>
                                    <option value="CREDIT_CARD">Cartão de crédito</option>
                                </select>
                                <label for="payment_method">Método de Pagamento:</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <select name="payment_status" class="form-select" tabindex="0" id="payment_status">
                                    <option value="  " selected>Escolha um Status</option>
                                    <option value="pending">Pendente</option>
                                    <option value="paid">Pago</option>
                                    <option value="canceled">Cancelado</option>
                                </select>
                                <label for="payment_status">Status:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="payment_value" id="payment_value" class="form-control money" oninput="maskValue(this)" placeholder="Valor"/>
                                <label for="payment_value">Valor</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <select name="payment_type" class="form-select" tabindex="0" id="payment_type">
                                    <option value="  " selected>Escolha um Tipo</option>
                                    <option value="revenue">Entrada</option>
                                    <option value="expense">Saída</option>
                                </select>
                                <label for="payment_type">Tipo:</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" name="payment_dua_date" id="payment_dua_date" class="form-control" placeholder="Vencimento"/>
                                <label for="payment_dua_date">Vencimento</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="form-floating form-floating-outline">
                                <input type="date" name="payment_paid_at" id="payment_paid_at" class="form-control" placeholder="Pagamento"/>
                                <label for="payment_paid_at">Pagamento</label>
                            </div>
                        </div>
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mt-3">
                            <button type="submit" class="btn btn-success">Adicionar</button>
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
                    <form action="{{ route('invoices') }}" method="GET" class="row g-2">
                        @csrf
                        <div class="col-12">
                            <div class="form-floating form-floating-outline">
                                <input type="text" name="search" id="search" class="form-control" placeholder="Descrição"/>
                                <label for="search">Descrição | Curso | Método de Pagamento | Status</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-floating form-floating-outline">
                                <input type="date" name="payment_paid_at" id="payment_paid_at" class="form-control" placeholder="Data de Pagamento"/>
                                <label for="payment_paid_at">Data de Pagamento</label>
                            </div>
                        </div>
                        <div class="col-12 col-sm-12 col-md-4 col-lg-4">
                            <div class="form-floating form-floating-outline">
                                <input type="date" name="payment_due_date" id="payment_due_date" class="form-control" placeholder="Data de Vencimento"/>
                                <label for="payment_due_date">Data de Vencimento</label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-4 col-lg-4">
                            <div class="form-floating form-floating-outline mb-4">
                                <select name="payment_status" class="form-select" tabindex="0" id="payment_status">
                                    <option value="  " selected>Escolha um Status</option>
                                    <option value="paid">Pagos</option>
                                    <option value="pending">Pendentes</option>
                                    <option value="canceled">Cancelados</option>
                                </select>
                                <label for="payment_status">Status:</label>
                            </div>
                        </div>
                        @if (Auth::user()->role == 'admin')
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-floating form-floating-outline">
                                    <select name="course_id" class="form-select" tabindex="0" id="course_id">
                                        <option value="  " selected>Nenhum curso selecionado</option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}">{{ $course->title }}</option>
                                        @endforeach
                                    </select>
                                    <label for="course_id">Escolha um curso:</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-6">
                                <div class="form-floating form-floating-outline">
                                    <select name="user_id" class="form-select" tabindex="0" id="user_id">
                                        <option value="  " selected>Nenhum perfil selecionado</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="user_id">Escolha um Perfil:</label>
                                </div>
                            </div>
                        @endif
                        <div class="col-12 d-flex flex-wrap justify-content-center gap-4 row-gap-4 mt-3">
                            <button type="submit" class="btn btn-success">Filtrar</button>
                            <button type="reset" class="btn btn-outline-danger" data-bs-dismiss="modal" aria-label="Close">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('usageChart');
        const chart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Pagos',
                    'Pendentes',
                    'Cancelados',
                ],
                datasets: [{
                    data: [
                        {{ $stats['paid'] }},
                        {{ $stats['pending'] }},
                        {{ $stats['canceled'] }},
                    ],
                    backgroundColor: [
                        '#28c76f',
                        '#ff9f43',
                        '#FF0000',
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '75%'
            }
        });
    </script>
@endsection