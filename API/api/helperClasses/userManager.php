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

        public static function createDefaultUser(){
            $eM = Bootstrap::getEntityManager();
            if($eM->getrepository('user')->findAll()==null){
                //Creating new user
                $standarduser = new User();
                $standarduser->setName('admin');
                $pwHash = \Sodium\crypto_pwhash_scryptsalsa208sha256_str('admin', 
                    SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE,
                    SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE);
                $standarduser->setPassword_hash($pwHash);
                //Storeing created user
                $eM->persist($standarduser);
                //Flushing changes
                $eM->flush();
            }
        }
    }
?>