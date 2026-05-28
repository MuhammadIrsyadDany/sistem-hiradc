@props(['type' => 'success'])

@php
    $styles = [
        'success' => ['bg' => '#d4edda', 'color' => '#155724', 'border' => '#00a65a', 'icon' => 'fas fa-check-circle'],
        'danger' => ['bg' => '#f8d7da', 'color' => '#721c24', 'border' => '#dc3545', 'icon' => 'fas fa-times-circle'],
        'warning' => [
            'bg' => '#fff3cd',
            'color' => '#856404',
            'border' => '#f0a500',
            'icon' => 'fas fa-exclamation-triangle',
        ],
        'info' => ['bg' => '#e8f5ee', 'color' => '#004d2e', 'border' => '#006b3f', 'icon' => 'fas fa-info-circle'],
    ];
    $s = $styles[$type] ?? $styles['info'];
@endphp

<div style="background:{{ $s['bg'] }}; color:{{ $s['color'] }};
            border-left:4px solid {{ $s['border'] }};
            border-radius:8px; padding:12px 16px; margin-bottom:16px;
            display:flex; align-items:center; justify-content:space-between;"
    class="alert-custom">
    <div>
        <i class="{{ $s['icon'] }} mr-2"></i>
        {{ $slot }}
    </div>
    <button onclick="this.parentElement.style.display='none'"
        style="background:none; border:none; cursor:pointer;
                   color:{{ $s['color'] }}; font-size:16px; padding:0 4px;">
        &times;
    </button>
</div>
