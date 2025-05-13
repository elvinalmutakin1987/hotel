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
                <li class="breadcrumb-item active" aria-current="page">Check Out</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">

                <a type="button" class="btn btn-outline-primary mb-3" href="{{ route('checkout.create') }}"> Add New </a>

                <input type="date" id="date" name="date" class="form-control mb-3"
                    value="{{ $date ?? date('Y-m-d') }}">

                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Reservation Number</th>
                            <th class="w-25 text-center">Room</th>
                            <th class="w-25 text-center">Check Out Time</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($check_out->count() == 0)
                            <tr>
                                <td colspan="3" class="text-center">No data</td>
                            </tr>
                        @else
                            @foreach ($check_out as $d)
                                <tr>
                                    <td>{{ $d->reservation->number }}</td>
                                    <td class="text-center">{{ $d->room->number }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($d->created_at)->format('d F Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
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

        function search() {
            var url = '{{ route('checkout.index', ['date' => '_date']) }}'
            url = url.replace('_date', $("#date").val())
            window.open(url, '_self')
        }

        $("#date").on('change', function(e) {
            search()
        })
    </script>
@endpush
