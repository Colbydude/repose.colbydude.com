<?php

namespace App\Http\Controllers;

use App\Models\SecretSanta2025;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class SecretSantaController extends Controller
{
    /**
     * The cutoff date for the secret santa.
     *
     * @var string
     */
    public const CUTOFF_DATE = 'December 12th 2025';

    /**
     * Show the secret santa landing page.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $secretSantaRecord = SecretSanta2025::with('match')->where('user_id', Auth::id())->first();

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
        $secretSantaRecord = SecretSanta2025::firstOrNew(['user_id' => Auth::id()]);

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

        SecretSanta2025::updateOrCreate(
            ['user_id' => Auth::id()],
            array_merge(
                $request->input(),
                ['user_id' => Auth::id()],
            )
        );

        return redirect()->route('secret-santa.index');
    }
}
