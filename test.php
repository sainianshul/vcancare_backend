<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
try {
    $nurseId = App\Models\NurseProfile::first()->user_id;
    $user = App\Models\User::first();
    if($user) { Auth::login($user); }
    $req = Illuminate\Http\Request::create('/admin/nurses/'.$nurseId.'/bids/data', 'GET');
    $req->headers->set('X-Requested-With', 'XMLHttpRequest');
    $res = app()->handle($req);
    echo $res->getContent();
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
