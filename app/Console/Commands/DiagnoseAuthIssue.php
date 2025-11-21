<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DiagnoseAuthIssue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:diagnose-auth';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose authentication issues in production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Diagnosing Authentication Issues...');
        $this->newLine();

        // 1. Check database connection
        $this->info('1. Checking database connection...');
        try {
            DB::connection()->getPdo();
            $this->info('   ✅ Database connection: OK');
        } catch (\Exception $e) {
            $this->error('   ❌ Database connection: FAILED');
            $this->error('   Error: ' . $e->getMessage());
            return 1;
        }

        // 2. Check users table
        $this->info('2. Checking users table...');
        try {
            $userCount = DB::table('users')->count();
            $this->info("   ✅ Users table exists with {$userCount} users");
        } catch (\Exception $e) {
            $this->error('   ❌ Users table: ERROR');
            $this->error('   Error: ' . $e->getMessage());
            return 1;
        }

        // 3. Check APP_KEY
        $this->info('3. Checking APP_KEY...');
        if (empty(config('app.key'))) {
            $this->error('   ❌ APP_KEY is not set!');
            $this->warn('   Run: php artisan key:generate --force');
            return 1;
        } else {
            $this->info('   ✅ APP_KEY is set');
        }

        // 4. Check password hashing
        $this->info('4. Testing password hashing...');
        $testPassword = 'test123';
        $hashedPassword = Hash::make($testPassword);
        $this->info("   Test password: {$testPassword}");
        $this->info("   Hashed: " . substr($hashedPassword, 0, 30) . '...');
        
        if (Hash::check($testPassword, $hashedPassword)) {
            $this->info('   ✅ Password hashing: OK');
        } else {
            $this->error('   ❌ Password hashing: FAILED');
            return 1;
        }

        // 5. Check recent users
        $this->info('5. Checking recent users...');
        $recentUsers = User::latest()->take(5)->get(['id', 'name', 'email', 'created_at']);
        if ($recentUsers->isEmpty()) {
            $this->warn('   ⚠️  No users found in database');
        } else {
            $this->table(
                ['ID', 'Name', 'Email', 'Created At'],
                $recentUsers->map(fn($u) => [
                    $u->id,
                    $u->name,
                    $u->email,
                    $u->created_at->format('Y-m-d H:i:s')
                ])
            );
        }

        // 6. Check invitations
        $this->info('6. Checking invitations...');
        try {
            $invitationCount = DB::table('invitations')->count();
            $pendingCount = DB::table('invitations')->where('status', 'pending')->count();
            $this->info("   ✅ Total invitations: {$invitationCount}");
            $this->info("   ✅ Pending invitations: {$pendingCount}");
        } catch (\Exception $e) {
            $this->error('   ❌ Invitations table: ERROR');
            $this->error('   Error: ' . $e->getMessage());
        }

        // 7. Test user authentication
        $this->newLine();
        if ($this->confirm('Do you want to test authentication with an existing user?', false)) {
            $email = $this->ask('Enter user email');
            $password = $this->secret('Enter password');

            $user = User::where('email', $email)->first();
            
            if (!$user) {
                $this->error("   ❌ User not found: {$email}");
            } else {
                $this->info("   ✅ User found: {$user->name}");
                
                if (Hash::check($password, $user->password)) {
                    $this->info('   ✅ Password verification: SUCCESS');
                } else {
                    $this->error('   ❌ Password verification: FAILED');
                    $this->warn('   This indicates a password mismatch or hashing issue');
                }
            }
        }

        // 8. Offer to create test user
        $this->newLine();
        if ($this->confirm('Do you want to create a test user?', false)) {
            $testEmail = $this->ask('Enter test user email', 'test@example.com');
            $testPassword = $this->secret('Enter test user password');
            $testName = $this->ask('Enter test user name', 'Test User');

            try {
                $testUser = User::create([
                    'name' => $testName,
                    'email' => $testEmail,
                    'password' => Hash::make($testPassword),
                    'email_verified_at' => now(),
                ]);

                $this->info("   ✅ Test user created successfully!");
                $this->info("   Email: {$testEmail}");
                $this->info("   Password: {$testPassword}");
                $this->info("   Try logging in with these credentials.");

                // Verify immediately
                if (Hash::check($testPassword, $testUser->password)) {
                    $this->info('   ✅ Password verification: SUCCESS');
                } else {
                    $this->error('   ❌ Password verification: FAILED (This should not happen!)');
                }
            } catch (\Exception $e) {
                $this->error('   ❌ Failed to create test user');
                $this->error('   Error: ' . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info('🎉 Diagnosis complete!');
        
        return 0;
    }
}
