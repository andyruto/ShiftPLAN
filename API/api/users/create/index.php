<?php
    /**
     * index.php to create a new user
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x
     */

    require '../../prepareExec.php';

    final class Main {
        //Method invoked on script execution
        public static function run() {
            // Takes raw data from the request
            $json = file_get_contents('php://input');
            $request = json_decode($json);
            checkApiKey($request->api_key);
            checkSessionKey($request->session_key);
            header('Content-Type: application/json');
            $entityManager = Bootstrap::getEntityManager();
            if($entityManager->getRepository('User')->findBy(array('name' => $request->name))==null){

                // creating new user
                $user = new User();
                if(!empty($request->name)){
                    $user->setName($request->name);
                }else{
                    $respondJSON = array('success' => 'false');
                    echo(json_encode($respondJSON));
                    exit();
                }
                if(!empty($request->weekly_working_minutes)){
                    $user->setWeekly_working_minutes($request->weekly_working_minutes);
                }
                if(!empty($request->working_week_days)){
                    $user->setWorking_week_days($request->working_week_days);
                }
                if(!empty($request->year_vacation_days)){
                    $user->setYear_vacation_days($request->year_vacation_days);
                }
                if(!empty($request->password_hash)){
                    $user->setPassword_hash($request->password_hash);
                }else{
                    $respondJSON = array('success' => 'false');
                    echo(json_encode($respondJSON));
                    exit();
                }
                // write the data to db
                $entityManager->persist($user);
                $entityManager->flush();
                $respondJSON = array('success' => 'true');
                    echo(json_encode($respondJSON));
                    exit();
            }else{
                Logger::getLogger()->log('ERROR', 'dublicated entry');
                $respondJSON = array('success' => 'false');
                echo(json_encode($respondJSON));
                exit();
            }
        }
    }

    Main::run();
?>