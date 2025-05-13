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
                <li class="breadcrumb-item"> <a
                        class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover me-2"
                        href="{{ route('checkout.index') }}">
                        Check Out
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Create</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <table id="table1" class="table table-striped mt-3">
                    <thead class="table-group-divider">
                        <tr>
                            <th>Reservation Number</th>
                            <th class="w-25">Room</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider">
                        @if ($reservation->count() == 0)
                            <tr>
                                <td colspan="2" class="text-center">No data</td>
                            </tr>
                        @else
                            @foreach ($reservation as $d)
                                <tr>
                                    <td>
                                        <form class="d-inline" action="{{ route('checkout.update', $d->id) }}"
                                            method="POST" id="form-delete{{ $d->id }}">
                                            @csrf
                                            @method('PUT')
                                            <a class="link-primary link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover"
                                                href="#" data-id="{{ $d->id }}"
                                                onclick="check_out({{ $d->id }}); return false;">
                                                {{ $d->number }}
                                            </a>
                                        </form>
                                    </td>
                                    <td>{{ $d->room->number }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

                <a type="button" class="btn btn-outline-secondary" href="{{ route('checkout.index') }}">Back</a>
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

        function check_out(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Confirming will change the room status!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, check-out it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-delete' + id).submit();
                }
            });
        }
    </script>
@endpush
