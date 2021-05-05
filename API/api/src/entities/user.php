<?php
    /**
     * user.php
     * 
     * Users table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-21 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_users")
     */
    class User
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
         * @var int
         */
        protected $user_type = 0;

        /**
         * @ORM\Column(type="string", unique=true, nullable=false)
         * @var string
         */
        protected $name;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $hidden = 0;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $overtime = 0;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $weekly_working_minutes = 0;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $weekly_working_days = 0;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $year_vacation_days = 0;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $login_attemps = 0;

        /**
         * @ORM\Column(type="string", nullable=false, length=64)
         * @var string
         */
        protected $password_hash;
// id
        public function getId(){
            return $this->id;
        }
// user type
        public function getUser_type(){
            return $this->user_type;
        }
        public function setUser_type($user_type){
            $this->user_type = $user_type;
        }
// name
        public function getName(){
            return $this->name;
        }
        public function setName($name){
            $this->name = $name;
        }
// hidden
        public function getHidden(){
            return $this->hidden;
        }
        public function setHidden($hidden){
            $this->hidden = $hidden;
        }
// overtime
        public function getOvertime(){
            return $this->overtime;
        }
        public function setOvertime($overtime){
            $this->overtime = $overtime;
        }
// weekly working minutes
        public function getWeekly_working_minutes(){
            return $this->weekly_working_minutes;
        }
        public function setWeekly_working_minutes($weekly_working_minutes){
            $this->weekly_working_minutes = $weekly_working_minutes;
        }
// weekly working days
        public function getWeekly_working_days(){
            return $this->weekly_working_days;
        }
        public function setWeekly_working_days($weekly_working_days){
            $this->weekly_working_days = $weekly_working_days;
        }
// year vacation days
        public function getYear_vacation_days(){
            return $this->year_vacation_days;
        }
        public function setYear_vacation_days($year_vacation_days){
            $this->year_vacation_days = $year_vacation_days;
        }
// login_attemps
        public function getLogin_attemps(){
            return $this->login_attemps;
        }
        public function setLogin_attemps($login_attemps){
            $this->login_attemps = $login_attemps;
        }
// password hash
        public function getPassword_hash(){
            return $this->password_hash;
        }
        public function setPassword_hash($password_hash){
            $this->password_hash = $password_hash;
        }
    }
?>