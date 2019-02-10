<nav class="py-2">
    <div class="container flex flex-col sm:flex-row overflow-x-auto">
        <div class="flex py-2 justify-center sm:mr-3">
            <a class="inline-flex items-center whitespace-no-wrap" href="{{ Auth::check() ? route('dashboard') : route('homepage') }}">
                <h1 class="text-2xl">Sakalan<small class="ml-1 text-xs {{Request::is('/') ? 'text-grey-light' : 'text-blue'}} uppercase">Portal</small></h1>
            </a>
        </div>
        <div class="flex-1 flex justify-between items-baseline text-xs sm:py-2">
            <!-- Left Side Of Navbar -->
            <ul class="nav-center list-reset flex items-baseline">
                @auth
                <li class="flex">
                    <a class="px-2 py-1 uppercase tracking-wide font-semibold inline-flex items-center" href="{{ route('teams') }}">Teams</a>
                </li>
                @endauth
            </ul>
    
            <ul class="nav-right list-reset flex items-baseline justify-end ml-2">
                @auth
                    <li class="inline-flex items-center text-lg">
                        <div class="hidden sm:inline-flex">Hello, <span class="mx-1 font-semibold">{{ Auth::user()->first_name }}</span>!</div>
                        <a class="ml-3 px-2 py-1 inline-flex text-grey-dark hover:text-red" title="logout" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <svg viewBox="0 0 120 140" stroke-linecap="round" stroke-linejoin="round" stroke-width="15" class="h-5">
                                <circle cx="60" cy="70" r="50" stroke-dasharray="235.6" stroke="currentColor" fill="none" style="transform: rotate(-45deg); transform-origin: center;"></circle>
                                <path d="M60,10 L60 60" stroke="currentColor"></path>
                            </svg>
                        </a>
    
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>