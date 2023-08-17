<?php

declare(strict_types=1);

namespace Effectra\Core\Cmd\Router;

use Effectra\Core\Router\RouterConfigurator;
use League\CLImate\CLImate;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddRouter extends Command
{

    protected function configure()
    {
        $this->setName('route:add')
            ->setDescription('Add new route')
            ->addArgument('path', InputArgument::REQUIRED, 'The path of the route')
            ->addArgument('controller', InputArgument::REQUIRED, 'The name of the Controller')
            ->addOption('method', 'm', InputOption::VALUE_OPTIONAL, 'The Http method')
            ->addOption('type', 't', InputOption::VALUE_OPTIONAL, 'The Type of router','web')
            ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $climate = new CLImate();

        $path = $input->getArgument('path') ;
        $controller = $input->getArgument('controller') ;
        $method =  $input->getOption('method');
        $type =  $input->getOption('type');

        $result = RouterConfigurator::addRoute($controller,$type,$path);

      if($result){
        $climate->lightGreen()->out('  Route added successfully');
        return Command::SUCCESS;
      }
      else{
         return Command::FAILURE;
        }
    }
}
