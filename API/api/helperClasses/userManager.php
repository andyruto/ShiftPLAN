<?php
    /**
     * userManager.php
     * 
     * PHP file containing class to manage the users.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-07-05 / Maximilian T. | Kontr0x
     */

    class UserManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        private $user = null; //Variable to store an user object
        
        private function __construct($NameOrId){
            $this->eM = Bootstrap::getEntityManager();
            if(isset($NameOrId)){
                //Looking for user in database
                if(is_numeric($NameOrId)){
                    $this->user = $this->eM->find('user', $NameOrId);
                }else{
                    $this->user = $this->eM->getRepository('user')->findBy(array('name' => $NameOrId))[0];
                }
                if($this->user == null){
                    Logger::getLogger()->log('ERROR', "User with name / id ".$NameOrId." doesn't exist in database");
                    $this->errorCode = ErrorCode::UserNotFound;
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter user name / id is empty. Class probably created as UserManager::creator');
                $this->errorCode = ErrorCode::NoUserNameOrIdGiven;
            }
        }

        //Overload constructor to create new entities
        public static function creator(){
            return new self(null);
        }

        //Overload constructor to work wirh existing entities
        public static function obj($NameOrId){
            return new self($NameOrId);
        }

        //Function to return password hash of user
        public function getPasswordHash(){
            return $this->user->getPassword_hash();
        }

        //Function to return name of user
        public function getUserName(){
            return $this->user->getName();
        }

        //Function to return type of user
        public function getUserType(){
            return $this->user->getUser_type();
        }

        //Function to get user profile
        public function getUserProfile(){
            $user = array();
            $userArray = array('id' => $this->user->getId(), 'type' => $this->user->getUser_type(), 'name' => $this->user->getName(), 'hidden' => $this->user->getHidden(), 'overtime' => $this->user->getOvertime(), 'weeklyWorkingMinutes' => $this->user->getWeekly_working_minutes(), 'weeklyWorkingDays' => $this->user->getWeekly_working_days(), 'yearVacationDays' => $this->user->getYear_vacation_days());
            $user = array_merge($user, array($userArray));
            Logger::getLogger()->log('INFO', 'Returning profile of own user profile');
            return array('profile' => array_values($user));
        }

        //Get users from database with filter
        public function getUsersWithFilter($filter, $value){
            if($this->getUserType() >= UserType::Manager){
                Logger::getLogger()->log('DEBUG', 'User who requested the search has privileges higher or exact like a manager');
                if($filter == "all" && $value == "complete"){
                    Logger::getLogger()->log('DEBUG', 'Searching for all users in database');
                    $users = $this->eM->getrepository('user')->findAll();
                }else{
                    Logger::getLogger()->log('DEBUG', 'Searching for all users in database with filter '.$filter);
                    $users = $this->eM->getRepository('user')->findBy(array($filter => $value));
                }
                $usersArray = array();
                if($users != null){
                    Logger::getLogger()->log('INFO', 'The search found users in the database');
                    //Formatting the output of the found users
                    foreach($users as $user){
                        $userArray = array('id' => $user->getId(), 'type' => $user->getUser_type(), 'name' => $user->getName(), 'hidden' => $user->getHidden(), 'overtime' => $user->getOvertime(), 'weeklyWorkingMinutes' => $user->getWeekly_working_minutes(), 'weeklyWorkingDays' => $user->getWeekly_working_days(), 'yearVacationDays' => $user->getYear_vacation_days());
                        $usersArray = array_merge($usersArray, array($userArray));
                    }
                }else{
                    Logger::getLogger()->log('INFO', 'The search found no users in database');
                    self::$errorCode = ErrorCode::FilterNotMatching;
                }
            }else{
                if(preg_match(Validation::UserFiltersNoAdminRights, $filter)){
                    Logger::getLogger()->log('DEBUG', 'User who requested the search has privileges of employee');
                    $users = $this->eM->getRepository('user')->findBy(array($filter => $value));
                    $usersArray = array();
                    if($users != null){
                        //Formatting the output of the found users
                        foreach($users as $user){
                            $userArray = array('id' => $user->getId(), 'name' => $user->getName());
                            $usersArray = array_merge($usersArray, array($user->getName() => $userArray));
                        }
                    }else{
                        Logger::getLogger()->log('INFO', 'The search found no users in database');
                        self::$errorCode = ErrorCode::FilterNotMatching;
                    }
                }else{
                    Logger::getLogger()->log('WARNING', 'User who requested the search has not enough privileges');
                    self::$errorCode = ErrorCode::MissingRights;
                }
            }
            return array('profiles' => array_values($usersArray));
        }

        //Get users with id and name
        public function getUsers($value){
            if($value == "visible"){
                Logger::getLogger()->log('INFO', 'Searching for all unhidden users in database');
                $users = $this->eM->getRepository('user')->findBy(array('hidden' => 0));
            }else{
                if($value == "invisible"){
                    Logger::getLogger()->log('INFO', 'Searching for all hidden users in database');
                    $users = $this->eM->getRepository('user')->findBy(array('hidden' => 1));
                }else{
                    if($value == "all"){
                        Logger::getLogger()->log('INFO', 'Searching for all users in database');
                        $users = $this->eM->getrepository('user')->findAll();
                    }else{
                        Logger::getLogger()->log('WARNING', 'A unknown filter for the search was requested');
                        self::$errorCode = ErrorCode::UnknownValue;
                    }
                }
            }
            $usersArray = array();
            foreach($users as $user){
                $userArray = array('id' => $user->getId(), 'name' => $user->getName());
                $usersArray = array_merge($usersArray, array($user->getName() => $userArray));
            }
            return array('profiles' => array_values($usersArray));
        }
        
        //Function to create a default user if the user table is empty
        public function createDefaultUser(){
            if($this->eM->getrepository('user')->findAll()==null){
                //Creating new user
                $standarduser = new User();
                $standarduser->setName('admin');
                $standarduser->setUser_type(2);
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

        //Function to create a user if the user
        public function createUser($name, $pwHash, $hidden){
            if($this->eM->getRepository('user')->findBy(array('name' => $name))[0] === Null){
                //Creating new user
                $newUser = new User();
                $newUser->setName($name);
                $newUser->setPassword_hash(\Sodium\bin2hex($pwHash));
                $newUser->setHidden($hidden);
                //Storeing created user
                $this->eM->persist($newUser);
                //Flushing changes
                $this->eM->flush();
                return ErrorCode::NoError;
            }else{
                return ErrorCode::UserAlreadyExists;
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
                if($this->user->getChlgExpiration_date() >= new DateTime('now')){
                    $sM = new SslKeyManager();
                    $this->errorCode = $sM->sDecrypt($chlg, $nonce, $key);
                    //if the decrytion worked out with no errors
                    if($this->errorCode == ErrorCode::NoError){
                        if($this->user->getChallenge() != $sM->getResult()){
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
