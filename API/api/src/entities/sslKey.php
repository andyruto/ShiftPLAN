<?php
    /**
     * sslKey.php
     * 
     * SSL key table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_ssl_key")
     */
    class SslKey
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="string", nullable=false)
         * @var string
         */
        protected $id;

        /**
         * @ORM\Column(type="datetime", nullable=false)
         * @var DateTime
         */
        protected $expiration_date;

// id
        public function getId(){
            return $this->id;
        }
        public function setId($id){
            $this->id = $id;
        }
// expiration date
        public function getExpiration_date(){
            return $this->expiration_date;
        }
        public function setExpiration_date(){
            $this->expiration_date = new DateTime('now');
            $this->expiration_date->modify('+1 year');
        }
    }
?>