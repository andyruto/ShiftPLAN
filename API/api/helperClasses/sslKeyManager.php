<?php
    /**
     * sslManager.php
     * 
     * PHP file containing class to manage the ssl keys.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    class SslKeyManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        private $result = "";
        private $keyPair = "";

        function __construct(){
            $this->eM = Bootstrap::getEntityManager();
            $key = $this->eM->find('SslKey', 1);
            $this->keyPair = \Sodium\hex2bin($key->getKeyPair());
        }

        public static function createOrReplaceKeyPair(){
            $eM = Bootstrap::getEntityManager();
            $sslKeys = $eM->getRepository('sslKey')->findAll();
            $key = null;
            if($sslKeys==null){
                Logger::getLogger()->log('INFO', 'no ssl key in DB! creating new pair');
                $key = new SslKey();
            }elseif($sslKeys[0]->getExpiration_date() < new DateTime('now')){
                Logger::getLogger()->log('INFO', 'ssl keys expired! creating new pair');
                $key = $sslKeys[0];
            }
            if($key != null){
                $keyPair = \Sodium\crypto_box_keypair();
                $key->setKeyPair(\Sodium\bin2hex($keyPair));
                $key->setExpiration_date();
                $eM->persist($key);
                //Flushing changes
                $eM->flush();
            }
        }

        public function getPublicKey(){
            return \Sodium\crypto_box_publickey($this->keyPair);
        }

        private function getSecretKey(){
            return \Sodium\crypto_box_secretkey($this->keyPair);
        }

        public function aEncrypt($plainText, $publicKey){
            $cipherText = \Sodium\crypto_box_seal($plainText, $publicKey);
            $this->result = $cipherText;
            $this->errorCode = ErrorCode::NoError;
            return $this->errorCode;
        }

        public function aDecrypt($cipherText){
            $plainText = \Sodium\crypto_box_seal_open($cipherText, $this->keyPair);
            if($plainText === false) {
                $this->errorCode = ErrorCode::MalformedMessageOrInvalidMAC;
            }else{
                $this->errorCode = ErrorCode::NoError;
            }
            $this->result = $plainText;
            return $this->errorCode;
        }

        public function sEncrypt($plainText, $nonce, $salt, $keyString){
            $outlength = \Sodium\CRYPTO_SIGN_SEEDBYTES;
            $key = \Sodium\crypto_pwhash_scryptsalsa208sha256($outlength, $keyString, $salt, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE);
            // Using your key to encrypt information
            $cipherText = \Sodium\crypto_secretbox($plainText, $nonce, $key);
            $this->errorCode = ErrorCode::NoError;
            $this->result = $cipherText;
            return $this->errorCode;
        }

        public function sDecrypt($cipherText, $nonce, $salt, $keyString){
            $outlength = \Sodium\CRYPTO_SIGN_SEEDBYTES;
            $key = \Sodium\crypto_pwhash_scryptsalsa208sha256($outlength, $keyString, $salt, SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_OPSLIMIT_INTERACTIVE,
            SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_MEMLIMIT_INTERACTIVE);
            // Using the key to decryot information
            $plainText = \Sodium\crypto_secretbox_open($cipherText, $nonce, $key);
            if ($plainText === false) {
                $this->errorCode = ErrorCode::BadCyperText;
            }else{
                $this->errorCode = ErrorCode::NoError;
            }
            $this->result = $plainText;
            return $this->errorCode;
        }

        public function getResult(){
            $tmp = $this->result;
            $this->result = null;
            return $tmp;
        }

        public static function getSalt(){
            return \Sodium\randombytes_buf(SODIUM_CRYPTO_PWHASH_SCRYPTSALSA208SHA256_SALTBYTES);
        }

        public static function getNonce(){
            return \Sodium\randombytes_buf(\Sodium\CRYPTO_SECRETBOX_NONCEBYTES);
        }
    }

?>