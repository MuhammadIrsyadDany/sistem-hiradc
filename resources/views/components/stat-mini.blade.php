@props(['label', 'value', 'color' => '#006b3f'])

<div
    style="background:#fff; border-radius:10px; padding:14px 18px;
            box-shadow:0 2px 8px rgba(0,0,0,0.06);
            border-left:3px solid {{ $color }};
            text-align:center;">
    <div style="font-size:24px; font-weight:800; color:#1a202c;">
        {{ $value }}
    </div>
    <div
        style="font-size:11px; color:#718096; font-weight:600;
                text-transform:uppercase; letter-spacing:0.5px;">
        {{ $label }}
    </div>
</div>
