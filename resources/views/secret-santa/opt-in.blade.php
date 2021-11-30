@extends('layouts.app')

@section('content')
    <div class="relative max-w-xl mx-auto">
        <div class="text-center">
            <h2 class="text-3xl font-extrabold tracking-tight text-white sm:text-4xl">
                Discord Secret Santa
            </h2>
            <p class="mt-4 text-lg leading-6 text-gray-500 text-justify">
                We're holding our second Discord Secret Santa! Fill out the form below to opt-in. On <strong>{{ \App\Http\Controllers\SecretSantaController::CUTOFF_DATE }}</strong>, you'll be sent an email with information on your match for the gift exchange. The recommended spending limit is $50 and digital purchases are highly encouraged. Try and get your gift sent before Christmas, please!!
            </p>
        </div>
        <div class="mt-12">
            <form action="{{ route('secret-santa.opt-in.post') }}" method="POST" class="grid grid-cols-1 gap-y-6 sm:grid-cols-2 sm:gap-x-8">
                @csrf

                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-300">First name</label>
                    <div class="mt-1">
                        <input type="text" name="first_name" id="first_name" autocomplete="given-name" value="{{ $secretSantaRecord->first_name }}" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" required>
                    </div>
                </div>
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-300">Last name</label>
                    <div class="mt-1">
                        <input type="text" name="last_name" id="last_name" autocomplete="family-name" value="{{ $secretSantaRecord->last_name }}" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" required>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-300">Email</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" value="{{ $secretSantaRecord->email }}" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" required>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-300">Mailing Address</label>
                    <div class="mt-1">
                        <textarea id="address" name="address" rows="2" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" required>{{ $secretSantaRecord->address }}</textarea>
                    </div>
                </div>
                <div class="sm:col-span-2">
                    <label for="message" class="block text-sm font-medium text-gray-300">List off some things you might like for Christmas or link an Amazon Wishlist or something similar. Help out your Secret Santa to get something super cool for you!</label>
                    <div class="mt-1">
                        <textarea id="message" name="message" rows="4" class="py-3 px-4 block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" required>{{ $secretSantaRecord->message }}</textarea>
                    </div>
                </div>
                <div class="sm:col-span-2 mb-6">
                    <button type="submit" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-md shadow-sm text-base font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Merry Christmas!!
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop
