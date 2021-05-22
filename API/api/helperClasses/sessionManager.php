<?php
    /**
     * sessionManager.php
     * 
     * PHP file containing class to manage the sessions.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    class SessionManager{

        private $eM = null; //Variable to store entity manager in
        private $session = null;
        private $errorCode = ErrorCode::NoError;

        function __construct($session){
            Logger::getLogger()->log('DEBUG', 'Called session manager');
            //Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();
            if(!empty($session)){
                //Looking for session in database
                $this->session = $this->eM->find('Session', $session);
                if($this->session == null){
                    Logger::getLogger()->log('ERROR', "Session ".$session." doesn't exist in database");
                    $this->errorCode = ErrorCode::InvalidSession;
                }
            }else{
                Logger::getLogger()->log('ERROR', 'Parameter session is empty');
                $this->errorCode = ErrorCode::NoSessionGiven;
            }
        }

        function checkSession(){
            if($this->errorCode == ErrorCode::NoError){
                if($session->getExpiration_date() < new DateTime('now')){
                    Logger::getLogger()->log('DEBUG', 'session '.$this->session->getId().' expired');
                    $eM->remove($session);
                    $eM->flush();
                    $eM->clear();
                    $this->errorCode = ErrorCode::SessionExpired;
                }
            }
            return $this->errorCode;
        }
        
        //Adding a Session
        function createSession($username, $passwordHash){
            // Checking of parameters are set
            if(!empty($username)&&!empty($passwordHash&&$this->errorCode==Error::NoSessionGiven)){
                $this->errorCode = ErrorCode::NoError;
                // Looking for user with username in database
                $user = $entityManager->getRepository('User')->findBy(array('name' => $username));
                // If user not found the entity managaer returns null
                if($user[0]==null){
                    Logger::getLogger()->log('INFO', $username.' user not found');
                    $this->errorCode = ErrorCode::UserNotFound;
                // Checking of password hash matches stored password hash
                }elseif($user[0]->getPassword_hash()==$passwordHash){
                    Logger::getLogger()->log('INFO', 'password for '.$username.' ok');
                    $session = new Session();
                    $sessionKey = generateRandomString(20);
                    while($sessionKey==$entityManager->find('Session', $sessionKey)){
                        $sessionKey = generateRandomString(20);
                    }
                    // creating new session for user
                    $session->setId($sessionKey);
                    $session->setExpiration_date();
                    $session->setUser_id($user[0]->getid());
                    $entityManager->persist($session);
                    $entityManager->flush();
                    Logger::getLogger()->log('INFO', 'new session created with id = '.$session->getId());
                    $respondArray = array('session' => $session->getId());
                    sendOutput($this->errorCode, $respondArray);
                }else{
                    // user were found in db but password was incorrect
                    Logger::getLogger()->log('INFO', 'wrong password for'.$username);
                    $this->errorCode = ErrorCode::WrongPassword;
                }
            }
            return $this->errorCode;
        }

        //Checking linked user permission
        function checkPermission($permissions){
            if($this->errorCode==ErrorCode::NoError){

            }
            return $this->errorCode;
        }
    }
?>