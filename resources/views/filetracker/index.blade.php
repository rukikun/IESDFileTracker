<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Documents - IESD File Tracker</title>
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
        .modal-backdrop {
            background: rgba(0, 0, 0, 0.5);
        }
        
        /* Enhanced dropdown styles */
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
        
        /* Enhanced toast/alert styles */
        .toast-notification {
            animation: fadeIn 0.3s ease-out;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Confirmation dialog styles */
        .confirm-dialog {
            animation: modalFadeIn 0.3s ease-out;
        }
        
        /* Ensure dropdowns always appear on top */
        .relative .absolute {
            z-index: 1000 !important;
        }
        
        /* Header enhancements */
        header {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        header .logo-container {
            transition: transform 0.3s ease;
        }
        
        header .logo-container:hover {
            transform: scale(1.05);
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
            background-color: #4f46e5 !important; /* Same as add document button */
        }
        
        .dark .bg-white.text-blue-600 {
            color: white !important;
        }
        
        .dark .hover\:bg-gray-100:hover {
            background-color: rgba(255, 255, 255, 0.05) !important;
        }
        
        .dark .hover\:bg-blue-50:hover {
            background-color: rgba(66, 153, 225, 0.1) !important;
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
        
        .dark tbody tr:hover td,
        .dark tbody tr:hover td .font-semibold,
        .dark tbody tr:hover td .text-xs,
        .dark tbody tr:hover td .text-gray-600,
        .dark tbody tr:hover td .text-indigo-600,
        .dark tbody tr:hover td .text-blue-600,
        .dark tbody tr:hover td .text-red-600,
        .dark tbody tr:hover td span,
        .dark tbody tr:hover td a {
            color: #000000 !important;
        }
        
        .dark .dropdown-menu {
            background: rgba(45, 55, 72, 0.98) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .dark .modal-backdrop {
            background: rgba(0, 0, 0, 0.7);
        }
        
        /* Dark mode modal content styling */
        .dark .bg-white {
            background-color: #1a202c !important;
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
        
        .dark .border-gray-300 {
            border-color: #4a5568 !important;
        }
        
        .dark .bg-gray-50 {
            background-color: #2d3748 !important;
        }
        
        .dark .bg-red-100 {
            background-color: #742a2a !important;
        }
        
        .dark .text-red-600 {
            color: #fc8181 !important;
        }
        
        /* Dark mode cancel button styling */
        .dark .bg-gray-100 {
            background-color: #2d3748 !important;
        }
        
        .dark .text-gray-700 {
            color: #ffffff !important;
        }
        
        /* Dark mode cancel button specific styling */
        .dark .cancel-button-text {
            color: #000000 !important;
        }
        
        /* Ensure dropdowns are not clipped */
        .category-dropdown {
            pointer-events: auto !important;
        }
        
        .sidebar {
            overflow: visible !important;
        }
        
        /* Hamburger menu animation */
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
        
        /* Sidebar animation */
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
        
        /* Main content adjustment */
        .main-content {
            transition: margin-left 0.3s ease, width 0.3s ease;
            width: 100%; /* Default to full width */
        }
        
        .main-content.sidebar-collapsed {
            margin-left: 0;
            width: 100%;
        }
        
        /* Ensure dropdowns appear above everything */
        .category-dropdown {
            position: fixed !important;
            z-index: 99999 !important;
        }
        
        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        /* Highlight animation for newly added documents */
        @keyframes highlightNew {
            0% {
                background-color: #fef3c7;
                transform: scale(1.02);
            }
            50% {
                background-color: #fde68a;
                transform: scale(1.01);
            }
            100% {
                background-color: transparent;
                transform: scale(1);
            }
        }
        
        .new-document-highlight {
            animation: highlightNew 2s ease-in-out;
        }

        /* Updated document highlight animation */
        @keyframes highlightUpdated {
            0% {
                background-color: #dbeafe;
                transform: scale(1.02);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }
            25% {
                background-color: #bfdbfe;
                transform: scale(1.01);
                box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
            }
            50% {
                background-color: #93c5fd;
                transform: scale(1.005);
                box-shadow: 0 8px 20px rgba(59, 130, 246, 0.5);
            }
            75% {
                background-color: #dbeafe;
                transform: scale(1.002);
                box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            }
            100% {
                background-color: transparent;
                transform: scale(1);
                box-shadow: none;
            }
        }
        
        .updated-document-highlight {
            animation: highlightUpdated 2s ease-in-out;
        }

        
        /* Custom scrollbar styling for main content area */
        .main-content::-webkit-scrollbar {
            width: 4px;
        }

        .main-content::-webkit-scrollbar-track {
            background: transparent;
        }

        .main-content::-webkit-scrollbar-thumb {
            background-color: rgba(156, 163, 175, 0.8);
            border-radius: 2px;
        }

        .main-content::-webkit-scrollbar-thumb:hover {
            background-color: rgba(156, 163, 175, 1);
        }

        .dark .main-content::-webkit-scrollbar-thumb {
            background-color: rgba(75, 85, 99, 0.8);
        }

        .dark .main-content::-webkit-scrollbar-thumb:hover {
            background-color: rgba(75, 85, 99, 1);
        }
    </style>
</head>
<body class="bg-gray-50" x-data="fileTracker()">
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
                    <div class="w-12 h-12 bg-white rounded-full shadow-md flex items-center justify-center p-1 logo-container">
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

                <!-- Right section: Theme Toggle, Notifications, and Logout -->
                <div class="flex items-center space-x-6">
                    <!-- Dark Mode Toggle -->
                    <button @click="toggleDarkMode()" 
                            class="text-white hover:text-blue-200 transition-colors p-2 rounded-lg hover:bg-blue-700"
                            title="Toggle Dark Mode">
                        <i :class="darkMode ? 'fas fa-sun' : 'fas fa-moon'" class="text-xl"></i>
                    </button>

                    
                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline ml-6">
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
        <!-- Enhanced Sidebar -->
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
                
                <!-- Add Category Section -->
                <div class="mb-6">
                    <div class="glass-effect rounded-lg p-4">
                        <h3 class="text-gray-800 font-semibold mb-3 flex items-center">
                            <i class="fas fa-plus-circle mr-2 text-indigo-600"></i>
                            Add New Category
                        </h3>
                        <div class="space-y-3">
                            <input type="text" 
                                   x-model="newCategory.category_name" 
                                   placeholder="Category name" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-800">
                            <textarea x-show="newCategory.category_name.trim() !== ''" 
                                      x-transition:enter="transition ease-out duration-300"
                                      x-transition:enter-start="opacity-0 transform -translate-y-2"
                                      x-transition:enter-end="opacity-100 transform translate-y-0"
                                      x-transition:leave="transition ease-in duration-200"
                                      x-transition:leave-start="opacity-100 transform translate-y-0"
                                      x-transition:leave-end="opacity-0 transform -translate-y-2"
                                      x-model="newCategory.description" 
                                      placeholder="Description (optional)" 
                                      rows="2"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-800 resize-none"></textarea>
                            
                            <button @click="addCategory" 
                                    class="w-full btn-primary text-white py-2 rounded-lg font-semibold">
                                <i class="fas fa-plus mr-2"></i>Add Category
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Category List -->
                <div class="space-y-2" style="overflow: visible;">
                    <!-- All Categories Option (Moved to Top) -->
                    <div class="category-card glass-effect rounded-lg p-4 cursor-pointer"
                         :class="{'active': selectedCategory === null}"
                         @click="selectCategory(null)">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 rounded-full bg-gradient-to-r from-purple-500 to-pink-500"></div>
                                <div>
                                    <h4 class="font-semibold text-gray-800">All Categories</h4>
                                    <p class="text-xs text-gray-600">View all documents</p>
                                </div>
                            </div>
                            <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full font-semibold"
                                  x-text="`${totalDocuments} docs`"></span>
                        </div>
                    </div>
                    
                    <template x-for="category in categories" :key="category.id">
                        <div class="category-card glass-effect rounded-lg p-4 cursor-pointer relative"
                             :class="{'active': selectedCategory === category.id}"
                             @click="selectCategory(category.id)"
                             style="overflow: visible;">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <div class="w-3 h-3 rounded-full"
                                         :style="`background-color: ${category.color}`"></div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800" x-text="category.category_name"></h4>
                                        <p class="text-xs text-gray-600" x-text="category.description || 'No description'"></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <div class="relative group">
                                        <button @click.stop="toggleDropdown(category.id)" 
                                                class="text-gray-400 hover:text-gray-600 p-2 hover:bg-gray-100 rounded-lg transition-all duration-200">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div x-show="activeDropdown === category.id" 
                                             @click.away="activeDropdown = null"
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95 transform -translate-x-2"
                                             x-transition:enter-end="opacity-100 scale-100 transform translate-x-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 scale-100 transform translate-x-0"
                                             x-transition:leave-end="opacity-0 scale-95 transform -translate-x-2"
                                             class="category-dropdown absolute left-full top-0 ml-1 w-52 dropdown-menu rounded-lg shadow-xl border border-gray-200"
                                             style="z-index: 9999999; position: fixed; overflow: visible;">
                                            <button @click.stop="editCategory(category)" 
                                                    class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 flex items-center transition-all duration-200 group">
                                                <i class="fas fa-edit mr-3 text-blue-500 group-hover:scale-110 transition-transform"></i>
                                                <span class="font-medium">Edit Category</span>
                                            </button>
                                            <div class="border-t border-gray-100"></div>
                                            <button @click.stop="confirmDeleteCategory(category)" 
                                                    class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 hover:text-red-700 flex items-center transition-all duration-200 group">
                                                <i class="fas fa-trash mr-3 group-hover:scale-110 transition-transform"></i>
                                                <span class="font-medium">Delete Category</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Admin Files Button -->
                <div class="mt-6" x-show="hasAdminDocuments">
                    <button @click="selectCategory('admin')" 
                            class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white py-3 rounded-lg font-semibold hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-shield-alt mr-2"></i>
                        ADMIN FILES
                    </button>
                </div>
                
                            </div>
        </aside>

        <!-- Loading Overlay -->
        <div x-show="loading || searchLoading || filterLoading" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-40">
            <!-- Dark mode covering div -->
            <div x-show="darkMode" 
                 class="absolute inset-0 bg-gray-800" style="z-index: -1;"></div>
            <div :class="darkMode ? 'bg-gray-800' : 'bg-white'" class="rounded-lg p-6 flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                <p :class="darkMode ? 'text-gray-200' : 'text-gray-700'" class="font-medium" x-text="searchLoading ? 'Searching...' : (filterLoading ? 'Applying filters...' : 'Applying filters...')"></p>
            </div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col p-6 main-content overflow-y-auto" :class="{ 'sidebar-collapsed': !sidebarOpen, 'bg-gray-100': !darkMode, 'bg-gray-900': darkMode }">
            <div class="glass-effect rounded-xl shadow-xl flex-1 flex flex-col">
                <!-- Filters Section -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                            <i class="fas fa-filter mr-2 text-indigo-600"></i>
                            Filters
                        </h3>
                        <button @click="clearFilters" 
                                class="text-sm text-gray-600 hover:text-gray-800 transition-colors">
                            <i class="fas fa-times mr-1"></i>Clear All
                        </button>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                            <select x-model="filters.category_id" 
                                    @change="handleFilterChange($event)"
                                    :disabled="loading || filterLoading"
                                    :class="{ 'opacity-50 cursor-not-allowed': loading || filterLoading }"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Year Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <select x-model="filters.year" 
                                    @change="handleFilterChange($event)"
                                    :disabled="loading || filterLoading"
                                    :class="{ 'opacity-50 cursor-not-allowed': loading || filterLoading }"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Years</option>
                                @foreach($availableYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Month Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                            <select x-model="filters.month" 
                                    @change="handleFilterChange($event)"
                                    :disabled="loading || filterLoading"
                                    :class="{ 'opacity-50 cursor-not-allowed': loading || filterLoading }"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <option value="">All Months</option>
                                <option value="1">January</option>
                                <option value="2">February</option>
                                <option value="3">March</option>
                                <option value="4">April</option>
                                <option value="5">May</option>
                                <option value="6">June</option>
                                <option value="7">July</option>
                                <option value="8">August</option>
                                <option value="9">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        
                        <!-- Search Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                            <div class="relative">
                                <input type="text" 
                                       x-model="filters.search" 
                                       @input.debounce.300ms="handleSearchInput"
                                       @keydown.enter="handleSearchEnter"
                                       :disabled="loading || filterLoading || searchLoading"
                                       :class="{ 'opacity-50 cursor-not-allowed': loading || filterLoading || searchLoading }"
                                       placeholder="Search documents..." 
                                       class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Active Filters Display -->
                    <div x-show="hasActiveFilters()" class="mt-4 flex flex-wrap gap-2">
                        <!-- Category Filter -->
                        <div x-show="filters.category_id" 
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <span>Category: <span x-text="getCategoryName(filters.category_id)"></span></span>
                            <button @click="removeFilter('category_id')" 
                                    class="ml-2 hover:text-indigo-600 transition-colors duration-150 hover:scale-110 transform">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Year Filter -->
                        <div x-show="filters.year" 
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <span>Year: <span x-text="filters.year"></span></span>
                            <button @click="removeFilter('year')" 
                                    class="ml-2 hover:text-indigo-600 transition-colors duration-150 hover:scale-110 transform">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Month Filter -->
                        <div x-show="filters.month" 
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <span>Month: <span x-text="getMonthName(filters.month)"></span></span>
                            <button @click="removeFilter('month')" 
                                    class="ml-2 hover:text-indigo-600 transition-colors duration-150 hover:scale-110 transform">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <!-- Search Filter -->
                        <div x-show="filters.search" 
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 transform scale-100"
                             x-transition:leave-end="opacity-0 transform scale-90"
                             class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <span>Search: <span x-text="filters.search"></span></span>
                            <button @click="removeFilter('search')" 
                                    class="ml-2 hover:text-indigo-600 transition-colors duration-150 hover:scale-110 transform">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Header with Add Button -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            Showing <span x-text="startRecord" class="font-semibold"></span>-<span x-text="endRecord" class="font-semibold"></span> 
                            <span x-show="hasActiveFilters()">filtered</span> 
                            of <span x-text="filteredDocuments.length" class="font-semibold"></span> documents
                        </div>
                        <div class="flex items-center space-x-4">
                            @if(Auth::user() && Auth::user()->isSuperAdmin())
                                <a href="{{ route('archives.index') }}" 
                                   @click="navigateToArchives($event)"
                                   class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                                    <i class="fas fa-archive mr-2"></i>Archives
                                </a>
                            @endif
                            <button @click="showAddDocument = true" 
                                    class="btn-primary text-white px-6 py-3 rounded-lg font-semibold">
                                <i class="fas fa-plus mr-2"></i>Add Document
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Documents Table -->
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-file-alt mr-3 text-indigo-600"></i>
                            Documents
                        </h2>
                        <div class="text-sm text-gray-600">
                            Showing <span x-text="startRecord" class="font-semibold"></span>-<span x-text="endRecord" class="font-semibold"></span> of <span x-text="filteredDocuments.length" class="font-semibold"></span> documents
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto transition-all duration-300 ease-in-out">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-gray-200 bg-gray-50">
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">
                                        <input type="checkbox" @change="toggleSelectAll" class="mr-2">
                                        Name
                                    </th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Category</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Year</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Link</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Actions</th>
                                    <th class="text-left py-3 px-4 font-semibold text-gray-700">Month</th>
                                </tr>
                            </thead>
                            <tbody class="transition-opacity duration-300 ease-in-out"
                           :class="{ 'opacity-50': loading }">
                                <template x-for="document in paginatedDocuments" :key="document.id">
                                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-all duration-300"
                                        :class="{ 'new-document-highlight': newDocumentId === document.id }"
                                        :data-document-id="document.id">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <input type="checkbox" :value="document.id" x-model="selectedDocuments" class="mr-3 w-4 h-4 text-indigo-600 rounded focus:ring-indigo-500">
                                                <div>
                                                    <div class="font-semibold text-gray-800" x-text="document.document_name"></div>
                                                    <div class="text-xs text-gray-500" x-text="formatDate(document.created_at)"></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                                  :style="`background-color: ${getCategoryColor(document.category_name)}20; color: ${getCategoryColor(document.category_name)}`"
                                                  x-text="document.category_name"></span>
                                        </td>
                                        <td class="py-4 px-4 text-gray-600">
                                            <span x-text="document.document_year || 'N/A'"></span>
                                        </td>
                                        <td class="py-4 px-4">
                                            <a :href="document.url" 
                                               target="_blank" 
                                               class="text-indigo-600 hover:text-indigo-800 underline flex items-center">
                                                <i class="fas fa-external-link-alt mr-1 text-xs"></i>
                                                <span class="truncate max-w-xs" x-text="document.url"></span>
                                            </a>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="flex space-x-2">
                                                <button @click="editDocument(document)" 
                                                        class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-colors"
                                                        title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button @click="deleteDocument(document.id)" 
                                                        class="text-red-600 hover:text-red-800 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                                        title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4 text-gray-600">
                                            <span x-text="getMonthNameById(document.document_month_id)"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        
                        <!-- Pagination Controls -->
                        <div x-show="totalPages > 1" class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                Page <span x-text="currentPage" class="font-semibold"></span> of <span x-text="totalPages" class="font-semibold"></span>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <!-- Previous Button -->
                                <button @click="previousPage()" 
                                        :disabled="currentPage === 1"
                                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1, 'hover:bg-gray-100 active:scale-95': currentPage > 1 }"
                                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md transition-all duration-200 transform">
                                    <i class="fas fa-chevron-left mr-1"></i>
                                    Previous
                                </button>
                                
                                <!-- Page Numbers -->
                                <template x-for="page in Math.min(totalPages, 5)" :key="page">
                                    <button @click="goToPage(page)" 
                                            :class="{ 'bg-indigo-600 text-white border-indigo-600 active:scale-95': page === currentPage, 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 active:scale-95': page !== currentPage }"
                                            class="px-3 py-2 text-sm font-medium border rounded-md transition-all duration-200 transform"
                                            x-text="page"></button>
                                </template>
                                
                                <!-- Next Button -->
                                <button @click="nextPage()" 
                                        :disabled="currentPage === totalPages"
                                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages, 'hover:bg-gray-100 active:scale-95': currentPage < totalPages }"
                                        class="px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md transition-all duration-200 transform">
                                    Next
                                    <i class="fas fa-chevron-right ml-1"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div x-show="filteredDocuments.length === 0" class="text-center py-12">
                            <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 text-lg">No documents found</p>
                            <p class="text-gray-400 text-sm mt-2">Try adjusting your search or add a new document</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Document Modal -->
    <div x-show="showAddDocument" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
         class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4 relative">
            <!-- Loading Overlay -->
            <div x-show="addDocumentLoading" 
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 bg-opacity-95 rounded-xl flex items-center justify-center z-10"
                 :class="darkMode ? 'bg-gray-800' : 'bg-white'">
                <div class="flex flex-col items-center">
                    <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                    <p :class="darkMode ? 'text-gray-200' : 'text-gray-700'" class="font-medium">Adding document...</p>
                </div>
            </div>
            
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-file-plus mr-3 text-indigo-600"></i>
                Add New Document
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select x-model="newDocument.category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a category</option>
                        <template x-for="category in categories" :key="category.id">
                            <option :value="category.id" x-text="category.category_name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Name</label>
                    <input type="text" 
                           x-model="newDocument.document_name" 
                           placeholder="Enter document name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <input type="number" 
                               x-model="newDocument.document_year" 
                               placeholder="e.g., 2024" 
                               min="2000" 
                               max="2100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <select x-model="newDocument.document_month_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Month</option>
                            @foreach($months as $month)
                            <option value="{{ $month->id }}">{{ $month->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input type="url" 
                           x-model="newDocument.url" 
                           placeholder="https://example.com/document" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button @click="showAddDocument = false; resetNewDocument()" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors cancel-button-text">
                    Cancel
                </button>
                <button @click="addDocument" 
                        class="px-4 py-2 btn-primary text-white rounded-lg">
                    <i class="fas fa-plus mr-2"></i>Add Document
                </button>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div x-show="showEditCategory" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
         class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-edit mr-3 text-indigo-600"></i>
                Edit Category
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category Name</label>
                    <input type="text" 
                           x-model="editingCategory.category_name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea x-model="editingCategory.description" 
                              rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 resize-none"></textarea>
                </div>
                <div class="flex space-x-2">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
                        <input type="color" 
                               x-model="editingCategory.color" 
                               class="w-full h-10 border border-gray-300 rounded cursor-pointer">
                    </div>
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button @click="showEditCategory = false" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors cancel-button-text">
                    Cancel
                </button>
                <button @click="updateCategory" 
                        class="px-4 py-2 btn-primary text-white rounded-lg">
                    <i class="fas fa-save mr-2"></i>Save Changes
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteConfirm" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
         class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-cloak>
        <div class="confirm-dialog bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Category</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to delete this category? 
                    <span class="font-semibold text-gray-900" x-text="categoryToDelete?.category_name"></span>
                    <br>
                    This will also delete all documents in this category.
                </p>
                <div class="flex justify-center space-x-3">
                    <button @click="cancelDelete()" 
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium cancel-button-text">
                        Cancel
                    </button>
                    <button @click="deleteCategoryConfirmed()" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Document Modal -->
    <div x-show="showEditDocument" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
         class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-cloak>
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-edit mr-3 text-indigo-600"></i>
                Edit Document
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select x-model="editingDocument.category_id" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a category</option>
                        <template x-for="category in categories" :key="category.id">
                            <option :value="category.id" x-text="category.category_name"></option>
                        </template>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Document Name</label>
                    <input type="text" 
                           x-model="editingDocument.document_name" 
                           placeholder="Enter document name" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                        <input type="number" 
                               x-model="editingDocument.document_year" 
                               placeholder="e.g., 2024" 
                               min="2000" 
                               max="2100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                        <select x-model="editingDocument.document_month_id" 
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="">Select Month</option>
                            @foreach($months as $month)
                            <option value="{{ $month->id }}">{{ $month->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">URL</label>
                    <input type="url" 
                           x-model="editingDocument.url" 
                           placeholder="https://example.com/document" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="flex justify-end space-x-3 mt-6">
                <button @click="showEditDocument = false" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors cancel-button-text">
                    Cancel
                </button>
                <button @click="updateDocument" 
                        :disabled="editDocumentLoading"
                        :class="{ 'opacity-50 cursor-not-allowed': editDocumentLoading }"
                        class="px-4 py-2 btn-primary text-white rounded-lg">
                    <template x-if="editDocumentLoading">
                        <i class="fas fa-spinner fa-spin mr-2"></i>
                    </template>
                    <template x-if="!editDocumentLoading">
                        <i class="fas fa-save mr-2"></i>
                    </template>
                    <span x-text="editDocumentLoading ? 'Saving...' : 'Save Changes'"></span>
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Document Confirmation Modal -->
    <div x-show="showDeleteDocumentConfirm" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
         x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
         class="fixed inset-0 modal-backdrop flex items-center justify-center z-50" x-cloak>
        <div class="confirm-dialog bg-white rounded-xl shadow-2xl p-6 w-full max-w-md mx-4">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Document</h3>
                <p class="text-sm text-gray-600 mb-6">
                    Are you sure you want to delete this document? 
                    <span class="font-semibold text-gray-900" x-text="documentToDelete?.document_name"></span>
                    <br>
                    This action cannot be undone.
                </p>
                <div class="flex justify-center space-x-3">
                    <button @click="cancelDeleteDocument()" 
                            class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium cancel-button-text">
                        Cancel
                    </button>
                    <button @click="deleteDocumentConfirmed()" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        <i class="fas fa-trash mr-2"></i>Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Archives Loading Modal -->
    <div x-show="archivesLoading" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 flex flex-col items-center max-w-sm mx-4">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
            <p class="text-gray-700 font-medium text-lg">Loading Archives...</p>
            <p class="text-gray-500 text-sm mt-2">Please wait while we redirect you</p>
        </div>
    </div>

    <script>
        function fileTracker() {
            return {
                categories: @json($categories),
                categoriesWithDocuments: @json($categoriesWithDocuments),
                uncategorizedDocuments: @json($uncategorizedDocuments),
                documents: @json($documents),
                allDocuments: @json($documents),
                availableYears: @json($availableYears),
                months: @json($months),
                selectedCategory: null,
                searchTerm: '',
                showAddDocument: false,
                showEditDocument: false,
                showEditCategory: false,
                showDeleteConfirm: false,
                showDeleteDocumentConfirm: false,
                activeDropdown: null,
                categoryToDelete: null,
                documentToDelete: null,
                sidebarOpen: true,
                darkMode: localStorage.getItem('darkMode') === 'true', // Load from localStorage
                loading: false, // Loading state for filters
                addDocumentLoading: false, // Loading state for add document
                editDocumentLoading: false, // Loading state for edit document
                searchLoading: false, // Loading state for search
                searchLoadingTimer: null, // Timer for search loading delay
                filterLoading: false, // Loading state for category/year/month filters
                archivesLoading: false, // Loading state for archives navigation
                totalDocumentsCount: @json($documents->count()), // Total documents count
                filters: {
                    category_id: @json($filters['category_id'] ?? ''),
                    year: @json($filters['year'] ?? ''),
                    month: @json($filters['month'] ?? ''),
                    search: @json($filters['search'] ?? ''),
                },
                newCategory: {
                    category_name: '',
                    description: '',
                    color: '#6B46C1',
                    icon: 'folder'
                },
                editingCategory: {
                    id: null,
                    category_name: '',
                    description: '',
                    color: '#6B46C1',
                    icon: 'folder'
                },
                newDocument: {
                    category_id: '',
                    document_name: '',
                    url: '',
                    document_year: '',
                    document_month_id: ''
                },
                editingDocument: {
                    id: null,
                    category_id: '',
                    document_name: '',
                    url: '',
                    document_year: '',
                    document_month_id: ''
                },
                selectedDocuments: [],
                currentPage: 1,
                perPage: 10,
                newDocumentId: null,
                removingFilter: null, // Track which filter is being removed for animation
                
                get filteredDocuments() {
                    let filtered = this.documents;
                    
                    if (this.selectedCategory === 'admin') {
                        // Show only admin documents
                        filtered = filtered.filter(doc => doc.is_admin === true);
                    } else if (this.selectedCategory !== null) {
                        // Filter by selected category ID using foreach logic
                        filtered = filtered.filter(doc => {
                            // Ensure proper category comparison
                            return doc.category_id === this.selectedCategory || 
                                   (doc.category && doc.category.id === this.selectedCategory);
                        });
                    }
                    
                    if (this.searchTerm) {
                        const search = this.searchTerm.toLowerCase();
                        filtered = filtered.filter(doc => 
                            doc.document_name.toLowerCase().includes(search) ||
                            (doc.category_name && doc.category_name.toLowerCase().includes(search)) ||
                            (doc.category && doc.category.category_name && doc.category.category_name.toLowerCase().includes(search))
                        );
                    }
                    
                    return filtered;
                },
                
                get paginatedDocuments() {
                    const start = (this.currentPage - 1) * this.perPage;
                    const end = start + this.perPage;
                    return this.filteredDocuments.slice(start, end);
                },
                
                get totalPages() {
                    return Math.ceil(this.filteredDocuments.length / this.perPage);
                },
                
                get startRecord() {
                    return this.filteredDocuments.length === 0 ? 0 : (this.currentPage - 1) * this.perPage + 1;
                },
                
                get endRecord() {
                    const end = this.currentPage * this.perPage;
                    return end > this.filteredDocuments.length ? this.filteredDocuments.length : end;
                },
                
                get totalDocuments() {
                    return this.documents.length;
                },
                
                get hasAdminDocuments() {
                    return this.documents.some(doc => doc.is_admin === true);
                },
                
                selectCategory(categoryId) {
                    this.selectedCategory = categoryId;
                    this.currentPage = 1; // Reset to first page when changing category
                },
                
                goToPage(page) {
                    if (page >= 1 && page <= this.totalPages && page !== this.currentPage) {
                        this.loading = true;
                        setTimeout(() => {
                            this.currentPage = page;
                            this.loading = false;
                        }, 150);
                    }
                },
                
                previousPage() {
                    if (this.currentPage > 1) {
                        this.loading = true;
                        setTimeout(() => {
                            this.currentPage--;
                            this.loading = false;
                        }, 150);
                    }
                },
                
                nextPage() {
                    if (this.currentPage < this.totalPages) {
                        this.loading = true;
                        setTimeout(() => {
                            this.currentPage++;
                            this.loading = false;
                        }, 150);
                    }
                },
                
                get isAdminFilesSelected() {
                    return this.selectedCategory === 'admin';
                },
                
                navigateToArchives(event) {
                    event.preventDefault();
                    this.archivesLoading = true;
                    
                    // Simulate loading delay then navigate with fade
                    setTimeout(() => {
                        // Start fade out
                        this.archivesLoading = false;
                        
                        // Wait for fade out to complete, then navigate
                        setTimeout(() => {
                            window.location.href = event.target.closest('a').href;
                        }, 200);
                    }, 600);
                },
                
                async addCategory() {
                    if (!this.newCategory.category_name.trim()) {
                        this.showToast('Please enter a category name', 'error');
                        return;
                    }
                    
                    try {
                        const response = await fetch('/api/categories', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.newCategory)
                        });
                        
                        if (response.ok) {
                            const newCategory = await response.json();
                            this.categories.push(newCategory);
                            this.resetNewCategory();
                            this.showToast('Category added successfully', 'success');
                        }
                    } catch (error) {
                        console.error('Error adding category:', error);
                        this.showToast('Error adding category', 'error');
                    }
                },
                
                editCategory(category) {
                    this.editingCategory = { ...category };
                    this.showEditCategory = true;
                },
                
                async updateCategory() {
                    if (!this.editingCategory.category_name.trim()) {
                        this.showToast('Please enter a category name', 'error');
                        return;
                    }
                    
                    try {
                        const response = await fetch(`/api/categories/${this.editingCategory.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(this.editingCategory)
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Update error:', errorData);
                            this.showToast(`Error: ${errorData.message || 'Failed to update category'}`, 'error');
                            return;
                        }
                        
                        const updatedCategory = await response.json();
                        const index = this.categories.findIndex(c => c.id === updatedCategory.id);
                        if (index !== -1) {
                            this.categories[index] = updatedCategory;
                        }
                        this.showEditCategory = false;
                        this.showToast('Category updated successfully', 'success');
                    } catch (error) {
                        console.error('Error updating category:', error);
                        this.showToast('Error updating category', 'error');
                    }
                },
                
                toggleDropdown(categoryId) {
                    this.activeDropdown = this.activeDropdown === categoryId ? null : categoryId;
                },
                
                confirmDeleteCategory(category) {
                    this.categoryToDelete = category;
                    this.showDeleteConfirm = true;
                    this.activeDropdown = null;
                },
                
                async deleteCategoryConfirmed() {
                    if (!this.categoryToDelete) return;
                    
                    try {
                        const response = await fetch(`/api/categories/${this.categoryToDelete.id}/force-delete`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Delete error:', errorData);
                            this.showToast(`Error: ${errorData.message || 'Failed to delete category'}`, 'error');
                            return;
                        }
                        
                        this.categories = this.categories.filter(c => c.id !== this.categoryToDelete.id);
                        this.documents = this.documents.filter(doc => doc.category_id !== this.categoryToDelete.id);
                        if (this.selectedCategory === this.categoryToDelete.id) {
                            this.selectedCategory = null;
                        }
                        this.showDeleteConfirm = false;
                        this.categoryToDelete = null;
                        this.showToast('Category deleted successfully', 'success');
                    } catch (error) {
                        console.error('Error deleting category:', error);
                        this.showToast('Error deleting category', 'error');
                    }
                },
                
                cancelDelete() {
                    this.showDeleteConfirm = false;
                    this.categoryToDelete = null;
                },
                
                async deleteCategory(categoryId) {
                    if (!confirm('Are you sure you want to delete this category? This will also delete all documents in this category.')) {
                        return;
                    }
                    
                    try {
                        const response = await fetch(`/api/categories/${categoryId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Delete error:', errorData);
                            this.showToast(`Error: ${errorData.message || 'Failed to delete category'}`, 'error');
                            return;
                        }
                        
                        this.categories = this.categories.filter(c => c.id !== categoryId);
                        this.documents = this.documents.filter(doc => doc.category_id !== categoryId);
                        if (this.selectedCategory === categoryId) {
                            this.selectedCategory = null;
                        }
                        this.showToast('Category deleted successfully', 'success');
                    } catch (error) {
                        console.error('Error deleting category:', error);
                        this.showToast('Error deleting category', 'error');
                    }
                },
                
                async addDocument() {
                    if (!this.newDocument.document_name.trim() || !this.newDocument.url.trim() || !this.newDocument.category_id) {
                        this.showToast('Please fill in all required fields', 'error');
                        return;
                    }
                    
                    this.addDocumentLoading = true;
                    
                    const category = this.categories.find(c => c.id == this.newDocument.category_id);
                    
                    try {
                        const response = await fetch('/api/documents', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                category_id: this.newDocument.category_id,
                                category_name: category.category_name,
                                document_name: this.newDocument.document_name,
                                url: this.newDocument.url,
                                document_year: this.newDocument.document_year,
                                document_month_id: this.newDocument.document_month_id
                            })
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Error adding document:', errorData);
                            this.showToast(`Error: ${errorData.message || 'Failed to add document'}`, 'error');
                            return;
                        }
                        
                        const newDoc = await response.json();
                        
                        // Insert document in sorted position (by year descending, then month descending)
                        const insertIndex = this.documents.findIndex(doc => 
                            doc.document_year < newDoc.document_year || 
                            (doc.document_year === newDoc.document_year && doc.document_month_id < newDoc.document_month_id)
                        );
                        
                        if (insertIndex === -1) {
                            // Insert at the end if no suitable position found
                            this.documents.push(newDoc);
                            this.allDocuments.push(newDoc);
                        } else {
                            // Insert at the correct position
                            this.documents.splice(insertIndex, 0, newDoc);
                            this.allDocuments.splice(insertIndex, 0, newDoc);
                        }
                        
                        this.totalDocumentsCount++;
                        
                        // Set the new document ID for highlighting
                        this.newDocumentId = newDoc.id;
                        
                        // Clear the highlight after 3 seconds
                        setTimeout(() => {
                            this.newDocumentId = null;
                        }, 3000);
                        
                        // Update category counts
                        const categoryIndex = this.categoriesWithDocuments.findIndex(c => c.id == category.id);
                        if (categoryIndex !== -1) {
                            this.categoriesWithDocuments[categoryIndex].documents_count++;
                        }
                        
                        this.resetNewDocument();
                        this.showAddDocument = false;
                        this.showToast('Document added successfully', 'success');
                    } catch (error) {
                        console.error('Error adding document:', error);
                        this.showToast('Error adding document', 'error');
                    } finally {
                        this.addDocumentLoading = false;
                    }
                },
                
                editDocument(document) {
                    this.editingDocument = { 
                        id: document.id,
                        category_id: document.category_id || (document.category ? document.category.id : ''),
                        document_name: document.document_name,
                        url: document.url,
                        document_year: document.document_year || '',
                        document_month_id: document.document_month_id || ''
                    };
                    this.showEditDocument = true;
                },
                
                async updateDocument() {
                    if (!this.editingDocument.document_name.trim() || !this.editingDocument.url.trim() || !this.editingDocument.category_id) {
                        this.showToast('Please fill in all required fields', 'error');
                        return;
                    }
                    
                    this.editDocumentLoading = true;
                    
                    try {
                        const response = await fetch(`/api/documents/${this.editingDocument.id}`, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                category_id: this.editingDocument.category_id,
                                document_name: this.editingDocument.document_name,
                                url: this.editingDocument.url,
                                document_year: this.editingDocument.document_year,
                                document_month_id: this.editingDocument.document_month_id
                            })
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Error updating document:', errorData);
                            this.showToast(`Error: ${errorData.message || 'Failed to update document'}`, 'error');
                            return;
                        }
                        
                        const updatedDocument = await response.json();
                        
                        // Remove the old document from the array
                        this.documents = this.documents.filter(doc => doc.id !== updatedDocument.id);
                        
                        // Insert the updated document in the correct sorted position (by year descending, then month descending)
                        console.log('Repositioning document - Year:', updatedDocument.document_year, 'Month:', updatedDocument.document_month_id);
                        
                        const insertIndex = this.documents.findIndex(doc => {
                            // Find first document that should come after the updated document
                            // Documents are sorted by year descending, then month descending
                            if (doc.document_year < updatedDocument.document_year) {
                                // Current doc has lower year, so updated doc should come before it
                                return true;
                            }
                            if (doc.document_year === updatedDocument.document_year && 
                                doc.document_month_id < updatedDocument.document_month_id) {
                                // Same year but lower month, so updated doc should come before it
                                return true;
                            }
                            return false;
                        });
                        
                        console.log('Insert index:', insertIndex);
                        
                        if (insertIndex === -1) {
                            // No document found that should come after, so add to end (lowest year/month)
                            this.documents.push(updatedDocument);
                        } else {
                            // Insert at the calculated position
                            this.documents.splice(insertIndex, 0, updatedDocument);
                        }
                        
                        // Add highlight animation to repositioned document
                        this.$nextTick(() => {
                            const rowElement = document.querySelector(`[data-document-id="${updatedDocument.id}"]`);
                            if (rowElement) {
                                rowElement.classList.add('updated-document-highlight');
                                setTimeout(() => {
                                    rowElement.classList.remove('updated-document-highlight');
                                }, 2000);
                            }
                        });
                        
                        this.showEditDocument = false;
                        this.showToast('Document updated successfully', 'success');
                    } catch (error) {
                        console.error('Error updating document:', error);
                        this.showToast('Error updating document', 'error');
                    } finally {
                        this.editDocumentLoading = false;
                    }
                },
                
                async deleteDocument(documentId) {
                    const document = this.documents.find(doc => doc.id === documentId);
                    if (!document) return;
                    
                    this.documentToDelete = document;
                    this.showDeleteDocumentConfirm = true;
                },
                
                async deleteDocumentConfirmed() {
                    if (!this.documentToDelete) return;
                    
                    try {
                        const response = await fetch(`/api/documents/${this.documentToDelete.id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        });
                        
                        if (!response.ok) {
                            const errorData = await response.json();
                            console.error('Error deleting document:', errorData);
                            this.showToast(`Error: ${errorData.message || 'Failed to delete document'}`, 'error');
                            return;
                        }
                        
                        this.documents = this.documents.filter(doc => doc.id !== this.documentToDelete.id);
                        this.showDeleteDocumentConfirm = false;
                        this.documentToDelete = null;
                        this.showToast('Document deleted successfully', 'success');
                    } catch (error) {
                        console.error('Error deleting document:', error);
                        this.showToast('Error deleting document', 'error');
                    }
                },
                
                cancelDeleteDocument() {
                    this.showDeleteDocumentConfirm = false;
                    this.documentToDelete = null;
                },
                
                toggleSelectAll(event) {
                    if (event.target.checked) {
                        this.selectedDocuments = this.filteredDocuments.map(doc => doc.id);
                    } else {
                        this.selectedDocuments = [];
                    }
                },
                
                resetNewDocument() {
                    this.newDocument = {
                        category_id: '',
                        document_name: '',
                        url: '',
                        document_year: '',
                        document_month_id: ''
                    };
                },
                
                resetNewCategory() {
                    this.newCategory = {
                        category_name: '',
                        description: '',
                        color: '#6B46C1',
                        icon: 'folder'
                    };
                },
                
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    if (this.darkMode) {
                        document.body.classList.add('dark');
                    } else {
                        document.body.classList.remove('dark');
                    }
                    // Save to localStorage
                    localStorage.setItem('darkMode', this.darkMode.toString());
                },
                
                getCategoryColor(categoryName) {
                    const category = this.categories.find(c => c.category_name === categoryName);
                    return category ? category.color : '#6B46C1';
                },
                
                formatDate(dateString) {
                    if (!dateString) return '';
                    const date = new Date(dateString);
                    return date.toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'short', 
                        day: 'numeric' 
                    });
                },
                
                showToast(message, type = 'info') {
                    // Enhanced toast notification with animations
                    const toast = document.createElement('div');
                    const bgColor = type === 'success' ? 'bg-gradient-to-r from-green-500 to-emerald-600' : 
                                   type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-600' : 
                                   'bg-gradient-to-r from-blue-500 to-indigo-600';
                    
                    const icon = type === 'success' ? 'fa-check-circle' : 
                                type === 'error' ? 'fa-exclamation-circle' : 
                                'fa-info-circle';
                    
                    toast.className = `toast-notification fixed top-4 right-4 px-6 py-4 rounded-lg text-white z-50 ${bgColor} flex items-center space-x-3 min-w-[300px]`;
                    toast.style.transition = 'opacity 1s ease-out';
                    toast.innerHTML = `
                        <i class="fas ${icon} text-xl"></i>
                        <div class="flex-1">
                            <p class="font-semibold">${type.charAt(0).toUpperCase() + type.slice(1)}</p>
                            <p class="text-sm opacity-90">${message}</p>
                        </div>
                        <button onclick="this.parentElement.style.opacity='0'; setTimeout(() => this.parentElement.remove(), 1000)" class="ml-4 hover:opacity-75 transition-opacity">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    
                    document.body.appendChild(toast);
                    
                    // Auto-remove after 5 seconds
                    setTimeout(() => {
                        if (toast && toast.parentElement) {
                            toast.style.opacity = '0';
                            setTimeout(() => {
                                if (toast && toast.parentElement) {
                                    toast.parentElement.removeChild(toast);
                                }
                            }, 1000);
                        }
                    }, 5000);
                },
                
                toggleSidebar() {
                    this.sidebarOpen = !this.sidebarOpen;
                },
                
                // Filter methods
                handleFilterChange(event) {
                    console.log('Filter change triggered:', event?.target?.name, this.filters);
                    
                    // Prevent any default behavior that might cause page refresh
                    if (event) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    // Reset to first page when filters change
                    this.currentPage = 1;
                    
                    this.filterLoading = true;
                    this.applyFilters();
                },
                
                handleSearchInput() {
                    // Clear any existing search loading timer
                    if (this.searchLoadingTimer) {
                        clearTimeout(this.searchLoadingTimer);
                        this.searchLoadingTimer = null;
                    }
                    
                    // Check if this is a search-only filter change
                    const isSearchOnly = this.filters.search && 
                        !this.filters.category_id && 
                        !this.filters.year && 
                        !this.filters.month;
                    
                    if (isSearchOnly) {
                        // Set a timer to show loading after 2 seconds for search
                        this.searchLoadingTimer = setTimeout(() => {
                            this.searchLoading = true;
                        }, 2000);
                    } else {
                        // Show loading immediately for other filters
                        this.loading = true;
                    }
                    
                    this.applyFilters();
                },
                
                handleSearchEnter() {
                    // Clear any existing timer and show loading immediately
                    if (this.searchLoadingTimer) {
                        clearTimeout(this.searchLoadingTimer);
                        this.searchLoadingTimer = null;
                    }
                    this.searchLoading = true;
                    this.applyFilters();
                },
                
                async applyFilters() {
                    console.log('Applying filters:', this.filters);
                    
                    try {
                        const params = new URLSearchParams();
                        
                        if (this.filters.category_id) params.append('category_id', this.filters.category_id);
                        if (this.filters.year) params.append('year', this.filters.year);
                        if (this.filters.month) params.append('month', this.filters.month);
                        if (this.filters.search) params.append('search', this.filters.search);
                        
                        // Use the correct route URL
                        const response = await fetch('/filetracker?' + params.toString(), {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        
                        console.log('Filter response status:', response.status);
                        
                        if (response.ok) {
                            const data = await response.json();
                            console.log('Filter data received:', data);
                            
                            this.documents = data.documents;
                            this.totalDocumentsCount = data.totalDocuments;
                            this.categoriesWithDocuments = data.categoriesWithDocuments;
                            this.uncategorizedDocuments = data.uncategorizedDocuments;
                            
                            // Update URL without page refresh
                            const url = '/filetracker?' + params.toString();
                            window.history.pushState({}, '', url);
                            
                            console.log('Filters applied successfully');
                        } else {
                            console.error('Filter request failed with status:', response.status);
                            const errorText = await response.text();
                            console.error('Error response:', errorText);
                            this.showToast('Error applying filters. Please try again.', 'error');
                        }
                    } catch (error) {
                        console.error('Filter error:', error);
                        this.showToast('Error applying filters. Please check your connection.', 'error');
                        
                        // Don't refresh the page - just show error and keep current state
                        // This prevents unwanted page refreshes
                    } finally {
                        // Clear search loading timer and states
                        if (this.searchLoadingTimer) {
                            clearTimeout(this.searchLoadingTimer);
                            this.searchLoadingTimer = null;
                        }
                        this.loading = false;
                        this.searchLoading = false;
                        this.filterLoading = false;
                    }
                },
                
                clearFilters() {
                    // Set loading state for animation
                    this.filterLoading = true;
                    
                    setTimeout(() => {
                        this.filters = {
                            category_id: '',
                            year: '',
                            month: '',
                            search: ''
                        };
                        this.applyFilters();
                    }, 200); // Brief delay for visual feedback
                },
                
                removeFilter(key) {
                    // Set loading state for animation
                    this.filterLoading = true;
                    this.removingFilter = key; // Set the filter being removed
                    
                    // Allow time for the leave animation to play
                    setTimeout(() => {
                        if (key === 'category_id') {
                            this.filters.category_id = '';
                        } else if (key === 'year') {
                            this.filters.year = '';
                        } else if (key === 'month') {
                            this.filters.month = '';
                        } else if (key === 'search') {
                            this.filters.search = '';
                        }
                        this.removingFilter = null; // Reset after removal
                        this.applyFilters();
                    }, 200); // 200ms matches the transition duration
                },
                
                hasActiveFilters() {
                    return Object.values(this.filters).some(value => value !== '');
                },
                
                getActiveFilters() {
                    return Object.fromEntries(
                        Object.entries(this.filters).filter(([key, value]) => value !== '')
                    );
                },
                
                getFilterLabel(key) {
                    const labels = {
                        category_id: 'Category',
                        year: 'Year',
                        month: 'Month',
                        search: 'Search'
                    };
                    return labels[key] || key;
                },
                
                getFilterValue(key, value) {
                    if (key === 'category_id') {
                        return this.getCategoryName(value);
                    } else if (key === 'month') {
                        return this.getMonthName(value);
                    }
                    return value;
                },
                
                getCategoryName(categoryId) {
                    const category = this.categories.find(cat => cat.id == categoryId);
                    return category ? category.category_name : 'Unknown';
                },
                
                getMonthName(monthId) {
                    const month = this.months.find(m => m.id == monthId);
                    return month ? month.name : 'Unknown';
                },
                
                formatDocumentDate(dateString) {
                    return 'N/A'; // No longer needed since we removed date field
                },
                
                formatMonth(monthNumber) {
                    if (!monthNumber) return 'N/A';
                    const months = ['', 'January', 'February', 'March', 'April', 'May', 'June', 
                                   'July', 'August', 'September', 'October', 'November', 'December'];
                    return months[monthNumber] || 'N/A';
                },
                
                // Helper function to convert month name or number to ID
                getMonthIdByName(monthInput) {
                    if (!monthInput) return '';
                    
                    const trimmedInput = monthInput.toString().trim();
                    
                    // If it's a number, find month by month_number
                    if (!isNaN(trimmedInput)) {
                        const monthNum = parseInt(trimmedInput);
                        if (monthNum >= 1 && monthNum <= 12) {
                            const month = this.months.find(m => m.month_number === monthNum);
                            return month ? month.id : '';
                        }
                    }
                    
                    // If it's a name, find month by name (case-insensitive)
                    const month = this.months.find(m => 
                        m.name.toLowerCase() === trimmedInput.toLowerCase()
                    );
                    
                    return month ? month.id : '';
                },
                
                // Helper function to get month name by ID
                getMonthNameById(monthId) {
                    if (!monthId) return 'N/A';
                    const month = this.months.find(m => m.id == monthId);
                    return month ? month.name : 'N/A';
                },
                
                // Initialize dark mode on page load
                init() {
                    if (this.darkMode) {
                        document.body.classList.add('dark');
                    } else {
                        document.body.classList.remove('dark');
                    }
                }
            }
        }
    </script>
</body>
</html>
