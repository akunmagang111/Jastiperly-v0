<!-- meta tags and other links -->
<!DOCTYPE html>
<html lang="en" data-theme="light">

<x-head />

<body>

    <!-- ..:: sidebar start ::.. -->
    @auth
        @if(Auth::user()->role === 'finance')
            @include('components.sidebar_finance')
        @elseif(Auth::user()->role === 'admin')
            @include('components.sidebar_admin')
        @elseif(Auth::user()->role === 'superadmin')
            @include('components.sidebar_superadmin')
        @endif
    @endauth
    <!-- ..:: sidebar end ::.. -->

    <main class="dashboard-main">

        <!-- ..:: navbar start ::.. -->
        @auth
            @if(Auth::user()->role === 'finance')
                @include('components.navbar_finance')
            @elseif(Auth::user()->role === 'admin')
                @include('components.navbar_admin')
            @elseif(Auth::user()->role === 'superadmin')
                @include('components.navbar_superadmin')
            @endif
        @endauth
        <!-- ..:: navbar end ::.. -->
        <div class="dashboard-main-body">
            
            <!-- ..::  breadcrumb  start ::.. -->
            <x-breadcrumb title='{{ isset($title) ? $title : "" }}' subTitle='{{ isset($subTitle) ? $subTitle : "" }}' />
            <!-- ..::  header area end ::.. -->

            @yield('content')
        
        </div>
        <!-- ..::  footer  start ::.. -->
        <x-footer />
        <!-- ..::  footer area end ::.. -->

    </main>

    <!-- ..::  scripts  start ::.. -->
    <x-script  script='{!! isset($script) ? $script : "" !!}' />
    @stack('scripts')
    <!-- ..::  scripts  end ::.. -->

    {{-- Alert Success --}}
    @if(session('success'))
        <script>
            alert("{{ session('success') }}");
        </script>
    @endif

</body>

</html>