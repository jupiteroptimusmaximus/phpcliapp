<?php
// cliapp.php

require __DIR__.'/vendor/autoload.php';
require_once 'curl-master/curl.php';
//require __DIR__.'/commands/JqueryTestCommand.php';


use Symfony\Component\Console\Application;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class JqueryTestCommand extends Command
{
    protected function configure()
    {
        $this->setName('JqueryTestCommand');
        $this->setDescription('parameter je url stranka');

        $this->addArgument('url', InputArgument::REQUIRED);
        $this->setHelp("This command allows you to check if site is using jquery");
        $this->addOption('lib', 'l', InputOption::VALUE_REQUIRED, '', 'jquery');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Testujem vyskyt pouzitia zvolenej technologie na url: ' . $input->getArgument('url'));
        $output->writeln('Zvolena technologia na kontrolu: ' . $input->getOption('lib'));
        
        $curl = new Curl;
        $response = $curl->get($input->getArgument('url'));
        $crawler = new Crawler($response->body);
        $scripts = $crawler->filter('script');
        
        
        
    }
}


$application = new Application('CLI JqueryTest', '0.1.0');

$application->add(new JqueryTestCommand());

$application->run();

?>