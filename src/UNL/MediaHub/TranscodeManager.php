<?php
class UNL_MediaHub_TranscodeManager
{
    private $jobsList;
    private $commandAttempted = false;
    private $commandOutput;
    private $commandResultCode;

    const COMMAND_RESTART = 'reset-pm2';
    const COMMAND_LIST_WORKERS = 'list-workers-pm2';

    public function __construct($options = array()) {
        if (!$this->hasAccess()) {
            throw new Exception('You do not have permission to view this page.', 403);
        }

        $this->handlePost();

        $this->jobsList = new UNL_MediaHub_TranscodingJobList(array(
            'orderby' => 'id',
            'order' => 'desc',
            'all_not_complete' => true,
            'limit' => 200
        ));
    }

    public function hasAccess() {
        $auth = UNL_MediaHub_AuthService::getInstance();
        return $auth->isLoggedIn() && $auth->getUser()->canTranscodePro();
    }

    public function hasJobs() {
        return count($this->jobsList->items) > 0;
    }

    public function jobsCount() {
        return count($this->jobsList->items);
    }

    public function getJobs() {
        return $this->jobsList->items;
    }

    public function commandAttempted() {
        return $this->commandAttempted;
    }

    public function getCommandResults() {
        if ($this->commandAttempted) {
            $results = new StdClass();
            $results->code = $this->commandResultCode;
            $results->output = $this->commandOutput;
            return $results;
        }
    }

    private function handlePost() {
        if ($_POST && isset($_POST['command'])) {
            $this->execCommand($_POST['command']);
        }
    }

    private function execCommand($command) {
        switch($command) {
            case self::COMMAND_RESTART:
                $execCommand = 'sudo -s -H -u apache pm2 stop all && sudo -s -H -u apache pm2 start all';
                $execCommand = 'pwddd && whoami';
                break;

            case self::COMMAND_LIST_WORKERS:
                $execCommand = 'sudo -s -H -u apache pm2 stop all';
                $execCommand = 'which php && ls -l';
                break;

            default:
                $execCommand = '';
        }

        $this->commandAttempted = false;
        if (!empty($execCommand)) {
            exec($execCommand, $this->commandOutput, $this->commandResultCode);
            $this->commandAttempted = true;
        }
    }
}