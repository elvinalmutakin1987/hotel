@extends('partials.main')

@section('content')
    <style>
        .oval {
            border-radius: 10px;
        }
    </style>
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
                <a type="button" class="btn btn-outline-primary mb-3"
                    href="{{ route('reservations.create', ['view' => 'calendar']) }}"> Add New
                </a>
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
                </div>

                <table id="table1" class="table table-hover table-bordered table-sm mt-3 table-responsive">
                    <thead class="table-group-divider ">
                        <tr>
                            <th scope="col">Rooms</th>
                            @php
                                $dayInMonth = \Carbon\Carbon::parse($year . '-' . $month . '-01')->daysInMonth;
                            @endphp
                            @for ($i = 1; $i <= $dayInMonth; $i++)
                                <th scope="col" style="width: 32px;"
                                    class="{{ $i == date('d') ? 'bg-dark text-white' : '' }}">
                                    {{ sprintf('%02d', $i) }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @foreach ($room_type as $rt)
                            <tr>
                                <td colspan="{{ $dayInMonth + 1 }}" class="fw-bold">
                                    {{ $rt->name }}
                                </td>
                            </tr>
                            @foreach ($room as $r)
                                @if ($rt->id == $r->roomtype_id)
                                    <tr>
                                        <td>{{ $r->number }}</td>
                                        @for ($i = 1; $i <= $dayInMonth; $i++)
                                            @php
                                                $currentDate = \Carbon\Carbon::parse("$year-$month-$i");

                                                $isReserved = \App\Models\Reservation::where('room_id', $r->id)
                                                    ->whereIn('status', ['Pending', 'Confirmed'])
                                                    ->whereDate('check_in_date', '<=', $currentDate)
                                                    ->whereDate('check_out_date', '>', $currentDate)
                                                    ->exists();
                                            @endphp

                                            @if ($isReserved)
                                                <td class="bg-danger oval">
                                                </td>
                                            @else
                                                <td class="bg-success oval"></td>
                                            @endif
                                        @endfor
                                    </tr>
                                @endif
                            @endforeach
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
            var url = '{!! route('reservations.index', ['year' => '_year', 'month' => '_month', 'view' => '_view']) !!}'
            url = url.replace('_year', $("#year").val())
            url = url.replace('_month', $("#month").val())
            url = url.replace('_view', $("#view").val())
            window.open(url, '_self')
        }
    </script>
@endpush
