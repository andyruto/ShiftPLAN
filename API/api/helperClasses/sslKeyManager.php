<?php
    /**
     * sslManager.php
     * 
     * PHP file containing class to manage the ssl keys.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-23 / Maximilian T. | Kontr0x
     */

    class SslKeyManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        private $result = ""; //Storing result of function ouput
        private $keyPair = ""; //Storing keypair to return keys

        function __construct(){
            $this->eM = Bootstrap::getEntityManager();
            $key = $this->eM->find('SslKey', 1);
            //Formating key pair to get it work with sodium
            $this->keyPair = \Sodium\hex2bin($key->getKeyPair());
        }

        //Creating or replacing the ssl key pair
        public static function createOrReplaceKeyPair(){
            $eM = Bootstrap::getEntityManager();
            $sslKeys = $eM->getRepository('sslKey')->findAll();
            $key = null;
            //No ssl keys stored in the DB
            if($sslKeys==null){
                Logger::getLogger()->log('INFO', 'no ssl key in DB! creating new pair');
                $key = new SslKey();
            //Checking if ssl key pair is expired
            }elseif($sslKeys[0]->getExpiration_date() < new DateTime('now')){
                Logger::getLogger()->log('INFO', 'ssl keys expired! creating new pair');
                $key = $sslKeys[0];
            }
            //Creating new ssl key pair
            if($key != null){
                $keyPair = \Sodium\crypto_box_keypair();
                $key->setKeyPair(\Sodium\bin2hex($keyPair));
                $key->setExpiration_date();
                $eM->persist($key);
                //Flushing changes
                $eM->flush();
            }
        }

        //Returning public key in hex format
        public function getPublicKey(){
            return \Sodium\bin2hex(\Sodium\crypto_box_publickey($this->keyPair));
        }

        //Returning private key in bin format
        private function getSecretKey(){
            return \Sodium\crypto_box_secretkey($this->keyPair);
        }

        //Encrypting plaintext asynchron
        public function aEncrypt($plainText, $publicKey){
            $cipherText = \Sodium\crypto_box_seal($plainText, \Sodium\hex2bin($publicKey));
            $this->result = \Sodium\bin2hex($cipherText);
            $this->errorCode = ErrorCode::NoError;
            return $this->errorCode;
        }

        //Decrypting ciphertext asynchron
        public function aDecrypt($cipherText){
            $plainText = \Sodium\crypto_box_seal_open(\Sodium\hex2bin($cipherText), $this->keyPair);
            //If decryption failed setting errorcode
            if($plainText === false) {
                $this->errorCode = ErrorCode::MalformedMessageOrInvalidMAC;
            }else{
                $this->errorCode = ErrorCode::NoError;
            }
            $this->result = $plainText;
            return $this->errorCode;
        }

        //Encrypting plaintext synchron
        public function sEncrypt($plainText, $nonce, $key){
            // Using your key to encrypt information
            $cipherText = \Sodium\crypto_secretbox($plainText, \Sodium\hex2bin($nonce), \Sodium\hex2bin($key));
            $this->errorCode = ErrorCode::NoError;
            $this->result = \Sodium\bin2hex($cipherText);
            return $this->errorCode;
        }

        //Decrypting ciphertext synchron
        public function sDecrypt($cipherText, $nonce, $key){
            // Using the key to decryot information
            $plainText = \Sodium\crypto_secretbox_open(\Sodium\hex2bin($cipherText), \Sodium\hex2bin($nonce), \Sodium\hex2bin($key));
            //If decryption failed setting errorcode
            if ($plainText === false) {
                $this->errorCode = ErrorCode::BadCyperText;
            }else{
                $this->errorCode = ErrorCode::NoError;
            }
            $this->result = $plainText;
            return $this->errorCode;
        }

        //Returning result
        public function getResult(){
            $tmp = $this->result;
            //Resetting result to prevent dublicates
            $this->result = null;
            return $tmp;
        }

        //Genrating a new nonce for encrytion
        public static function getNonce(){
            return \Sodium\bin2hex(\Sodium\randombytes_buf(\Sodium\CRYPTO_SECRETBOX_NONCEBYTES));
        }
    }
?>