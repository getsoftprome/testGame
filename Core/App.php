<?php
namespace Core;

use Model\Duel;
use Model\DuelLog;
use Model\Player;
use Model\User;



class App{

    public $user;
    private $view;
    private $router;
    private $configPage = [];


    public function __construct(){
        $this->view = new View();
        $this->router = new Route();

        $this->router->add('mainPage','/game/main');
        $this->router->add('logout','/game/logout');
        $this->router->add('searchDuelPage','/game/duel-search');
    }

    private function updateUser(){
        $this->user = User::getCurrentUser();
        $this->configPage['user'] = $this->user;
    }

    public function start(){
        $this->updateUser();
        if(!$this->user){
            $this->authPage($_GET);
            return true;
        }

        if($this->user['status'] === 'playing'){
            $this->playPage($_GET);
            return true;
        }
        $function = $this->router->get($_SERVER['REQUEST_URI']);
        if(!$function){
            $function = 'mainPage';
        }
        $this->$function($_GET);

        return true;
    }

    public function authPage($params){

        $nickname = $params['nickname']??'';
        $password = $params['password']??'';

        if($nickname !== '' && $password !== ''){
            $auth = User::auth($nickname,$password);
            if($auth['hash'] !== ''){
                header('Location: /game/main');
                exit;
            }
            $this->configPage['message'] = $auth['message'];
        }

        $this->view->render('authPage', $this->configPage);
    }

    public function playPage($params){
        $duel = Duel::getByUserId($this->user['id']);
        $players = Player::getPlayers($duel['id'],$this->user['id']);
        $logs = DuelLog::get($duel['id']);

        if(isset($params['attack']) && $duel['start_time'] < time()){
            $players['enemy'] = Player::attack($players, $players['player']['damage']);
            if($players['enemy']['health_points']<=0){
                Duel::end($duel, $players);
            }
            header('Location: /game/play');
            exit;
        }
        $this->configPage['duel'] = $duel;
        $this->configPage['players'] = $players;
        $this->configPage['logs'] = array_reverse($logs);

        $this->view->render('playPage', $this->configPage);
    }

    public function mainPage($params){
        $this->view->render('mainPage', $this->configPage);
    }

    public function searchDuelPage($params){
        if(isset($params['search']) && $this->user['status'] !== 'playing'){
            if(Duel::search($this->user)){
                header('Location: /game/play');
                exit;
            }
        }elseif(isset($params['cancel_search']) && $this->user['status'] === 'searching'){
            Duel::cancelSearch($this->user);
        }
        $this->updateUser();

        $this->view->render('duelSearchPage',$this->configPage);
    }

    public function logout($params){
        User::logout();
        header('Location: /game');
        exit;
    }

}