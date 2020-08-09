<?php
/**
 * session.php
 * sessions table definition for doctrine framework
 * author: Maximilian T. | Kontr0x
 * last edit / by: 2020-08-08 / Maximilian T. | Kontr0x
 */

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity 
 * @ORM\Table(name="sessions")
 */
class Session
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var string
     */
    protected $id;

    /**
     * @ORM\Column(type="date")
     * @var date
     */
    protected $expiration_date;

    /**
     * @ORM\Column(type="integer")
     * @ORM\OneToMany(targetEntity="User", mappedBy="id")
     * @var int
     */
    protected $user_id;

    public function setId($id){
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getExpiration_date()
    {
        return $this->expiration_date;
    }

    public function setExpiration_date($expiration_date)
    {
        $this->expiration_date = $expiration_date;
    }

    public function getUser_id(){
        return $this->user_id;
    }

    public function setUser_id($user_id){
        $this->user_id = $user_id;
    }
}