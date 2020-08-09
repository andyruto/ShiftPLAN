<!--
-- index.php
--
-- author: Maximilian T. | Kontr0x
-- last edit / by: 2020-08-09 / Maximilian T. | Kontr0x
-->
<?php
    require '../prepareExec.php';

    final class Main {
        //Method invoked on script execution
        public static function run() {
            //$request = json_decode($_POST['request']);
            $request = json_decode(json_encode(array('session' => 'null', 'name' => 'kevin')));
            $entityManager = Bootstrap::getEntityManager();
            $user = $entityManager->getRepository('User')->findBy(array('name' => $request['name'])); 

            if($request->session==null){
                Logger::getLogger()->log('INFO', 'no existing session for api request');
                if($user!=null){
                    Logger::getLogger()->log('INFO', 'user from request found in db checking credentials');
                    if($user->getPassword_hash()==$request['password_hash']){
                        Logger::getLogger()->log('INFO', 'credentials OK, creating new session');
                        $session = new Session();
                        $session->setId(generateRandomString(20));
                        $session->setExpiration_date();
                        $session->setUser_id($user->getid());
                        Logger::getLogger()->log('INFO', 'new session created with id = '.$session->getId());
                        $entityManager->persist($session);
                        $entityManager->flush();
                        Logger::getLogger()->log('INFO', 'flushed session');
                        $respondJSON->successfull = true;
                        echo(json_encode($respondJSON));
                    }
                }else{
                    Logger::getLogger()->log('INFO', 'user '.$request['name'].' not found');
                    $respondJSON->successfull = false;
                        echo(json_encode($respondJSON));
                }
            }else{
                Logger::getLogger()->log('INFO', 'checking if session is valid');
                $session = $entityManager->find('Session', $request['session'])!=null;
                if($session!=null&&$session->getExpiration_date()>=date()){
                    Logger::getLogger()->log('INFO', 'session is valid until '.$session->getExpiration_date());
                    $respondJSON->successfull = true;
                    echo(json_encode($respondJSON));
                }else{
                    Logger::getLogger()->log('INFO', 'session expired on '.$session->getExpiration_date());
                    $respondJSON->successfull = false;
                    echo(json_encode($respondJSON));
                }
            }
        }
    }

    Main::run();
?>