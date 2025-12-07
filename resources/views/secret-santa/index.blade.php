@extends('layouts/app')

@section('content')
    <div class="relative max-w-xl mx-auto">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">
                Discord Secret Santa
            </h2>
            @if ($secretSantaRecord->match != null)
                <h3 class="text-xl font-extrabold tracking-tight text-secondary sm:text-2xl">
                    You've been matched!
                </h3>
                <div class="my-4 py-10 px-6 bg-gray-200 text-gray-600 dark:bg-gray-800 dark:text-gray-400 text-left rounded-md">
                    <strong class="text-primary">Name:</strong><br />
                    {{ $secretSantaRecord->match->first_name }} {{ $secretSantaRecord->match->last_name }} | {{ $secretSantaRecord->match->user->username }}<br />
                    <br />
                    <strong class="text-primary">Email:</strong><br />
                    {{ $secretSantaRecord->match->email }}<br />
                    <br />
                    <strong class="text-primary">Address:</strong><br />
                    {{ $secretSantaRecord->match->address }}<br />
                    <br />
                    <strong class="text-primary">Their message to you:</strong><br />
                    {!! \Illuminate\Mail\Markdown::parse(nl2br($secretSantaRecord->match->message)) !!}
                </div>
            @else
                @if (new \Carbon\Carbon(\App\Http\Controllers\SecretSantaController::CUTOFF_DATE) > \Carbon\Carbon::now())
                    <h3 class="text-xl font-extrabold tracking-tight text-primary sm:text-2xl">
                        You're in!
                    </h3>
                    <p class="mt-4 text-lg leading-6 text-secondary text-justify">
                        On <strong>{{ \App\Http\Controllers\SecretSantaController::CUTOFF_DATE }}</strong>, you'll be sent an email with information on your match for the gift exchange. The recommended spending limit is $50 and digital purchases are highly encouraged. Try and get your gift sent before Christmas, please!! You will also be able to visit this page to get information on your match after <strong>{{ \App\Http\Controllers\SecretSantaController::CUTOFF_DATE }}</strong>.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('secret-santa.opt-in.get') }}" class="text-secondary underline">Edit Response</a>
                    </div>
                @else
                    <h3 class="text-xl font-extrabold tracking-tight text-primary sm:text-2xl">
                        Secret Santa is closed!
                    </h3>
                @endif
            @endif
        </div>
    </div>
@stop
