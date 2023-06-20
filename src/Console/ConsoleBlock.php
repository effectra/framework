<?php

namespace Effectra\Core\Console;

use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * A utility class for displaying styled console blocks.
 */
class ConsoleBlock
{
    protected $io;

    /**
     * Create a new instance of the ConsoleBlock class.
     *
     * @param mixed $input The input object.
     * @param mixed $output The output object.
     */
    public function __construct($input, $output)
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * Display an info block with a given event.
     *
     * @param string $event The event to display.
     * @return void
     */
    public function info($event)
    {
        $s = $this->addSpace($event);
        $this->io->writeln("\n");
        $this->io->writeln("  <bg=blue;fg=white>  $s  </>");
        $this->io->writeln("  <bg=blue;fg=white>  $event  </>");
        $this->io->writeln("  <bg=blue;fg=white>  $s  </>");
        $this->io->writeln("\n");
    }

    /**
     * Display an error block.
     *
     * @return void
     */
    public function error()
    {
        $s = $this->addSpace('Error:');
        $this->io->writeln("\n");
        $this->io->writeln("  <error>  $s  </error>");
        $this->io->writeln("  <error>  Error:  </error>");
        $this->io->writeln("  <error>  $s  </error>");
        $this->io->writeln("\n");
    }

    /**
     * Display a warning block with a given event.
     *
     * @param string $event The event to display.
     * @return void
     */
    public function warning($event)
    {
        $s = $this->addSpace($event);
        $this->io->writeln("\n");
        $this->io->writeln("  <bg=yellow;fg=white>  $s  </>");
        $this->io->writeln("  <bg=yellow;fg=white>  $event  </>");
        $this->io->writeln("  <bg=yellow;fg=white>  $s  </>");
        $this->io->writeln("\n");
    }

    /**
     * Display a success block with an optional event.
     *
     * @param string $event The event to display (default: 'Info').
     * @return void
     */
    public function success($event = 'Info')
    {
        $this->io->writeln("  <info>$event</info>");
        $this->io->writeln("\n");
    }

    /**
     * Display an error message with an optional event.
     *
     * @param string $event The event to display (default: empty).
     * @return void
     */
    public function errorMsg($event = '')
    {
        if (!empty($event)) {
            $this->io->writeln("\n");
            $this->io->writeln("  $event");
            $this->io->writeln("\n");
        }
    }

    /**
     * Add space characters equal to the length of a given word.
     *
     * @param string $word The word to calculate the space length for.
     * @return string The generated space string.
     */
    public function addSpace($word)
    {
        $l = strlen($word);
        $s =  '';
        for ($i = 0; $i < $l; $i++) {
            $s .= ' ';
        }
        return $s;
    }

    /**
     * Add dots to a given word with a specified total length.
     *
     * @param string $word The word to add dots to.
     * @param string $wordEnd The optional ending string to append after the word (default: empty).
     * @param int $totalDots The total length of the string, including the word and dots (default: 100).
     * @return string The generated string with dots.
     */
    public function addDots($word, $wordEnd = '', $totalDots = 100)
    {
        $l = strlen($word);
        $s =  '.';
        for ($i = 0; $i < $totalDots - $l; $i++) {
            $s .= '.';
        }
        return '  ' . $word . $s . $wordEnd;
    }

    /**
     * Display a plain text message.
     *
     * @param string $text The text message to display.
     * @return void
     */
    public function text($text)
    {
        $this->io->writeln("\n");
        $this->io->writeln('  ' . $text);
        $this->io->writeln("\n");
    }
}
