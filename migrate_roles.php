<?php
use App\Models\User;
use Spatie\Permission\Models\Role;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = User::all();
foreach ($users as $user) {
    if ($user->hasRole('candidat')) {
        $user->assignRole('demandeur');
        $user->removeRole('candidat');
        echo "User {$user->email} migrated to demandeur role.\n";
    }
}
