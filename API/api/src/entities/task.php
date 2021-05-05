<?php
    /**
     * task.php
     * 
     * Tasks table definition for doctrine framework.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-04-13 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_tasks")
     */
    class Task
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
// id
        public function getId(){
            return $this->id;
        }
// name
        public function getName(){
            return $this->name;
        }
        public function setName($name){
            $this->name = $name;
        }
    }
?>