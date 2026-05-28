@props(['title', 'subtitle' => null, 'icon' => 'fas fa-circle', 'backUrl' => null, 'backText' => 'Kembali'])

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        @if ($backUrl)
            <a href="{{ $backUrl }}"
                style="width:36px; height:36px; border-radius:10px;
                      background:#e8f5ee; color:#006b3f;
                      display:flex; align-items:center;
                      justify-content:center; text-decoration:none;
                      transition:all 0.2s;"
                onmouseover="this.style.background='#006b3f';this.style.color='#fff';"
                onmouseout="this.style.background='#e8f5ee';this.style.color='#006b3f';">
                <i class="fas fa-arrow-left" style="font-size:13px;"></i>
            </a>
        @endif
        <div>
            <h1 style="font-size:20px; font-weight:700; color:#2d3748; margin:0;">
                <i class="{{ $icon }} mr-2" style="color:#006b3f;"></i>
                {{ $title }}
            </h1>
            @if ($subtitle)
                <small style="color:#a0aec0; font-size:12px;">{{ $subtitle }}</small>
            @endif
        </div>
    </div>
    <div class="d-flex align-items-center" style="gap:8px;">
        {{ $slot }}
    </div>
</div>
