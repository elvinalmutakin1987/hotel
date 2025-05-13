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
                        href="{{ route('reservations.index') }}">
                        Reservations
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Show</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <fieldset class="border border rounded-3 p-3 mb-3">
                            <legend class="float-none w-auto px-3 fs-5">Guest</legend>
                            <table id="table1" class="table mt-3">
                                <tbody>
                                    <tr>
                                        <td style="width: 40%">Name</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->guest->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>ID Number</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->guest->id_card_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->city }}</td>
                                    </tr>
                                    <tr>
                                        <td>City</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->city }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->phone }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset class="border border rounded-3 p-3 mb-3">
                            <legend class="float-none w-auto px-3 fs-5">Room</legend>
                            <table id="table1" class="table mt-3">
                                <tbody>
                                    <tr>
                                        <td style="width: 40%">Check-in Date</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Check-out Date</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('d F Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Room</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->room->number }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <fieldset class="border border rounded-3 p-3 mb-3">
                            <legend class="float-none w-auto px-3 fs-5">Billing</legend>
                            <div class="mb-3">
                                <table id="table1" class="table mt-3">
                                    <thead class="table-group-divider">
                                        <tr>
                                            <th>Items</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Sub Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="table-group-divider">
                                        <tr>
                                            <td id="room-select">Room </td>
                                            <td class="text-end" id="room-price">
                                                {{ Illuminate\Support\Number::format($reservation->price) }}
                                            </td>
                                            <td class="text-end qty">1</td>
                                            <td class="sub-total text-end">
                                                {{ Illuminate\Support\Number::format($reservation->price * 1) }}
                                            </td>
                                        </tr>
                                        @foreach ($additional_item as $ai)
                                            <tr>
                                                <td>{{ $ai->name }}
                                                    <input type="hidden" class="qty-hidden" name="qty[]"
                                                        id="qty{{ $ai->id }}">
                                                </td>
                                                <td class="text-end">
                                                    {{ Illuminate\Support\Number::format($ai->price) }}
                                                </td>
                                                <td class="text-end qty" contenteditable='true'>1</td>
                                                <td class="text-end sub-total">0</td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tbody class="table-group-divider">
                                        <tr>
                                            <th colspan="3" class="text-end">Amount</th>
                                            <th class="text-end" id="total-additional-time">
                                                {{ Illuminate\Support\Number::format($reservation->amount) }}
                                            </th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col">
                        <fieldset class="border border rounded-3 p-3 mb-3">
                            <legend class="float-none w-auto px-3 fs-5">Payment</legend>
                            <table id="table1" class="table mt-3">
                                <tbody>
                                    <tr>
                                        <td style="width: 40%">Payment Method</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->payment_method }}</td>
                                    </tr>
                                    <tr>
                                        <td>Bank Name</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->bank_name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Account/Card Number</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->account_number }}</td>
                                    </tr>
                                    <tr>
                                        <td>Transaction Id</td>
                                        <td style="width: 10px">:</td>
                                        <td>{{ $reservation->transaction_id }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </fieldset>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <legend class="float-none w-auto">Status : {{ $reservation->status }}
                        </legend>
                    </div>
                </div>
                <a type="button" class="btn btn-outline-secondary"
                    href="{{ route('reservations.index', ['view' => 'list']) }}">Back</a>
            </div>
        </div>
    </div>
@endsection
