@component('mail::message')
# Secret Santa - You've been matched!

Hello and thank you for opting in to the annual Secret Santa for Colbydude's Repose! You've been matched!

@component('mail::panel')
**Name:**<br />
{{ $match->first_name }} {{ $match->last_name }} | {{ $match->user->username }}

**Email:**<br />
{{ $match->email }}

**Address:**<br />
{{ $match->address }}

**Their message to you:**<br />
{{ $match->message }}
@endcomponent

You can also find your match's information at any time at the link below. If you run into hiccups, please reach out to your match ASAP. Remember to try and send your gift before Christmas!

@component('mail::button', ['url' => route('secret-santa.index')])
View Match
@endcomponent

Merry Christmas!<br />
❤️ Colbydude
@endcomponent
