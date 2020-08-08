<!--
-- bootstrap.php
--
-- PHP file containing doctrine main config
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-08-07 / Maximilian T. | Kontr0x
-->
<?php
    use Doctrine\ORM\EntityManager;
    use Doctrine\ORM\Tools\Console\ConsoleRunner;
    use DoctrineConnector\EntityManagerFactory;

    require 'prepareExec.php';

    $entityManager = Bootstrap::getEntityManager();
    return ConsoleRunner::createHelperSet($entityManager);
?>