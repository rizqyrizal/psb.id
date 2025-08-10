@component('mail::message')
# Halo {{ $user->name ?: 'Calon Guru' }}

Anda diundang menjadi **Guru** di {{ config('app.name') }}.

Silakan klik tombol di bawah untuk menerima undangan:

@component('mail::button', ['url' => route('agree-invitation', ['token' => $user->remember_token])])
Setuju
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
