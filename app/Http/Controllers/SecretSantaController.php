<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SecretSantaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the secret santa landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('secret-santa/index');
    }

    /**
     * Show the secret-santa opt-in form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getOptIn()
    {
        return view('secret-santa/opt-in');
    }

    /**
     * Process the secret-santa opt-in form.
     *
     * @return \Illuminate\Contracts\Support\Renderagble
     */
    public function postOptIn()
    {
        // @TODO: Process.
        dd('yup');

        return redirect()->route('secret-santa.index');
    }
}
