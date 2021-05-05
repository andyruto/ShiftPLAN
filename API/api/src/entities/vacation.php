<?php
    /**
     * vacation.php
     * 
     * Vacation table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-04-21 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_vacations")
     */
    class Vacation
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
         * @var date
         */
        protected $start_date;

        /**
         * @ORM\Column(type="date", nullable=false)
         * @var date
         */
        protected $end_date;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $user_id;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var boolean
         */
        protected $approved;

        /**
         * @ORM\Column(type="date", nullable=true)
         * @var date
         */
        protected $last_modified;

        /**
         * @ORM\Column(type="integer", nullable=true)
         * @var int
         */
        protected $last_modified_by;
// id
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
// approved
        public function getApproved(){
            return $this->approved;
        }
        public function setApproved($approved){
            $this->approved = $approved;
        }
// last_modified
        public function getLast_modified(){
            return $this->last_modified;
        }
        public function setLast_modified($last_modified){
            $this->last_modified = $last_modified;
        }
// last_modified_by
        public function getLast_modified_by(){
            return $this->last_modified_by;
        }
        public function setLast_modified_by($last_modified_by){
            $this->last_modified_by = $last_modified_by;
        }
    }
?>