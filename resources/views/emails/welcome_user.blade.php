@component('mail::message')
    # Hello {{ $user->name }}!

    Welcome to our website. We're glad to have you on board!

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
