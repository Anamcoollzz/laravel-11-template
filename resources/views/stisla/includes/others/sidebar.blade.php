<div class="main-sidebar">
  <aside id="sidebar-wrapper">

    <div class="sidebar-brand">
      @if (config('stisla.logo_aplikasi') != '')
        <a href="{{ url('') }}">
          <img style="max-width: 60%;" src="{{ asset(config('stisla.logo_aplikasi')) }}"></a>
      @else
        <a href="{{ url('') }}">{{ $_app_name }}</a>
      @endif
    </div>

    <div class="sidebar-brand sidebar-brand-sm">
      <a href="{{ url('') }}">{{ $_app_name_mobile }}</a>
    </div>

    <ul class="sidebar-menu">
      @foreach ($_sidebar_menus as $_group)
        <li class="menu-header">{{ __($_group->group_name) }}</li>

        @php
          $_user = auth()->user();
        @endphp
        @foreach ($_group->menus as $_menu)
          {{-- if single menu --}}
          @if ($_menu->childs->count() === 0)
            @php
              $_menu_condition = $_menu->permission === null || $_user->can($_menu->permission);
            @endphp
            @if ($_menu_condition)
              <li @if (Request::is($_menu->is_active_if_url_includes)) class="active" @endif>
                <a class="nav-link" href="{{ $_menu->fix_url }}" @if ($_menu->is_blank) target="_blank" @endif>
                  <i class="{{ $_menu->icon }}"></i>
                  <span>{{ __($_menu->menu_name) }}</span>
                </a>
              </li>
            @endif

            {{-- else with child dropdown menu --}}
          @else
            @php
              $_is_active = $_menu->childs
                  ->pluck('is_active_if_url_includes')
                  ->filter(function ($item) {
                      return Request::is($item);
                  })
                  ->count();
              $_menu_condition = $_menu->childs
                  ->pluck('permission')
                  ->filter(function ($item) use ($_user) {
                      return $_user->can($item);
                  })
                  ->count();
            @endphp
            @if ($_menu_condition)
              <li class="nav-item dropdown @if ($_is_active) active @endif">
                <a href="#" class="nav-link has-dropdown">
                  <i class="{{ $_menu->icon }}"></i>
                  <span>{{ __($_menu->menu_name) }}</span>
                </a>
                <ul class="dropdown-menu">
                  @foreach ($_menu->childs as $_child_menu)
                    @php
                      $_sub_menu_condition = $_child_menu->permission === null || $_user->can($_child_menu->permission);
                    @endphp
                    @if ($_sub_menu_condition)
                      <li @if (Request::is($_child_menu->is_active_if_url_includes)) class="active" @endif>
                        <a class="nav-link" href="{{ $_child_menu->fix_url }}" @if ($_child_menu->is_blank) target="_blank" @endif>
                          {{ __($_child_menu->menu_name) }}
                        </a>
                      </li>
                    @endif
                  @endforeach
                </ul>
              </li>
            @endif
          @endif
        @endforeach
      @endforeach
    </ul>

    <div class="mt-1 mb-4 p-3 hide-sidebar-mini">
      <a href="{{ route('profile.index') }}" class="btn btn-primary btn-lg btn-block btn-icon-split">
        <i class="fas fa-user"></i> {{ Auth::user()->roles->first()->name }}
      </a>
    </div>
  </aside>
</div>
