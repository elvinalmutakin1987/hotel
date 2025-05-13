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
                        href="{{ route('role.index') }}">
                        Role
                    </a></li>
                <li class="breadcrumb-item active" aria-current="page">Add</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('role.store') }}" method="post">
                    @csrf
                    @method('POST')
                    <div class="mb-3">
                        <label for="name" class="form-label">Role Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Guests & Reservasions</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="guests" name="guests" value="guests">
                            <label class="form-check-label" for="guests">
                                Guests
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="reservations" name="reservations"
                                value="reservations">
                            <label class="form-check-label" for="reservations">
                                Reservations
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkin" name="checkin" value="checkin">
                            <label class="form-check-label" for="checkin">
                                Check In
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="checkout" name="checkout" value="checkout">
                            <label class="form-check-label" for="checkout">
                                Check Out
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Housekeeping</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cleaning" name="cleaning" value="cleaning">
                            <label class="form-check-label" for="cleaning">
                                Cleaning
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="laundry" name="laundry" value="laundry">
                            <label class="form-check-label" for="laundry">
                                Laundry
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Room Management</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="roomtypes" name="roomtypes"
                                value="roomtypes">
                            <label class="form-check-label" for="roomtypes">
                                Room Types
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="rooms" name="rooms" value="rooms">
                            <label class="form-check-label" for="rooms">
                                Rooms
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="additionalitems" name="additionalitems"
                                value="additionalitems">
                            <label class="form-check-label" for="additionalitems">
                                Additional Items
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Space Rentals</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="contracts" name="contracts"
                                value="contracts">
                            <label class="form-check-label" for="contracts">
                                Contracts
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="billings" name="billings"
                                value="billings">
                            <label class="form-check-label" for="billings">
                                Billings
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="spaceforrent" name="spaceforrent"
                                value="spaceforrent">
                            <label class="form-check-label" for="spaceforrent">
                                Space For Rent
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="costlist" name="costlist"
                                value="costlist">
                            <label class="form-check-label" for="costlist">
                                Cost List
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Inventory Management</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="purchase" name="purchase"
                                value="purchase">
                            <label class="form-check-label" for="purchase">
                                Purchase
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="goodreceipt" name="goodreceipt"
                                value="goodreceipt">
                            <label class="form-check-label" for="goodreceipt">
                                Good Receipt
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="dispatching" name="dispatching"
                                value="dispatching">
                            <label class="form-check-label" for="dispatching">
                                Dispatching
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="inventory" name="inventory"
                                value="inventory">
                            <label class="form-check-label" for="inventory">
                                Invetory
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="stockopname" name="stockopname"
                                value="stockopname">
                            <label class="form-check-label" for="stockopname">
                                Stock Opname
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="supplier" name="supplier"
                                value="supplier">
                            <label class="form-check-label" for="supplier">
                                Supplier
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">Finance & Accounting</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="transactions" name="transactions"
                                value="transactions">
                            <label class="form-check-label" for="transactions">
                                Transactions
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="invoices" name="invoices"
                                value="invoices">
                            <label class="form-check-label" for="invoices">
                                Invoices
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="expenses" name="expenses"
                                value="expenses">
                            <label class="form-check-label" for="expenses">
                                Expenses
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="chartofaccounts" name="chartofaccounts"
                                value="chartofaccounts">
                            <label class="form-check-label" for="chartofaccounts">
                                Chart Of Accounts
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="finansialreports"
                                name="finansialreports" value="finansialreports">
                            <label class="form-check-label" for="finansialreports">
                                Finansial Reports
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="journalentries" name="journalentries"
                                value="journalentries">
                            <label class="form-check-label" for="journalentries">
                                Journal Entries
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="balancesheet" name="balancesheet"
                                value="balancesheet">
                            <label class="form-check-label" for="balancesheet">
                                Balance Sheet
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="cashflow" name="cashflow"
                                value="cashflow">
                            <label class="form-check-label" for="cashflow">
                                Cash Flow
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="profitlost" name="profitlost"
                                value="profitlost">
                            <label class="form-check-label" for="profitlost">
                                Profit Lost
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="name" class="form-label">User & Staff</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="staff" name="staff"
                                value="staff">
                            <label class="form-check-label" for="staff">
                                Staff
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="user" name="user"
                                value="user">
                            <label class="form-check-label" for="user">
                                User
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="role" name="role"
                                value="role">
                            <label class="form-check-label" for="role">
                                Role
                            </label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-outline-primary">Submit</button>
                    <a type="button" class="btn btn-outline-secondary" href="{{ route('role.index') }}">Back</a>
                </form>
            </div>
        </div>


    </div>
@endsection
