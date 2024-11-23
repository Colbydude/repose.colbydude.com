<?php

namespace App\Console\Commands;

use App\Mail\SecretSantaMail;
use App\Models\SecretSanta2023;
use App\Models\SecretSanta2024;
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
        $santas = SecretSanta2024::with('user')->get();

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
        $santaUserIds = $santas->pluck('user_id')->toArray();
        $addressMap = $santas->pluck('address', 'user_id');
        $potentialMatches = $santaUserIds;

        // Retrieve last year's matches based on user_id
        $lastYearMatches = SecretSanta2023::whereIn('user_id', $santaUserIds)
            ->get(['user_id', 'match_id'])
            ->pluck('match_id', 'user_id')
            ->toArray();

        $attemptCount = 0;
        $maxAttempts = 100;

        do {
            $badMatches = false;
            shuffle($potentialMatches);
            $attemptCount++;

            foreach ($santaUserIds as $index => $santaUserId) {
                $matchedUserId = $potentialMatches[$index];

                // Check for self-match, same address, and last year's match.
                if (
                    $santaUserId == $matchedUserId ||
                    $addressMap[$santaUserId] == $addressMap[$matchedUserId] ||
                    (array_key_exists($santaUserId, $lastYearMatches) && $lastYearMatches[$santaUserId] == $matchedUserId)
                ) {
                    $badMatches = true;
                    $this->info("Rematching...");
                    break;
                }
            }

            if ($attemptCount > $maxAttempts) {
                $this->error("Unable to resolve matches within $maxAttempts attempts.");
                return;
            }
        } while ($badMatches);

        $this->info("Writing matches to DB...");

        foreach ($santas as $index => $santa) {
            $santa->match_id = $santas->firstWhere('user_id', $potentialMatches[$index])->id;
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
