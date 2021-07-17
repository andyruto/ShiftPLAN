<?php
    /**
     * timeSpanManager.php
     * 
     * PHP file containing class to manage the time spans.
     * 
     * author: Maximilian T. | Kontr0x
     * last edit / by: 2021-07-18 / Maximilian T. | Kontr0x
     */

    class TimeSpanManager{
        private $eM = null; //Variable to store entity manager in
        private $errorCode = ErrorCode::NoError;
        private $timeSpan = null; //Variable to store an time span object
        
        private function __construct($timeSpanId){
            Logger::getLogger()->log('INFO', 'Called time span manager');
            //Getting entity manager for database access
            $this->eM = Bootstrap::getEntityManager();
            if(isset($timeSpanId)){
                //Looking for time span in database
                $this->timeSpan = $this->eM->find('taskTimeSpan', $timeSpanId);

                if($this->timeSpanId == null){
                    Logger::getLogger()->log('ERROR', "Time span with id ".$timeSpanId." doesn't exist in database");
                    $this->errorCode = ErrorCode::TimeSpanNotFound;
                }else{
                    Logger::getLogger()->log('DEBUG', "Time span with id ".$timeSpanId." found in database");
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter time span id is empty. Class probably created as TimeSpanManager::creator');
                $this->errorCode = ErrorCode::NoTimeSpanIdGiven;
            }
        }

        //Overload constructor to create new entities
        public static function creator(){
            Logger::getLogger()->log('INFO', 'Class created as TimeSpanManager::creator');
            return new self(null);
        }

        //Overload constructor to work with existing entities
        public static function obj($timeSpanId){
            return new self($timeSpanId);
        }

        //Function to create a time span
        public function createTimeSpan($appointed_day, $start, $end, $required_employees, $last_modified_by, $connected_task_id){
            Logger::getLogger()->log('INFO', 'Creating new time span');
            if(taskHasNoTimeSpan($connected_task_id)){
                //Creating new time span
                $newTimeSpan = new Task();
                if(preg_match(Validation::SimpleDateFormat, $appointed_day)){
                    $newTimeSpan->setAppointed_day($appointed_day);
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'appointed_day\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
                if(preg_match(Validation::SimpleDateFormat, $start)){
                    $newTimeSpan->setStart($start);
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'start\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
                if(preg_match(Validation::SimpleDateFormat, $end)){
                    $newTimeSpan->setEnd($end);
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'end\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
                if(is_integer($required_employees)){
                    $newTimeSpan->setRequired_employees($required_employees);
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'required_employees\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
                if(preg_match(Validation::UserName, $last_modified_by)){
                    $newTimeSpan->setLast_modified_by($last_modified_by);
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'last_modified_by\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
                $newTimeSpan->setLast_modified();
                if(preg_match(Validation::IdOfNumbers, $connected_task_id)){
                    if(taskExists($connected_task_id)){
                        $newTimeSpan->setTask_id($connected_task_id);
                    }
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'connected_task_id\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
                //Storeing created time span
                $this->eM->persist($newTimeSpan);
                //Flushing changes
                $this->eM->flush();
            }
            return $this->errorCode;
        }

        //Function to modify the time span from db
        public function modifyTimeSpan($arrayOfChanges){
            if($this->errorCode == ErrorCode::NoError){
                if(array_key_exists("last_modified_by", $arrayOfChanges)){
                    $this->timeSpan->setLast_modified();
                    if(preg_match(Validation::UserName, $arrayOfChanges->last_modified_by)){
                        $this->timeSpan->setLast_modified_by($arrayOfChanges->last_modified_by);
                    }else{
                        Logger::getLogger()->log('ERROR', 'validation failed on \'last_modified_by\'');
                        $this->errorCode = ErrorCode::ValidationFailed;
                    }
                    if(array_key_exists("appointed_day", $arrayOfChanges)){
                        if(preg_match(Validation::SimpleDateFormat, $arrayOfChanges->appointed_day)){
                            $this->timeSpan->setAppointed_day($arrayOfChanges->appointed_day);
                            Logger::getLogger()->log('DEBUG', 'Time span modified appointed day to '.$arrayOfChanges->appointed_day);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'appointed_day\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("start", $arrayOfChanges)){
                        if(preg_match(Validation::SimpleDateFormat, $arrayOfChanges->start)){
                            $this->timeSpan->setStart($arrayOfChanges->start);
                            Logger::getLogger()->log('DEBUG', 'Time span modified start day to '.$arrayOfChanges->start);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'start\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("end", $arrayOfChanges)){
                        if(preg_match(Validation::SimpleDateFormat, $arrayOfChanges->end)){
                            $this->timeSpan->setEnd($arrayOfChanges->end);
                            Logger::getLogger()->log('DEBUG', 'Time span modified end day to '.$arrayOfChanges->end);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'end\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("required_employees", $arrayOfChanges)){
                        if(is_integer($required_employees)){
                            $this->timeSpan->setRequired_employees($arrayOfChanges->required_employees);
                            Logger::getLogger()->log('DEBUG', 'Time span modified required employees to '.$arrayOfChanges->required_employees);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'required_employees\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("connected_task_id", $arrayOfChanges)){
                        if(preg_match(Validation::IdOfNumbers, $arrayOfChanges->connected_task_id)){
                            if(taskExists($connected_task_id)){
                                $this->timeSpan->setTask_id($arrayOfChanges->connected_task_id);
                                Logger::getLogger()->log('DEBUG', 'Time span modified connected task id to '.$arrayOfChanges->connected_task_id);
                            }
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'connected_task_id\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    //Flushing changes
                    $this->eM->flush();
                    Logger::getLogger()->log('INFO', 'Time span with id '.$this->timeSpan->getId().' got modified');
                }else{
                    Logger::getLogger()->log('ERROR', 'Remove time span function canceled due to missing last_modified_by parameter');
                }
            }else{
                Logger::getLogger()->log('ERROR', 'Modify time span function canceled due to '.$this->errorCode.' error already occured');
            }
            return $this->errorCode;
        }

        //Function to remove the time span from db
        public function removeTimeSpan(){
            if($this->errorCode == ErrorCode::NoError){
                $timeSpanId = $this->timeSpan->getId();
                //Removing time span from database
                $this->eM->remove($this->timeSpan);
                //Flushing changes
                $this->eM->flush();
                Logger::getLogger()->log('INFO', 'Time span with id '.$timeSpanId.' got removed');
            }else{
                Logger::getLogger()->log('ERROR', 'Remove time span function canceled due to '.$this->errorCode.' error already occured');
            }
            return $this->errorCode;
        }

        //Function to check if task with id exists
        public function taskExists($taskId){
            if($this->errorCode == ErrorCode::NoError){
                if($this->eM->find('task', $taskId) !== Null){
                    return True;
                }
                Logger::getLogger()->log('ERROR', 'Task with id '.$taskId.' not found in database');
                $this->errorCode = ErrorCode::TaskNotFound;
            }else{
                Logger::getLogger()->log('ERROR', 'Check if task exists function canceled due to '.$this->errorCode.' error already occured');
            }
            return False;
        }

        //Function to check if task already has a time span
        public function taskHasNoTimeSpan($taskId){
            if($this->errorCode == ErrorCode::NoError){
                if($this->eM->getRepository('taskTimeSpan')->findBy(array('task_Id' => $connected_task_id))[0] === Null){
                    return True;
                }
                Logger::getLogger()->log('ERROR', 'Task with id '.$taskId.' is already connected to a time span');
                $this->errorCode = ErrorCode::TaskAlreadyHasTimeSpan;
            }else{
                Logger::getLogger()->log('ERROR', 'Check if task has no time span connected function canceled due to '.$this->errorCode.' error already occured');
            }
            return False;
        }

        //Returning the finish code
        public function getFinishCode(){
            return $this->errorCode;
        }

        //Returning database object of class
        public function getDbObject(){
            if($this->errorCode == ErrorCode::NoError){
                return $this->timeSpan;
            }else{
                Logger::getLogger()->log('ERROR', 'Get database function canceled due to '.$this->errorCode.' error already occured');
            }
        }
    }
?>