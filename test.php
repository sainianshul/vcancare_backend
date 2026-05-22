<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    $user = App\Models\User::first();
    $service = app()->make(App\Services\SupportService::class);
    $ticket = $service->createTicket($user, [
        'category' => 'technical',
        'subject' => 'App keeps crashing',
        'description' => 'My app crashes on startup',
        'priority' => 2,
    ]);
    echo "Created Ticket: " . $ticket->reference_id . "\n";
    $service->addMessage($ticket, $user, 'Please help!');
    echo "Added message\n";
    $admin = App\Models\User::whereHas('roles', function($q) { $q->where('name', 'Admin'); })->first();
    if($admin) {
        $service->addMessage($ticket, $admin, 'We are looking into this.', [], true);
        echo "Added admin reply\n";
    }
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
