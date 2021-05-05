<?php
    /**
     * index.php
     * Script checking the structure of the current api version. If
     * the structure is missing this script will provide the functionality
     * to create the api structure and DB. If it exists and is valid for
     * the current version it'll show a message.
     * 
     * author: Andreas G.
     * last edit / by: 2021-04-24 / Maximilian T. | Kontr0x
     */

    require 'prepareExec.php';

    //Class launching the main script checking the DB and API
    //and invoking the prepare script if required.
    final class Main {
        //Method invoked on script execution
        public static function run() {

            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(Bootstrap::getEntityManager());
            $classes = Bootstrap::getEntityManager()->getMetadataFactory()->getAllMetadata();
            $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

            if (sizeof($SchemaUpdate) == 0) {
                Logger::getLogger()->log('INFO', 'The database is synchronized. Nothing to do.');
            } else {
                    Logger::getLogger()->log('INFO', 'The database isn\'t synchronized. DB update will be triggered.');

                    $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(Bootstrap::getEntityManager());
                    $classes = Bootstrap::getEntityManager()->getMetadataFactory()->getAllMetadata();
                    $schemaTool->updateSchema($classes);

                    $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(Bootstrap::getEntityManager());
                    $classes = Bootstrap::getEntityManager()->getMetadataFactory()->getAllMetadata();
                    $SchemaUpdate = $schemaTool->getUpdateSchemaSql($classes);

                    if (sizeof($SchemaUpdate) == 0) {
                        Logger::getLogger()->log('INFO', 'The database successfully synchronized.');
                    } else {
                        Logger::getLogger()->log('ERROR', 'Error while validation of the database.' . '\n' . 'Please run \'php vendor/bin/doctrine orm:schema-tool:update\' manually.');
                    }
            }
            $entityManager = Bootstrap::getEntityManager();
            if($entityManager->getRepository('apiKey')->findAll()==null){
                //Creating new user
                $standarduser = new User();
                $standarduser->setName('admin');
                $standarduser->setPassword_hash('8122cba12b897aa5546baf90b6c82c9f646f976b3555033cbc5e0b72d4f7a5bc');
                //Storeing created user
                $entityManager->persist($standarduser);
                //Creating new api key
                $standardApiKey = new ApiKey();
                $standardApiKey->setid(generateRandomString(20));
                $standardApiKey->setName('standardApiKey');
                //Storeing created api key
                $entityManager->persist($standardApiKey);
                //Flushing changes
                $entityManager->flush();
                header('Content-Type: application/json');
                $respondJSON = array('success' => false, 'apiKey' => $standardApiKey->getId());
                echo(json_encode($respondJSON));
                exit();
            }
            // Takes raw data from the request
            $json = file_get_contents('php://input');
            
            if(checkApiKey(json_decode($json)->api_key)){
                header('Content-Type: application/json');
                $respondJSON = array('success' => true);
                echo(json_encode($respondJSON));
                exit();
            }
            
        }
    }

    Main::run();
?>