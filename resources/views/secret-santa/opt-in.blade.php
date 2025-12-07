@extends('layouts.app')

@section('content')
    <div class="relative max-w-xl mx-auto">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-primary sm:text-4xl">
                Discord Secret Santa
            </h2>
            <p class="mt-4 text-lg leading-6 text-secondary text-justify">
                We're holding our annual Discord Secret Santa! Fill out the form below to opt-in. On <strong>{{ \App\Http\Controllers\SecretSantaController::CUTOFF_DATE }}</strong>, you'll be sent an email with information on your match for the gift exchange. The recommended spending limit is $50 and digital purchases are highly encouraged. Try and get your gift sent before Christmas, please!!
            </p>
        </div>
        <div class="mt-12">
            <form action="{{ route('secret-santa.opt-in.post') }}" method="POST" class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                @csrf

                <div>
                    <x-label for="first_name">First name</x-label>
                    <x-input id="first_name" name="first_name" autocomplete="given-name" value="{{ $secretSantaRecord->first_name }}" required />
                </div>
                <div>
                    <x-label for="last_name">Last name</x-label>
                    <x-input id="last_name" name="last_name" autocomplete="family-name" value="{{ $secretSantaRecord->last_name }}" required />
                </div>
                <div class="sm:col-span-2">
                    <x-label for="email">Email</x-label>
                    <x-input type="email" id="email" name="email" autocomplete="email" value="{{ $secretSantaRecord->email }}" required />
                </div>
                <div class="sm:col-span-2">
                    <x-label for="address">Mailing Address</x-label>
                    <x-textarea id="address" name="address" rows="2" required>{{ $secretSantaRecord->address }}</x-textarea>
                </div>
                <div class="sm:col-span-2">
                    <x-label for="message">List off some things you might like for Christmas or link an Amazon Wishlist or something similar. Help out your Secret Santa to get something super cool for you!</x-label>
                    <x-textarea id="message" name="message" rows="4" required>{{ $secretSantaRecord->message }}</x-textarea>
                </div>
                <div class="sm:col-span-2 mb-6">
                    <x-button variant="accent" type="submit" class="w-full">
                        Merry Christmas!!
                    </x-button>
                </div>
            </form>
        </div>
    </div>
@stop
