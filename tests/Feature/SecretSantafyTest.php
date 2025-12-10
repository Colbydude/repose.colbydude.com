<?php

use App\Mail\SecretSantaMail;
use App\Models\SecretSanta2024;
use App\Models\SecretSanta2025;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('matches everyone, respects address and last-year constraints, and sends emails', function () {
    // Tested approximately 50 times, and RNG can lead to impossible scenarios,
    // this sets us to a deterministic, successful, seed.
    mt_srand(12345);

    Mail::fake();

    $users = User::factory()->count(6)->create();

    // Addresses: 2 people at address A, 2 at B, and 2 different C & D
    $addresses = [
        'A',
        'A',
        'B',
        'B',
        'C',
        'D',
    ];

    $santas = collect($users)->values()->map(function ($user, $i) use ($addresses) {
        return SecretSanta2025::factory()->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'address' => $addresses[$i],
        ]);
    });

    // Set up some previous matches so we can assert they're avoided.
    SecretSanta2024::factory()->create([
        'user_id' => $users[0]->id,
        'match_id' => $users[1]->id,
    ]);
    SecretSanta2024::factory()->create([
        'user_id' => $users[2]->id,
        'match_id' => $users[3]->id,
    ]);

    // Run the command.
    $this->artisan('secretsantafy')->assertSuccessful();

    // Reload santas from DB to see saved match_id values.
    $santas = SecretSanta2025::with('user')->get();

    // Ensure everyone got a saved match_id and the matches respect constraints.
    expect($santas->count())->toBe(6);

    $lastYearMap = SecretSanta2024::whereIn('user_id', $santas->pluck('user_id')->toArray())
        ->get(['user_id', 'match_id'])
        ->pluck('match_id', 'user_id')
        ->toArray();

    foreach ($santas as $santa) {
        // match_id must be present and point to an existing id.
        expect($santa->match_id)->not->toBeNull();
        $matched = $santas->firstWhere('id', $santa->match_id);
        expect($matched)->not->toBeNull();

        // Not a self match.
        expect($santa->user_id)->not->toBe($matched->user_id);

        // Not the same address.
        expect($santa->address)->not->toBe($matched->address);

        // Not last year's match (if last year existed).
        if (isset($lastYearMap[$santa->user_id])) {
            expect($lastYearMap[$santa->user_id])->not->toBe($matched->user_id);
        }
    }

    // Ensure an email was sent to each participant, with the correct mailable.
    Mail::assertSent(SecretSantaMail::class, 6);

    // Assert each participant got exactly 1 email.
    foreach ($santas as $santa) {
        Mail::assertSent(SecretSantaMail::class, function ($mail) use ($santa) {
            return $mail->hasTo($santa->email);
        });
    }
});

it('throws when constraints make matching impossible (everyone at same address)', function () {
    $users = User::factory()->count(2)->create();

    SecretSanta2025::factory()->create([
        'user_id' => $users[0]->id,
        'email' => $users[0]->email,
        'address' => 'SAME_ADDRESS',
    ]);

    SecretSanta2025::factory()->create([
        'user_id' => $users[1]->id,
        'email' => $users[1]->email,
        'address' => 'SAME_ADDRESS',
    ]);

    $this->assertThrows(
        fn() => $this->artisan('secretsantafy')
    );
});

it('throws when last-year constraint eliminates all possible receivers', function () {
    // Example: Three users where one matched with another the previous year,
    //          and the others live at the same address.
    $users = User::factory()->count(3)->create();

    SecretSanta2025::factory()->create([
        'user_id' => $users[0]->id,
        'email' => $users[0]->email,
        'address' => 'ADDRESS1',
    ]);
    SecretSanta2025::factory()->create([
        'user_id' => $users[1]->id,
        'email' => $users[1]->email,
        'address' => 'ADDRESS2',
    ]);
    SecretSanta2025::factory()->create([
        'user_id' => $users[2]->id,
        'email' => $users[2]->email,
        'address' => 'ADDRESS2',
    ]);

    SecretSanta2024::factory()->create([
        'user_id' => $users[0]->id,
        'match_id' => $users[1]->id,
    ]);

    $this->assertThrows(
        fn() => $this->artisan('secretsantafy')
    );
});
