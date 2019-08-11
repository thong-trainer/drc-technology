  <aside class="main-sidebar">
    @php
    $company = Auth::user()->companyInfo();
    @endphp
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ asset($company->image_url) }}" class="img-circle" alt="Logo">
        </div>
        <div class="pull-left info">
          <p>{{ $company->company_name }}</p>
          <!-- Status -->
          <a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> {{ __('title.dashboard') }}</a>
        </div>
      </div>

      <!-- search form (Optional) -->
<!--       <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
              <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
              </button>
            </span>
        </div>
      </form> -->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      
      <ul class="sidebar-menu" data-widget="tree">
        @if(Auth::user()->allowView(config('global.modules.sale')))
        <li class="header">SALE MODULE</li>
          @if(Auth::user()->allowView(config('global.modules.quotation')))
          <li class="{{ request()->segment(1) === 'quotation' ? 'active' : '' }}"><a href="{{ route('quotation.index') }}"><i class="fa fa-list-ol"></i> <span>{{ __('title.sale_orders') }}</span></a></li>
          @endif
          @if(Auth::user()->allowView(config('global.modules.invoice')))
          <li class="{{ request()->segment(1) === 'invoice' ? 'active' : '' }}"><a href="{{ route('invoice.index') }}"><i class="fa fa-file-text"></i> <span>{{ __('title.invoices') }}</span></a></li>          
          @endif
        @if(Auth::user()->allowView(config('global.modules.customer')))
        <li class="{{ request()->segment(1) === 'customer' ? 'active' : '' }}"><a href="{{ route('customer.index') }}"><i class="fa fa-address-book"></i> <span>{{ __('title.customers') }}</span></a></li>
        @endif          
        @endif

        <li class="header">STOCK MODULE</li>
        <!-- Optionally, you can add icons to the links -->
        @if(Auth::user()->allowView(config('global.modules.product')))
        <li class="{{ request()->segment(1) === 'product' ? 'active' : '' }}"><a href="{{ route('product.index') }}"><i class="fa  fa-cubes"></i> <span>{{ __('title.products') }}</span></a></li>
        @endif        
        @if(Auth::user()->allowView(config('global.modules.stock')))
        <li class="{{ request()->segment(1) === 'stock' ? 'active' : '' }}"><a href="{{ route('stock.index') }}"><i class="fa  fa-exchange"></i> <span>{{ __('title.stock_movements') }}</span></a></li>
        @endif
        @if(Auth::user()->allowView(config('global.modules.supplier')))
        <li class="{{ request()->segment(1) === 'supplier' ? 'active' : '' }}"><a href="{{ route('supplier.index') }}"><i class="fa fa-group"></i> <span>{{ __('title.suppliers') }}</span></a></li>
        @endif

        <li class="header">SETUP</li>
        <li class="treeview {{ request()->segment(1) === 'setup' ? 'active' : '' }}">
          <a href="#"><i class="fa fa-gear"></i> <span>Configuration</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a> 
          <ul class="treeview-menu">
            @if(Auth::user()->allowView(config('global.modules.product')))
            <li class="{{ request()->segment(2) === 'unit' ? 'active' : '' }}"><a href="{{ route('unit.index') }}"><i class="fa fa-arrow-right"></i> {{ __('title.units') }}</a></li>
            @endif            
            @if(Auth::user()->allowView(config('global.modules.category')))
            <li class="{{ request()->segment(2) === 'category' ? 'active' : '' }}"><a href="{{ route('category.index') }}"><i class="fa fa-arrow-right"></i> {{ __('title.categories') }}</a></li>
            @endif
            @if(Auth::user()->allowView(config('global.modules.dimension')))
            <li class="{{ request()->segment(2) === 'dimension' ? 'active' : '' }}"><a href="{{ route('dimension.index') }}"><i class="fa fa-arrow-right"></i> <span>{{__('title.dimensions')}}</span></a></li>        
            <li class="{{ request()->segment(2) === 'dimension-group' ? 'active' : '' }}"><a href="{{ route('dimension-group.index') }}"><i class="fa fa-arrow-right"></i> <span>{{__('title.dimension_groups')}}</span></a></li>                    
            @endif 
            @if(Auth::user()->allowView(config('global.modules.customer_group')))
            <li class="{{ request()->segment(2) === 'customer-group' ? 'active' : '' }}"><a href="{{ route('customer-group.index') }}"><i class="fa fa-arrow-right"></i> <span>{{__('title.customer_groups')}}</span></a></li>        
            @endif
          </ul>
        </li>
        <li class="treeview {{ request()->segment(1) === 'auth' ? 'active' : '' }}">
          <a href="#"><i class="fa fa-user"></i> <span>Authorization</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            @if(Auth::user()->allowView(config('global.modules.user')))
            <li class="{{ request()->segment(2) === 'user' ? 'active' : '' }}"><a href="{{ route('user.index') }}"><i class="fa fa-arrow-right"></i> {{ __('title.users') }}</a></li>
            @endif
            @if(Auth::user()->allowView(config('global.modules.role')))
            <li class="{{ request()->segment(2) === 'role' ? 'active' : '' }}"><a href="{{ route('role.index') }}"><i class="fa fa-arrow-right"></i> {{ __('title.roles') }}</a></li>
            @endif
            @if(Auth::user()->allowView(config('global.modules.setting')))
            <li class="{{ request()->segment(2) === 'setting' ? 'active' : '' }}"><a href="{{ route('setting.index') }}"><i class="fa fa-arrow-right"></i> {{ __('title.settings') }}</a></li>
            @endif                      
          </ul>
        </li>                
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>