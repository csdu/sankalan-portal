<nav class="py-2">
    <div class="container mx-auto px-4 flex flex-col items-baseline sm:flex-row overflow-x-auto">
        <div class="flex py-2 justify-center sm:mr-3">
            <a class="inline-flex items-center whitespace-nowrap" href="{{ Auth::check() ? route('dashboard') : route('homepage') }}">
                <h1 class="text-2xl">Sankalan<small class="ml-1 text-xs text-blue-500 uppercase">Portal</small></h1>
            </a>
        </div>
        <div class="w-full sm:flex-1 flex justify-between items-baseline sm:py-2">
            <!-- Left Side Of Navbar -->
            <ul class="nav-center list-reset inline-flex items-baseline uppercase tracking-wide font-semibold text-xs">
                <li class="inline-flex">
                    <a class="px-2 py-1 inline-flex items-center" href="{{ route('events.index') }}">Events</a>
                </li>
                @auth
                <li class="inline-flex">
                    <a class="px-2 py-1  inline-flex items-center" href="{{ route('teams') }}">Teams</a>
                </li>
                @endauth
            </ul>

            <ul class="nav-right list-reset inline-flex items-baseline justify-end ml-2">
                <li class=" inline-flex text-sm uppercase tracking-wide text-black font-semibold leading-none mr-4">
                    <a href="/help" class="inline-flex items-baseline" title="help">
                        @include('svg.question', ['classes' => 'self-center text-blue-dark h-4 mr-2'])
                        Help
                    </a>
                </li>
                @auth
                    <li class="inline-flex items-center text-sm md:text-lg">
                        <div class="hidden sm:inline-flex">Hello, <span class="mx-1 font-semibold">{{ Auth::user()->first_name }}</span>!</div>
                        <a class="ml-2 px-2 py-1 inline-flex text-slate-700 hover:text-red-500" title="logout" href="{{ route('logout') }}"
                            onclick="event.preventDefault();
                                            document.getElementById('logout-form').submit();">
                            <svg class="h-4" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 20 20"><path fill="currentColor" fill-rule="evenodd" d="M4.16 4.16l1.42 1.42A6.99 6.99 0 0 0 10 18a7 7 0 0 0 4.42-12.42l1.42-1.42a9 9 0 1 1-11.69 0zM9 0h2v8H9V0z"/></svg>
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </li>
                @elseif(!Route::is('homepage'))
                    <li class="inline-flex items-baseline">
                        <a href="{{ route('homepage') }}#login" class="link mr-4">Login</a>
                        <a href="{{ route('homepage') }}#register" class="btn is-blue font-semibold">Register</a>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>
