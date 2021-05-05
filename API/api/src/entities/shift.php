<?php
    /**
     * shift.php
     * 
     * Shifts table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_shifts")
     */
    class Shift
    {
        /**
         * @ORM\Id 
         * @ORM\GeneratedValue 
         * @ORM\Column(type="integer")
         * @var int
         */
        protected $id;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @ORM\OneToMany(targetEntity="User", mappedBy="id")
         * @var int
         */
        protected $assignee;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @ORM\OneToMany(targetEntity="User", mappedBy="id")
         * @var int
         */
        protected $supervisor;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @ORM\OneToMany(targetEntity="Task", mappedBy="id")
         * @var int
         */
        protected $task;

        /**
         * @ORM\Column(type="datetime", nullable=false)
         * @var DateTime
         */
        protected $shift_start;

        /**
         * @ORM\Column(type="datetime", nullable=false)
         * @var DateTime
         */
        protected $shift_end;

        /**
         * @ORM\Column(type="string", nullable=false, length=64)
         * @var string
         */
        protected $comment;

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
// id
        public function getId(){
            return $this->id;
        }
// assigned_user
        public function getAssigned_user(){
            return $this->assigned_user;
        }
        public function setAssigned_user($assigned_user){
            $this->assigned_user = $assigned_user;
        }
// supervisor_user
        public function getSupervisor_user(){
            return $this->supervisor_user;
        }
        public function setSupervisor_user($supervisor_user){
            $this->supervisor_user = $supervisor_user;
        }
// task
        public function getTask(){
            return $this->task;
        }
        public function setTask($task){
            $this->task = $task;
        }
// shift_start
        public function getShift_start(){
            return $this->shift_start;
        }
        public function setShift_start($shift_start){
            $this->shift_start = $shift_start;
        }
// shift_end
        public function getShift_end(){
            return $this->shift_end;
        }
        public function setShift_end($shift_end){
            $this->shift_end = $shift_end;
        }
// comment
        public function getComment(){
            return $this->comment;
        }
        public function setComment($comment){
            $this->comment = $comment;
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
        public function setLast_modified($last_modified){
            $this->last_modified = $last_modified;
        }
    }
?>