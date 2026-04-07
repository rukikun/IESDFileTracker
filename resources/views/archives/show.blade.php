@extends('layouts.app')

@section('title', 'Archive Entry #' . $archive->id)

@section('header', 'Archive Entry Details')

@section('content')
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('archives.index') }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Archives
        </a>
    </div>

    <!-- Archive Entry Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Archive Entry #{{ $archive->id }}</h1>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-{{ $archive->action_color }}-100 text-{{ $archive->action_color }}-800">
                <i class="{{ $archive->action_icon }} mr-2"></i>
                {{ ucfirst($archive->action) }}
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
            <div>
                <span class="font-medium text-gray-700">Date & Time:</span>
                <div class="text-gray-900">{{ $archive->created_at->format('M j, Y g:i A') }}</div>
                <div class="text-gray-500 text-xs">{{ $archive->formatted_time }}</div>
            </div>
            <div>
                <span class="font-medium text-gray-700">Document Title:</span>
                <div class="text-gray-900">{{ $archive->document_title }}</div>
            </div>
            <div>
                <span class="font-medium text-gray-700">Document Type:</span>
                <div class="text-gray-900">{{ ucfirst($archive->document_type) }}</div>
            </div>
            <div>
                <span class="font-medium text-gray-700">User:</span>
                <div class="text-gray-900">{{ $archive->user->name }}</div>
                <div class="text-gray-500 text-xs">{{ $archive->user->email }}</div>
            </div>
            <div>
                <span class="font-medium text-gray-700">IP Address:</span>
                <div class="text-gray-900">{{ $archive->ip_address ?? 'N/A' }}</div>
            </div>
            @if($archive->description)
                <div class="md:col-span-2 lg:col-span-3">
                    <span class="font-medium text-gray-700">Description:</span>
                    <div class="text-gray-900 mt-1">{{ $archive->description }}</div>
                </div>
            @endif
        </div>
    </div>

    <!-- Data Changes -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Data Changes</h3>
        </div>
        
        <div class="p-6">
            @if($archive->action === 'created')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <h4 class="text-green-800 font-medium mb-2">
                        <i class="fas fa-plus-circle mr-2"></i>New Document Created
                    </h4>
                    @if($archive->new_data)
                        <div class="mt-3">
                            <h5 class="text-sm font-medium text-green-700 mb-2">Document Data:</h5>
                            <pre class="bg-white border border-green-200 rounded p-3 text-xs overflow-x-auto">{{ json_encode($archive->new_data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>

            @elseif($archive->action === 'deleted')
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="text-red-800 font-medium mb-2">
                        <i class="fas fa-trash-circle mr-2"></i>Document Deleted
                    </h4>
                    @if($archive->old_data)
                        <div class="mt-3">
                            <h5 class="text-sm font-medium text-red-700 mb-2">Deleted Document Data:</h5>
                            <pre class="bg-white border border-red-200 rounded p-3 text-xs overflow-x-auto">{{ json_encode($archive->old_data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                    @endif
                </div>

            @elseif($archive->action === 'edited')
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-blue-800 font-medium mb-2">
                        <i class="fas fa-edit-circle mr-2"></i>Document Edited
                    </h4>
                    
                    @if($archive->old_data && $archive->new_data)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-3">
                            <!-- Old Data -->
                            <div>
                                <h5 class="text-sm font-medium text-blue-700 mb-2">Previous Data:</h5>
                                <pre class="bg-white border border-blue-200 rounded p-3 text-xs overflow-x-auto">{{ json_encode($archive->old_data, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                            
                            <!-- New Data -->
                            <div>
                                <h5 class="text-sm font-medium text-blue-700 mb-2">Updated Data:</h5>
                                <pre class="bg-white border border-blue-200 rounded p-3 text-xs overflow-x-auto">{{ json_encode($archive->new_data, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>

                        <!-- Changes Summary -->
                        <div class="mt-4 p-3 bg-blue-100 rounded">
                            <h5 class="text-sm font-medium text-blue-800 mb-2">Changes Summary:</h5>
                            <div class="text-xs text-blue-700">
                                @php
                                    $changes = [];
                                    $oldData = $archive->old_data;
                                    $newData = $archive->new_data;
                                    
                                    if (is_array($oldData) && is_array($newData)) {
                                        foreach ($newData as $key => $newValue) {
                                            $oldValue = $oldData[$key] ?? null;
                                            if ($oldValue !== $newValue) {
                                                $changes[] = "<strong>{$key}:</strong> " . 
                                                           ($oldValue ? "'{$oldValue}'" : 'null') . 
                                                           " → " . 
                                                           ($newValue !== null ? "'{$newValue}'" : 'null');
                                            }
                                        }
                                        
                                        // Check for removed keys
                                        foreach ($oldData as $key => $oldValue) {
                                            if (!array_key_exists($key, $newData)) {
                                                $changes[] = "<strong>{$key}:</strong> '{$oldValue}' → <em>removed</em>";
                                            }
                                        }
                                    }
                                @endphp
                                
                                @if(count($changes) > 0)
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($changes as $change)
                                            <li>{!! $change !!}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <em>No detectable changes in data structure</em>
                                @endif
                            </div>
                        </div>
                    @else
                        <p class="text-blue-700 text-sm">No detailed data comparison available.</p>
                    @endif
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-gray-700">No detailed data available for this action type.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
