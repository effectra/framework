<?php

declare(strict_types=1);

namespace Effectra\Core\Sever;

class Docker
{
    protected $dockerComposePath;

    public function __construct($dockerComposePath = './docker-compose.yml')
    {
        $this->dockerComposePath = $dockerComposePath;
    }

    public function up()
    {
        $command = "docker-compose -f {$this->dockerComposePath} up -d";
        $this->executeCommand($command);
    }

    public function down()
    {
        $command = "docker-compose -f {$this->dockerComposePath} down";
        $this->executeCommand($command);
    }

    public function run($service, $command)
    {
        $command = "docker-compose -f {$this->dockerComposePath} exec -T $service $command";
        $this->executeCommand($command);
    }

    public function composer($command)
    {
        $this->run('composer', "composer $command");
    }

    public function aval($command)
    {
        $this->run('php', "php aval $command");
    }

    public function mysql()
    {
        $this->run('mysql', 'mysql');
    }

    protected function executeCommand($command)
    {
        passthru($command, $status);
        if ($status !== 0) {
            exit($status);
        }
    }
}