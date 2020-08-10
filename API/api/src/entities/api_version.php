<?php
    /**
     * api_version.php
     * 
     * Api_versions table definition for doctrine framework.
     * 
     * author: Andreas G.
     * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x
     */

    use Doctrine\ORM\Mapping as ORM;

    /**
     * @ORM\Entity 
     * @ORM\Table(name="tb_api_version")
     */
    class Api_version
    {
        /**
         * @ORM\Id 
         * @ORM\GeneratedValue 
         * @ORM\Column(type="integer")
         * @var int
         */
        protected $id;

        /**
         * @ORM\Column(type="string", nullable=false)
         * @var string
         */
        protected $version;
// id
        public function getId() {
            return $this->id;
        }
// version
        public function getVersion() {
            return $this->version;
        }
        public function setVersion($version) {
            $this->version = $version;
        }
    }
?>