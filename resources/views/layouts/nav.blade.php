<div class="ui fixed inverted menu">
    <div class="ui container">
        <a href="/home" class="header item">
            {{ config('app.name', 'Laravel') }}
        </a>

        <div class="right menu">
            @if (Auth::guest())
                <a href="{{ route('login') }}" class="ui item">Login</a>
                <a href="{{ route('register') }}" class="ui item">Register</a>
            @else
                <div class="ui dropdown item">
                    {{ Auth::user()->name }} <i class="dropdown icon"></i>

                    <div class="menu">
                        <a class="item" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                    </div>
                </div>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('.ui.dropdown').dropdown();
    </script>
@endpush
