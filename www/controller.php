<?php

use BigBlueButton\Responses\BaseResponse;

class Controller
{
    private static $LOGOUT_URL = 'http://online.votdoroga.ru/return.php';

    /**
    private static $SERVER_URL = 'http://test-install.blindsidenetworks.com/bigbluebutton/';
    private static $SECRET_KEY = '8cd8ef52e8e101574e400365b55e11a6';
     **/
    private static $SERVER_URL = 'https://bbb.serveirc.com/bigbluebutton/';
    private static $SECRET_KEY = 'e5f18ab2ca3c5b8addce0ff5a2cc0985';

    private static $P_LISTENER = 1;
    private static $P_MANAGER = 2;

    private static $PASSWORD = "password";
    private static $RIGHTS = "rights";
    private static $LOGIN = "login";

    private static $MANAGER_PASS = "manager";

    public $title;
    public $viewPage;
    public $bbb;

    public $err;
    public $warn;
    public $info;

    private $dummy;

    public function __construct($dummy = false)
    {
        $this->dummy = $dummy;
        session_start();
        $this->firstPage();
        $this->bbb = new \BigBlueButton\BigBlueButton(
            $this->dummy,
            Controller::$SECRET_KEY,
            Controller::$SERVER_URL
        );
    }

    /*******************************************
     **********    START POINT
     ******************************************/
    public function handle()
    {
        $action = !empty($_REQUEST['action']) ? $_REQUEST['action'] : '';
        if (!empty($action)) {
            try {
                $this->$action();
            } catch (ValidationException $e) {
                $this->warn = 'Ошибки ввода значений полей: ' . $e->listFields();
            } catch (PermissionDeniedException $e) {
                $this->warn = 'У Вас не достаточно прав для просмотра данной страницы!';
                $this->firstPage();
            } catch (ControllerException $e) {
                $this->err = 'Ошибка обработки запроса! Детали: ' . $e->getMessage();
            } catch (Exception $e) {
                $this->err = 'Критическая ошибка на странице! ' .$e->getMessage(). 'Детали: ' . $e->getTraceAsString();
            }
        }
    }

    public function showMessages()
    {
        if (!empty($this->err)) {
            echo '<div class="alert alert-danger" role="alert">' . $this->err . '</div>';
        }
        if (!empty($this->warn)) {
            echo '<div class="alert alert-warning" role="alert">' . $this->warn . '</div>';
        }
        if (!empty($this->info)) {
            echo '<div class="alert alert-success" role="alert">' . $this->info . '</div>';
        }
    }

    /*******************************************
     **********    ACTIONS
     ******************************************/

    private function login()
    {
        $pass = $_REQUEST[Controller::$PASSWORD];
        $_SESSION[Controller::$LOGIN] = $_REQUEST[Controller::$LOGIN];
        $_SESSION[Controller::$PASSWORD] = $pass;
        if (Controller::$MANAGER_PASS == $pass) {
            $_SESSION[Controller::$RIGHTS] = Controller::$P_MANAGER;
            $this->managers();
        } else {
            $_SESSION[Controller::$RIGHTS] = Controller::$P_LISTENER;
            $this->firstPage();
        }
    }

    private function logout()
    {
        session_destroy();
        ob_start();
        header('Location: index.php', true, 301);
        ob_end_flush();
    }

    private function firstPage()
    {
        if ($this->isLoggedIn()) {
            $this->publics();
        } else {
            $this->title = 'Добро пожаловать';
            $this->viewPage = 'login.php';
        }
    }

    private function managers()
    {
        $this->checkPermission(Controller::$P_MANAGER);
        $this->title = 'Админская';
        $this->viewPage = 'manager.php';
    }

    private function return ()
    {
        $this->info = 'Вы покинули комнату, спасибо за то, что были с нами!';
        $this->firstPage();
    }

    private function join($mId = null)
    {
        $this->checkPermission(Controller::$P_LISTENER);
        $mId = empty($mId) ? readParameter('id') : $mId;
        $p = new \BigBlueButton\Parameters\JoinMeetingParameters($mId, $this->getLogin(), $this->getPassword());
	$p->setRedirect(true);
        $url = $this->bbb->getJoinMeetingURL($p);
        ob_start();
        header('Location: ' . $url);
        ob_end_flush();
    }

    private function stop()
    {
        $this->checkPermission(Controller::$P_MANAGER);
        $p = new \BigBlueButton\Parameters\EndMeetingParameters(readParameter('id'), $this->getPassword());
        $response = $this->bbb->endMeeting($p);
        if ($this->goodResponse($response)) {
            $this->info = 'Трансляция успешно остановлена!';
        } else {
            $this->warn = 'Попытка остановить трансляцию привела к ошибке! Детали: ' . $response->getMessage();
        }
        $this->publics();

    }

    private function publics()
    {
        $this->checkPermission(Controller::$P_LISTENER);
        $this->title = 'Приемная';
        $this->viewPage = 'public.php';
    }

    private function create()
    {
        $this->checkPermission(Controller::$P_MANAGER);
        $this->title = 'Создать новую';
        $this->viewPage = 'edit.php';
    }

    private function info()
    {
        $this->checkPermission(Controller::$P_MANAGER);
        $this->title = 'Редактировать';
        $this->viewPage = 'edit.php';
    }

    private function save()
    {
        $this->checkPermission(Controller::$P_MANAGER);
        $id = empty($_REQUEST['id']) ? md5(uniqid(rand(), true)) : readParameter('id');
        $meetingName = readParameter('meetingName');
        $meetingDurationMin = readParameter('meetingDurationMin');
        $joinMeeting = readParameter('joinMeeting');
        $attendeePassword = readParameter('attendeePassword');
        $p = new \BigBlueButton\Parameters\CreateMeetingParameters($id, $meetingName);
        $p->setWebcamsOnlyForModerator("true" == readParameter('webcamsOnlyForModerator'));
        $p->setRecord(false);
        $p->setAutoStartRecording(false);
        if ($meetingDurationMin != '') $p->setDuration(intval($meetingDurationMin));
        $p->setAllowStartStopRecording(false);
        $p->setAttendeePassword($attendeePassword);
        $p->setModeratorPassword(Controller::$MANAGER_PASS);
        $p->setLogoutUrl(Controller::$LOGOUT_URL);
        //print_r($p);
        $response = $this->bbb->createMeeting($p);
        if ($this->goodResponse($response) && $joinMeeting != '') {
            $this->join($response->getMeetingId());
        }
        $this->publics();
    }

    /*******************************************
     **********    SERVICES
     ******************************************/

    public function goodResponse(BaseResponse $response)
    {
        if ($response->getReturnCode() != 'SUCCESS') {
            $this->warn = $response->getMessage() . ' [key=' . $response->getMessageKey() . ']';
            return false;
        }
        return true;
    }

    public function getLogin()
    {
        return $_SESSION[Controller::$LOGIN];
    }

    public function getPassword()
    {
        return $_SESSION[Controller::$PASSWORD];
    }

    public function isLoggedIn()
    {
        return !empty($_SESSION[Controller::$RIGHTS]);
    }

    /**
     * @param $priority
     * @throws PermissionDeniedException
     */
    private function checkPermission($priority)
    {
        if (!$this->isLoggedIn() || $_SESSION[Controller::$RIGHTS] < $priority) {
            throw new PermissionDeniedException('Permission denied. Required priority = ' . $priority, -1);
        }
    }

    public function isManager()
    {
        return $this->isLoggedIn() ? $_SESSION[Controller::$RIGHTS] == Controller::$P_MANAGER : false;
    }

    public function getFormMethod()
    {
        return $this->dummy ? 'get' : 'post';
    }
}


class ValidationException extends Exception
{
    public $fields = array();

    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

    public function addField($fName)
    {
        $this->fields[] = $fName;
    }

    public function listFields()
    {
        $list = "";
        foreach ($this->fields as $field) {
            if (!empty($list)) {
                $list .= ', ';
            }
            $list .= $field;
        }
    }


}

class PermissionDeniedException extends ControllerException
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

}

class ControllerException extends Exception
{
    public function __construct($message, $code)
    {
        parent::__construct($message, $code);
    }

}

function readParameter($name)
{
    return !empty($_REQUEST[$name]) ? $_REQUEST[$name] : '';
}