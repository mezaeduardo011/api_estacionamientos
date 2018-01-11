<?php
namespace Catatumbo\Core\Commun;

use \Monolog\Logger;
use \Monolog\Handler\StreamHandler;
use \Monolog\Handler\FirePHPHandler;

trait Logs
{
     public $log;

     public function logDebug($mensaje)
     {
         $this->log = new Logger(Constant::FW.' '.Constant::VERSION);
         $this->log->pushHandler(new StreamHandler(Constant::LOG_DIR.'debug_'.date('Y-m-d').'.log', Logger::DEBUG));
         $this->log->pushHandler(new FirePHPHandler());
         $this->log->debug($mensaje);
     }

     public function logError($mensaje)
     {
         $this->log = new Logger(Constant::FW.' '.Constant::VERSION);
         $this->log->pushHandler(new StreamHandler(Constant::LOG_DIR.'error_'.date('Y-m-d').'.log', Logger::ERROR));
         $this->log->pushHandler(new FirePHPHandler());
         $this->log->error(strip_tags($mensaje));
     }

    public function logInfo($mensaje)
    {
        $this->log = new Logger(Constant::FW.' '.Constant::VERSION);
        $this->log->pushHandler(new StreamHandler(Constant::LOG_DIR.'info_'.date('Y-m-d').'.log', Logger::INFO));
        $this->log->pushHandler(new FirePHPHandler());
        $this->log->info($mensaje);
    }

}