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
                <li class="breadcrumb-item active" aria-current="page">Cleaning</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">

                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Room Number</th>
                            <th>Status</th>
                            <th class="text-center w-25">Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($room->count() == 0)
                            <tr>
                                <td colspan="3" class="text-center">No data</td>
                            </tr>
                        @else
                            @foreach ($room as $d)
                                <tr>
                                    <td>{{ $d->number }}</td>
                                    <td>
                                        @if ($d->status == 'Available')
                                            <span class="badge bg-success">Available</span>
                                        @elseif($d->status == 'Occupied')
                                            <span class="badge bg-info">Occupied</span>
                                        @elseif($d->status == 'Reserved')
                                            <span class="badge bg-primary">Reserved</span>
                                        @elseif($d->status == 'Housekeeping')
                                            <span class="badge bg-warning">Housekeeping</span>
                                        @elseif($d->status == 'Maintenance')
                                            <span class="badge bg-warning">Maintenance</span>
                                        @elseif($d->status == 'Out of Order')
                                            <span class="badge bg-danger">Out of Order</span>
                                        @elseif($d->status == 'Blocked')
                                            <span class="badge bg-dark">Blocked</span>
                                        @elseif($d->status == 'Check-in')
                                            <span class="badge text-bg-light">Check-in</span>
                                        @elseif($d->status == 'Check-out')
                                            <span class="badge bg-secondary">Check-out</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                                            href="{{ route('cleaning.edit', $d->id) }}">
                                            Edit
                                        </a>
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
    </script>
@endpush
