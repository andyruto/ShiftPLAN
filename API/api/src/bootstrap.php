<?php
/**
 * bootstrap.php
 * 
 * PHP file containing doctrine main config
 * 
 * author: Maximilian T. | Kontr0x
 * last edit / by: 2021-04-24 / Maximilian T. | Kontr0x
 */

//Importing all entities classes from path
foreach (scandir(ROOT . '/src/entities'.'/') as $filename) {
    $path = ROOT . '/src/entities'.'/'.$filename;
    if (is_file($path)) {
        require $path;
    }
}

//Including files from framework
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

final class Bootstrap{
    
    //Property that stores the one and only config
    private static $entityManager = null;

    //Settings parameters to create Doctrine ORM configuration for Annotations
    private static $isDevMode = false;
    private static $proxyDir = null;
    private static $cache = null;
    private static $useSimpleAnnotationReader = false;
    private static $paths = array(ROOT . "/src/entities");
    private static $config = null;

    
    // database configuration parameters
    private static $conn = null;
    
    //Constructor for building the config object
    private function __construct(){
        
    }
    
    //Function to get doctrine's entityManager or create if not already exists
    //@return: entityManager object 
    public static function getEntityManager() : EntityManager{
        //Checking if a entityManager object is already made
        if(self::$entityManager==null){
            Logger::getLogger()->log('INFO','Creating entity manager');
            //Creating doctrine config with parameters made in class
            self::$config = Setup::createAnnotationMetadataConfiguration(self::$paths, self::$isDevMode, self::$proxyDir, self::$cache, self::$useSimpleAnnotationReader);
            //Setting connection parameters for database connection
            if(Config::getConfig()->get("Database")->getValue("dbPort") == 0){
                self::$conn = array(
                    'driver' => 'pdo_mysql',
                    'user'     => Config::getConfig()->get("Database")->getValue("dbUser"),
                    'password' => Config::getConfig()->get("Database")->getValue("dbPassword"),
                    'dbname'   => Config::getConfig()->get("Database")->getValue("dbName"),
                    'host' => Config::getConfig()->get("Database")->getValue("dbHost")
                );
            }else{
                self::$conn = array(
                    'driver' => 'pdo_mysql',
                    'user'     => Config::getConfig()->get("Database")->getValue("dbUser"),
                    'password' => Config::getConfig()->get("Database")->getValue("dbPassword"),
                    'dbname'   => Config::getConfig()->get("Database")->getValue("dbName"),
                    'host' => Config::getConfig()->get("Database")->getValue("dbHost"),
                    'port' => Config::getConfig()->get("Database")->getValue("dbPort")
                );
            }
            
            //Creating entityManager
            self::$entityManager = EntityManager::create(self::$conn, self::$config);
            try {
                //Check database connection
                self::$entityManager->getConnection()->connect();
                Logger::getLogger()->log('INFO','Connection established to the database');
            } catch (\Exception $e) {
                //Throwing an error when database connection wasnt succesfull
                Logger::getLogger()->log('ERROR','Not connected to the database');
            }
        }
        return self::$entityManager;
    }

    //prevent to get unserialized
    private function __wakeup(){}
    //prevent the instance from being cloned which would create a second instance of config
    private function __clone(){}
}
?>