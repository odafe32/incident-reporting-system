<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ManageUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:manage 
                            {action : The action to perform (list, create)}
                            {--role= : Filter by role or specify role for new user}
                            {--name= : Name for new user}
                            {--email= : Email for new user}
                            {--password= : Password for new user}
                            {--department= : Department for new user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage users in the system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'list':
                $this->listUsers();
                break;
            case 'create':
                $this->createUser();
                break;
            default:
                $this->error('Invalid action. Use "list" or "create".');
                return 1;
        }

        return 0;
    }

    /**
     * List users
     */
    private function listUsers()
    {
        $role = $this->option('role');
        
        $query = User::query();
        
        if ($role) {
            $query->where('role', $role);
        }
        
        $users = $query->orderBy('role')->orderBy('name')->get();
        
        if ($users->isEmpty()) {
            $this->info('No users found.');
            return;
        }

        $headers = ['ID', 'Name', 'Email', 'Role', 'Department', 'Created At'];
        $rows = [];

        foreach ($users as $user) {
            $rows[] = [
                substr($user->id, 0, 8) . '...',
                $user->name,
                $user->email,
                $user->role,
                $user->department ?? 'N/A',
                $user->created_at->format('Y-m-d H:i:s'),
            ];
        }

        $this->table($headers, $rows);
        $this->info('Total users: ' . $users->count());
    }

    /**
     * Create a new user
     */
    private function createUser()
    {
        $name = $this->option('name') ?? $this->ask('Enter user name');
        $email = $this->option('email') ?? $this->ask('Enter user email');
        $password = $this->option('password') ?? $this->secret('Enter user password');
        $role = $this->option('role') ?? $this->choice('Select user role', User::getRoles());
        $department = $this->option('department');

        if (!$department && in_array($role, [User::ROLE_DOCTOR, User::ROLE_NURSE, User::ROLE_STAFF])) {
            $department = $this->choice('Select department', User::getDepartments());
        }

        try {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => $role,
                'department' => $department,
                'email_verified_at' => now(),
            ]);

            $this->info("User created successfully!");
            $this->info("ID: {$user->id}");
            $this->info("Name: {$user->name}");
            $this->info("Email: {$user->email}");
            $this->info("Role: {$user->role}");
            $this->info("Department: " . ($user->department ?? 'N/A'));

        } catch (\Exception $e) {
            $this->error("Failed to create user: " . $e->getMessage());
            return 1;
        }
    }
}