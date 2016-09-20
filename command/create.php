<?php

namespace gresnet\extension\command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class create extends \phpbb\console\command\command {
    
    protected $output;
    
    public $dirs = array('command','config','controller','event','language','migrations','model','styles','tests');


    public function __construct(    \phpbb\user $user, \phpbb\config\config $conf) {
        parent::__construct($user);
        $this->db = $db;
        $this->conf = $conf;
    }
    
    /**
     * Set the command
     */
    protected function configure() {
        
        $this->setName('extension:create')
                ->setDescription('Creats a boilerplate for a phpbb 3.1 extension.')
                ->addArgument('extension-name')
                ->addArgument('namespace')
                ->addArgument('extension-display-name')
                ->addArgument('authors-name')
                ->addArgument('authors-email')
                //->addOption("test",null,null,"Send data to testing server.")
                //->addOption("dryrun",null,null,"Skip sending to remote server.")
                //->addOption("v", null, null, "Output emails to console.")
                ;
        
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        $this->output = $output;
        $this->set_arguments($input);
        $this->create_extension_directory();
        $this->create_composer_json();
        
        $this->output(getcwd());
    }
    
    protected function create_extension_directory() {
        chdir("../ext/");
        if(is_dir($this->namespace)) {
            chdir($this->namespace);
        } else {
            mkdir($this->namespace);
            chdir($this->namespace);
        }
        if(file_exists($this->extensionName)) {
            throw new \Exception("An extension with the name {$this->extensionName} or a file with that name already exists in the {$this->namespace} namespace. Please fix this problem first!");
        }
        mkdir($this->extensionName);
        chdir($this->extensionName);
        
        
        
    }
    
    protected function create_composer_json() {
        $json = '{
	"name": "'.$this->namespace.'/'. $this->extenisonName. '",
	"type": "phpbb-extension",
	"description": "",
	"homepage": "http://gresak.net/phpbb",
	"version": "1.0.0",
	"license": "GPL-2.0",
	"authors": [
		{
			"name": "'.$this->authorsName.'",
			"email": "'.$this->authorsEmail.'",
			"homepage": "",
			"role": ""
		}
	],
	"require": {
		"php": ">=5.3.3",
		"composer/installers": "~1.0"
	},
	"extra": {
		"display-name": "'.$this->extnesionDisplayName.'",
		"soft-require": {
			"phpbb/phpbb": ">=3.1.0"
		}
	}
        }';
        
        file_put_contents("composer.json", $json);
    }
    
    protected function set_arguments($input) {
        $this->extensionName = $input->getArgument('extension-name');
        if(empty($this->extensionName)) {
            throw new \Exception("The extension-name argument is required!");
        }
        $this->namespace = $input->getArgument('namespace');
        if(empty($this->namespace)) {
            $this->namespace = "acme";
        }
        $this->extensionDisplayName = $input->getArgument('extension-display-name');
        if(empty($this->extensionDisplayName)) {
            $this->extensionDisplayName = $this->extensionName;
        }
        $this->authorsName = $input->getArgument('authors-name');
        if(empty($this->authorsName)) {
            $this->authorsName = "Wile E. Coyote";
        }
        $this->authorsEmail = $input->getArgument('authors-email');
        if(empty($this->authorsEmail)) {
            $this->authorsEmail = "change.this@example.com";
        }
    }
    
    protected function output($message) {
        $this->output->writeln(date('d/m/y H:i')." ".$message);
    }
}

