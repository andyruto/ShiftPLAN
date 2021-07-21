<?php
    /**
     * apiKey.php
     * 
     * Api_keys table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-05 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_api_keys")
     */
    class ApiKey
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="string", nullable=false)
         * @var string
         */
        protected $id;

        /**
         * @ORM\Column(type="string", unique=true, nullable=false)
         * @var string
         */
        protected $name;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $apiKey_read = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $apiKey_write = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $sessions_read = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $sessions_write = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $users_read = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $users_write = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $tasks_read = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $tasks_write = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $vacation_read = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $vacation_write = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $sick_note_read = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $sick_note_write = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $shift_read = false;

        /**
         * @ORM\Column(type="boolean", nullable=false)
         * @var bool
         */
        protected $shift_write = false;
// id
        public function getId(){
            return $this->id;
        }
        public function setId($id){
            $this->id=$id;
        }
// name
        public function getName(){
            return $this->name;
        }
        public function setName($name){
            $this->name = $name;
        }
// apiKey_read
        public function getApiKey_read(){
            return $this->apiKey_read;
        }
        public function setApiKey_read($apiKey_read){
            $this->apiKey_read = $apiKey_read;
        }
// apiKey_write
        public function getApiKey_write(){
            return $this->apiKey_write;
        }
        public function setApiKey_write($apiKey_write){
            $this->apiKey_write = $apiKey_write;
        }
// sessions_read
        public function getSessions_read(){
            return $this->sessions_read;
        }
        public function setSessions_read($sessions_read){
            $this->sessions_read = $sessions_read;
        }
// sessions_write
        public function getSessions_write(){
            return $this->sessions_write;
        }
        public function setSessions_write($sessions_write){
            $this->sessions_write = $sessions_write;
        }
// users_read
        public function getUsers_read():bool{
            return $this->users_read;
        }
        public function setUsers_read($users_read){
            $this->users_read = $users_read;
        }
// users_write
        public function getUsers_write(){
            return $this->users_write;
        }
        public function setUsers_write($users_write){
            $this->users_write = $users_write;
        }
// tasks_read
        public function getTasks_read(){
            return $this->tasks_read;
        }
        public function setTasks_read($tasks_read){
            $this->tasks_read = $tasks_read;
        }
// tasks_write
        public function getTasks_write(){
            return $this->tasks_write;
        }
        public function setTasks_write($tasks_write){
            $this->tasks_write = $tasks_write;
        }
// vacation_read
        public function getVacation_read(){
            return $this->vacation_read;
        }
        public function setVacation_read($vacation_read){
            $this->vacation_read = $vacation_read;
        }
// vacation write
        public function getVacation_write(){
            return $this->vacation_write;
        }
        public function setVacation_write($vacation_write){
            $this->vacation_write = $vacation_write;
        }
// sick_note_read
        public function getSick_note_read(){
            return $this->sick_note_read;
        }
        public function setSick_note_read($sick_note_read){
            $this->sick_note_read = $sick_note_read;
        }
// sick_note_write
        public function getSick_note_write(){
            return $this->sick_note_write;
        }
        public function setSick_note_write($sick_note_write){
            $this->sick_note_write = $sick_note_write;
        }
// shift_read
        public function getShift_read(){
            return $this->shift_read;
        }
        public function setShift_read($shift_read){
            $this->shift_read = $shift_read;
        }
// shift_write
        public function getShift_write(){
            return $this->shift_write;
        }
        public function setShift_write($shift_write){
            $this->shift_write = $shift_write;
        }
    }
?>