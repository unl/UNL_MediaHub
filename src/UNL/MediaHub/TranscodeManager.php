<?php
class UNL_MediaHub_TranscodeManager
{
	public $options = array();

    private $jobsList;

    const COMMAND_RESULT_SESSION = 'transcode-command-result';
    const COMMAND_RESTART = 'reset-pm2';
    const COMMAND_LIST_WORKERS = 'list-workers-pm2';

    public function __construct($options = array()) {
        if (!$this->hasAccess()) {
            throw new UNL_MediaHub_RuntimeException('You do not have permission to view this page.', 403);
        }

	    $this->options = $options + $this->options;

        $this->handlePost();

        $this->jobsList = new UNL_MediaHub_TranscodingJobList(array(
            'orderby' => 'datecreated',
            'order' => 'desc',
            'limit' => 50
        ));
    }

    public function hasAccess() {
        $auth = UNL_MediaHub_AuthService::getInstance();
        return $auth->isLoggedIn() && $auth->getUser()->isAdmin();
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

    public function getCommandResults() {
        if (array_key_exists(self::COMMAND_RESULT_SESSION, $_SESSION) && !empty($_SESSION[self::COMMAND_RESULT_SESSION])) {
            return $_SESSION[self::COMMAND_RESULT_SESSION];
        }
    }

    public function clearCommandResults()
    {
        if (array_key_exists(self::COMMAND_RESULT_SESSION, $_SESSION)) {
            unset($_SESSION[self::COMMAND_RESULT_SESSION]);
        }
    }

    private function handlePost() {
        if ($_POST && isset($_POST['command'])) {
            $this->execCommand($_POST['command']);
	        UNL_MediaHub::redirect(UNL_MediaHub_Controller::getURL() . 'transcode-manager');
        }
    }

    private function execCommand($command) {
        switch($command) {
            case self::COMMAND_RESTART:
                $execCommand = 'HOME=/usr/share/httpd pm2 stop all && HOME=/usr/share/httpd pm2 start all';
                break;

            case self::COMMAND_LIST_WORKERS:
                $execCommand = 'HOME=/usr/share/httpd pm2 list';
                break;

            default:
                $execCommand = '';
        }

        if (!empty($execCommand)) {
            $commandOutput = $commandResultCode = NULL;
            exec($execCommand, $commandOutput, $commandResultCode);
            $results = new StdClass();
            $results->code = $commandResultCode;
            $results->output = $commandOutput;
            $_SESSION[self::COMMAND_RESULT_SESSION] = $results;
        }
    }
}
