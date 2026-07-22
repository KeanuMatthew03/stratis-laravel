<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>STRATIS - Strategic Tourism Analytics</title>
    
    <!-- CSS Dependencies -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    
    <!-- JS Dependencies -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        /* Custom Leaflet Tooltip */
        .leaflet-tooltip {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            padding: 0;
            color: #1e293b;
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-slate-800 font-sans h-screen overflow-hidden">
    
    <!-- Sidebar Navigation (Compact & Hoverable) -->
    <aside class="group fixed left-0 top-0 h-full bg-white border-r border-slate-200 flex flex-col shrink-0 shadow-[4px_0_24px_rgba(0,0,0,0.05)] z-50 transition-all duration-300 ease-in-out w-20 hover:w-64 overflow-hidden">
        
        <!-- Header Sidebar -->
        <div class="border-b border-slate-200 h-[80px] w-full shrink-0 relative overflow-hidden">
            <!-- Collapsed: Emblem doang (logo-icon.png) -->
            <img src="/logo-icon.png?v={{ time() }}" alt="Logo Icon" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[60px] h-[60px] object-contain opacity-100 group-hover:opacity-0" style="transition: opacity 0.5s ease;">
            <!-- Expanded: Full logo gede (logo.png) -->
            <img src="/logo.png?v={{ time() }}" alt="Logo Full" class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full object-contain scale-[2] opacity-0 group-hover:opacity-100" style="transition: opacity 0.5s ease;">
        </div>
        
        <nav class="flex-1 py-6 flex flex-col gap-2">
            <a href="/" class="flex items-center px-6 py-3.5 text-sm font-medium transition-colors {{ request()->is('/') ? 'bg-teal-50 text-[#0F766E] border-r-4 border-[#0F766E]' : 'text-slate-600 hover:bg-slate-50 border-r-4 border-transparent hover:border-slate-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                <span class="ml-5 opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity duration-300">Dashboard</span>
            </a>
            
            <a href="/ai-assistant" class="flex items-center px-6 py-3.5 text-sm font-medium transition-colors {{ request()->is('ai-assistant') ? 'bg-teal-50 text-[#0F766E] border-r-4 border-[#0F766E]' : 'text-slate-600 hover:bg-slate-50 border-r-4 border-transparent hover:border-slate-200' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" class="shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                <span class="ml-5 opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity duration-300">AI Intelligence</span>
            </a>
        </nav>
        
        <div class="h-[60px] shrink-0 border-t border-slate-200 text-xs text-center text-slate-400 font-medium bg-slate-50 flex items-center justify-center">
            <span class="opacity-0 group-hover:opacity-100 whitespace-nowrap transition-opacity duration-300">BSKLN &copy; 2026</span>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="h-full overflow-y-auto relative bg-[#F8FAFC] pl-20">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
