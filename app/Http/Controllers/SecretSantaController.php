<?php

namespace App\Http\Controllers;

use App\Models\SecretSanta2024;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SecretSantaController extends Controller
{
    /**
     * The cutoff date for the secret santa.
     *
     * @var string
     */
    public const CUTOFF_DATE = 'December 2nd 2024';

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
        $secretSantaRecord = SecretSanta2024::with('match')->where('user_id', auth()->id())->first();

        if ($secretSantaRecord == null) {
            return redirect()->route('secret-santa.opt-in.get');
        }

        return view('secret-santa/index', compact('secretSantaRecord'));
    }

    /**
     * Show the secret-santa opt-in form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function getOptIn()
    {
        $secretSantaRecord = SecretSanta2024::firstOrNew(['user_id' => auth()->id()]);

        if ($secretSantaRecord->match_id != null || new Carbon(SecretSantaController::CUTOFF_DATE) < Carbon::now()) {
            return redirect()->route('home');
        }

        return view('secret-santa/opt-in', compact('secretSantaRecord'));
    }

    /**
     * Process the secret-santa opt-in form.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function postOptIn(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'message' => 'required|string',
        ]);

        SecretSanta2024::updateOrCreate(
            ['user_id' => auth()->id()],
            array_merge(
                $request->input(),
                ['user_id' => auth()->id()],
            )
        );

        return redirect()->route('secret-santa.index');
    }
}
