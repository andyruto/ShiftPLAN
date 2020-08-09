<!--
-- index.php
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-08-09 / Maximilian T. | Kontr0x
-->
<?php
    require 'prepareExec.php';

    final class Main {
        //Method invoked on script execution
        public static function run() {
            $request = json_decode($_POST['request']);
            $user = new User();
            $entityManager = Bootstrap::getEntityManager();
            $user->setName($request->name);
            $user->setWeekly_working_minutes($request->Weekly_working_minutes);
            $user->setWorking_week_days($request->Working_week_days);
            $user->setYear_vacation_days($request->Year_vacation_days);
            $user->setPassword_hash($request->Password_hash);

            $entityManager->persist($user);
            $entityManager->flush();
        }
    }

    Main::run();
?>