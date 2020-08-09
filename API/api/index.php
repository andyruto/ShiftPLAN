<!--
-- index.php
--
-- Script checking the structure of the current api version. If
-- the structure is missing this script will provide the functionality
-- to create the api structure and DB. If it exists and is valid for
-- the current version it'll show a message.
--
-- author: Andreas G.
-- last edit / by: 2020-08-09 / Andreas G.
-->
<?php
    require 'prepareExec.php';

    //Class launching the main script checking the DB and API
    //and invoking the prepare script if required.
    final class Main {
        //Method invoked on script execution
        public static function run() {
            $out = null;
            $rv = null;

            exec('vendor/bin/doctrine orm:validate-schema', $out, $rv);

            if ($rv == 0) {
                Logger::getLogger()->log('INFO', 'The database is synchronized. Nothing to do.');
            } else {
                if ($rv != null && $rv != 0) {
                    Logger::getLogger()->log('INFO', 'The database isn\'t synchronized. DB update will be triggered.');

                    $schemaTool = new \Doctrine\ORM\Tools\SchemaTool(Bootstrap::getEntityManager());
                    $classes = Bootstrap::getEntityManager()->getMetadataFactory()->getAllMetadata();
                    $schemaTool->createSchema($classes);

                    exec('vendor/bin/doctrine orm:validate-schema', $out, $rv);

                    if ($rv == 0) {
                        Logger::getLogger()->log('INFO', 'The database successfully synchronized.');
                    } else {
                        Logger::getLogger()->log('ERROR', 'Error while validation of the database.' . '\n' . 'Please run \'php vendor/bin/doctrine orm:schema-tool:update\' manually.');
                    }
                } else {
                    Logger::getLogger()->log('ERROR', 'Error while validation of the database.' . '\n' . 'Please run \'php vendor/bin/doctrine orm:validate-schema\' manually.');
                }
            }

            var_dump($_POST);
        }
    }

    Main::run();
?>