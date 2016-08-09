<?php
// File: JqueryTestCommand.php
// Purpose of this command is to determine if the requested web page uses some sort of javascript technology/framework/library
// It is doing so by examining the html and looking for some keyword in page's scripts url attribute

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DomCrawler\Crawler;

class JqueryTestCommand extends Command
{
    protected function configure()
    {
        $this->setName('JqueryTestCommand');
        $this->setDescription('Parameter is an url of web page');

        // adding required argument which is url of page to check
        $this->addArgument('url', InputArgument::REQUIRED);
        
        // set help message of the command
        $this->setHelp("This command allows you to check if site is using jquery or another javascript framework");
        
        // optional argument, use when you want to check page for other technology than jquery
        $this->addOption('lib', 'l', InputOption::VALUE_REQUIRED, '', 'jquery');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // just variable to hold logical answer
        $technologyFound = false;
        
        // only copying the optional argument to a variable 
        $technology = $input->getOption('lib');
        
        // url of page to check
        $url = $input->getArgument('url');
    
        // info message about what is going on
        $output->writeln('Testing usage of technology on url: ' . $input->getArgument('url'));
        $output->writeln('Technology to check: ' . $input->getOption('lib'));
        
        // page content is loaded using a Curl wrapper library that i found at github.com -> https://github.com/shuber/curl
        // first we need to download the html of page
        $curl = new Curl;
        $curlResponse = $curl->get($url);
        
        // check if curl returned valid page
        if(isset($curlResponse->body)) {
            // then feed it to symphony DomCrawler component
            $crawler = new Crawler($curlResponse->body);            
        }
        // if error occurs because of invalid or non-existing url
        else {
            $output->writeln(' Error parsing the page from requested url ! ( ' . $url . ' )');
            $output->writeln(' Script now ends, check if provided url is correct and try again...');  
            return;
        }
        
        // now just filter out script dom elements from html document
        $domScriptElements = $crawler->filter('script');

        foreach ($domScriptElements as $domScriptElem) {

            // this will get the 'src' attribute from dom element
            $scriptSrc = $domScriptElem->getAttribute('src');
            // now we need to get rid of some delimiters like '/' and '.'
            $scriptSrcArr = explode('/', $scriptSrc );
            foreach ($scriptSrcArr as $srcDelim) {
              $scriptSrcArr2 = explode('.', $srcDelim );  
            }
            foreach ($scriptSrcArr2 as $srcDelim2) {
              // if we find the string 'jquery' of string of other technology we consider this a success
              if (strtolower($srcDelim2) == strtolower($technology)) {
                $technologyFound = true;
              }  
            }            
        }
        
        if ($technologyFound) {
          $output->writeln('Technology called ' . $technology . ' has been found !');
        }
        
        else {
          $output->writeln('Technology called ' . $technology . ' has NOT been found !');
        }
      
    }
}

?>