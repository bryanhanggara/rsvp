<x-mail::message>
{{-- Logo Header --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{url('img/avatar/logo_kse.jpeg')}}" alt="Logo" style="width: 150px;">
</div>

{{-- Custom Greeting --}}
# Hai Beswan! 🎓

Untuk bisa menggunakan website ini sepenuhnya, kamu harus **verifikasi email terlebih dahulu** ya. Yuk klik tombol di bawah ini!

{{-- Action Button --}}
@isset($actionText)
<?php
    $color = match ($level) {
        'success', 'error' => $level,
        default => 'primary',
    };
?>
<x-mail::button :url="$actionUrl" :color="$color">
{{ $actionText }}
</x-mail::button>
@endisset

{{-- Outro --}}
Terima kasih sudah bergabung bersama kami. Kalau ada kendala, hubungi kami kapan saja.

Salam hangat,  
Eksternal PKSE UNSRI

{{-- Subcopy --}}
@isset($actionText)
<x-slot:subcopy>
Jika tombol tidak bisa diklik, salin dan tempel URL berikut ke browser kamu:

<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>
@endisset
</x-mail::message>
