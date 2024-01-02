<?php

declare(strict_types=1);

namespace Effectra\Core\Tasks;

/**
 * The TaskScheduler class manages the scheduling and running of tasks.
 */
class TaskScheduler
{
    protected static array $tasks = [];

    /**
     * Schedule a task to be executed at a specific time.
     *
     * @param string   $time     The time at which the task should be executed.
     * @param \Closure $callback The callback function or method to be executed.
     * @param array    $args     The arguments for the callback function or method.
     */
    public static function schedule(string $time, \Closure $callback, array $args = []): void
    {
        static::$tasks[] = compact('time', 'callback', 'args');
    }

    /**
     * Run the scheduled tasks.
     */
    public static function run(): void
    {
        $now = time();

        foreach (static::$tasks as $task) {
            $taskTime = strtotime($task['time']);

            if ($taskTime <= $now) {
                call_user_func($task['callback'], $task['args']);
            }
        }
    }
}
