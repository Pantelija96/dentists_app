@extends('layouts.layout')

@section('title') - Inspect work order @endsection

@section('additionalPluginCSS')
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/footable.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="inspect-main-container" style="margin-top: 100px;">

        <div class="row">
            <div class="col-12 mb-30">
                <div class="support-ticket-system support-ticket-system--search">

                    <div class="breadcrumb-main m-0 breadcrumb-main--table justify-content-sm-between ">
                        <div class=" d-flex flex-wrap justify-content-center breadcrumb-main__wrapper">
                            <div class="d-flex align-items-center ticket__title justify-content-center me-md-25 mb-md-0 mb-20">
                                <h4 class="text-capitalize fw-500 breadcrumb-title">{{ $work_order->name }}</h4>
                            </div>
                            <div class="userDatatable-content d-inline-block">
                                <span class="bg-opacity-success  color-success rounded-pill userDatatable-content-status active" style="background-color: {{ $work_order->status->color ?? '#ccc' }}; color: #000;">
                                    {{ $work_order->status->name }}
                                </span>
                            </div>
                        </div>
                        <div class="action-btn">
                            <a href="{{ route('work.download', $work_order->id) }}" class="btn btn-primary">
                                Download files
                            </a>
                        </div>
                    </div>

                    @php
                        $groups = $work_order->items->groupBy(function ($item) {
                            return
                                $item->work_type_id . '|' .
                                ($item->material_id ?? 'null') . '|' .
                                md5($item->parameters ?? '');
                        });
                    @endphp

                    <div id="toothGroupsContainer"
                         style="font-size: 0.85rem; color: #555; margin-top: 30px;">

                        <table class="table table-sm">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Material</th>
                                <th>Teeth</th>
                                <th>Parameters</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($groups as $index => $items)
                                @php
                                    $first = $items->first();
                                    $teeth = $items->pluck('tooth_number')->sort()->values();
                                    $parameters = json_decode($first->parameters ?? '{}', true);
                                @endphp

                                <tr>
                                    <td>{{ $loop->iteration }}</td>

                                    <td>
                                        {{ $first->workType->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $first->material->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $teeth->join(', ') }}
                                    </td>

                                    <td>
                                        @if(!empty($parameters))
                                            @foreach($parameters as $paramName => $value)
                                                <div>
                                                    <strong>{{ $paramName }}:</strong>
                                                    @if(is_bool($value))
                                                        {{ $value ? 'Yes' : 'No' }}
                                                    @elseif($value === 1 || $value === 0)
                                                        {{ $value ? 'Yes' : 'No' }}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </div>
                                            @endforeach
                                        @else
                                            <em>No parameters</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="inspect-row">
            @if(auth()->user()->role == 1 || auth()->user()->role == 'admin')
                <div class="card card-default card-md mb-4">
                    <div class="card-header  py-20">
                        <h6>Select Status</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.work.changestatus', $work_order->id) }}">
                            @csrf()
                            <div class="select-size">
                                <div class="dm-select ">

                                    <select name="status" id="statuses" class="form-control  form-control-lg">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" @if($status->id == $work_order->status->id) selected @endif>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-lg btn-squared btn-shadow-primary fw-400 mt-3">Change status</button>
                        </form>
                    </div>
                </div>
            @endif

            <div class="card card-default card-md mb-4">
                <div class="card-header py-20">
                    <h6>Comments</h6>
                </div>
                <div class="card-body pb-10">


                    @forelse($work_order->comments as $comment)
                        <div class="dm-comment-box media mb-3">
                            <div class="dm-comment-box__author">
                                <figure>
                                    <img src="{{ asset('assets/Slike/ikonice/profile-avatar.png') }}" class="d-flex" alt="{{ $comment->user->first_name }}">
                                </figure>
                            </div>
                            <div class="dm-comment-box__content media-body">
                                <div class="comment-content-inner cci">
                                    <span class="cci__author-info">{{ $comment->user->first_name }}</span>
                                    <p class="cci__comment-text">{{ $comment->comment }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No comments yet.</p>
                    @endforelse


                </div>
            </div>
            <div class="col-lg-12">
                <div class="card card-default card-md mb-4">
                    <div class="card-body pb-10">
                        <div class="reply-editor media">
                            <!-- ends: .reply-editor__author -->
                            <div class="reply-editor__form media-body">
                                <form action="{{ route('work.addcomment', $work_order->id) }}" method="POST">
                                    @csrf()
                                    <div class="row">
                                        <div class="form-group col-12">
                                            <textarea class="form-control mb-4" name="comment" id="comment"></textarea>
                                            <button type="submit" class="btn btn-primary btn-lg btn-squared btn-shadow-primary fw-400">Add Comment</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- ends: .reply-editor__form -->
                        </div>
                        <!-- ends: .reply-editor -->
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('additionalPluginJS')
    <script src="{{ asset('assets/assets/vendor_assets/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/assets/vendor_assets/js/footable.min.js') }}"></script>
@endsection

@section('additionalPageJS')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            $('#statuses').select2({
                minimumResultsForSearch: Infinity,
                allowClear: true,
            });

            const table = document.querySelector('.userDatatable table');
            if (!table) return;

            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            const paginationList = document.querySelector('.dm-pagination');
            if (!paginationList) return;

            let currentPage = 1;
            let rowsPerPage;
            let totalPages;
            let resizeTimer;

            // Funkcija koja vraca broj redova po stranici na osnovu sirine prozora
            function getRowsPerPage() {
                const w = window.innerWidth;

                if (w < 576) {
                    return 5;
                } else if (w < 768) {
                    return 8;
                } else if (w < 1200) {
                    return 12;
                } else if (w < 1600) {
                    return 8;
                } else {
                    return 16;
                }
            }

            // Prikazujemo odgovarajucu stranicu
            function showPage(page) {
                if (page < 1) page = 1;
                if (page > totalPages) page = totalPages;

                currentPage = page;

                const start = (page - 1) * rowsPerPage;
                const end = start + rowsPerPage;

                rows.forEach((row, index) => {
                    row.style.display = (index >= start && index < end) ? '' : 'none';
                });

                // Resetovanje aktivne klase
                const allLinks = paginationList.querySelectorAll('.dm-pagination__link');
                allLinks.forEach(a => a.classList.remove('active'));

                // Active na trenutnoj stranici
                const activeLink = paginationList.querySelector(
                    `.dm-pagination__link[data-page="${page}"]`
                );
                if (activeLink) {
                    activeLink.classList.add('active');
                }
            }

            // Pravimo paginaciju
            function buildPagination() {
                paginationList.innerHTML = '';

                if (totalPages <= 1) {
                    // Nema potrebe za paginacijom
                    return;
                }

                const li = document.createElement('li');
                li.className = 'dm-pagination__item';

                // PREV
                const prevLink = document.createElement('a');
                prevLink.href = '#';
                prevLink.className = 'dm-pagination__link pagination-control prev';
                prevLink.innerHTML = '<span class="la la-angle-left"></span>';
                prevLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (currentPage > 1) {
                        showPage(currentPage - 1);
                    }
                });
                li.appendChild(prevLink);

                // Brojevi stranica
                for (let i = 1; i <= totalPages; i++) {
                    const pageA = document.createElement('a');
                    pageA.href = '#';
                    pageA.className = 'dm-pagination__link';
                    pageA.dataset.page = i;

                    const span = document.createElement('span');
                    span.className = 'page-number';
                    span.textContent = i;

                    pageA.appendChild(span);

                    pageA.addEventListener('click', function (e) {
                        e.preventDefault();
                        const pageNum = parseInt(this.dataset.page, 10);
                        if (!isNaN(pageNum)) {
                            showPage(pageNum);
                        }
                    });

                    li.appendChild(pageA);
                }

                // Next strelica
                const nextLink = document.createElement('a');
                nextLink.href = '#';
                nextLink.className = 'dm-pagination__link pagination-control next';
                nextLink.innerHTML = '<span class="la la-angle-right"></span>';
                nextLink.addEventListener('click', function (e) {
                    e.preventDefault();
                    if (currentPage < totalPages) {
                        showPage(currentPage + 1);
                    }
                });
                li.appendChild(nextLink);

                paginationList.appendChild(li);
            }

            // Kalkulise i prikazuje paginaciju
            function recalcPagination() {
                rowsPerPage = getRowsPerPage();
                totalPages = Math.ceil(rows.length / rowsPerPage);

                if (currentPage > totalPages) {
                    currentPage = totalPages;
                }
                if (currentPage < 1) {
                    currentPage = 1;
                }

                buildPagination();
                showPage(currentPage);
            }

            // Inicijalno
            recalcPagination();

            // Timer za ispitivanje resize eventa
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    recalcPagination();
                }, 200); // Ceka 200ms nakon poslednjeg resize eventa
            });
        });
    </script>
@endsection
