<?php

namespace App\Console\Commands;

use App\Mail\SecretSantaMail;
use App\Models\SecretSanta2024;
use App\Models\SecretSanta2025;
use Exception;
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
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Fetching Secret Santas...");
        $santas = SecretSanta2025::with('user')->get();

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
    private function matchSantas(Collection $santas): void
    {
        $santaUserIds = $santas->pluck('user_id')->toArray();
        $addressMap = $santas->pluck('address', 'user_id');
        $lastYearMatches = SecretSanta2024::whereIn('user_id', $santaUserIds)
            ->get(['user_id', 'match_id'])
            ->pluck('match_id', 'user_id')
            ->toArray();

        $matches = [];
        $remainingReceivers = collect($santaUserIds);

        foreach ($santaUserIds as $santaUserId) {
            $validReceivers = $remainingReceivers->filter(function ($receiverId) use ($santaUserId, $addressMap, $lastYearMatches) {
                return $santaUserId !== $receiverId // Avoid self-match
                    && $addressMap[$santaUserId] !== $addressMap[$receiverId] // Avoid matching same address
                    && (!isset($lastYearMatches[$santaUserId]) || $lastYearMatches[$santaUserId] !== $receiverId); // Avoid last year's match
            });

            if ($validReceivers->isEmpty()) {
                throw new Exception("Unable to find valid matches. Adjust constraints or data.");
            }

            // Pick a random valid receiver.
            $matchId = $validReceivers->random();
            $matches[$santaUserId] = $matchId;

            // Remove the chosen receiver from the pool.
            $remainingReceivers = $remainingReceivers->reject(fn($id) => $id === $matchId);
        }

        $this->info("Writing matches to DB...");

        foreach ($santas as $santa) {
            $santa->match_id = $santas->firstWhere('user_id', $matches[$santa->user_id])->id;
            $santa->save();
        }
    }

    /**
     * Send out emails to let users know they've been matched for Secret Santa.
     *
     * @param  Illuminate\Support\Collection $santas
     * @return void
     */
    private function sendMatchEmails(Collection $santas): void
    {
        foreach ($santas as $santa) {
            $match = $santas->firstWhere('id', $santa->match_id);
            $mailable = new SecretSantaMail($santa, $match);

            Mail::to($santa->email)
                ->send($mailable);
        }
    }
}
