<?php

// index.php
// 
// Script checking the structure of the current api version. If
// the structure is missing this script will provide the functionality
// to create the api structure and DB. If it exists and is valid for
// the current version it'll show a message.
// 
// author: Andreas G.
// last edit / by: 2020-08-09 / Maximilian T. | Kontr0x

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
            if($entityManager->getRepository('Api_key')->findAll()==null){
                $standartuser = new User();
                $standartuser->setName('admin');
                $standartuser->setPassword_hash('8122cba12b897aa5546baf90b6c82c9f646f976b3555033cbc5e0b72d4f7a5bc');
                $standartApiKey = new Api_key();
                $standartApiKey->setid(generateRandomString(20));
                $standartApiKey->setName('standartApiKey');
                $entityManager->persist($standartApiKey);
                $entityManager->flush();
                header('Content-Type: application/json');
                $respondJSON = array('success' => false, 'apikey' => apiKey);
                echo(json_encode($respondJSON));
                exit();
            }elseif(checkApiKey()){

            }
        }
    }

    Main::run();
?>