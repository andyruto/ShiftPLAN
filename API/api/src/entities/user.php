<?php
    /**
     * user.php
     * 
     * Users table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-09 / Andreas G.
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
        protected $weekly_working_minutes = 0;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $working_week_days = 0;

        /**
         * @ORM\Column(type="integer", nullable=false)
         * @var int
         */
        protected $year_vacation_days = 0;

        /**
         * @ORM\Column(type="string", nullable=false)
         * @var string
         */
        protected $password_hash;

        public function getId()
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function getHidden(){
            return $this->hidden;
        }

        public function setHidden($hidden){
            $this->hidden = $hidden;
        }

        public function getWeekly_working_minutes(){
            return $this->weekly_working_minutes;
        }

        public function setWeekly_working_minutes($weekly_working_minutes){
            $this->weekly_working_minutes = $weekly_working_minutes;
        }

        public function getWorking_week_days(){
            return $this->working_week_days;
        }

        public function setWorking_week_days($working_week_days){
            $this->working_week_days = $working_week_days;
        }

        public function getYear_vacation_days(){
            return $this->year_vacation_days;
        }

        public function setYear_vacation_days($year_vacation_days){
            $this->year_vacation_days = $year_vacation_days;
        }

        public function getPassword_hash(){
            return $this->hidden;
        }

        public function setPassword_hash($password_hash){
            $this->password_hash = $password_hash;
        }
    }
?>