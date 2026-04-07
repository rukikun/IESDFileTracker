<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document Archives - IESD File Tracker</title>
    <link rel="icon" type="image/png" href="{{ asset('images/Philippine-Statistics-Authority-PSA-logo.png') }}?v=2">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        [x-cloak] { display: none !important; }
        .sidebar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .category-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .category-card:hover {
            transform: translateX(4px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .category-card.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            border-left-color: #6366f1;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(102, 126, 234, 0.4);
        }
        .dropdown-menu {
            background: rgba(255, 255, 255, 0.98);
            border: 1px solid rgba(0, 0, 0, 0.1);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15), 0 4px 10px rgba(0, 0, 0, 0.05);
            animation: dropdownSlide 0.2s ease-out;
            z-index: 9999 !important;
        }
        
        @keyframes dropdownSlide {
            from {
                opacity: 0;
                transform: translateY(-10px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        .hamburger-line {
            transition: all 0.3s ease;
            transform-origin: center;
        }
        
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        .sidebar {
            transition: transform 0.3s ease, width 0.3s ease, opacity 0.3s ease;
            position: relative;
            flex-shrink: 0;
        }
        
        .sidebar.collapsed {
            transform: translateX(-100%);
            width: 0;
            opacity: 0;
        }
        
        .main-content {
            transition: margin-left 0.3s ease, width 0.3s ease;
            width: 100%;
        }
        
        .main-content.sidebar-collapsed {
            margin-left: 0;
            width: 100%;
        }
        
        .category-dropdown {
            position: fixed !important;
            z-index: 99999 !important;
        }
        
        /* Enhanced toast/alert styles */
        .toast-notification {
            animation: slideInRight 0.4s ease-out;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        .toast-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            position: relative;
            overflow: hidden;
        }
        
        .toast-success::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            animation: shimmer 3s infinite;
        }
        
        @keyframes shimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }
            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }
        
        .toast-error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
        
        .toast-info {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
        
        /* Dark Mode Styles */
        .dark {
            background-color: #1a1a1a;
        }
        
        .dark body {
            background-color: #f0f0f0 !important;
        }
        
        .dark .glass-effect {
            background: rgba(40, 40, 40, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .dark .sidebar-gradient {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
        }
        
        .dark header {
            background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
            border-bottom-color: #1a202c;
        }
        
        .dark .category-card {
            border-left-color: transparent;
        }
        
        .dark .category-card:hover {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .dark .category-card.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.2) 0%, rgba(139, 92, 246, 0.2) 100%);
            border-left-color: #6366f1;
        }
        
        .category-card.active h4,
        .category-card.active p {
            color: #ffffff !important;
        }
        
        .dark .category-card h4,
        .dark .category-card p {
            color: #e2e8f0;
        }
        
        .dark .text-gray-800 {
            color: #e2e8f0 !important;
        }
        
        .dark .text-gray-700 {
            color: #cbd5e0 !important;
        }
        
        .dark .text-gray-600 {
            color: #a0aec0 !important;
        }
        
        .dark .text-gray-500 {
            color: #718096 !important;
        }
        
        .dark .text-gray-400 {
            color: #4a5568 !important;
        }
        
        .dark .border-gray-200 {
            border-color: #2d3748 !important;
        }
        
        .dark .border-gray-300 {
            border-color: #4a5568 !important;
        }
        
        .dark .bg-gray-50 {
            background-color: #1a1a1a !important;
        }
        
        .dark .bg-white {
            background-color: #1f2937 !important;
        }
        
        .dark .hover\:bg-gray-100:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        
        .dark input,
        .dark textarea,
        .dark select {
            background-color: #2d3748 !important;
            color: #e2e8f0 !important;
            border-color: #4a5568 !important;
        }
        
        .dark input:focus,
        .dark textarea:focus,
        .dark select:focus {
            border-color: #6366f1 !important;
        }
        
        .dark table {
            background-color: #2d3748 !important;
        }
        
        .dark th {
            background-color: #1a202c !important;
            color: #e2e8f0 !important;
        }
        
        .dark td {
            color: #e2e8f0 !important;
        }
        
        .dark .border-b {
            border-color: #4a5568 !important;
        }
        
        .dark .dropdown-menu {
            background: rgba(45, 55, 72, 0.98) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .dark .hover\:bg-gray-50:hover {
            background-color: #ffffff !important;
        }
        
        .dark .bg-red-50 {
            background-color: #7f1d1d !important;
        }
        
        .dark .text-red-700:hover {
            color: #f87171 !important;
        }
        
        .dark .text-gray-900 {
            color: #ffffff !important;
        }
        
        .dark .text-gray-800 {
            color: #ffffff !important;
        }
        
        .dark .text-gray-600 {
            color: #d1d5db !important;
        }
        
        .dark .text-gray-700 {
            color: #e5e7eb !important;
        }
        
        .dark .text-gray-500 {
            color: #9ca3af !important;
        }
        
        .dark .border-gray-200 {
            border-color: #374151 !important;
        }
        
        .dark .bg-gray-50 {
            background-color: #374151 !important;
        }
        
        .dark tbody tr:hover td,
        .dark tbody tr:hover td .font-semibold,
        .dark tbody tr:hover td .font-medium,
        .dark tbody tr:hover td .text-xs,
        .dark tbody tr:hover td .text-gray-500,
        .dark tbody tr:hover td .text-gray-600,
        .dark tbody tr:hover td .text-gray-800,
        .dark tbody tr:hover td .text-gray-900,
        .dark tbody tr:hover td .text-indigo-600,
        .dark tbody tr:hover td .text-blue-600,
        .dark tbody tr:hover td .text-red-600,
        .dark tbody tr:hover td span,
        .dark tbody tr:hover td a,
        .dark tbody tr:hover td div {
            color: #000000 !important;
        }
        
        .dark .bg-white.divide-y {
            background-color: #1f2937 !important;
        }
        
        .dark .bg-white.divide-y > div {
            background-color: #1f2937 !important;
        }
    </style>
</head>
<body class="bg-gray-50" x-data="documentArchives()">
    <!-- Header -->
    <header class="sidebar-gradient shadow-lg border-b-4 border-blue-700">
        <div class="px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Left section: Hamburger Menu and Logos -->
                <div class="flex items-center space-x-4">
                    <!-- Hamburger Menu Button -->
                    <button @click="toggleSidebar" 
                            class="text-white hover:text-blue-200 transition-colors p-2 rounded-lg hover:bg-blue-700 hamburger"
                            :class="{ 'active': !sidebarOpen }"
                            title="Toggle Sidebar">
                        <div class="w-6 h-5 flex flex-col justify-center space-y-1">
                            <div class="hamburger-line w-full h-0.5 bg-white"></div>
                            <div class="hamburger-line w-full h-0.5 bg-white"></div>
                            <div class="hamburger-line w-full h-0.5 bg-white"></div>
                        </div>
                    </button>
                    
                    <!-- Logo 1 - Philippine Statistics Authority -->
                    <div class="w-12 h-12 bg-white rounded-full shadow-md flex items-center justify-center p-1">
                        <img src="{{ asset('images/Philippine-Statistics-Authority-PSA-logo.png') }}" 
                             alt="PSA Logo" 
                             class="h-full w-full object-contain">
                    </div>
                    
                    <!-- Logo 2 -->
                    <div class="w-12 h-12 bg-white rounded-full shadow-md flex items-center justify-center p-2">
                        <img src="{{ asset('images/BP_color-logo-ver1-600px.png') }}" 
                             alt="BP Logo" 
                             class="h-8 w-8 object-contain">
                    </div>
                    
                    <!-- Title -->
                    <h1 class="text-white text-3xl font-bold tracking-wider ml-2">IESD FILE TRACKER</h1>
                </div>

                <!-- Right section: Theme Toggle and Logout -->
                <div class="flex items-center space-x-6">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode()" 
                            class="text-white hover:text-blue-200 transition-colors p-2 rounded-lg hover:bg-blue-700"
                            title="Toggle Dark Mode">
                        <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-xl"></i>
                    </button>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="bg-white text-blue-600 px-6 py-2.5 rounded-lg shadow-md hover:bg-blue-50 transition-all duration-200 font-semibold flex items-center space-x-2 hover:shadow-lg">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div class="flex h-screen" :class="darkMode ? 'bg-gray-900' : 'bg-gray-100'" style="height: calc(100vh - 80px);">
        <!-- Sidebar -->
        <aside class="sidebar w-80 sidebar-gradient shadow-2xl z-40"
               :class="{ 'collapsed': !sidebarOpen }"
               style="overflow: auto; height: 100%;">
            <div class="p-6 text-white">
                <!-- Header -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-2 flex items-center">
                        <i class="fas fa-folder-tree mr-3"></i>
                        Categories
                    </h2>
                    <p class="text-white/80 text-sm">Manage your document categories</p>
                </div>
                
                <!-- Navigation -->
                <div class="space-y-2 mb-6">
                    <a href="{{ route('dashboard') }}" 
                       class="glass-effect rounded-lg p-4 cursor-pointer flex items-center space-x-3 hover:bg-white/10 transition-all">
                        <i class="fas fa-tachometer-alt text-gray-800"></i>
                        <span class="font-semibold text-gray-800">Dashboard</span>
                    </a>
                    <a href="{{ route('archives.index') }}" 
                       class="glass-effect rounded-lg p-4 cursor-pointer flex items-center space-x-3 bg-white/20 border-l-4 border-white">
                        <i class="fas fa-archive text-white"></i>
                        <span class="font-semibold text-white">Archives</span>
                    </a>
                </div>
                
                            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col p-6 main-content overflow-y-auto" :class="{ 'sidebar-collapsed': !sidebarOpen, 'bg-gray-100': !darkMode, 'bg-gray-900': darkMode }">
            <div class="glass-effect rounded-xl shadow-xl flex-1 flex flex-col">
                <!-- Header -->
                <div class="p-6 border-b border-gray-200">
                    <div class="mb-6">
                        <h1 class="text-3xl font-bold text-gray-900">Document Archives</h1>
                        <p class="mt-2 text-gray-600">View all document changes, deletions, and edits across the system.</p>
                    </div>
                </div>

                <!-- Filters Section -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-filter mr-2 text-indigo-600"></i>
                            Filters
                            <div x-show="filterLoading" class="ml-3">
                                <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-indigo-600"></div>
                            </div>
                        </h3>
                        <button @click="clearFilters" 
                                class="text-sm text-gray-600 hover:text-gray-800 transition-colors">
                            <i class="fas fa-times mr-1"></i>Clear All
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Action Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                            <select x-model="filters.action" 
                                    @change="handleFilterChange()"
                                    :disabled="filterLoading"
                                    :class="{ 'opacity-50 cursor-not-allowed': filterLoading }"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Actions</option>
                                @foreach($actions ?? [] as $action)
                                    <option value="{{ $action }}">{{ ucfirst($action) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                            <select x-model="filters.category_id" 
                                    @change="handleFilterChange()"
                                    :disabled="filterLoading"
                                    :class="{ 'opacity-50 cursor-not-allowed': filterLoading }"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($categories ?? \App\Models\Category::orderBy('category_name')->get() as $category)
                                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <div class="relative">
                                <input type="text" 
                                       x-model="filters.search" 
                                       @input.debounce.300ms="handleFilterChange()"
                                       :disabled="filterLoading"
                                       :class="{ 'opacity-50 cursor-not-allowed': filterLoading }"
                                       placeholder="Search documents..." 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Archives Section -->
                <div class="p-6 flex-1 overflow-auto">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-archive mr-2 text-indigo-600"></i>
                            Archive Entries (<span x-text="pagination.total || 0"></span> total)
                        </h3>
                    </div>
                    
                    <div x-show="archives.length > 0" class="relative">
                        <!-- Loading Overlay -->
                        <div x-show="filterLoading" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100"
                             x-transition:leave-end="opacity-0"
                             class="absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center z-10 rounded-lg">
                            <div class="flex flex-col items-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600 mb-2"></div>
                                <p class="text-gray-600 text-sm">Applying filters...</p>
                            </div>
                        </div>
                        
                        <!-- Archives Table -->
                        <div class="overflow-x-auto transition-all duration-300 ease-in-out">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 bg-gray-50">
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">
                                            Date & Time
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">
                                            Action
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">
                                            Document
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">
                                            Category
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">
                                            User
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700">
                                            Date Modified
                                        </th>
                                        <th class="text-left py-3 px-4 font-semibold text-gray-700 text-center">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="transition-opacity duration-300 ease-in-out"
                                       :class="{ 'opacity-50': filterLoading }">
                                    <template x-for="archive in archives" :key="archive.id">
                                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-all duration-300"
                                            :data-archive-id="archive.id">
                                            <td class="py-4 px-4">
                                                <div>
                                                    <div class="font-semibold text-gray-800" x-text="archive.formatted_date"></div>
                                                    <div class="text-xs text-gray-500" x-text="archive.formatted_time"></div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                                      :class="`bg-${archive.action_color}-100 text-${archive.action_color}-800`">
                                                    <i :class="archive.action_icon" class="mr-1"></i>
                                                    <span x-text="archive.action.charAt(0).toUpperCase() + archive.action.slice(1)"></span>
                                                </span>
                                            </td>
                                            <td class="py-4 px-4">
                                                <div>
                                                    <div class="font-semibold text-gray-800" x-text="archive.document_title"></div>
                                                    <div x-show="archive.description" class="text-xs text-gray-500 mt-1" x-text="archive.description"></div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4">
                                                <template x-if="archive.category">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                                          :style="`background-color: ${archive.category.color}20; color: ${archive.category.color}`">
                                                        <span x-text="archive.category.name"></span>
                                                    </span>
                                                </template>
                                                <template x-if="!archive.category">
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                                        Uncategorized
                                                    </span>
                                                </template>
                                            </td>
                                            <td class="py-4 px-4 text-gray-600">
                                                <div>
                                                    <div class="font-medium text-gray-800" x-text="archive.user.name"></div>
                                                    <div class="text-xs text-gray-500" x-text="archive.user.email"></div>
                                                </div>
                                            </td>
                                            <td class="py-4 px-4 text-gray-600">
                                                <div x-text="archive.formatted_date_only"></div>
                                            </td>
                                            <td class="py-4 px-4 text-center">
                                                <button @click="confirmDelete(archive.id, archive.document_title)" 
                                                        class="text-red-500 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-all duration-200">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div x-show="archives.length === 0" class="text-center py-12">
                        <i class="fas fa-archive text-4xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No archive entries found</h3>
                        <p class="text-gray-500">No document changes match your current filters.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="display: none;">
        <div class="bg-white rounded-xl p-6 max-w-md w-full mx-4 shadow-2xl border border-gray-100">
            <div class="flex flex-col items-center mb-4">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-800 mb-2">Delete Document</h3>
                <p class="text-gray-600 text-center mb-6">
                    Are you sure you want to delete this document? <span class="font-semibold" x-text="deleteItemName"></span><br>
                    This action cannot be undone.
                </p>
            </div>
            
            <div class="flex justify-center space-x-3">
                <button @click="showDeleteModal = false" 
                        class="px-6 py-2.5 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-all duration-200 font-semibold">
                    Cancel
                </button>
                <form :action="deleteUrl" method="POST" class="inline" @submit="handleDelete($event)">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all duration-200 font-semibold flex items-center space-x-2">
                        <i class="fas fa-trash-alt"></i>
                        <span>Delete</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function documentArchives() {
            return {
                darkMode: false,
                sidebarOpen: true,
                selectedCategory: 'all',
                activeDropdown: null,
                newCategory: {
                    name: ''
                },
                showDeleteModal: false,
                deleteItemId: null,
                deleteItemName: '',
                deleteUrl: '',
                archives: {!! json_encode($archives->getCollection()->map(function ($archive) {
                            return [
                                'id' => $archive->id,
                                'document_title' => $archive->document_title,
                                'document_type' => $archive->document_type,
                                'action' => $archive->action,
                                'action_color' => $archive->action_color,
                                'action_icon' => $archive->action_icon,
                                'description' => $archive->description,
                                'formatted_date' => $archive->created_at->format('M j, Y g:i A'),
                                'formatted_time' => $archive->formatted_time,
                                'formatted_date_only' => $archive->created_at->format('F j, Y'),
                                'category' => $archive->category ? [
                                    'id' => $archive->category->id,
                                    'name' => $archive->category->category_name,
                                    'color' => $archive->category->color,
                                    'icon' => $archive->category->icon,
                                ] : null,
                                'user' => [
                                    'name' => $archive->user->name,
                                    'email' => $archive->user->email,
                                ]
                            ];
                        })->toArray()) !!},
                pagination: @json($archives->toArray()),
                loading: false,
                filterLoading: false,
                filters: {
                    action: '{{ request("action", "") }}',
                    category_id: '{{ request("category_id", "") }}',
                    search: '{{ request("search", "") }}'
                },
                
                init() {
                    // Ensure modal is hidden on page load
                    this.showDeleteModal = false;
                    // Check for saved dark mode preference
                    this.darkMode = localStorage.getItem('darkMode') === 'true';
                    if (this.darkMode) {
                        document.documentElement.classList.add('dark');
                    }
                },
                
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                },
                
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    document.documentElement.classList.toggle('dark');
                    localStorage.setItem('darkMode', this.darkMode);
                },
                
                selectCategory(categoryId) {
                    this.selectedCategory = categoryId;
                },
                
                toggleDropdown(categoryId) {
                    this.activeDropdown = this.activeDropdown === categoryId ? null : categoryId;
                },
                
                addCategory() {
                    if (this.newCategory.name.trim()) {
                        console.log('Adding category:', this.newCategory.name);
                        this.newCategory.name = '';
                    }
                },
                
                async handleFilterChange() {
                    this.filterLoading = true;
                    
                    try {
                        const params = new URLSearchParams();
                        Object.keys(this.filters).forEach(key => {
                            if (this.filters[key]) {
                                params.append(key, this.filters[key]);
                            }
                        });
                        
                        const response = await fetch(`/archives?${params.toString()}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        if (response.ok) {
                            const data = await response.json();
                            this.archives = data.archives.data;
                            this.pagination = data.archives;
                            // Update URL without page reload
                            window.history.pushState({}, '', `?${params.toString()}`);
                        } else {
                            this.showToast('Error applying filters. Please try again.', 'error');
                        }
                    } catch (error) {
                        console.error('Filter error:', error);
                        this.showToast('Error applying filters. Please check your connection.', 'error');
                    } finally {
                        this.filterLoading = false;
                    }
                },
                
                clearFilters() {
                    this.filters = {
                        action: '',
                        category_id: '',
                        search: ''
                    };
                    this.handleFilterChange();
                },
                
                confirmDelete(id, name) {
                    this.deleteItemId = id;
                    this.deleteItemName = name;
                    this.deleteUrl = '/archives/' + id;
                    this.showDeleteModal = true;
                },
                
                async handleDelete(event) {
                    event.preventDefault();
                    
                    try {
                        const formData = new FormData();
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
                        formData.append('_method', 'DELETE');
                        
                        const response = await fetch(this.deleteUrl, {
                            method: 'POST',
                            body: formData
                        });
                        
                        if (response.ok) {
                            this.showToast('Archive entry deleted successfully', 'success');
                            this.showDeleteModal = false;
                            // Find and remove the deleted row from the table
                            const deletedRow = document.querySelector(`[data-archive-id="${this.deleteItemId}"]`);
                            if (deletedRow) {
                                deletedRow.style.transition = 'opacity 0.3s ease-out';
                                deletedRow.style.opacity = '0';
                                setTimeout(() => {
                                    deletedRow.remove();
                                }, 300);
                            }
                        } else {
                            const errorData = await response.json().catch(() => ({}));
                            this.showToast(`Error: ${errorData.message || 'Failed to delete archive entry'}`, 'error');
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        this.showToast('Error deleting archive entry. Please try again.', 'error');
                    }
                },
                
                showToast(message, type = 'info') {
                    // Enhanced toast notification with beautiful animations
                    const toast = document.createElement('div');
                    
                    // Set background class based on type
                    const bgClass = type === 'success' ? 'toast-success' : 
                                   type === 'error' ? 'toast-error' : 'toast-info';
                    
                    // Set icon based on type
                    const icon = type === 'success' ? 'fa-check-circle' : 
                                 type === 'error' ? 'fa-exclamation-circle' : 
                                 'fa-info-circle';
                    
                    toast.className = `toast-notification fixed top-6 right-6 px-6 py-5 rounded-2xl text-white z-50 ${bgClass} flex items-center space-x-4 min-w-[320px] relative`;
                    toast.style.transition = 'all 0.3s ease-out';
                    toast.innerHTML = `
                        <div class="relative z-10 flex items-center space-x-4 flex-1">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <i class="fas ${icon} text-xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-lg leading-tight">${message}</p>
                                <p class="text-white/80 text-sm mt-1">${type === 'success' ? 'Success' : type === 'error' ? 'Error' : 'Information'}</p>
                            </div>
                            <button onclick="this.closest('.toast-notification').style.transform='translateX(120%)'; setTimeout(() => this.closest('.toast-notification').remove(), 300)" 
                                    class="flex-shrink-0 w-8 h-8 bg-white/20 rounded-full flex items-center justify-center hover:bg-white/30 transition-all duration-200 backdrop-blur-sm">
                                <i class="fas fa-times text-sm"></i>
                            </button>
                        </div>
                    `;
                    
                    document.body.appendChild(toast);
                    
                    // Add entrance animation
                    setTimeout(() => {
                        toast.style.transform = 'translateX(0)';
                    }, 10);
                    
                    // Auto-remove after 6 seconds
                    setTimeout(() => {
                        if (toast && toast.parentElement) {
                            toast.style.transform = 'translateX(120%)';
                            setTimeout(() => {
                                if (toast && toast.parentElement) {
                                    toast.parentElement.removeChild(toast);
                                }
                            }, 300);
                        }
                    }, 6000);
                }
            }
        }
    </script>
</body>
</html>
