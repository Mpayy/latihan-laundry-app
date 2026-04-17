  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

    <ul class="sidebar-nav" id="sidebar-nav">

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{route('dashboard')}}">
          <i class="bi bi-grid"></i>
          <span>Dashboard</span>
        </a>
      </li><!-- End Dashboard Nav -->
      @if (Auth::user()->level->level_name == 'admin')
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('users.index') }}">
          <i class="bi bi-person"></i>
          <span>Users</span>
        </a>
      </li><!-- End Users Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('customers.index') }}">
          <i class="bi bi-people"></i>
          <span>Customers</span>
        </a>
      </li><!-- End Customers Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('services.index') }}">
          <i class="bi bi-tags"></i>
          <span>Services</span>
        </a>
      </li><!-- End Services Page Nav -->

      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('vouchers.index') }}">
          <i class="bi bi-ticket-perforated"></i>
          <span>Vouchers</span>
        </a>
      </li><!-- End Vouchers Page Nav -->
      @endif
      @if (Auth::user()->level->level_name == 'operator')
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('orders.index') }}">
          <i class="bi bi-bag-check"></i>
          <span>Orders</span>
        </a>
      </li><!-- End Orders Page Nav -->
      @endif
      @if (Auth::user()->level->level_name == 'pimpinan')
      <li class="nav-item">
        <a class="nav-link collapsed" href="{{ route('reports.index') }}">
          <i class="bi bi-envelope"></i>
          <span>Laporan</span>
        </a>
      </li><!-- End Laporan Page Nav -->
      @endif
    </ul>

  </aside><!-- End Sidebar-->