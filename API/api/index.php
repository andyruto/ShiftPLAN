<!--
-- index.php
--
-- Script checking the structure of the current api version. If
-- the structure is missing this script will provide the functionality
-- to create the api structure and DB. If it exists and is valid for
-- the current version it'll show a message.
--
-- author: Andreas G.
-- last edit / by: 2020-06-10 / Andreas G.
-->
<?php
    require 'prepareExec.php';

    Logger::getLogger()->log('ERROR', 'Current time zone: '.date_default_timezone_get());
    Logger::getLogger()->log('WARNING', 'Current server time zone: '.(new DateTime())->getTimezone()->getName());
    Logger::getLogger()->log('DEBUG', 'Dirname: '.__DIR__.'/vendor/autoload.php');
    Logger::getLogger()->log('DEBUG', 'Dirname: '.ROOT.'/vendor/autoload.php');
?>