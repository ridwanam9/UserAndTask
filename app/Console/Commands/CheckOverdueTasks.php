<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Models\ActivityLog;
use Illuminate\Support\Carbon;

class CheckOverdueTasks extends Command
{
    protected $signature = 'tasks:check-overdue';
    protected $description = 'Check for overdue tasks and log them';

    public function handle(): int
    {
        $tasks = Task::where('due_date', '<', now())
                     ->where('status', '!=', 'done')
                     ->get();

        foreach ($tasks as $task) {
            ActivityLog::create([
                'user_id' => $task->assigned_to,
                'action' => 'task_overdue',
                'description' => "Task overdue: {$task->id}",
                'logged_at' => now(),
            ]);

            $this->info("Logged overdue task: {$task->id}");
        }

        return Command::SUCCESS;
    }
}

