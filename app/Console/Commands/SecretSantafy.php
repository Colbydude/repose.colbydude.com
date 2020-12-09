<?php

namespace App\Console\Commands;

use App\Mail\SecretSantaMail;
use App\Models\SecretSanta2020;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;

class SecretSantafy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'secretsantafy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perform matching for Secret Santa';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Fetching Secret Santas...");
        $santas = SecretSanta2020::with('user')->get();

        $this->info("Matching...");
        $this->matchSantas($santas);

        $this->info("Sending emails...");
        $this->sendMatchEmails($santas);

        return 0;
    }

    /**
     * Shuffle the Secret Santa entries and match them with each other.
     * Continue to reshuffle if people with the same address match with each other.
     *
     * @param  Illuminate\Support\Collection $santas
     * @return void
     */
    private function matchSantas(Collection $santas) : void
    {
        $santaIds = $santas->pluck('id');
        $matchIds = $santaIds->shuffle();

        $badMatches = false;

        for ($i = 0; $i < count($santas); $i++) {
            $matchId = $matchIds[$i];

            $santas[$i]->match_id = $matchId;
            $match = $santas->firstWhere('id', $matchId);

            if ($santas[$i]->id == $match->id) {
                $badMatches = true;
                break;
            }

            if ($santas[$i]->address == $match->address) {
                $badMatches = true;
                break;
            }
        }

        if ($badMatches) {
            $this->line("Rematching...");
            $this->matchSantas($santas);

            return;
        }

        $this->info("Writing matches to DB...");

        foreach ($santas as $santa) {
            $santa->save();
        }
    }

    /**
     * Send out emails to let users know they've been matched for Secret Santa.
     *
     * @param  Illuminate\Support\Collection $santas
     * @return void
     */
    private function sendMatchEmails(Collection $santas) : void
    {
        foreach ($santas as $santa) {
            $match = $santas->firstWhere('id', $santa->match_id);
            $mailable = new SecretSantaMail($santa, $match);

            Mail::to($santa->email)
                ->send($mailable);
        }
    }
}
