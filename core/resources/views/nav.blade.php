<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            @if(Auth::check())
            <div class="pull-left image">
                <img src="{{ asset( Auth::user()->photo != '' ? 'assets/img/uploads/'.Auth::user()->photo : 'assets/img/uploads/no-image.jpg') }}" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p> {{  Auth::user()->name }} </p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
            @endif
        </div>
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header">MAIN MENU</li>
            <li class="{{ HTML::menu_active('/') }}"><a href="{{ url('/') }}"><i class="fa fa-home"></i> Dashboard</a></li>
            <li class="{{ HTML::menu_active('clients') }}"><a href="{{ url('clients') }}"><i class="fa fa-users"></i> Clients</a></li>
            <li class="{{ HTML::menu_active('invoices') }}"><a href="{{ url('invoices') }}"><i class="fa fa-file-pdf-o"></i> Invoices</a></li>
            <li class="{{ HTML::menu_active('estimates') }}"><a href="{{ url('estimates') }}"><i class="fa fa-quote-left"></i> Estimates </a></li>
            <li class="{{ HTML::menu_active('payments') }}"><a href="{{ url('payments') }}"><i class="fa fa-money"></i> Payments</a></li>
            <li class="{{ HTML::menu_active('expenses') }}"><a href="{{ url('expenses') }}"><i class="fa fa-credit-card "></i> Expenses</a></li>
            <li class="{{ HTML::menu_active('products') }}"><a href="{{ url('products') }}"><i class="fa fa-puzzle-piece"></i> Products</a></li>
            <li class="{{ HTML::menu_active('reports') }}"><a href="{{ url('reports') }}"><i class="fa fa-line-chart"></i> Reports</a></li>
            <li class="{{ HTML::menu_active('users') }}"><a href="{{ route('users.index') }}"><i class="fa fa-user "></i> Users</a></li>
            <li class="{{ HTML::menu_active('settings') }}"><a href="{{ url('settings/company') }}"><i class="fa fa-cogs fa-1x "></i> Settings</a></li>
            <li class="header">ACCOUNT MENU</li>
            <li class="{{ HTML::menu_active('profile') }}"><a href="{{ url('profile') }}"><i class="fa fa-file fa-1x "></i> Profile</a></li>
            <li class="{{ HTML::menu_active('logout') }}"><a href="{{ url('auth/logout') }}"><i class="fa fa-sign-out fa-1x "></i> Logout</a></li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>