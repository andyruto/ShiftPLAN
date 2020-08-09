<?php
    /**
     * session.php
     * 
     * Sessions table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-09 / Andreas G.
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_sessions")
     */
    class Session
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="string")
         * @var string
         */
        protected $id;

        /**
         * @ORM\Column(type="datetime", nullable=false)
         * @var DateTime
         */
        protected $expiration_date;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @ORM\OneToMany(targetEntity="User", mappedBy="id")
         * @var int
         */
        protected $user_id;

        public function getId()
        {
            return $this->id;
        }
        
        public function setId($id) {
            $this->id = $id;
        }

        public function getExpiration_date()
        {
            return $this->expiration_date;
        }

        public function setExpiration_date()
        {
            $this->expiration_date = new DateTime('now');
            $this->expiration_date->modify('+1 month');
        }

        public function getUser_id(){
            return $this->user_id;
        }

        public function setUser_id($user_id){
            $this->user_id = $user_id;
        }
    }
?>