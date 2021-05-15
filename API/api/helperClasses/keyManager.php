<?php
    /**
     * sslManager.php
     * 
     * PHP file containing class to manage the ssl keys.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    class SslManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;

        function __construct(){
            
        }

        private function createPrivateKey(){
            $key = new SslKey();
            $private_key = openssl_pkey_new();
            $public_key_pem = openssl_pkey_get_details($private_key)['key'];
            $key->setId($public_key_pem);
            $key->setExpiration_date();
            $this->eM->persist($session);
            $this->eM->flush();
        }

        public function getPublicKey(){
            if($this->eM->getRepository('sslKey')->findAll()!=null){
                $key = $this->eM->getRepository('sslKeys')->findBy(array('id' => 1))[0];
                return $public_key = openssl_pkey_get_public($key);
            }
        }
    }

?>