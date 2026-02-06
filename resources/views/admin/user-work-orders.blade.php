@extends('layouts.layout')

@section('title') - User's work orders @endsection

@section('additionalPluginCSS')
    <link rel="stylesheet" href="{{ asset('assets/assets/vendor_assets/css/footable.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/assets/filtering.css') }}">
@endsection

@section('content')
    <div class="container-fluid" style="padding-top: 50px;">
        <div class="row">
            <div class="col-lg-12 mb-30">
                <div class="card mt-30">
                    <div class="card-body">
                        <div class="adv-table-table global-shadow border-light-0 w-100 adv-table">
                            <div class="table-responsive">
                                <div class="adv-table-table__header">
                                    <h4>{{ $target_user->first_name }} {{ $target_user->last_name }} - {{ __('app.work_orders_dashboard') }}</h4>
                                    <div class="adv-table-table__button" style="display: flex;">
                                        <div class="action-btn">
                                            <a href="{{ route('work.show.addNew') }}" class="btn px-15 btn-primary" style="color: #fff;">
                                                <i class="las la-plus fs-16"></i>{{ __('app.add_work') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-form-container" id="filter-form-work-orders"></div>
                                <table id="work-orders-table" class="table mb-0 table-borderless adv-table" data-sorting="true" data-filter-container="#filter-form-work-orders" data-paging-current="1" data-paging-position="right" data-paging-size="10">
                                    <thead>
                                    <tr class="userDatatable-header">
                                        <th>
                                            <span class="userDatatable-title">ID</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">{{ __('app.user') }}</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">email</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">{{ __('app.work_name') }}</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">{{ __('app.files') }}</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title">{{ __('app.date') }}</span>
                                        </th>
                                        <th data-type="html" data-name='status'>
                                            <span class="userDatatable-title">status</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title float-end">{{ __('app.action') }}</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($work_orders as $work_order)
                                        <tr data-status="{{ $work_order->status->name ?? 'Unknown' }}">
                                            <td>
                                                <div class="userDatatable-content">{{ $work_order->id }}</div>
                                            </td>
                                            <td>
                                                <div class="d-flex">
                                                    <div class="userDatatable-inline-title">
                                                        <a href="#" class="text-dark fw-500">
                                                            <h6>{{ $work_order->user->first_name }} {{ $work_order->user->last_name }}</h6>
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $work_order->user->email }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $work_order->name }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    <a href="{{ route('work.download', $work_order->id) }}">Link to files</a>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content">
                                                    {{ $work_order->created_at->translatedFormat('F d, Y @ H:i') }}
                                                </div>
                                            </td>
                                            <td>
                                                <div class="userDatatable-content d-inline-block">
                                                    <span class="rounded-pill userDatatable-content-status active" style="background-color: {{ $work_order->status->color ?? '#ccc' }}; color: #000;">
                                                        {{ $work_order->status->translated_name ?? 'Unknown' }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td>
                                                <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                                                    <li>
                                                        <a href="{{ route('work.inspect', $work_order->id) }}" class="view" title = "{{ __('app.inspect') }}">
                                                            <i class="uil uil-eye"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('work.edit', $work_order->id) }}" class="view" title = "{{ __('app.edit') }}">
                                                            <i class="uil uil-pen"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="{{ route('work.remove', $work_order->id) }}" class="view" title = "{{ __('app.delete_work_order') }}">
                                                            <i class="uil uil-trash-alt"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No work orders made yet.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('additionalPluginJS')
    <script src="{{ asset('assets/assets/vendor_assets/js/footable.min.js') }}"></script>
@endsection

@section('additionalPageJS')
    <script>
        window.statusesFromDB = @json($statusesForJs);
    </script>
    <script src="{{ asset('assets/scripts/admin-dashboard.js') }}"></script>
@endsection
