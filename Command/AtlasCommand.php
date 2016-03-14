<?php

namespace Olousouzian\AtlasBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Olousouzian\AkitaBundle\Library\Atlas;

class AtlasCommand extends ContainerAwareCommand
{
    protected $availableFormats = array('lambertI', 'lambertII', 'lambertIIExtended', 'lambertIII', 'lambertIV', 'lambert93', 'all');

    protected function configure()
    {
        $this
            ->setName('atlas:convert')
            ->setDescription('Convert WGS84 coordinates to Lambert coordinates')
             ->addOption(
                'format', '', InputOption::VALUE_REQUIRED,
                'Output format like: ' . implode(', ', $this->availableFormats),
                $this->availableFormats[6]
            )
            ->addOption('output', '', InputOption::VALUE_OPTIONAL, 'json', InputOption::VALUE_NONE)
             ->addOption(
                'latitude', '', InputOption::VALUE_REQUIRED
            )
             ->addOption(
                'longitude', '', InputOption::VALUE_REQUIRED
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {   
        $format = $input->getOption('format');
        $sOut = $input->getOption('output');
        $latitude = $input->getOption('latitude');
        $longitude = $input->getOption('longitude');
        $results = array();
        
        switch ($format) {
            case $this->availableFormats[0]:
                $results["LambertI"] = Atlas::WGS84toLambertI($longitude, $latitude);
                break;
            case $this->availableFormats[1]:
                $results["LambertII"] = Atlas::WGS84toLambertII($longitude, $latitude);
                break;
            case $this->availableFormats[2]:
                $results["LambertIIE"] = Atlas::WGS84toLambertIIE($longitude, $latitude);
                break;
            case $this->availableFormats[3]:
                $results["LambertIII"] = Atlas::WGS84toLambertIII($longitude, $latitude);
                break;
            case $this->availableFormats[4]:
                $results["LambertIV"] = Atlas::WGS84toLambertIV($longitude, $latitude);
                break;
            case $this->availableFormats[5]:            
                $results["Lambert93"] = Atlas::WGS84toLambert93($longitude, $latitude);
                break;            
            default:
                $results = array();
                $results["LambertI"] = Atlas::WGS84toLambertI($longitude, $latitude);
                $results["LambertII"] = Atlas::WGS84toLambertII($longitude, $latitude);
                $results["LambertIIE"] = Atlas::WGS84toLambertIIE($longitude, $latitude);
                $results["LambertIII"] = Atlas::WGS84toLambertIII($longitude, $latitude);
                $results["LambertIV"] = Atlas::WGS84toLambertIV($longitude, $latitude);
                $results["Lambert93"] = Atlas::WGS84toLambert93($longitude, $latitude);                
                break;
        }
        
       if ($sOut == "json"){
           $output->writeln(json_encode($results));
       } else {
           foreach ($results as $format => $values) {
               $output->writeln("***********************************************************************************");                              
               $output->writeln("* FORMAT\t\t|\t\tVALUE X\t\t\t\t|\t\tVALUE Y\t\t*");
               $output->writeln("* ".$format."\t\t|\t\tX : ".$values['x']."\t\t|\t\tY:  ".$values['y']."\t\t*");               
               $output->writeln("***********************************************************************************");               
           }
       }                
    }      
}