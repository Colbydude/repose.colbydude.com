@extends('layouts/app')

@section('content')
<div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
    <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
        <h1 class="text-primary text-4xl">Colbydude's Repose</h1>
    </div>
    <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
        <a href="{{ route('secret-santa.index') }}" class="text-secondary underline">Secret Santa!!</a>
    </div>
</div>
@stop
