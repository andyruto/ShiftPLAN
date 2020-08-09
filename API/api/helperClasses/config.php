<!--
-- config.php
--
-- PHP file containing the specific config reader / provider implementation
-- for our project. It's a config reader / provider class that provides all necesarry settings.
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-08-08 / Andreas G.
-->
<?php
    //Importing general class first
    require ROOT . '/helperClasses/configClasses/general.php';

    //Importing all other config and config helper classes from path
    foreach (scandir(ROOT . '/helperClasses/configClasses'.'/') as $filename) {
        $path = ROOT . '/helperClasses/configClasses'.'/'.$filename;
        if (is_file($path) && $path != ROOT . '/helperClasses/configClasses/general.php') {
            require $path;
        }
    }

    final class Config{

        //Property that stores the one and only config
        private static $config = null;

        //Property to store logging object
        private $logging = null;

        //Property to store database object
        private $database = null;

        //Constructor for building the config object
        private function __construct(){
            $this->logging = new Logging();
            $this->database = new Database();
        }

        //Function to get config or create if not already exists
        //@return: Config object 
        public static function getConfig() : self{
            if(self::$config==null){
                self::$config = new self();
                self::$config->readConfigFile(ROOT.'/settings.conf'); //call function readConfigFile with path to config
            }
            return self::$config;
        }

        //Function reading the config file and writing it into the desired object
        //@param $path: Path to config
        private function readConfigFile(string $path){
            $configStream = fopen($path, 'r'); //open a filestream to config
            $currentSection = null;
            $matchInRow = null;

            while(!feof($configStream)){ //looping through to the end of file
                $line = read_line($configStream); //getting the current stream as string

                if(preg_match('/\s*\[([[:upper:]][a-zA-Z0-9]+)\]\s*/',$line ,$matchInRow, PREG_UNMATCHED_AS_NULL)==1 && !(in_array(null, $matchInRow))){
                    $currentSection=strtolower($matchInRow[1]);
                } else if(preg_match('/\s*([a-zA-Z]+)\s+\=\s+([^\|\<\>\;\&\`\´\?\%\"\§\²\³\¼\½\¬\{\[\]\}\′\+\=\:]+)/',$line ,$matchInRow, PREG_UNMATCHED_AS_NULL)==1 && !(in_array(null, $matchInRow))){
                    if(property_exists($this, $currentSection)){
                        $this->$currentSection->putInValue($matchInRow[1], trim($matchInRow[2]));
                    }
                }
            }
            fclose($configStream); //closing filestream to config

            //Going through every conf and checking the validity
            foreach(get_object_vars($this) as $vars){
                $vars->checkCompleteness();
            }
        }

        //prevent to get unserialized
        private function __wakeup(){}

        //prevent the instance from being cloned which would create a second instance of config
        private function __clone(){}

        //Universal get function returning the specific conf object
        public function get(string $className) : object{
            $className = strtolower($className);
            if(property_exists($this, $className)){
                return $this->$className;
            } else{
                throw new InvalidArgumentException("The class name argument doesn't fit the possible values.");
            }
        }

        //Function to print current config from all section in debug log
        public function printConfig(){
            foreach(get_object_vars($this) as $var){
                $var->printAllValues();
            }
        }
    }
?>