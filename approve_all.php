<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$updated = \App\Models\Member::where('approval_status', 'pending')
    ->update([
        'approval_status' => 'approved',
        'is_active' => true,
        'approved_at' => now(),
        'approved_by' => 1
    ]);

echo "✅ Approved {$updated} members successfully!\n\n";

$approved = \App\Models\Member::where('approval_status', 'approved')->get();
echo "Approved Members:\n";
echo str_repeat('-', 60) . "\n";
foreach($approved as $member) {
    echo "✓ {$member->full_name} ({$member->email})\n";
    echo "  Member ID: {$member->member_id}\n";
    echo "  Can login at: http://127.0.0.1:8001/member/login\n\n";
}
echo "\nNote: Approval emails were not sent via command line.\n";
echo "To send emails, approve through: http://127.0.0.1:8001/members/pending-approvals\n";
