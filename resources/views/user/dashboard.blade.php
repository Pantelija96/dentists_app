@extends('layouts.layout')

@section('title') - User dashboard @endsection

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

                        <div class="userDatatable adv-table-table global-shadow border-light-0 w-100 adv-table">
                            <div class="table-responsive">
                                <div class="adv-table-table__header">
                                    <h4>{{ auth()->user()->first_name }} - dashboard</h4>
                                    <div class="adv-table-table__button" style="display: flex;">
                                        <div class="action-btn">
                                            <a href="{{ route('work.show.addNew') }}" class="btn px-15 btn-primary" style="color: #fff;">
                                                <i class="las la-plus fs-16"></i>{{ __('app.add_work') }}</a>
                                        </div>
                                    </div>
                                </div>
                                <div id="filter-form-container"></div>
                                <table id="user-work-orders-table" class="table mb-0 table-borderless adv-table" data-sorting="true" data-filter-container="#filter-form-container" data-paging-current="1" data-paging-position="right" data-paging-size="10">
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
                                            <span class="userDatatable-title">{{ __('app.publish') }}</span>
                                        </th>
                                        <th>
                                            <span class="userDatatable-title float-end">{{ __('app.action') }}</span>
                                        </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($work_orders as $work_order)
                                        <tr @if($work_order->deleted) style="background-color: #e59797" @endif data-status="{{ $work_order->status->name ?? 'Unknown' }}">
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
                                                    <a href="{{ route('work.download', $work_order->id) }}">{{ __('app.link') }}</a>
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
                                                <div class="d-flex ">
                                                    @if($work_order->draft)
                                                        <a href="{{ route('work.publish', $work_order->id) }}" class="btn btn-primary btn-sm" style = "color: white;">
                                                            {{ __('app.publish') }}
                                                        </a>
                                                    @else
                                                        <span>{{ __('app.published') }}</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <ul class="orderDatatable_actions mb-0 d-flex flex-wrap">
                                                    <li>
                                                        <a href="{{ route('work.inspect', $work_order->id) }}" class="view" title = "{{ __('app.inspect') }}">
                                                            <i class="uil uil-eye"></i>
                                                        </a>
                                                    </li>
                                                    @if(!$work_order->locked)
                                                        <li>
                                                            <a href="{{ route('work.edit', $work_order->id) }}" class="view" title = "{{ __('app.edit') }}">
                                                                <i class="uil uil-pen"></i>
                                                            </a>
                                                        </li>
                                                        @if(!$work_order->deleted)
                                                            <li>
                                                                <a href="{{ route('work.remove', $work_order->id) }}" class="view" title = "{{ __('app.delete_work_order') }}">
                                                                    <i class="uil uil-trash-alt"></i>
                                                                </a>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <a href="{{ route('work.remove', $work_order->id) }}" class="view">
                                                                    <i class="fas fa-caret-left"></i>
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                    @if($work_order->deleted && $work_order->locked)
                                                        <li>
                                                            <a href="{{ route('work.remove', $work_order->id) }}" class="view">
                                                                <i class="fas fa-caret-left"></i>
                                                            </a>
                                                        </li>
                                                    @endif

                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">{{ __('app.no_work_orders') }}</td>
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
    <script src="{{ asset('assets/scripts/user-dashboard.js') }}"></script>
@endsection
