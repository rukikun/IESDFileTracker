<!-- Notification Dropdown - Direct implementation to fix data passing -->
<div class="relative">
    <!-- Notification Button -->
    <button onclick="toggleNotificationDropdown()" 
            class="relative text-white hover:text-blue-200 transition-colors p-2 rounded-lg hover:bg-blue-700"
            title="Notifications">
        <i class="fas fa-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-bold">
                {{ $unreadCount > 99 ? '99+' : $unreadCount }}
            </span>
        @endif
    </button>

    <!-- Notification Dropdown - Bigger size with filters -->
    <div id="notificationDropdown" class="hidden absolute top-full right-0 mt-2 w-[500px] rounded-lg shadow-2xl border" 
         :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'" 
         style="z-index: 999999999; position: fixed; transform: translateZ(0); isolation: isolate; will-change: transform;">
        
        <!-- Arrow -->
        <div class="absolute -top-2 right-4 w-4 h-4 border-l border-t transform rotate-45"
             :class="darkMode ? 'bg-gray-800 border-gray-700' : 'bg-white border-gray-200'"></div>
        
        <!-- Header -->
        <div class="p-4 border-b relative"
             :class="darkMode ? 'border-gray-700' : 'border-gray-200'">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-lg" :class="darkMode ? 'text-white' : 'text-gray-800'">Notifications ({{ $notifications->count() }})</h3>
                <button onclick="hideNotificationDropdown()" :class="darkMode ? 'text-gray-400 hover:text-gray-300' : 'text-gray-400 hover:text-gray-600'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Filter Tabs -->
        <div class="flex border-b" :class="darkMode ? 'border-gray-700 bg-gray-700' : 'border-gray-200 bg-gray-50'">
            <button onclick="filterNotifications('all')" 
                    id="filter-all"
                    class="flex-1 py-3 text-sm font-medium border-b-2 transition-colors"
                    :class="darkMode ? 'border-blue-500 text-blue-400 hover:bg-gray-600' : 'border-blue-500 text-blue-600 hover:bg-gray-100'">
                <i class="fas fa-inbox mr-2"></i>All
            </button>
            <button onclick="filterNotifications('data_edit')" 
                    id="filter-data_edit"
                    class="flex-1 py-3 text-sm font-medium border-b-2 border-transparent transition-colors"
                    :class="darkMode ? 'text-gray-300 hover:text-white hover:bg-gray-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100'">
                <i class="fas fa-edit mr-2"></i>Edited
            </button>
            <button onclick="filterNotifications('user_login')" 
                    id="filter-user_login"
                    class="flex-1 py-3 text-sm font-medium border-b-2 border-transparent transition-colors"
                    :class="darkMode ? 'text-gray-300 hover:text-white hover:bg-gray-600' : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100'">
                <i class="fas fa-sign-in-alt mr-2"></i>User Login
            </button>
        </div>

        <!-- Content -->
        <div class="p-4 max-h-[500px] overflow-y-auto">
            @if($notifications->count() === 0)
                <div class="text-center py-12" :class="darkMode ? 'text-gray-400' : 'text-gray-600'">
                    <i class="fas fa-bell-slash mr-3 text-4xl" :class="darkMode ? 'text-gray-500' : 'text-gray-400'"></i>
                    <p class="mt-3 text-lg" :class="darkMode ? 'text-gray-300' : ''">No notifications</p>
                    <p class="text-sm mt-1" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">You're all caught up!</p>
                </div>
            @else
                <!-- All Notifications -->
                <div id="notifications-all" class="notification-group">
                    @foreach($notifications as $notification)
                        <div class="mb-4 p-4 rounded-lg border transition-all duration-200 hover:shadow-md"
                             :class="darkMode ? 'bg-gray-700 border-gray-600 hover:bg-gray-600' : 'bg-gray-50 border-gray-200 hover:bg-gray-100'">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mt-1">
                                    @switch($notification->type)
                                        @case('data_edit')
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                 :class="darkMode ? 'bg-blue-900' : 'bg-blue-100'">
                                                <i class="fas fa-edit" :class="darkMode ? 'text-blue-400' : 'text-blue-600'"></i>
                                            </div>
                                            @break
                                        @case('user_login')
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                 :class="darkMode ? 'bg-green-900' : 'bg-green-100'">
                                                <i class="fas fa-sign-in-alt" :class="darkMode ? 'text-green-400' : 'text-green-600'"></i>
                                            </div>
                                            @break
                                        @case('user_logout')
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                 :class="darkMode ? 'bg-orange-900' : 'bg-orange-100'">
                                                <i class="fas fa-sign-out-alt" :class="darkMode ? 'text-orange-400' : 'text-orange-600'"></i>
                                            </div>
                                            @break
                                        @default
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                                 :class="darkMode ? 'bg-gray-600' : 'bg-gray-100'">
                                                <i class="fas fa-info-circle" :class="darkMode ? 'text-gray-400' : 'text-gray-600'"></i>
                                            </div>
                                    @endswitch
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-base" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $notification->title }}</p>
                                    <p class="mt-1 text-sm leading-relaxed" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{{ $notification->message }}</p>
                                    <p class="text-xs mt-2 flex items-center" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Edited Notifications Only -->
                <div id="notifications-data_edit" class="notification-group hidden">
                    @foreach($notifications->where('type', 'data_edit') as $notification)
                        <div class="mb-4 p-4 rounded-lg border transition-all duration-200 hover:shadow-md"
                             :class="darkMode ? 'bg-blue-900/30 border-blue-700 hover:bg-blue-900/50' : 'bg-blue-50 border-blue-200 hover:bg-blue-100'">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mt-1">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                         :class="darkMode ? 'bg-blue-900' : 'bg-blue-100'">
                                        <i class="fas fa-edit" :class="darkMode ? 'text-blue-400' : 'text-blue-600'"></i>
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-base" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $notification->title }}</p>
                                    <p class="mt-1 text-sm leading-relaxed" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{{ $notification->message }}</p>
                                    <p class="text-xs mt-2 flex items-center" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- User Login Notifications Only -->
                <div id="notifications-user_login" class="notification-group hidden">
                    @foreach($notifications->where('type', 'user_login')->concat($notifications->where('type', 'user_logout')) as $notification)
                        <div class="mb-4 p-4 rounded-lg border transition-all duration-200 hover:shadow-md"
                             :class="darkMode ? 'bg-green-900/30 border-green-700 hover:bg-green-900/50' : 'bg-green-50 border-green-200 hover:bg-green-100'">
                            <div class="flex items-start space-x-4">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mt-1">
                                    @if($notification->type === 'user_login')
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                             :class="darkMode ? 'bg-green-900' : 'bg-green-100'">
                                            <i class="fas fa-sign-in-alt" :class="darkMode ? 'text-green-400' : 'text-green-600'"></i>
                                        </div>
                                    @else
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center"
                                             :class="darkMode ? 'bg-orange-900' : 'bg-orange-100'">
                                            <i class="fas fa-sign-out-alt" :class="darkMode ? 'text-orange-400' : 'text-orange-600'"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-base" :class="darkMode ? 'text-white' : 'text-gray-900'">{{ $notification->title }}</p>
                                    <p class="mt-1 text-sm leading-relaxed" :class="darkMode ? 'text-gray-300' : 'text-gray-700'">{{ $notification->message }}</p>
                                    <p class="text-xs mt-2 flex items-center" :class="darkMode ? 'text-gray-400' : 'text-gray-500'">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    const button = event.currentTarget;
    const searchFilter = document.querySelector('[data-search-filter]') || document.querySelector('input[placeholder*="Search"]')?.parentElement;
    
    if (dropdown.classList.contains('hidden')) {
        // Calculate position for fixed positioning
        const rect = button.getBoundingClientRect();
        dropdown.style.top = (rect.bottom + 8) + 'px';
        dropdown.style.right = (window.innerWidth - rect.right - 29) + 'px';
        dropdown.style.left = 'auto';
        
        // Hide search filter
        if (searchFilter) {
            searchFilter.style.zIndex = '1';
            searchFilter.style.position = 'relative';
        }
    } else {
        // Show search filter when closing
        if (searchFilter) {
            searchFilter.style.zIndex = '';
            searchFilter.style.position = '';
        }
    }
    
    dropdown.classList.toggle('hidden');
    console.log('Toggle dropdown, now hidden:', dropdown.classList.contains('hidden'));
}

function hideNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    const searchFilter = document.querySelector('[data-search-filter]') || document.querySelector('input[placeholder*="Search"]')?.parentElement;
    
    // Show search filter when closing
    if (searchFilter) {
        searchFilter.style.zIndex = '';
        searchFilter.style.position = '';
    }
    
    dropdown.classList.add('hidden');
    console.log('Hide dropdown');
}

function filterNotifications(type) {
    // Hide all notification groups
    document.querySelectorAll('.notification-group').forEach(group => {
        group.classList.add('hidden');
    });
    
    // Remove active state from all tabs
    document.querySelectorAll('[id^="filter-"]').forEach(tab => {
        tab.classList.remove('border-blue-500', 'text-blue-600');
        tab.classList.add('border-transparent', 'text-gray-600');
    });
    
    // Show selected notification group
    const selectedGroup = document.getElementById('notifications-' + type);
    if (selectedGroup) {
        selectedGroup.classList.remove('hidden');
    }
    
    // Add active state to selected tab
    const selectedTab = document.getElementById('filter-' + type);
    if (selectedTab) {
        selectedTab.classList.remove('border-transparent', 'text-gray-600');
        selectedTab.classList.add('border-blue-500', 'text-blue-600');
    }
    
    console.log('Filtered notifications by:', type);
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationDropdown');
    const button = event.target.closest('button[onclick*="toggleNotificationDropdown"]');
    
    if (!dropdown.contains(event.target) && !button) {
        dropdown.classList.add('hidden');
    }
});

// Debug: Check if dropdown exists
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('notificationDropdown');
    if (dropdown) {
        console.log('✅ Notification dropdown found');
        console.log('📊 Notifications count: {{ $notifications->count() }}');
        console.log('🔔 Unread count: {{ $unreadCount }}');
    } else {
        console.log('❌ Notification dropdown NOT found');
    }
});
</script>
