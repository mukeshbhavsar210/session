<ul class="navbar-nav mb-auto w-100">    
    <li class="nav-item">
        <a href="{{ route('dashboard') }}" class="nav-link {{ (\Request::route()->getName() == 'admin.dashboard') ? 'active' : '' }}">
            <i class="iconoir-view-grid menu-icon"></i>
            <span>Dashboard</span>                        
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('categories.index') }}" class="nav-link {{ (\Request::route()->getName() == 'categories.index') || (\Request::route()->getName() == 'menu.edit') ? 'active' : '' }}">
            <i class="iconoir-view-grid menu-icon"></i>
            <span>Menu</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="{{ route('categories.index') }}" class="nav-link">
            <i class="iconoir-view-grid menu-icon"></i>
            <span>Category</span>
        </a>
    </li>         
    <li class="nav-item">
        <a href="{{ route('products.index') }}" class="nav-link {{ (\Request::route()->getName() == 'products.index') || (\Request::route()->getName() == 'products.edit') ? 'active' : '' }}">
            <i class="iconoir-compact-disc menu-icon"></i>
            <span>Products</span>
        </a>
    </li>     
    <li class="nav-item">
        <a href="{{ route('orders.index') }}" class="nav-link {{ (\Request::route()->getName() == 'orders.index') ? 'active' : '' }}">
            <i class="iconoir-journal-page menu-icon"></i>
            <span>Orders</span>
        </a>
    </li>    
    <li class="nav-item">
        <a href="{{ route('areas.index') }}" class="nav-link {{ (\Request::route()->getName() == 'areas.index') ? 'active' : '' }}">
            <i class="iconoir-view-grid menu-icon"></i>
            <span>Tables</span>
        </a>
    </li>    
    <li class="nav-item">
        <a href="{{ route('permissions.index') }}" class="nav-link {{ (\Request::route()->getName() == 'permissions.index') || (\Request::route()->getName() == 'permissions.edit') ? 'active' : '' }}">
            <i class="iconoir-view-grid menu-icon"></i>
            <span>Permissions</span>
        </a>
    </li>  
    {{-- @can('create menus')
        <li class="nav-item">
            <a href="{{ route('products.index') }}" class="nav-link {{ (\Request::route()->getName() == 'products.index') || (\Request::route()->getName() == 'products.edit') ? 'active' : '' }}">
                <p>Products</p>
            </a>
        </li>
    @endcan
    @can('create tables')
        <li class="nav-item">
            <a href="{{ route('areas.index') }}" class="nav-link {{ (\Request::route()->getName() == 'areas.index') ? 'active' : '' }}">
                <p>Tables</p>
            </a>
        </li>
    @endcan
    @can('view orders')
        <li class="nav-item">
            <a href="{{ route('orders.index') }}" class="nav-link {{ (\Request::route()->getName() == 'orders.index') ? 'active' : '' }}">
                <p>Orders</p>
            </a>
        </li>
    @endcan
    @can('create permissions')
        <li class="nav-item">
            <a href="{{ route('permissions.index') }}" class="nav-link {{ (\Request::route()->getName() == 'permissions.index') || (\Request::route()->getName() == 'permissions.edit') ? 'active' : '' }}">
                <p>Permissions</p>
            </a>
        </li>  
    @endcan --}}
    <li class="nav-item">
        <a class="nav-link" href="#extra" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarApplications">
            <i class="iconoir-page-star menu-icon"></i>
            <span>Settings</span>
        </a>
        <div class="collapse " id="extra">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('roles.index') }}" class="nav-link {{ (\Request::route()->getName() == 'roles.index') || (\Request::route()->getName() == 'roles.edit') ? 'active' : '' }}">
                        <p>Roles</p>
                    </a>
                </li> 
                <li class="nav-item">
                    <a href="{{ route('configurations.index') }}" class="nav-link {{ (\Request::route()->getName() == 'configurations.index') ? 'active' : '' }}">
                        <p>Configuration</p>
                    </a>
                </li>   
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link {{ (\Request::route()->getName() == 'users.index') ? 'active' : '' }}">
                        <span>Users</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('pages.index') }}" class="nav-link {{ (\Request::route()->getName() == 'pages.index') ? 'active' : '' }}">
                        <span>Pages</span>
                    </a>
                </li>                               
            </ul>
        </div>
    </li>
</ul>