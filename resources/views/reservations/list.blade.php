@extends('partials.main')

@section('content')
    <div class="content">

        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-body-tertiary rounded-3">
                <li class="breadcrumb-item"> <a
                        class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                        href="{{ route('dashboard') }}">
                        Dashboard
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Reservations</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <a type="button" class="btn btn-primary mb-3"
                    href="{{ route('reservations.create', ['view' => 'list']) }}"> Add New </a>
                <div class="d-flex flex-row gap-2 mb-3">
                    <div class="flex-fill w-100">
                        <select class="form-select" id="month" name="month" data-placeholder="Choose type">
                            <option value="01"
                                {{ request()->get('month') == '01' || $month == '01' ? 'selected' : '' }}>
                                January</option>
                            <option value="02"
                                {{ request()->get('month') == '02' || $month == '02' ? 'selected' : '' }}>
                                February</option>
                            <option value="03"
                                {{ request()->get('month') == '03' || $month == '03' ? 'selected' : '' }}>
                                March</option>
                            <option value="04"
                                {{ request()->get('month') == '04' || $month == '04' ? 'selected' : '' }}>
                                April</option>
                            <option value="05"
                                {{ request()->get('month') == '05' || $month == '05' ? 'selected' : '' }}>
                                May</option>
                            <option value="06"
                                {{ request()->get('month') == '06' || $month == '06' ? 'selected' : '' }}>
                                June</option>
                            <option value="07"
                                {{ request()->get('month') == '07' || $month == '07' ? 'selected' : '' }}>
                                July</option>
                            <option value="08"
                                {{ request()->get('month') == '08' || $month == '08' ? 'selected' : '' }}>
                                August</option>
                            <option value="09"
                                {{ request()->get('month') == '09' || $month == '09' ? 'selected' : '' }}>
                                September
                            </option>
                            <option value="10"
                                {{ request()->get('month') == '10' || $month == '10' ? 'selected' : '' }}>
                                October</option>
                            <option value="11"
                                {{ request()->get('month') == '11' || $month == '11' ? 'selected' : '' }}>
                                November
                            </option>
                            <option value="12"
                                {{ request()->get('month') == '12' || $month == '12' ? 'selected' : '' }}>
                                December
                            </option>
                        </select>
                    </div>
                    <div class="flex-fill w-100">
                        <input type="text" id="year" name="year" class="form-control"
                            placeholder="Insert years..." value="{{ request()->get('year') ?? date('Y') }}">
                    </div>
                    <div class="flex-fill w-100">
                        <select class="form-select" id="view" name="view" data-placeholder="Choose view">
                            <option value="calendar" {{ request()->get('view') == 'calendar' ? 'selected' : '' }}>
                                Calendar View</option>
                            <option value="list" {{ request()->get('view') == 'list' ? 'selected' : '' }}>
                                List View</option>
                        </select>
                    </div>
                    <div class="flex-fill w-100">
                        <input type="text" id="search" name="search" class="form-control"
                            placeholder="Search guests..." value="{{ request()->get('search') }}">
                    </div>
                </div>
                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Number</th>
                            <th>Room</th>
                            <th>Room Type</th>
                            <th class="text-center">Room Status</th>
                            <th>Guest</th>
                            <th class="text-center">Check-in Date</th>
                            <th class="text-center">Check-out Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($reservation as $d)
                            @if (
                                \Carbon\Carbon::parse($d->check_in_date)->format('Y') == $year &&
                                    \Carbon\Carbon::parse($d->check_in_date)->format('m') == $month)
                                <tr>
                                    <td>
                                        <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                            href="{{ route('reservations.show', $d->id) }}">
                                            {{ $d->number }}
                                        </a>
                                    </td>
                                    <td>
                                        {{ $d->room->number }}
                                    </td>
                                    <td>{{ $d->room->roomtype->name }}</td>
                                    <td class="text-center">
                                        @if (
                                            $d->room->status == 'Available' &&
                                                $d->status == 'Confirmed' &&
                                                $d->check_out_date > date('Y-m-d') &&
                                                $d->check_in_date == date('Y-m-d') &&
                                                $d->room_check_in == 'Off')
                                            <form class="d-inline" action="{{ route('reservations.check_in', $d->id) }}"
                                                method="POST" id="form-check-in{{ $d->id }}">
                                                @csrf
                                                @method('PUT')
                                                <a class="link-success link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                                    href="#" data-id="{{ $d->id }}"
                                                    onclick="check_in({{ $d->id }}); return false;">
                                                    Check In
                                                </a>
                                            </form>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $d->guest->name }}</td>
                                    <td class="text-center">{{ \Carbon\Carbon::parse($d->check_in_date)->format('d F Y') }}
                                    </td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($d->check_out_date)->format('d F Y') }}</td>
                                    <td class="text-center">
                                        @if ($d->status == 'Pending')
                                            <form class="d-inline" action="{{ route('reservations.confirm', $d->id) }}"
                                                method="POST" id="form-confirm{{ $d->id }}">
                                                @csrf
                                                @method('PUT')
                                                <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                                    href="#" data-id="{{ $d->id }}"
                                                    onclick="confirm_data({{ $d->id }}); return false;">
                                                    Confirm
                                                </a>
                                            </form>
                                            <form class="d-inline" action="{{ route('reservations.cancel', $d->id) }}"
                                                method="POST" id="form-cancel{{ $d->id }}">
                                                @csrf
                                                @method('PUT')
                                                <a class="link-danger link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                                    href="#" data-id="{{ $d->id }}"
                                                    onclick="cancel_data({{ $d->id }}); return false;">
                                                    Cancel
                                                </a>
                                            </form>
                                        @else
                                            {{ $d->status }}
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        @if (session('message'))
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Success!",
                    text: "{{ session('message') }}",
                    icon: "success"
                });
            });
        @endif

        $(document).ready(function() {
            $('#month').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                minimumResultsForSearch: -1
            }).on('change', function() {
                search()
            });

            $('#view').select2({
                theme: "bootstrap-5",
                width: $(this).data('width') ? $(this).data('width') : $(this).hasClass('w-100') ? '100%' :
                    'style',
                placeholder: $(this).data('placeholder'),
                minimumResultsForSearch: -1
            }).on('change', function() {
                search()
            });
        });

        $(document).keyup(function(e) {
            if ($("#year").is(":focus") && (e.keyCode == 13)) {
                $(this).val(numeral(this.value).format("0"))
                search()
            }
        });

        function search() {
            var url = '{!! route('reservations.index', [
                'year' => '_year',
                'month' => '_month',
                'view' => '_view',
                'search' => '_search',
            ]) !!}'
            url = url.replace('_year', $("#year").val())
            url = url.replace('_month', $("#month").val())
            url = url.replace('_view', $("#view").val())
            url = url.replace('_search', $("#search").val())
            window.open(url, '_self')
        }

        $(document).keyup(function(e) {
            if ($("#search").is(":focus") && (e.keyCode == 13)) {
                search()
            }
        });

        function cancel_data(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You will cancel this reservation!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, cancel it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-cancel' + id).submit();
                }
            });
        }

        function confirm_data(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "You will confirm this reservation!",
                icon: "success",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, confirm it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-confirm' + id).submit();
                }
            });
        }

        function check_in(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Confirming will change the room status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#198754",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, check-in it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-check-in' + id).submit();
                }
            });
        }
    </script>
@endpush
