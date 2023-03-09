<?php

namespace Model;
use Core\DB\Connect;
use PDO;

class Player extends Model
{
    public static function getPlayers($duelId,$userId){
        $stmt = self::getPdoInstance()->prepare(
            "SELECT p.*, u.nickname `nickname` ,u.health_points `health_points_default`,u.id `user_id` FROM `player` p INNER JOIN `users` u ON p.user = u.id WHERE p.`duel` = :duel  LIMIT 2"
        );
        $stmt->execute([
            ':duel' => $duelId,
        ]);

        $players =  $stmt->fetchAll(PDO::FETCH_ASSOC);

        $player = $players[0]['user'] === $userId?$players[0]:$players[1];
        $enemy = $players[0]['user'] === $userId?$players[1]:$players[0];

        $player['health_points_percent'] = round(($player['health_points']/$player['health_points_default'])*100);
        $enemy['health_points_percent'] = round(( $enemy['health_points']/ $enemy['health_points_default'])*100);

        return ['player' => $player, 'enemy' => $enemy];
    }

    public static function attack($players,$damage){
        if($players['enemy']['health_points'] > 0){
            $players['enemy']['health_points']-= $damage;
            $stmt = self::getPdoInstance()->prepare(
                "UPDATE `player` SET `health_points` = `health_points` - :damage WHERE `id` = :id"
            );
            $stmt->execute([
                ':id' =>  $players['enemy']['id'],
                ':damage' =>  $damage,
            ]);
            $log = [];

            $log[$players['player']['user_id']] = 'Вы ударили '.$players['enemy']['nickname'].' на '.$damage.' урона.';
            $log[$players['enemy']['user_id']] = 'Вас ударил '.$players['player']['nickname'].' на '.$damage.' урона.';
            DuelLog::add($players['enemy']['duel'],$log);
        }

        $players['enemy']['health_points_percent'] = round(($players['enemy']['health_points']/$players['enemy']['health_points_default'])*100);

        return $players['enemy'];
    }
}