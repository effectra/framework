<?php

declare(strict_types=1);

namespace Effectra\Core\Server;

/**
 * Class DurationCalculator
 *
 * Calculates the duration between a start and stop time in milliseconds.
 */
class DurationCalculator
{
    protected $startTime;
    protected $endTime;

    /**
     * Start the duration timer.
     *
     * @return void
     */
    public function start(): void
    {
        $this->startTime = microtime(true);
    }

    /**
     * Stop the duration timer.
     *
     * @return void
     */
    public function stop(): void
    {
        $this->endTime = microtime(true);
    }

    /**
     * Get the duration between start and stop in milliseconds.
     *
     * @return float|null The duration in milliseconds, or null if start or stop is not set.
     */
    public function getDuration(): ?float
    {
        if ($this->startTime && $this->endTime) {
            return round(($this->endTime - $this->startTime) * 1000, 2); // Duration in milliseconds
        }

        return null;
    }
}
