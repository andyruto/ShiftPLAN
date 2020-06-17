<!--
-- index.php
--
-- Script checking the structure of the current api version. If
-- the structure is missing this script will provide the functionality
-- to create the api structure and DB. If it exists and is valid for
-- the current version it'll show a message.
--
-- author: Andreas G.
-- last edit / by: 2020-06-14 / Andreas G.
-->
<?php
    require 'prepareExec.php';

    //Class launching the main script checking the DB and API
    //and invoking the prepare script if required.
    final class Main {
        //Method invoked on script execution
        public static function run() {
            Logger::getLogger()->log('DEBUG', 'Test Test Test');

            Logger::getLogger()->log('DEBUG', 'RootDir: '.__DIR__);
        }
    }

    Main::run();
?>