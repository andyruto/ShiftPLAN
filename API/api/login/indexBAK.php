<?php
    /**
     * index.php
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2020-08-10 / Maximilian T. | Kontr0x
     */

    require '../prepareExec.php';

    final class Main {

        //Method invoked on script execution
        public static function run() {

            // Takes raw data from the request
            $json = file_get_contents('php://input');
            $request = json_decode($json);
            Logger::getLogger()->log('DEBUG', 'checking api key');
            if(checkApiKey($request->api_key)){
                Logger::getLogger()->log('DEBUG', 'api key valid');
            }

            // Creating entity manager for doctrine framwork sql access
            $entityManager = Bootstrap::getEntityManager();
            // Setting header for html output type
            header('Content-Type: application/json');

            // Checking if only a session key was given to check if session key is valid
            if(!empty($request->session)&&empty($request->name)&&empty($request->password_hash)){
                Logger::getLogger()->log('INFO', 'session key found in post');
                $session = $entityManager->find('Session', $request->session);
                //If session key not found the entity manager return null which equals an invalid session key
                if($session==null){
                    // session key wasnt found in database
                    Logger::getLogger()->log('INFO', 'session is invalid');
                    $respondJSON = array('success' => 'false');
                    // returning answer of request
                    echo(json_encode($respondJSON));
                    exit();

                }elseif($session->getExpiration_date()<new DateTime('now')){
                    // session key found in database but expired
                    Logger::getLogger()->log('INFO', 'session expired');
                    $respondJSON = array('success' => 'false');
                    // returning answer of request
                    echo(json_encode($respondJSON));
                    exit();

                }else{
                    // session key was found and is valid
                    Logger::getLogger()->log('INFO', 'session valid until '.$session->getExpiration_date()->format('yy-m-d H:m:s'));
                    $respondJSON = array('success' => 'true');
                    // returning answer of request
                    echo(json_encode($respondJSON));
                    exit();

                }
            // checking if an username and password hash was given in request
            }elseif(empty($request->session)&&!empty($request->name)&&!empty($request->password_hash)){
                Logger::getLogger()->log('INFO', 'name and password_hash found in post');
                $user = $entityManager->getRepository('User')->findBy(array('name' => $request->name));
                // em is returning null if no user was found
                if($user==null){
                    // no user with name found in db
                    Logger::getLogger()->log('INFO', 'user not found');
                    $respondJSON = array('success' => 'false');
                    echo(json_encode($respondJSON));
                    exit();

                }elseif($user[0]->getPassword_hash()==$request->password_hash){
                    // user found and password is ok
                    Logger::getLogger()->log('INFO', 'password ok');
                    $session = new Session();
                    $sessionKey = generateRandomString(20);
                    while($sessionKey==$entityManager->find('Session', $sessionKey)){
                        $sessionKey = generateRandomString(20);
                    }
                    // creating new session for user
                    $session->setId($sessionKey);
                    $session->setExpiration_date();
                    $session->setUser_id($user[0]->getid());
                    $entityManager->persist($session);
                    $entityManager->flush();
                    Logger::getLogger()->log('INFO', 'new session created with id = '.$session->getId());
                    $respondJSON = array('success' => 'true', 'session' => $session->getId());
                    echo(json_encode($respondJSON));
                    exit();

                }else{
                    // user were found in db but password was incorrect
                    Logger::getLogger()->log('INFO', 'wrong password');
                    $respondJSON = array('success' => 'false');
                    echo(json_encode($respondJSON));
                    exit();
                }
            }else{
                // combination of arguments was wrong
                Logger::getLogger()->log('ERROR', 'wrong arguments in post request');
                $respondJSON = array('success' => 'false');
                echo(json_encode($respondJSON));
                exit();
            }
            
        }
    }

    Main::run();
?>