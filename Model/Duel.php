<?php

namespace Model;

use PDO;

class Duel extends Model
{
    public static function create($playerFirst,$playerSecond){
        $startDelay = 30;
        $stmt = self::getPdoInstance()->prepare(
            "UPDATE `users` SET `status` = 'playing' WHERE `id` = :player_first OR `id` = :player_second "
        );
        $stmt->execute([
            ':player_first' => $playerFirst['id'],
            ':player_second' => $playerSecond['id'],
        ]);

        $stmt = self::getPdoInstance()->prepare(
            "INSERT INTO `duel`(`player_first`,`player_second`,`start_time`) VALUES (:player_first,:player_second,:start_time)"
        );
        $stmt->execute([
            ':player_first' => $playerFirst['id'],
            ':player_second' => $playerSecond['id'],
            ':start_time' => time()+$startDelay,
        ]);

        $duelId =self::getPdoInstance()->lastInsertId();

        $stmt = self::getPdoInstance()->prepare(
            "INSERT INTO `player`(`duel`,`health_points`,`damage`,`user`) VALUES (:duel_first,:health_points_first,:damage_first,:user_first),(:duel_second,:health_points_second,:damage_second,:user_second)"
        );

        $stmt->execute([
            ':duel_first' => $duelId,
            ':health_points_first' => $playerFirst['health_points'],
            ':damage_first' => $playerFirst['damage'],
            ':user_first' => $playerFirst['id'],
            ':duel_second' => $duelId,
            ':health_points_second' => $playerSecond['health_points'],
            ':damage_second' => $playerSecond['damage'],
            ':user_second' => $playerSecond['id']
        ]);


    }

    public static function search($user){

        if($user['status'] !== 'searching'){
            $stmt = self::getPdoInstance()->prepare(
                "UPDATE `users` SET `status` = 'searching' WHERE `id` = :id"
            );
            $stmt->execute([
                ':id' => $user['id'],
            ]);
        }

        $stmt = self::getPdoInstance()->prepare(
            "SELECT * FROM `users` WHERE `status` = 'searching' AND `id` != :id LIMIT 1"
        );
        $stmt->execute([
            ':id' => $user['id'],
        ]);

        $searchedUser = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!empty($searchedUser)){
            self::create($user,$searchedUser);
            return true;
        }
        return false;
    }

    public static function cancelSearch($user)
    {
        if($user['status'] == 'searching'){
            $stmt = self::getPdoInstance()->prepare(
                "UPDATE `users` SET `status` = '' WHERE `id` = :id"
            );
            $stmt->execute([
                ':id' => $user['id'],
            ]);
        }
    }

    public static function getByUserId($id){
        $stmt = self::getPdoInstance()->prepare(
            "SELECT * FROM `duel` WHERE `player_first` = :id_f OR `player_second` = :id_s LIMIT 1"
        );
        $stmt->execute([
            ':id_f' => $id,
            ':id_s' => $id,
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function end($duel, $players)
    {
        $log[$players['player']['user_id']] = 'Вы убили '.$players['enemy']['nickname'];
        $log[$players['enemy']['user_id']] = 'Вас убил '.$players['player']['nickname'];
        
        DuelLog::add($players['enemy']['duel'],$log);

        $stmt = self::getPdoInstance()->prepare(
            "UPDATE `users` SET `status` = '', `rating` = `rating`+1, `health_points` = `health_points`+1, `damage` = `damage`+1  WHERE `id` = :id"
        );
        $stmt->execute([
            ':id' => $players['player']['user_id'],
        ]);


        $stmt = self::getPdoInstance()->prepare(
            "UPDATE `users` SET `status` = '', `rating` = `rating`-1, `health_points` = `health_points`-1, `damage` = `damage`-1  WHERE `id` = :id"
        );
        $stmt->execute([
            ':id' => $players['enemy']['user_id'],
        ]);

        $stmt = self::getPdoInstance()->prepare(
            "DELETE FROM `duel` WHERE `id` = :id"
        );
        $stmt->execute([
            ':id' => $duel['id'],
        ]);

        $stmt = self::getPdoInstance()->prepare(
            "DELETE FROM `player` WHERE `duel` = :id"
        );
        $stmt->execute([
            ':id' => $duel['id'],
        ]);
    }
}