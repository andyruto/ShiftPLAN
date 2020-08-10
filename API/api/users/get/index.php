<?php
    /**
     *  index.php to get all or specific user / users
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
            $entityManager = Bootstrap::getEntityManager();
            // setting otput type of respond
            header('Content-Type: application/json');

            // checking if parameters are null to search for all users
            if(empty($request->filter)&&empty($request->value)){
                $entitys = $entityManager->getRepository('User')->findAll();
                $users_array = array();
                foreach ($entitys as $entity){
                    array_push($users_array, array($entity->getId(), 
                                                    $entity->getName(), 
                                                    $entity->getHidden(), 
                                                    $entity->getWeekly_working_minutes(), 
                                                    $entity->getWorking_week_days(), 
                                                    $entity->getYear_vacation_days()
                                                ));
                }
                $respondJSON = array('success' => 'true', 'users' => $users_array);
                echo(json_encode($respondJSON));
                exit();
            }elseif($request->filter=='id'&&!empty($request->value)){
                // searching for user with specific id
                $user = $entityManager->find('User', $request->value);
                $respondJSON = array('id' => $user->getId(), 
                                    'hidden' => $user->getHidden(),
                                    'weekly_working_minutes' => $user->getWeekly_working_minutes(),
                                    'working_week_days' => $user->getWorking_week_days(),
                                    'year_vacation_days' => $user->getYear_vacation_days());
                echo(json_encode($respondJSON));
                exit();
            }else{
                // searching for user with specific filter
                $respondJSON = $entityManager->getRepository('User')->findBy(array($request->filter => $request->filter));
                echo(json_encode($respondJSON));
                exit();
            }
        }
    }

    Main::run();
?>