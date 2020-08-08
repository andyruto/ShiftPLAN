<!--
-- index.php
--
-- Script checking the structure of the current api version. If
-- the structure is missing this script will provide the functionality
-- to create the api structure and DB. If it exists and is valid for
-- the current version it'll show a message.
--
-- author: Andreas G.
-- last edit / by: 2020-08-08 / Andreas G.
-->
<?php
    require 'prepareExec.php';

    //Class launching the main script checking the DB and API
    //and invoking the prepare script if required.
    final class Main {
        //Method invoked on script execution
        public static function run() {
        }
    }

    Main::run();
?>