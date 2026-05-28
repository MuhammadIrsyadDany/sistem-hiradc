@props([
    'icon' => 'fas fa-inbox',
    'message' => 'Belum ada data',
    'sub' => null,
])

<div style="padding:48px 20px; text-align:center;">
    <div
        style="width:72px; height:72px; border-radius:20px;
                background:#f0faf4; display:flex; align-items:center;
                justify-content:center; margin:0 auto 16px;">
        <i class="{{ $icon }}" style="font-size:28px; color:#c6f6d5;"></i>
    </div>
    <p style="font-size:14px; font-weight:600; color:#4a5568; margin-bottom:4px;">
        {{ $message }}
    </p>
    @if ($sub)
        <p style="font-size:12px; color:#a0aec0; margin:0;">{{ $sub }}</p>
    @endif
    {{ $slot }}
</div>
