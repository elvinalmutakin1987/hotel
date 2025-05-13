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
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('reservations.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="row">
                        <div class="col">
                            <fieldset class="border border rounded-3 p-3 mb-3">
                                <legend class="float-none w-auto px-3 fs-5">Guest</legend>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name">
                                    @error('name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="id_card_number" class="form-label">ID
                                        Number</label>
                                    <input type="text" class="form-control @error('id_card_number') is-invalid @enderror"
                                        id="id_card_number" name="id_card_number">
                                    @error('id_card_number')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" name="address">
                                </div>
                                <div class="mb-3">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" name="city">
                                </div>
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" required>
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </fieldset>
                        </div>
                        <div class="col">
                            <fieldset class="border border rounded-3 p-3 mb-3">
                                <legend class="float-none w-auto px-3 fs-5">Room</legend>
                                <div class="mb-3">
                                    <label for="check_in_date" class="form-label">Check-in Date</label>
                                    <input type="date" class="form-control" id="check_in_date" name="check_in_date"
                                        value="{{ date('Y-m-d') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="check_out_date" class="form-label">Check-out Date</label>
                                    <input type="date" class="form-control" id="check_out_date" name="check_out_date"
                                        value="{{ \Carbon\Carbon::parse(now())->addDays(1)->format('Y-m-d') }}">
                                </div>
                                <div class="mb-3">
                                    <div id="div-room-list">
                                    </div>
                                </div>
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
                                                <th class="text-center" style="width: 30px">#</th>
                                                <th>Items</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Sub Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-group-divider">
                                            <tr>
                                                <td>
                                                    <input class="form-check-input" type="checkbox" id="room_check"
                                                        name="room_check" checked disabled>
                                                </td>
                                                <td id="room-select">Room </td>
                                                <td class="text-end" id="room-price">
                                                    0
                                                </td>
                                                <td class="text-end qty">1</td>
                                                <td class="sub-total text-end">
                                                    0
                                                </td>
                                            </tr>
                                            @foreach ($additional_item as $ai)
                                                <tr>
                                                    <td>
                                                        <input class="form-check-input check-add-item" type="checkbox"
                                                            id="additionalitem_id{{ $ai->id }}"
                                                            name="additionalitem_id[]" value="{{ $ai->id }}">
                                                    </td>
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
                                                <th colspan="4" class="text-end">Amount</th>
                                                <th class="text-end" id="total-additional-time">0</th>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </fieldset>
                        </div>
                        <div class="col">
                            <fieldset class="border border rounded-3 p-3 mb-3">
                                <legend class="float-none w-auto px-3 fs-5">Payment</legend>
                                <input type="hidden" class="form-control @error('amount') is-invalid @enderror"
                                    id="amount" name="amount" readonly>
                                <div class="mb-3">
                                    <label class="form-label">Payment Method</label> <br>

                                    <input type="radio" class="btn-check" id="payment_method1" name="payment_method"
                                        autocomplete="off" value="Cash">
                                    <label class="btn btn-outline-success" for="payment_method1">Cash</label>

                                    <input type="radio" class="btn-check" id="payment_method2" name="payment_method"
                                        autocomplete="off" value="Bank Transfer">
                                    <label class="btn btn-outline-success" for="payment_method2">Bank
                                        Transfer</label>

                                    <input type="radio" class="btn-check" id="payment_method3" name="payment_method"
                                        autocomplete="off" value="Debit/Credit Card">
                                    <label class="btn btn-outline-success" for="payment_method3">Debit/Credit
                                        Card</label>

                                    <input type="radio" class="btn-check" id="payment_method4" name="payment_method"
                                        autocomplete="off" value="E-Wallet">
                                    <label class="btn btn-outline-success" for="payment_method4">E-Wallet</label>
                                </div>
                                <div class="mb-3">
                                    <label for="bank_name" class="form-label">Bank Name</label>
                                    <input type="text" class="form-control" id="bank_name" name="bank_name">
                                </div>
                                <div class="mb-3">
                                    <label for="account_number" class="form-label">Account/Card Number</label>
                                    <input type="text" class="form-control" id="account_number"
                                        name="account_number">
                                </div>
                                <div class="mb-3">
                                    <label for="transaction_id" class="form-label">Transaction Id</label>
                                    <input type="text" class="form-control" id="transaction_id"
                                        name="transaction_id">
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Submit
                        </button>
                        <ul class="dropdown-menu">
                            <li><button class="dropdown-item" name="status" value="Pending">Pending</button></li>
                            <li><button class="dropdown-item" name="status" value="Confirmed">Confirm</button></li>
                        </ul>
                    </div>
                    <a type="button" class="btn btn-outline-secondary"
                        href="{{ route('reservations.index', ['view' => 'list']) }}">Back</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('javascript')
    <script>
        $("#down_payment").on('keyup', function() {
            $(this).val(numeral(this.value).format("0,0"))
        })

        function room_click(room_id) {
            $.get("{{ route('reservations.get_room_by_id') }}", {
                room_id: room_id,
            }, function(data) {
                if (data.status == 'success') {
                    let roomPriceElement = document.getElementById("room-price");
                    $("#room-price").html(numeral(data.price).format("0,0"));
                    let closestTr = roomPriceElement.closest("tr");
                    let subTotalElement = closestTr.querySelector(".sub-total");
                    subTotalElement.textContent = numeral(data.price).format("0,0");

                    updateGrandTotal();
                }
            });
        }

        const checkDate = (date) => {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const inputDate = new Date(date);
            inputDate.setHours(0, 0, 0, 0);
            return inputDate >= today;
        };

        const checkDataInOut = (check_in_date, check_out_date) => {
            const date_in = new Date(check_in_date);
            date_in.setHours(0, 0, 0, 0);
            const date_out = new Date(check_out_date);
            date_out.setHours(0, 0, 0, 0);
            return date_in < date_out;
        };

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".check-add-item").forEach(function(checkbox) {
                checkbox.addEventListener("change", function() {
                    let row = this.closest("tr");
                    let price = parseFloat(row.querySelector("td:nth-child(3)").textContent.replace(
                        /,/g, '')) || 0;
                    let qty = parseInt(row.querySelector(".qty").textContent) || 1;
                    let subtotalCell = row.querySelector(".sub-total");
                    let qtyHidden = row.querySelector(".qty-hidden");

                    if (this.checked) {
                        $(subtotalCell).html(numeral(price * qty).format("0,0"));
                    } else {
                        $(subtotalCell).html("0");
                    }

                    qtyHidden.value = qty;

                    updateGrandTotal();
                });
            });

            document.querySelectorAll(".qty").forEach(function(qtyCell) {
                function updateSubtotal() {
                    let row = qtyCell.closest("tr");
                    let price = parseFloat(row.querySelector("td:nth-child(3)").textContent.replace(/,/g,
                        '')) || 0;
                    let qty = parseInt(qtyCell.textContent) || 1;
                    let subtotalCell = row.querySelector(".sub-total");
                    let qtyHidden = row.querySelector(".qty-hidden");

                    if (row.querySelector(".check-add-item").checked) {
                        $(subtotalCell).html(numeral(price * qty).format("0,0"));
                    }

                    $(qtyCell).html(numeral(qty).format("0,0"));
                    qtyHidden.value = qty;
                    updateGrandTotal();
                }

                qtyCell.addEventListener("input", updateSubtotal);
                qtyCell.addEventListener("keyup", updateSubtotal);
            });
        });

        function updateGrandTotal() {
            let total = 0;
            document.querySelectorAll(".sub-total").forEach(function(subtotalCell) {
                total += parseFloat(subtotalCell.textContent.replace(/,/g, '')) || 0;
            });
            $("#total-additional-time").html(numeral(total).format("0,0"));
            $("#amount").val(total);
        }
    </script>
@endpush
