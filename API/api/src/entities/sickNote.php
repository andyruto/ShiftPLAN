<?php
    /**
     * sickNote.php
     * 
     * Sick note table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_sick_note")
     */
    class SickNote
    {
        /**
         * @ORM\Id 
         * @ORM\GeneratedValue 
         * @ORM\Column(type="integer")
         * @var int
         */
        protected $id;

        /**
         * @ORM\Column(type="date", unique=true, nullable=false)
         * @var Date
         */
        protected $start_date;

        /**
         * @ORM\Column(type="date", nullable=false)
         * @var Date
         */
        protected $end_date;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @ORM\OneToMany(targetEntity="User", mappedBy="id")
         * @var int
         */
        protected $user_id;

        public function getId(){
            return $this->id;
        }
// start_date
        public function getStart_date(){
            return $this->start_date;
        }
        public function setStart_date($start_date){
            $this->start_date = $start_date;
        }
// end_date
        public function getEnd_date(){
            return $this->start_date;
        }
        public function setEnd_date($end_date){
            $this->end_date = $end_date;
        }
// user_id
        public function getUser_id(){
            return $this->user_id;
        }
        public function setUser_id($user_id){
            $this->user_id = $user_id;
        }
    }
?>