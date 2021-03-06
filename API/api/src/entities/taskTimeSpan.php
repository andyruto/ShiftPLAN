<?php
    /**
     * taskTimeSpan.php
     * 
     * Task time spans table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-07-18 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_task_time_spans")
     */
    class TaskTimeSpan
    {
        /**
         * @ORM\Id 
         * @ORM\GeneratedValue 
         * @ORM\Column(type="integer")
         * @var int
         */
        protected $id;

        /**
         * @ORM\Column(type="date", nullable=false)
         * @var Date
         */
        protected $appointed_day;

        /**
         * @ORM\Column(type="datetime", nullable=false)
         * @var Date
         */
        protected $start;

        /**
         * @ORM\Column(type="datetime", nullable=false)
         * @var Date
         */
        protected $end;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $required_employees;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @ORM\OneToMany(targetEntity="User", mappedBy="id")
         * @var int
         */
        protected $last_modified_by;

        /**
         * @ORM\Column(type="datetime", nullable=false)
         * @var DateTime
         */
        protected $last_modified;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @ORM\OneToMany(targetEntity="Task", mappedBy="id")
         * @var int
         */
        protected $task_id;
// id
        public function getId(){
            return $this->id;
        }
// appointed_day
        public function getAppointed_day(){
            return $this->appointed_day;
        }
        public function setAppointed_day($appointed_day){
            $this->appointed_day = date_create_from_format('Y-m-d', $appointed_day);
        }
// start
        public function getStart(){
            return $this->start;
        }
        public function setStart($start){
            $this->start = date_create_from_format('Y-m-d', $start);
        }
// end
        public function getEnd(){
            return $this->end;
        }
        public function setEnd($end){
            $this->end = date_create_from_format('Y-m-d', $end);
        }
// required_employees
        public function getRequired_employees(){
            return $this->required_employees;
        }
        public function setRequired_employees($required_employees){
            $this->required_employees = $required_employees;
        }
// last_modified_by
        public function getLast_modified_by(){
            return $this->last_modified_by;
        }
        public function setLast_modified_by($last_modified_by){
            $this->last_modified_by = $last_modified_by;
        }
// last_modified
        public function getLast_modified(){
            return $this->last_modified;
        }
        public function setLast_modified(){
            $this->last_modified = new DateTime('now');
        }
// task_id
        public function getTask_id(){
            return $this->task_id;
        }
        public function setTask_id($task_id){
            $this->task_id = $task_id;
        }
    }
?>