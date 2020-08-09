

<?php

    // index.php
    
    // author: Maximilian T. | Kontr0x
    // last edit / by: 2020-08-09 / Maximilian T. | Kontr0x

    require '../prepareExec.php';

    final class Main {
        //Method invoked on script execution
        public static function run() {

            checkApiKey(jsondecode($_POST['request'])->Api_key);

            $request = json_decode($_POST['request']);
            $entityManager = Bootstrap::getEntityManager();
            header('Content-Type: application/json');

            if(!empty($request->session)&&empty($request->name)&&empty($request->password_hash)){
                $session = $entityManager->find('Session', $request->session);
                if($session==null){
                    Logger::getLogger()->log('INFO', 'session is invalid');
                    $respondJSON = array('successfull' => 'false');
                    echo(json_encode($respondJSON));
                    exit();

                }elseif($session->getExpiration_date()<new DateTime('now')){
                    Logger::getLogger()->log('INFO', 'session expired');
                    $respondJSON = array('successfull' => 'false');
                    echo(json_encode($respondJSON));
                    exit();

                }else{
                    Logger::getLogger()->log('INFO', 'session valid until '.$session->getExpiration_date()->format('yy-m-d H:m:s'));
                    $respondJSON = array('successfull' => 'true');
                    echo(json_encode($respondJSON));
                    exit();

                }
            }elseif(empty($request->session)&&!empty($request->name)&&!empty($request->password_hash)){
                $user = $entityManager->getRepository('User')->findBy(array('name' => $request->name));
                if($user==null){
                    Logger::getLogger()->log('INFO', 'user not found');
                    $respondJSON = array('successfull' => 'false');
                    echo(json_encode($respondJSON));
                    exit();

                }elseif($user[0]->getPassword_hash()==$request->password_hash){
                    Logger::getLogger()->log('INFO', 'password ok');
                    $session = new Session();
                    $sessionKey = generateRandomString(20);
                    while($sessionKey==$entityManager->find('Session', $sessionKey)){
                        $sessionKey = generateRandomString(20);
                    }
                    $session->setId($sessionKey); //todo check if random already exist
                    $session->setExpiration_date();
                    $session->setUser_id($user[0]->getid());
                    $entityManager->persist($session);
                    $entityManager->flush();
                    Logger::getLogger()->log('INFO', 'new session created with id = '.$session->getId());
                    $respondJSON = array('successfull' => 'true', 'session' => $session->getId);
                    echo(json_encode($respondJSON));
                    exit();

                }else{
                    Logger::getLogger()->log('INFO', 'wrong password');
                    $respondJSON = array('successfull' => 'false');
                    echo(json_encode($respondJSON));
                    exit();
                }
            }else{
                Logger::getLogger()->log('ERROR', 'wrong arguments in post request');
                $respondJSON = array('successfull' => 'false');
                echo(json_encode($respondJSON));
                exit();
            }
            
        }
    }

    Main::run();
?>