<?php
    /**
     * userManager.php
     * 
     * PHP file containing class to manage the users.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-23 / Maximilian T. | Kontr0x
     */

    class UserManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        private $user = null; //Variable to store an user object
        
        private function __construct($name){
            $this->eM = Bootstrap::getEntityManager();
            if(!empty($name)){
                //Looking for user in database
                $this->user = $this->eM->getRepository('user')->findBy(array('name' => $name))[0];

                if($this->user == null){
                    Logger::getLogger()->log('ERROR', "User with name ".$name." doesn't exist in database");
                    $this->errorCode = ErrorCode::UserNotFound;
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter user name is empty. Class probably created as UserManager::creator');
                $this->errorCode = ErrorCode::NoUserNameGiven;
            }
        }

        public static function creator(){
            return new self(null);
        }

        public static function obj($name){
            return new self($name);
        }

        public function getPasswordHash(){
            return $this->user->getPassword_hash();
        }

        public function getFinishCode(){
            return $this->errorCode;
        }

        public function createDefaultUser(){
            if($this->eM->getrepository('user')->findAll()==null){
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
                $this->eM->persist($standarduser);
                //Flushing changes
                $this->eM->flush();
            }
        }
    }
?>