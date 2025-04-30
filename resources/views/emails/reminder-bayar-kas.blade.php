<x-mail::message>
{{-- Logo Header --}}
<div style="text-align: center; margin-bottom: 20px;">
    <img src="{{ url('img/avatar/logo_kse.jpeg') }}" alt="Logo" style="width: 150px;">
</div>

{{-- Custom Greeting --}}
# Hai {{ $user->name }} 🎓

Ini adalah pengingat untuk melakukan **pembayaran kas bulanan**.  
Silakan bayar kas sebelum tanggal **5 setiap bulannya**.

{{-- Action Button --}}
<x-mail::button :url="$actionUrl">
{{ $actionText }}
</x-mail::button>

Terima kasih telah menjadi bagian dari kami!  
Salam hangat,  
Eksternal PKSE UNSRI

{{-- Subcopy --}}
<x-slot:subcopy>
Jika tombol tidak bisa diklik, salin dan tempel URL berikut ke browser kamu:  
[{{ $actionUrl }}]({{ $actionUrl }})
</x-slot:subcopy>
</x-mail::message>
