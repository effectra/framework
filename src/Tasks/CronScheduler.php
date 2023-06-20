<?php

declare(strict_types=1);

namespace Effectra\Core\Tasks;

/**
 * The CronScheduler class manages the scheduling and saving of cron jobs.
 */
class CronScheduler
{
    protected array $jobs = [];

    /**
     * Add a new cron job.
     *
     * @param string $schedule The cron schedule.
     * @param string $command  The command to be executed.
     */
    public function addJob(string $schedule, string $command): void
    {
        $this->jobs[] = compact('schedule', 'command');
    }

    /**
     * Save the cron jobs to the system's crontab.
     */
    public function save(): void
    {
        $crontab = implode(PHP_EOL, $this->buildCronJobs());

        $output = shell_exec('crontab -l 2>/dev/null');
        $output .= PHP_EOL . $crontab;

        file_put_contents(sys_get_temp_dir() . '/crontab.txt', $output);
        exec('crontab ' . sys_get_temp_dir() . '/crontab.txt');
    }

    /**
     * Build an array of cron jobs.
     *
     * @return array The cron jobs.
     */
    protected function buildCronJobs(): array
    {
        $cronJobs = [];

        foreach ($this->jobs as $job) {
            $cronJobs[] = $job['schedule'] . ' ' . $job['command'];
        }

        return $cronJobs;
    }
}
