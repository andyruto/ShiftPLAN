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

                if($this->timeSpan == null){
                    Logger::getLogger()->log('ERROR', "Time span with id ".$timeSpanId." doesn't exist in database");
                    $this->errorCode = ErrorCode::TimeSpanNotFound;
                }else{
                    Logger::getLogger()->log('DEBUG', "Time span with id ".$timeSpanId." found in database");
                }
            }else{
                Logger::getLogger()->log('WARNING', 'Parameter time span id is empty. Class probably created as TimeSpanManager::handler');
            }
        }

        //Overload constructor to create, search entities
        public static function handler(){
            Logger::getLogger()->log('INFO', 'Class created as TimeSpanManager::handler');
            return new self(null);
        }

        //Overload constructor to work with existing entities
        public static function obj($timeSpanId){
            if(is_integer($timeSpanId)){
                return new self($timeSpanId);
            }else{
                $tmpObj = new self(null);
                $tmpObj->errorCode = ErrorCode::ValidationFailed;
                Logger::getLogger()->log('ERROR', 'id failed regex validation');    
                return $tmpObj;
            }
        }

        //Function to create a time span
        public function createTimeSpan($appointed_day, $start, $end, $required_employees, $last_modified_by, $connected_task_id){
            Logger::getLogger()->log('INFO', 'Creating new time span');
            //Creating new time span
            $newTimeSpan = new TaskTimeSpan();
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
            if(is_integer($last_modified_by)){
                $newTimeSpan->setLast_modified_by($last_modified_by);
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'last_modified_by\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            $newTimeSpan->setLast_modified();
            if(is_integer($connected_task_id)){
                if($this->taskExists($connected_task_id)){
                    $newTimeSpan->setTask_id($connected_task_id);
                }
            }else{
                Logger::getLogger()->log('ERROR', 'validation failed on \'connected_task_id\'');
                $this->errorCode = ErrorCode::ValidationFailed;
            }
            if(self::$errorCode == ErrorCode::NoError){
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
                if(array_key_exists("lastModifiedBy", $arrayOfChanges)){
                    $this->timeSpan->setLast_modified();
                    if(is_integer($arrayOfChanges["lastModifiedBy"])){
                        $this->timeSpan->setLast_modified_by($arrayOfChanges["lastModifiedBy"]);
                    }else{
                        Logger::getLogger()->log('ERROR', 'validation failed on \'lastModifiedBy\'');
                        $this->errorCode = ErrorCode::ValidationFailed;
                    }
                    if(array_key_exists("appointedDay", $arrayOfChanges)){
                        if(preg_match(Validation::SimpleDateFormat, $arrayOfChanges["appointedDay"])){
                            $this->timeSpan->setAppointed_day($arrayOfChanges["appointedDay"]);
                            Logger::getLogger()->log('DEBUG', 'Time span modified appointed day to '.$arrayOfChanges["appointedDay"]);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'appointedDay\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("start", $arrayOfChanges)){
                        if(preg_match(Validation::SimpleDateFormat, $arrayOfChanges["start"])){
                            $this->timeSpan->setStart($arrayOfChanges["start"]);
                            Logger::getLogger()->log('DEBUG', 'Time span modified start day to '.$arrayOfChanges["start"]);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'start\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("end", $arrayOfChanges)){
                        if(preg_match(Validation::SimpleDateFormat, $arrayOfChanges["end"])){
                            $this->timeSpan->setEnd($arrayOfChanges["end"]);
                            Logger::getLogger()->log('DEBUG', 'Time span modified end day to '.$arrayOfChanges["end"]);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'end\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("requiredEmployees", $arrayOfChanges)){
                        if(is_integer($arrayOfChanges["requiredEmployees"])){
                            $this->timeSpan->setRequired_employees($arrayOfChanges["requiredEmployees"]);
                            Logger::getLogger()->log('DEBUG', 'Time span modified required employees to '.$arrayOfChanges["requiredEmployees"]);
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'requiredEmployees\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(array_key_exists("connectedTaskId", $arrayOfChanges)){
                        if(is_integer($arrayOfChanges["connectedTaskId"])){
                            if($this->taskExists($arrayOfChanges["connectedTaskId"])){
                                $this->timeSpan->setTask_id($arrayOfChanges["connectedTaskId"]);
                                Logger::getLogger()->log('DEBUG', 'Time span modified connected task id to '.$arrayOfChanges["connectedTaskId"]);
                            }
                        }else{
                            Logger::getLogger()->log('ERROR', 'validation failed on \'connectedTaskId\'');
                            $this->errorCode = ErrorCode::ValidationFailed;
                        }
                    }
                    if(self::$errorCode == ErrorCode::NoError){
                        //Flushing changes
                        $this->eM->flush();
                        Logger::getLogger()->log('INFO', 'Time span with id '.$this->timeSpan->getId().' got modified');
                    }
                }else{
                    Logger::getLogger()->log('ERROR', 'Modify time span function canceled due to missing last_modified_by parameter');
                }
            }else{
                Logger::getLogger()->log('ERROR', 'Modify time span function canceled due to '.$this->errorCode.' error already occured');
            }
            return $this->errorCode;
        }

        //Function to search for a time span
        public function searchTimeSpans($filter, $value){
            $timeSpans = array();
            $timeSpanArray = array();
            if($this->errorCode == ErrorCode::NoError){
                if(preg_match(Validation::TimeSpanSearchFilters, $filter)){
                    $timeSpanDbObjects = $this->eM->getRepository('taskTimeSpan')->findBy(array($filter => $value));
                    if($timeSpanDbObjects !== Null){
                        //Formatting the output of the found time spans
                        foreach($timeSpanDbObjects as $timeSpan){
                            $timeSpanArray = array('id' => $timeSpan->getId(), 'appointedDay' => $timeSpan->getAppointed_day(), 'start' => $timeSpan->getStart(), 'end' => $timeSpan->getEnd(), 'requiredEmployees' => $timeSpan->getRequired_employees(), 'lastModifiedBy' => (UserManager::obj($timeSpan->getLast_modified_by()))->getUserProfileReduced()['profile'][0], 'lastModified' => $timeSpan->getLast_modified(), 'taskId' => $timeSpan->getTask_id());
                            $timeSpans = array_merge($timeSpans, array($timeSpanArray));
                        }
                    }else{
                        Logger::getLogger()->log('ERROR', 'Filter \''.$filter.'\' = \''.$value.'\' didnt match');
                        $this->errorCode = ErrorCode::TimeSpanNotFound;
                    }
                }else{
                    Logger::getLogger()->log('ERROR', 'validation failed on \'filter\'');
                    $this->errorCode = ErrorCode::ValidationFailed;
                }
            }else{
                Logger::getLogger()->log('ERROR', 'search time spans function canceled due to '.$this->errorCode.' error already occured');
            }
            return array('timeSpans' => array_values($timeSpans));
        }

        //Function to get time span
        public function getTimeSpan(){
            $timeSpan = array();
            $timeSpanArray = array();
            if($this->errorCode == ErrorCode::NoError){
                $timeSpanArray = array('id' => $this->timeSpan->getId(), 'appointedDay' => $this->timeSpan->getAppointed_day(), 'start' => $this->timeSpan->getStart(), 'end' => $this->timeSpan->getEnd(), 'requiredEmployees' => $this->timeSpan->getRequired_employees(), 'lastModifiedBy' => (UserManager::obj($this->timeSpan->getLast_modified_by()))->getUserProfileReduced()['profile'][0], 'lastModified' => $this->timeSpan->getLast_modified(), 'taskId' => $this->timeSpan->getTask_id());
                $timeSpan = array_merge($timeSpan, array($timeSpanArray));
                Logger::getLogger()->log('INFO', 'Returning time span stored in time span manager');
            }else{
                Logger::getLogger()->log('ERROR', 'get time span function canceled due to '.$this->errorCode.' error already occured');
            }
            return array('timeSpan' => array_values($timeSpan));
        }

        //Function to get all time spans
        public function getAllTimeSpans(){
            $timeSpans = array();
            $timeSpanArray = array();
            if($this->errorCode == ErrorCode::NoError){
                $timeSpanDbObjects = $this->eM->getRepository('taskTimeSpan')->findAll();
                if($timeSpanDbObjects !== Null){
                    //Formatting the output of the found time spans
                    foreach($timeSpanDbObjects as $timeSpan){
                        $timeSpanArray = array('id' => $timeSpan->getId(), 'appointedDay' => $timeSpan->getAppointed_day(), 'start' => $timeSpan->getStart(), 'end' => $timeSpan->getEnd(), 'requiredEmployees' => $timeSpan->getRequired_employees(), 'lastModifiedBy' => $timeSpan->getLast_modified_by(), 'lastModified' => (UserManager::obj($timeSpan->getLast_modified_by()))->getUserProfileReduced()['profile'][0], 'taskId' => $timeSpan->getTask_id());
                        $timeSpans = array_merge($timeSpans, array($timeSpanArray));
                    }
                }
            }else{
                Logger::getLogger()->log('ERROR', 'search all time spans function canceled due to '.$this->errorCode.' error already occured');
            }
            return array('timeSpans' => array_values($timeSpans));
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