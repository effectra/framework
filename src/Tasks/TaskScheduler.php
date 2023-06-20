<?php

declare(strict_types=1);

namespace Effectra\Core\Tasks;

/**
 * The TaskScheduler class manages the scheduling and running of tasks.
 */
class TaskScheduler
{
    protected array $tasks = [];

    /**
     * Schedule a task to be executed at a specific time.
     *
     * @param string $time     The time at which the task should be executed.
     * @param mixed  $callback The callback function or method to be executed.
     */
    public function schedule(string $time, $callback): void
    {
        $this->tasks[] = compact('time', 'callback');
    }

    /**
     * Run the scheduled tasks.
     */
    public function run(): void
    {
        $now = time();

        foreach ($this->tasks as $task) {
            $taskTime = strtotime($task['time']);

            if ($taskTime <= $now) {
                call_user_func($task['callback']);
            }
        }
    }
}
