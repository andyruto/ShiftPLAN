<?php
    /**
     * sessionManager.php
     * 
     * PHP file containing class to manage the sessions.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-26 / Maximilian T. | Kontr0x
     */

    class SessionManager{
        private $eM = null; //Variable to store entity manager in
        private $session = null; //Storing a session entity object
        private $errorCode = ErrorCode::NoError; //The error code for the current object

        private function __construct($sessionId){
            Logger::getLogger()->log('DEBUG', 'Called session manager');
            //Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();
            //If session is not empty
            if(!empty($sessionId)){
                //Looking for session in database
                $this->session = $this->eM->find('Session', $sessionId);
                //If the manager found the session with given id continue
                if($this->session == null){
                    Logger::getLogger()->log('ERROR', "Session ".$sessionId." doesn't exist in database");
                    $this->errorCode = ErrorCode::InvalidSession;
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter session is empty. Probably called as creator');
                $this->errorCode = ErrorCode::NoSessionGiven;
            }
        }

        //Overload constructor to create new entities
        public static function creator(){
            return new self(null);
        }

        //Overload constructor to work wirh existing entities
        public static function obj($sessionId){
            return new self($sessionId);
        }

        //Check if session is still valid otherwise delete the session and give feedback
        public function checkSession(){
            //check if session was found
            if($this->errorCode == ErrorCode::NoError){
                Logger::getLogger()->log('DEBUG', 'Checking session');
                //Check if session is not expired
                if($this->session->getExpiration_date() < new DateTime('now')){
                    Logger::getLogger()->log('DEBUG', 'session '.$this->session->getId().' expired');
                    $this->eM->remove($this->session);
                    $this->eM->flush();
                    $this->eM->clear();
                    $this->errorCode = ErrorCode::SessionExpired;
                }else{
                    //Check if user is not hidden
                    $user = $this->eM->find('user', $this->session->getUser_id());
                    //If the user is hidden remove the session to hinder a login
                    if($user->getHidden()){
                        Logger::getLogger()->log('DEBUG', 'user '.$user->getName().' not visible any more. Session will be closed.');
                        $this->eM->remove($this->session);
                        $this->eM->flush();
                        $this->eM->clear();
                        $this->errorCode = ErrorCode::SessionExpired;
                    }else{
                        Logger::getLogger()->log('DEBUG', 'Session valid');
                    }
                }
            }
            //Check if session was valid and exend the expiration date
            if($this->errorCode == ErrorCode::NoError){
                $this->session->setExpiration_date();
                $this->eM->flush();
            }
            return $this->errorCode;
        }
        
        //Adding a Session
        public function createSession($userName){
            // Checking of parameters are set
            if(!empty($userName)){
                $this->errorCode = ErrorCode::NoError;
                // Looking for user with username in database
                $user = $this->eM->getRepository('User')->findBy(array('name' => $userName))[0];
                // If user not found the entity managaer returns null
                if($user==null){
                    Logger::getLogger()->log('Error', $userName.' user not found');
                    $this->errorCode = ErrorCode::UserNotFound;
                }else{
                    $this->session = new Session();
                    $sessionKey = generateRandomString(20);
                    while($this->eM->find('Session', $sessionKey) != null){
                        $sessionKey = generateRandomString(20);
                    }
                    // creating new session for user
                    $this->session->setId($sessionKey);
                    $this->session->setExpiration_date();
                    $this->session->setUser_id($user->getid());
                    $this->eM->persist($this->session);
                    $this->eM->flush();
                    Logger::getLogger()->log('DEBUG', 'new session created with id = '.$this->session->getId());
                }
            }
            return $this->errorCode;
        }

        //Function to remove session from database
        public function removeSession(){
            if($this->errorCode == ErrorCode::NoError){
                $this->eM->remove($this->session);
                $this->eM->flush();
                $this->eM->clear();
            }
        }

        //Checking linked user type
        public function checkUserType($userType){
            if($this->errorCode==ErrorCode::NoError){
                $user = $this->eM->find('user', $this->session->getUser_id());
                //Checking if user has enough permissions to run script
                if($user->getType()<$userType){
                    Logger::getLogger()->log('Error', 'user '.$user->getName().' is not privileged enough');
                    $this->errorCode = ErrorCode::UserTypeNotMatching;
                }
            }
            return $this->errorCode;
        }

        //Returning user name associted with the session
        public function getUserName(){
            $user = $this->eM->find('user', $this->session->getUser_id());
            return $user->getName();
        }

        //Returning database object of class
        public function getDbObject(){
            if($this->errorCode == ErrorCode::NoError){
                return $this->session;
            }
        }

        //Returning the finish code of that object
        public function getFinishCode() {
            return $this->errorCode;
        }
    }
?>