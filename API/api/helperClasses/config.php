<!--
-- config.php
--
-- PHP file containing the specific config reader / provider implementation
-- for our project. It's a config reader / provider class that provides all necesarry settings.
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-06-10 / Maximilian T. | Kontr0x
-->
<?php
    require __DIR__ . '/configClasses/general.php';
    require __DIR__ . '/configClasses/logging.php';
    require __DIR__ . '/configClasses/database.php';

    final class Config {

        //Property that stores the one and only config
        private static $config = null;

        //Property to store logging object
        private $logging = null;

        //Property to store database object
        private $database = null;

        //Constructor for building the config object
        private function __construct() {
            $this->logging = new Logging();
            $this->database = new Database();
        }

        //Function to get config or create if not already exists
        //@return: Config object 
        public static function getConfig() : self {
            if(self::$config==null):
                self::$config = new self();
                self::$config->readConfigFile(ROOT.'/settings.conf'); //Call function readConfigFile with path to config
            endif;

            return self::$config;
        }

        //Function reading the config file and writing it into the desired object
        //@param $path: Path to config
        private function readConfigFile(string $path){
            $configStream = fopen($path, 'r'); //Open a filestream to config
            $currentSection = null;
            $matchInRow = null;
            while(!feof($configStream)){ //Looping through to the end of file
                $line = fgets($configStream); //Getting the current stream as string
                if(preg_match('/\s*\[([[:upper:]][a-zA-Z0-9]*)\]\s*/',$line ,$matchInRow)):
                    $currentSection=strtolower($matchInRow[1]);
                elseif(preg_match('/\s*([a-zA-Z]*)\s*\=\s*([^\|\<\>\;\&\$\#\!\*\`\´\?\%\"\§\²\³\¼\½\¬\{\[\]\}\′\+\=\:]*)/',$line ,$matchInRow)):
                    if(property_exists($this, $currentSection)):
                        $this->$currentSection->putInValue($matchInRow[1], trim($matchInRow[2]));
                    endif;
                endif;

            }
            fclose($configStream); //Closing filestream to config

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
            if(property_exists($this, $className)):
                return $this->$className;
            else:
                throw new InvalidArgumentException("The class name argument doesn't fit the possible values.");
            endif;
        }
    }
?>