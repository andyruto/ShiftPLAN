<?php
/**
 * api_key.php
 * api_keys table definition for doctrine framework
 * author: Maximilian T. | Kontr0x
 * last edit / by: 2020-08-08 / Maximilian T. | Kontr0x
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="api_keys")
 */
class Api_key
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $users_read;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $users_write;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $tasks_read;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $tasks_write;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $vacation_read;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $vacation_write;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $disability_certificate_read;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $disability_certificate_write;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $shift_read;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $shift_write;

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

    public function getUsers_read(){
        return $this->users_read;
    }

    public function setUsers_read($users_read){
        $this->users_read = $users_read;
    }

    public function getUsers_write(){
        return $this->users_write;
    }

    public function setUsers_write($users_write){
        $this->users_write = $users_write;
    }

    public function getTasks_read(){
        return $this->tasks_read;
    }

    public function setTasks_read($tasks_read){
        $this->tasks_read = $tasks_read;
    }

    public function getTasks_write(){
        return $this->tasks_write;
    }

    public function setTasks_write($tasks_write){
        $this->tasks_write = $tasks_write;
    }

    public function getVacation_read(){
        return $this->vacation_read;
    }

    public function setVacation_read($vacation_read){
        $this->vacation_read = $vacation_read;
    }

    public function getVacation_write(){
        return $this->vacation_write;
    }

    public function setVacation_write($vacation_write){
        $this->vacation_write = $vacation_write;
    }

    public function getDisability_certificate_read(){
        return $this->disability_certificate_read;
    }

    public function setDisabilty_certificate_read($disability_certificate_read){
        $this->disability_certificate_read = $disability_certificate_read;
    }

    public function getDisability_certificate_write(){
        return $this->disability_certificate_write;
    }

    public function setDisabilty_certificate_write($disability_certificate_write){
        $this->disability_certificate_write = $disability_certificate_write;
    }

    public function getShift_read(){
        return $this->shift_read;
    }

    public function setShift_read($shift_read){
        $this->shift_read = $shift_read;
    }

    public function getShift_write(){
        return $this->shift_write;
    }

    public function setShift_write($shift_write){
        $this->shift_write = $shift_write;
    }
}