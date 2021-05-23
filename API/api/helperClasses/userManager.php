<?php
    /**
     * userManager.php
     * 
     * PHP file containing class to manage the users.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    class UserManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        
        function __construct(){
            $this->eM = Bootstrap::getEntityManager();
        }

        function getUserPasswordHash($name){
            $user = $this->eM->getRepository('user')->findBy(array('name' => $name))[0];
            return $user->getPassword_hash();
        }

        public static function createDefaultUser(){
            $eM = Bootstrap::getEntityManager();
            if($eM->getrepository('user')->findAll()==null){
                //Creating new user
                $standarduser = new User();
                $standarduser->setName('admin');
                if(DEV_MODE){
                    Logger::getLogger()->log('DEBUG', 'Dev mode is active');
                    $pwHash = \Sodium\crypto_generichash('ProgrammersHatePhp');
                }else{
                    $pwHash = \Sodium\crypto_generichash('admin');
                }
                $standarduser->setPassword_hash(\Sodium\bin2hex($pwHash));
                //Storeing created user
                $eM->persist($standarduser);
                //Flushing changes
                $eM->flush();
            }
        }
    }
?>