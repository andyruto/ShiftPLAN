<?php
    /**
     * executionChecker.php
     * 
     * PHP file that checks all permissions to execute a request.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-05-14 / Maximilian T. | Kontr0x
     */

    class ExecutionChecker{
        private $apiKey = null;
        public function setApiKey($apiKey){$this->apiKey = $apiKey;}
        private $permissions = null;
        public function setPermissions($permissions){$this->permissions = $permissions;}
        private $session = null;
        public function setSession($session){$this->session = $session;}
        private $userType = null;
        public function setUserType($userType){$this->userType = $userType;}

        private function __construct(){}

        public static function apiKeyChecker(String $apiKey){
            $obj = new self();
            $obj->setApiKey($apiKey);
            return $obj;
        }

        public static function apiKeyPermissionChecker(String $apiKey, Array $permissions){
            $obj = self::apiKeyChecker($apiKey);
            $obj->setPermissions($permissions);
            return $obj;
        }

        public static function apiKeyPermissionSessionChecker(String $apiKey, Array $permissions, String $session){
            $obj = self::apiKeyPermissionChecker($apiKey, $permissions);
            $obj->setSession($session);
            return $obj;
        }

        public static function apiKeyPermissionSessionUserTypeChecker(String $apiKey, Array $permissions, String $session, Int $userType){
            $obj = self::apiKeyPermissionSessionChecker($apiKey, $permissions, $session);
            $obj->setUserType($userType);
            return $obj;
        }

        public function check(){
            checkDb();

            $apiKeyManager = new ApiKeyManager($this->apiKey);
            $finishCode = $apiKeyManager->checkApiKey();

            if($this->success($finishCode)){
                if(!empty($this->permissions)){
                    $finishCode = $apiKeyManager->checkPermission($permissions);
                }else{
                    Logger::getLogger()->log('DEBUG', 'check permission skiped');
                }
                if($this->success($finishCode)&&!empty($this->session)){
                    $sessionManager = new SessionManager($this->session);
                    $finishCode = $sessionManager.checkSession();
                }else{
                    Logger::getLogger()->log('DEBUG', 'check session skiped');
                }
            }

            if(!$success){
                $respondArray = array();
                sendOutput($finishCode, $respondArray);
                exit();
            }
        }

        private function success($finishCode){
            return $finishCode==ErrorCode::NoError;
        }

        public function checkDb(){
            $entityManager = Bootstrap::getEntityManager();
            try{
                if($entityManager->getRepository('dbVersion')->findAll()!=null){
                    Logger::getLogger()->log("DEBUG", "DBVerson found. A database exists.");
                    (new MigrationsManager())->doMigrations();
                }else{
                    Logger::getLogger()->log("DEBUG", "No DBVersion found. Database should be corrupted or empty.");
                    $this->syncDb();
                }
            }catch (DBALException $e){
                Logger::getLogger()->log("DEBUG", "DBALException occured. Force a reset to the current DB scheme.");
                $this->syncDb();
            }
        }

        private function syncDb(){
            $entityManager = Bootstrap::getEntityManager();
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
            $classes = $entityManager->getMetadataFactory()->getAllMetadata();
            $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

            if (sizeof($SchemaUpdate) == 0) {
                Logger::getLogger()->log('INFO', 'The database is synchronized. Nothing to do.');
                self::syncDbVersion();
            } else {
                Logger::getLogger()->log('INFO', 'The database isn\'t synchronized. DB update will be triggered.');

                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
                $classes = $entityManager->getMetadataFactory()->getAllMetadata();
                $schemaTool->updateSchema($classes);

                $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($entityManager);
                $classes = $entityManager->getMetadataFactory()->getAllMetadata();
                $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

                if (sizeof($SchemaUpdate) == 0) {
                    Logger::getLogger()->log('INFO', 'The database successfully synchronized.');
                    self::syncDbVersion();
                } else {
                    Logger::getLogger()->log('ERROR', 'Error while validation of the database.' . '\n' . 'Please run \'php vendor/bin/doctrine orm:schema-tool:update\' manually.');
                    exit();
                }
            }
        }

        private static function syncDbVersion(){
            $entityManager = Bootstrap::getEntityManager();
            $dbVersion = $entityManager->getRepository('DbVersion')->findBy(array('id' => 1))[0];
            if ($dbVersion == null) {
                $dbVersion = new DbVersion();
                $dbVersion->setVersion(API_VERSION);
                $entityManager->persist($dbVersion);
            } else {
                $dbVersion->setVersion(API_VERSION);
                $entityManager->flush();
            }
        }
    }
?>