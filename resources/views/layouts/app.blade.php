<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-md flex flex-col">
            <!-- Logo Section -->
            <div class="p-6 flex items-center justify-center border-b">
                <img src="{{ asset('logo.png') }}" alt="Wirtschaft Magazin Logo" width="80%">
            </div>

            <!-- Navigation -->
            <nav class="flex-1 p-4">
                <a href="{{ route('companies') }}" class="flex items-center py-3 px-4 rounded {{ Request::routeIs('companies') ? 'bg-gray-800 text-white font-semibold' : 'text-gray-800 hover:bg-gray-100' }}">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" id="user-hover" style="display: {{ Request::routeIs('companies') ? 'block' : 'none' }}" >
                        <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20.59 22C20.59 18.13 16.74 15 12 15C7.26003 15 3.41003 18.13 3.41003 22" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"   style="display: {{ Request::routeIs('companies') ? 'none' : 'block' }}">
                        <path d="M12 12C14.7614 12 17 9.76142 17 7C17 4.23858 14.7614 2 12 2C9.23858 2 7 4.23858 7 7C7 9.76142 9.23858 12 12 12Z" stroke="#5C5C5C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20.5901 22C20.5901 18.13 16.7402 15 12.0002 15C7.26015 15 3.41016 18.13 3.41016 22" stroke="#5C5C5C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    &nbsp;&nbsp;Unternehmer
                </a>
                <a href="{{ route('profile') }}" class="flex items-center mt-3 py-3 px-4 text-gray-800 hover:bg-gray-100 rounded {{ Request::routeIs('profile') ? 'bg-gray-800 text-white font-semibold' : 'text-gray-800 hover:bg-gray-100' }}">
                    
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: {{ Request::routeIs('profile') ? 'block' : 'none' }}">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM18.36 16.83C16.93 15.09 13.46 14.5 12 14.5C10.54 14.5 7.07 15.09 5.64 16.83C4.62 15.49 4 13.82 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 13.82 19.38 15.49 18.36 16.83ZM8.5 9.5C8.5 7.56 10.06 6 12 6C13.94 6 15.5 7.56 15.5 9.5C15.5 11.44 13.94 13 12 13C10.06 13 8.5 11.44 8.5 9.5Z" fill="white"/>
                    </svg>

                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="display: {{ Request::routeIs('profile') ? 'none' : 'block' }}">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM18.36 16.83C16.93 15.09 13.46 14.5 12 14.5C10.54 14.5 7.07 15.09 5.64 16.83C4.62 15.49 4 13.82 4 12C4 7.59 7.59 4 12 4C16.41 4 20 7.59 20 12C20 13.82 19.38 15.49 18.36 16.83ZM8.5 9.5C8.5 7.56 10.06 6 12 6C13.94 6 15.5 7.56 15.5 9.5C15.5 11.44 13.94 13 12 13C10.06 13 8.5 11.44 8.5 9.5Z" fill="#5C5C5C"/>
                    </svg>
                    &nbsp;&nbsp;Konto
                </a>
            </nav>

            <!-- Logout Button -->
            <div class="p-4 border-t">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full ml-4 flex items-center py-2 hover:text-gray-500 rounded hover:bg-gray-50">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M8.9 7.56023C9.21 3.96023 11.06 2.49023 15.11 2.49023H15.24C19.71 2.49023 21.5 4.28023 21.5 8.75023V15.2702C21.5 19.7402 19.71 21.5302 15.24 21.5302H15.11C11.09 21.5302 9.24 20.0802 8.91 16.5402M15 12.0002H3.62M5.85 8.65023L2.5 12.0002L5.85 15.3502" stroke="#5C5C5C" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                         &nbsp; Abmelden
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Header -->
            <header class="p-6 bg-white shadow flex justify-between items-center">
               
            </header>

            <!-- Main Content Area -->
            <main class="p-6 bg-gray-50 flex-1 overflow-y-auto">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
