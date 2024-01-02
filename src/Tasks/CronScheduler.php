<?php

declare(strict_types=1);

namespace Effectra\Core\Tasks;

use Effectra\Fs\File;
use RuntimeException;

/**
 * The CronScheduler class manages the scheduling and saving of cron jobs.
 */
class CronScheduler
{
    protected array $jobs = [];

    /**
     * Add a new cron job.
     *
     * @param string $min
     * @param string $hour
     * @param string $day
     * @param string $month
     * @param string $week
     * @param string $command
     *
     * @return void
     */
    public function addJob(string $min = '*', string $hour = '*', string $day = '*', string $month = '*', string $week = '*', string $command): void
    {
        $this->validateSchedule($min, $hour, $day, $month, $week);
        $schedule = sprintf('%s %s %s %s %s', $min, $hour, $day, $month, $week);
        $this->jobs[] = compact('schedule', 'command');
    }

    /**
     * Save the cron jobs to the crontab.
     *
     * @throws RuntimeException
     *
     * @return void
     */
    public function save(): void
    {
        $crontab = implode(PHP_EOL, $this->buildCronJobs());

        $output = shell_exec('crontab -l 2>/dev/null');
        $output .= PHP_EOL . $crontab;

        $temp = sys_get_temp_dir() . '/crontab.txt';

        if (!File::exists($temp)) {
            throw new RuntimeException("Error Processing CronJob: path '$temp' doesn't exist");
        }

        file_put_contents($temp, $output);

        exec('crontab ' . $temp);
    }

    /**
     * Build an array of cron jobs.
     *
     * @return array The cron jobs.
     */
    protected function buildCronJobs(): array
    {
        return array_map(function ($job) {
            return sprintf('%s php %s', $job['schedule'], $job['command']);
        }, $this->jobs);
    }

    /**
     * Validate cron schedule parameters.
     *
     * @param string $min
     * @param string $hour
     * @param string $day
     * @param string $month
     * @param string $week
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function validateSchedule(string $min, string $hour, string $day, string $month, string $week): void
    {
        $this->validateCronField($min, 'minute', 0, 59);
        $this->validateCronField($hour, 'hour', 0, 23);
        $this->validateCronField($day, 'day of month', 1, 31);
        $this->validateCronField($month, 'month', 1, 12);
        $this->validateCronField($week, 'day of week', 0, 6);
    }

    /**
     * Validate a single cron field.
     *
     * @param string $field
     * @param string $fieldName
     * @param int $min
     * @param int $max
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    protected function validateCronField(string $field, string $fieldName, int $min, int $max): void
    {
        if ($field !== '*' && !ctype_digit($field)) {
            throw new \InvalidArgumentException("$fieldName must be either a digit or '*'.");
        }

        $value = (int)$field;

        if ($value !== '*' && ($value < $min || $value > $max)) {
            throw new \InvalidArgumentException("$fieldName must be between $min and $max or '*'.");
        }
    }
}
