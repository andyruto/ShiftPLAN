<?php
    /**
     * migration_0_0_2.php
     * 
     * PHP file containing migration for version 0.0.2.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-15 / Maximilian T. | Kontr0x
     */

    class Migration_0_0_2{
        private $sql = ""; //Variable containing the sql query to execute
        private $eM = null; //The entity manager for db access

        //Constructor preparing the entity manager
        public function __construct(){
            $this->eM = Bootstrap::getEntityManager();
        }

        //Method executed on up migration
        public function up(){
            //Preparing the sql string
            $this->sql .= "ALTER TABLE tb_db_version ADD previous varchar(255);";

            //Preparing and executing the sql
            $stmt = $this->eM->getConnection()->prepare($this->sql);
            $stmt->execute();
        }

        //Method executed on down migration
        public function down(){
            //Preparing the sql string
            $this->sql .= "ALTER TABLE tb_db_version DROP previous;";

            //Preparing and executing the sql
            $stmt = $this->eM->getConnection()->prepare($this->sql);
            $stmt->execute();
        }
    }
?>