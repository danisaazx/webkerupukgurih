<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="/seo.png">
    <style>
        @media (max-width: 991.98px) {
            .sidebar-desktop {
                display: none !important;
            }
        }
        @media (min-width: 992px) {
            .offcanvas-lg {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm d-lg-none">
        <div class="container-fluid">
            <!-- Sidebar toggle button for mobile -->
            <button class="btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas">
                <i class="bi bi-list fs-3"></i>
            </button>
            <a class="navbar-brand fw-bold" href="#">Kerupuk Gurih</a>
            <div class="ms-auto">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="navbarUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()?->name }}" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong>{{ Auth::user()?->name }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="navbarUserDropdown">
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                    </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-flex" style="min-height: 100vh;">
        <!-- Sidebar Desktop -->
        <div class="sidebar-desktop flex-shrink-0 p-3 bg-light shadow-sm" style="width: 240px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
                <span class="fs-4 fw-bold text-primary"><i class="bi bi-box-seam"></i> Kerupuk Gurih </span>
            </a>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : 'text-dark'}}" aria-current="page">
                        <i class="bi bi-speedometer2 me-2"></i>
                        Dashboard
                    </a>
                </li>
                <!-- Master Data Menu with Collapse -->
                <li>
                    <a class="nav-link d-flex justify-content-between align-items-center {{ request()->is('products*') || request()->is('bahan_baku*') || request()->is('produksi*') ? 'active' : 'text-dark' }}" 
                       data-bs-toggle="collapse" href="#masterDataMenu" role="button" aria-expanded="{{ request()->is('products*') || request()->is('bahan_baku*') || request()->is('produksi*') ? 'true' : 'false' }}" aria-controls="masterDataMenu">
                        <span>
                            <i class="bi bi-collection me-2"></i>
                            Master Data
                        </span>
                        <i class="bi bi-chevron-down small"></i>
                    </a>
                    <div class="collapse {{ request()->is('products*') || request()->is('bahan_baku*') || request()->is('produksi*') ? 'show' : '' }}" id="masterDataMenu">
                        <ul class="nav flex-column ms-3">
                            <li>
                                    <a href="{{ route('bahan_baku.index')}}" class="nav-link {{ request()->is('bahan_baku*') ? 'active' : 'text-dark'}}">
                                        <i class="bi bi-archive me-2"></i>
                                        Ingredients
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : 'text-dark' }}">
                                        <i class="bi bi-boxes me-2"></i>
                                        Products
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('produksi.index')}}" class="nav-link {{ request()->is('produksi*') ? 'active' : 'text-dark'}}">
                                        <i class="bi bi-truck me-2"></i>
                                        Production
                                    </a>
                                </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="{{ route('transactions.index')}}" class="nav-link {{ request()->is('transactions*') ? 'active' : 'text-dark'}}">
                        <i class="bi bi-cash-coin me-2"></i>
                        Transaction
                    </a>
                </li>
                <li>
                        <a href="{{ route('laporan.bulanan')}}" class="nav-link {{ request()->is('laporan*') ? 'active' : 'text-dark'}}">
                            <i class="bi bi-graph-up me-2"></i>
                            Reports
                        </a>
                </li>
            </ul>
            <hr>
            <div class="dropdown mt-auto">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="sidebarUserDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="https://ui-avatars.com/api/?name={{ Auth::user()?->name }}" alt="" width="32" height="32" class="rounded-circle me-2">
                    <strong>{{ Auth::user()?->name }}</strong>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="sidebarUserDropdown">
                    <li><a class="dropdown-item" href="/profile">Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <!-- Sidebar Offcanvas Mobile -->
        <div class="offcanvas offcanvas-start offcanvas-lg" tabindex="-1" id="sidebarOffcanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title"><i class="bi bi-box-seam me-2"></i>Kerupuk Gurih </h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body p-0">
                <ul class="nav nav-pills flex-column mb-auto px-3">
                    <li class="nav-item">
                        <a href="/dashboard" class="nav-link {{ request()->is('dashboard') ? 'active' : 'text-dark'}}" aria-current="page">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Dashboard
                        </a>
                    </li>
                    <!-- Master Data Menu with Collapse (Mobile: show all) -->
                    <li class="mt-2 mb-1 text-muted small">Master Data</li>
                    <li>
                        <a href="{{ route('bahan_baku.index')}}" class="nav-link {{ request()->is('bahan_baku*') ? 'active' : 'text-dark'}}">
                            <i class="bi bi-archive me-2"></i>
                            Ingredients
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('products.index') }}" class="nav-link {{ request()->is('products*') ? 'active' : 'text-dark' }}">
                            <i class="bi bi-boxes me-2"></i>
                            Products
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('produksi.index')}}" class="nav-link {{ request()->is('produksi*') ? 'active' : 'text-dark'}}">
                            <i class="bi bi-truck me-2"></i>
                            Production
                        </a>
                    </li>
                    <li class="mt-2 mb-1 text-muted small">Lainnya</li>
                    <li>
                        <a href="{{ route('transactions.index')}}" class="nav-link {{ request()->is('transactions*') ? 'active' : 'text-dark'}}">
                            <i class="bi bi-cash-coin me-2"></i>
                            Transaction
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('laporan.bulanan')}}" class="nav-link {{ request()->is('laporan*') ? 'active' : 'text-dark'}}">
                            <i class="bi bi-graph-up me-2"></i>
                            Reports
                        </a>
                    </li>
                </ul>
                <hr>
                <div class="dropdown px-3 pb-3">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="sidebarUserDropdownMobile" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()?->name }}" alt="" width="32" height="32" class="rounded-circle me-2">
                        <strong>{{ Auth::user()?->name }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="sidebarUserDropdownMobile">
                        <li><a class="dropdown-item" href="/profile">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Logout
                        </button>
                    </form>
                </li>
                    </ul>
                </div>
            </div>
        </div>
        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS (for dropdown, collapse, offcanvas) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
