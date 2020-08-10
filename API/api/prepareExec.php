<?php
/**
 * prepareExec.php
 * 
 * Script preparing all imports and requirements for
 * all the script executions.
 * 
 * author: Andreas G.
 * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x
 */

    if(!defined('ROOT')) {
        define('ROOT', __DIR__);
    }

    if(!defined('API_VERSION')) {
        define('API_VERSION', '0.0.1');
    }

    //Function to fix new line error on fgets
    //@param $recource: source string to apply fix
    //@return: returning the fixed string
    function read_line($recource) : string {
        $buffer = fgets($recource);
        $buffer = str_replace("\n", "", $buffer);
        $buffer = str_replace("\r", "", $buffer);
        $buffer = str_replace("\r\n", "", $buffer);
        return $buffer;
    }

    //Function checking if string starts with a specific string
    //@param $string: The string to be checked.
    //@param $pattern: The pattern to search for in the string
    function starts_with(string $string, string $pattern) : bool {
        $length = strlen($pattern);

        return (substr($string, 0, $length) === $pattern);
    }

    //Function checking if string ends with a specific string
    //@param $string: The string to be checked.
    //@param $pattern: The pattern to search for in the string
    function ends_with(string $string, string $pattern) : bool {
        $length = strlen($pattern);

        if ($length == 0) {
            return true;
        }

        return (substr($string, -$length) === $pattern);
    }

    //Function creating a specific folder with htaccess file
    //for hiding purposes.
    //@param $path: The path with '/' to the folder to create
    function createWebHiddenFolder(string $path) {
        if (!file_exists($path)) {
            mkdir($path);
        }

        if (file_exists($path . '.htaccess')) {
            unlink($path . '.htaccess');
        }

        $fileWriter = fopen($path . '.htaccess', 'w');
        fwrite($fileWriter, 'Deny from all');
        fclose($fileWriter);
    }
    
    //Function to get random string by length
    //@param $length: length of random string
    //@return: returning random genrated string
    function generateRandomString($length): string {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    //Loading composer packages
    require ROOT . '/vendor/autoload.php';
    
    //Importing all classes from path
    foreach (scandir(ROOT . '/helperClasses'.'/') as $filename) {
        $path = ROOT . '/helperClasses'.'/'.$filename;
        if (is_file($path)) {
            require $path;
        }
    }
    
    //Loading main doctrine config class
    require ROOT . '/src/bootstrap.php';
    
    function checkApiKey($apiKey) : bool{
        $eM = Bootstrap::getEntityManager();
        if(empty($apiKey)||$eM->find('Api_key', $apiKey)==null){
            header('Content-Type: application/json');
            $respondJSON = array('success' => false);
            echo(json_encode($respondJSON));
            exit();
        }
        return true;
    }

    function checkSessionKey($sessionKey) : bool{
        $eM = Bootstrap::getEntityManager();
        $session_key = $eM->find('Session', $sessionKey);
        header('Content-Type: application/json');
        if($session_key==null){
            $respondJSON = array('success' => 'false');
            echo(json_encode($respondJSON));
            exit();
        }elseif($session_key->getExpiration_date()<new DateTime('now')){
            $eM->remove($session_key);
            $eM->flush();
            $eM->clear();
            $respondJSON = array('success' => 'false');
            echo(json_encode($respondJSON));
            exit();
        }
        return true;
    }

    Config::getConfig()->printConfig();
    
    ?>