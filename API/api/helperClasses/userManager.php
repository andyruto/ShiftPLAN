<?php
    /**
     * userManager.php
     * 
     * PHP file containing class to manage the users.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-26 / Maximilian T. | Kontr0x
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

        //Overload constructor to create new entities
        public static function creator(){
            return new self(null);
        }

        //Overload constructor to work wirh existing entities
        public static function obj($name){
            return new self($name);
        }

        //Function to return password hash of user
        public function getPasswordHash(){
            return $this->user->getPassword_hash();
        }

        //Function to return name of user
        public function getUserName(){
            return $this->user->getName();
        }
        
        //Function to create a default user if the user table is empty
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

        //Creating a new challenge to be encrypt be the client for authentification
        public function createChallenge(){
            Logger::getLogger()->log('DEBUG', 'creating challenge');
            $chlg = preg_replace('/(0)\.(\d+) (\d+)/', '$3$1$2', microtime()) . generateRandomString(20);
            $this->user->setChallenge($chlg);
            $this->user->setChlgExpiration_date();
            $this->eM->flush();
            $this->eM->clear();
            return $this->user->getChallenge();
        }

        //Validation the challenge
        public function checkChallenge($chlg, $nonce, $key){
            Logger::getLogger()->log('DEBUG', 'checking challenge');
            if($this->errorCode == ErrorCode::NoError){
                //Checking if the challenge is expired
                if($this->user->getChlgExpiration_date() <= new DateTime('now')){
                    $sM = new SslKeyManager();
                    $this->errorCode = $sM->sDecrypt($chlg, $nonce, $key);
                    //if the decrytion worked out with no errors
                    if($this->errorCode == ErrorCode::NoError){
                        if($this->user->getChallenge != $sM->getResult()){
                            $this->errorCode = ErrorCode::ChallengeFailed;
                        }
                    }
                }else{
                    $this->errorCode = ErrorCode::ChallengeExpired;
                }
            }
            return $this->errorCode;
        }
        
        //Returning the finish code
        public function getFinishCode(){
            return $this->errorCode;
        }

        //Returning database object of class
        public function getDbObject(){
            if($this->errorCode == ErrorCode::NoError){
                return $this->user;
            }
        }
    }
?>