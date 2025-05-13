<header>
    <nav class="navbar navbar-expand-md  fixed-top navbar-dark bg-dark" aria-label="Main navigation">
        <div class="container-fluid">
            <div class="navbar-collapse offcanvas-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                    @if (auth()->user()->hasAnyPermission(['guests', 'reservations', 'checkin', 'checkout']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown"
                                aria-expanded="false">Guests & Reservations</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown01">
                                @if (auth()->user()->hasPermissionTo('guests'))
                                    <li><a class="dropdown-item" href="{{ route('guests.index') }}">Guests</a></li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('reservations'))
                                    <li><a class="dropdown-item"
                                            href="{{ route('reservations.index') }}">Reservations</a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasAnyPermission(['guests', 'reservations']))
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('checkin'))
                                    <li><a class="dropdown-item" href="{{ route('checkin.index') }}">Check-In</a></li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('checkout'))
                                    <li><a class="dropdown-item" href="{{ route('checkout.index') }}">Check-Out</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->hasAnyPermission(['roomtypes', 'rooms', 'additionalitems']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown"
                                aria-expanded="false">Room Management</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown02">
                                @if (auth()->user()->hasPermissionTo('roomtypes'))
                                    <li><a class="dropdown-item" href="{{ route('room-types.index') }}">Room Types</a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('rooms'))
                                    <li><a class="dropdown-item" href="{{ route('rooms.index') }}">Rooms</a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('additionalitems'))
                                    <li><a class="dropdown-item" href="{{ route('additional-items.index') }}">Additional
                                            Items</a></li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->hasAnyPermission(['cleaning']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown03" data-bs-toggle="dropdown"
                                aria-expanded="false">House Keeping</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown03">
                                @if (auth()->user()->hasPermissionTo('cleaning'))
                                    <li><a class="dropdown-item" href="{{ route('cleaning.index') }}">Cleaning</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->hasAnyPermission(['purchase', 'goodreceipt', 'stock', 'stockopname', 'supplier']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-bs-toggle="dropdown"
                                aria-expanded="false">Inventory Management</a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown04">
                                @if (auth()->user()->hasPermissionTo('purchase'))
                                    <li><a class="dropdown-item" href="{{ route('purchase.index') }}">Purchase</a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('goodreceipt'))
                                    <li><a class="dropdown-item" href="{{ route('goodreceipt.index') }}">Good
                                            Receipt</a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasAnyPermission(['purchase', 'goodreceipt']))
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('supplier'))
                                    <li><a class="dropdown-item" href="{{ route('supplier.index') }}">Supplier</a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('stock'))
                                    <li><a class="dropdown-item" href="{{ route('stocks.index') }}">Stocks</a>
                                    </li>
                                @endif
                                @if (auth()->user()->hasAnyPermission(['supplier', 'stock']))
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                @endif
                                @if (auth()->user()->hasPermissionTo('stockout'))
                                    <li><a class="dropdown-item" href="{{ route('stockout.index') }}">Stock Out</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                </ul>
                <div class="d-flex">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown"
                                aria-expanded="false">{{ auth()->user()->name }}</a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown02">
                                <li><a class="dropdown-item" href="#">Change Password</a></li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
