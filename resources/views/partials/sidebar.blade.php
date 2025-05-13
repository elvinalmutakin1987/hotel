<div class="sidebar">
    <a href="{{ route('dashboard') }}" {!! Request::segment(1) == '' ? 'class="active"' : '' !!} class="fw-bold">Dashboard</a>
    <hr>
    @if (auth()->user()->hasAnyPermission(['guests', 'reservations', 'checkin', 'checkout']))
        <a href="#" class="fw-bold">Guests &
            Reservations</a>
        @if (auth()->user()->hasPermissionTo('guests'))
            <a href="{{ route('guests.index') }}" {!! Request::segment(1) == 'guests' ? 'class="active"' : '' !!}>Guests</a>
        @endif
        @if (auth()->user()->hasPermissionTo('reservations'))
            <a href="{{ route('reservations.index') }}" {!! Request::segment(1) == 'reservations' ? 'class="active"' : '' !!}>Reservations</a>
        @endif
        @if (auth()->user()->hasPermissionTo('checkin'))
            <a href="{{ route('checkin.index') }}" {!! Request::segment(1) == 'check-in' ? 'class="active"' : '' !!}>Check In</a>
        @endif
        @if (auth()->user()->hasPermissionTo('checkout'))
            <a href="{{ route('checkout.index') }}" {!! Request::segment(1) == 'check-out' ? 'class="active"' : '' !!}>Check Out</a>
        @endif
    @endif

    @if (auth()->user()->hasAnyPermission(['roomtypes', 'rooms', 'additionalitems']))
        <hr>
        <a href="#" class="fw-bold">Room Management</a>
        @if (auth()->user()->hasPermissionTo('roomtypes'))
            <a href="{{ route('room-types.index') }}" {!! Request::segment(1) == 'room-types' ? 'class="active"' : '' !!}>Room Types</a>
        @endif
        @if (auth()->user()->hasPermissionTo('rooms'))
            <a href="{{ route('rooms.index') }}" {!! Request::segment(1) == 'rooms' ? 'class="active"' : '' !!}>Rooms</a>
        @endif
        @if (auth()->user()->hasPermissionTo('additionalitems'))
            <a href="{{ route('additional-items.index') }}" {!! Request::segment(1) == 'additional-items' ? 'class="active"' : '' !!}>Additional Items</a>
        @endif
    @endif
    @if (auth()->user()->hasAnyPermission(['cleaning']))
        <hr>
        <a href="#" class="fw-bold">Housekeeping</a>
        @if (auth()->user()->hasPermissionTo('cleaning'))
            <a href="{{ route('cleaning.index') }}" {!! Request::segment(1) == 'cleaning' ? 'class="active"' : '' !!}>Cleaning</a>
        @endif
        {{-- <a href="#">Laundry</a> --}}
    @endif
    {{-- <hr>
    <a href="#" class="fw-bold">Space Rentals</a>
    <a href="#">Contracts</a>
    <a href="#">Billings</a>
    <a href="#">Space For Rent</a>
    <a href="#">Cost List</a> --}}
    @if (auth()->user()->hasAnyPermission(['purchase', 'goodreceipt', 'inventory', 'stockopname']))
        <hr>
        <a href="#" class="fw-bold">Inventory Management</a>
        @if (auth()->user()->hasPermissionTo('purchase'))
            <a href="{{ route('purchase.index') }}" {!! Request::segment(1) == 'purchase' ? 'class="active"' : '' !!}>Purchase</a>
        @endif
        <a href="#">Good Receipt</a>
        <a href="#">Inventory</a>
        <a href="#">Stock Opname</a>
        @if (auth()->user()->hasPermissionTo('supplier'))
            <a href="{{ route('supplier.index') }}" {!! Request::segment(1) == 'supplier' ? 'class="active"' : '' !!}>Supplier</a>
        @endif
    @endif
    {{-- <hr>
    <a href="#" class="fw-bold">Finance & Accounting</a>
    <a href="#">Transactions</a>
    <a href="#">Invoices</a>
    <a href="#">Expenses</a>
    <a href="#">Chart Of Accounts</a>
    <a href="#">Finansial Reports</a>
    <a href="#">Journal Entries</a>
    <a href="#">Balance Sheet</a>
    <a href="#">Cash Flow</a>
    <a href="#">Profit Lost</a> --}}
    @if (auth()->user()->hasAnyPermission(['staff', 'user', 'role']))
        <hr>
        <a href="#" class="fw-bold">Users & Staff</a>
        @if (auth()->user()->hasPermissionTo('staff'))
            <a href="{{ route('staff.index') }}" {!! Request::segment(1) == 'staff' ? 'class="active"' : '' !!}>Staff</a>
        @endif
        @if (auth()->user()->hasPermissionTo('user'))
            <a href="{{ route('user.index') }}" {!! Request::segment(1) == 'user' ? 'class="active"' : '' !!}>User</a>
        @endif
        @if (auth()->user()->hasPermissionTo('role'))
            <a href="{{ route('role.index') }}" {!! Request::segment(1) == 'role' ? 'class="active"' : '' !!}>Role</a>
        @endif
    @endif
    <hr>
    <a href="#" class="fw-bold">About</a>
</div>
