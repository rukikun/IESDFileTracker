<!-- Notification Dropdown Content - Using Controller, Model, and Foreach -->
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

    <!-- Notification Dropdown -->
    <div id="notificationDropdown" class="hidden absolute top-full right-0 mt-2 w-96 bg-white rounded-lg shadow-2xl border border-gray-200" style="z-index: 999999;">
        
        <!-- Arrow -->
        <div class="absolute -top-2 right-4 w-4 h-4 bg-white border-l border-t border-gray-200 transform rotate-45"></div>
        
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 relative">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold text-gray-800">Notifications ({{ $notifications->count() }})</h3>
                <button onclick="hideNotificationDropdown()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 max-h-96 overflow-y-auto">
            @if($notifications->count() === 0)
                <div class="text-center text-gray-600 py-8">
                    <i class="fas fa-bell-slash mr-2 text-2xl"></i>
                    <p class="mt-2">No notifications</p>
                </div>
            @else
                <!-- Notifications using foreach loop with Model data -->
                @foreach($notifications as $notification)
                    <div class="mb-3 p-3 bg-gray-50 rounded border hover:bg-gray-100 transition-colors">
                        <div class="flex items-start space-x-3">
                            <!-- Icon based on notification type from Model -->
                            <div class="flex-shrink-0 mt-1">
                                @switch($notification->type)
                                    @case('data_edit')
                                        <i class="fas fa-edit text-blue-600 text-lg"></i>
                                        @break
                                    @case('user_login')
                                        <i class="fas fa-sign-in-alt text-green-600 text-lg"></i>
                                        @break
                                    @case('user_logout')
                                        <i class="fas fa-sign-out-alt text-orange-600 text-lg"></i>
                                        @break
                                    @default
                                        <i class="fas fa-info-circle text-gray-600 text-lg"></i>
                                @endswitch
                            </div>
                            
                            <!-- Content from Model fields -->
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-900">{{ $notification->title }}</p>
                                <p class="text-sm text-gray-600">{{ $notification->message }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>

<script>
function toggleNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('hidden');
}

function hideNotificationDropdown() {
    const dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.add('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('notificationDropdown');
    const button = event.target.closest('button[onclick="toggleNotificationDropdown()"]');
    
    if (!dropdown.contains(event.target) && !button) {
        dropdown.classList.add('hidden');
    }
});
</script>
