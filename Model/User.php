<?php

namespace Model;

use Exception;
use PDO;
class User extends Model{

    private static function getHash($str){
        return hash('SHA256',$str);
    }

    /**
     * @throws Exception
     */
    public static function auth($nickname, $password){
        $hash = ['hash'=>'','message'=>''];

        $password = self::getHash($password);

        $stmt = self::getPdoInstance()->prepare(
            "SELECT `password` FROM `Users` WHERE `nickname` = :nickname LIMIT 1"
        );

        $stmt->execute([
            ':nickname' => $nickname,
        ]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if(empty($user)){
            $hash['hash'] = self::getHash($nickname.$password.time());
            $hash['message'] = 'Success';
            $stmt = self::getPdoInstance()->prepare(
                "INSERT INTO `users`(`nickname`,`password`,`rating`,`hash`,`damage`,`health_points`,`status`) VALUES(:nickname, :password, :rating, :hash, :damage, :health_points,'')"
            );
            $stmt->execute([
                ':nickname' => $nickname,
                ':hash' =>  $hash['hash'],
                ':password' => $password,
                ':rating' => 100,
                ':damage' => 10,
                ':health_points' => 100

            ]);


        }else{
            if($user['password'] === $password){
                $hash['hash'] = self::getHash($nickname.$password.time());
                $hash['message'] = 'Success';
                $stmt = self::getPdoInstance()->prepare(
                    "UPDATE `users` SET `hash` = :hash WHERE `nickname` = :nickname LIMIT 1"
                );
                $stmt->execute([
                    ':nickname' => $nickname,
                    ':hash' => $hash['hash']
                ]);
            }else{
                $hash['message'] = 'Wrong pass';
            }
        }

        if($hash['hash'] !== ''){
            setcookie('hash',$hash['hash'],time() + 36000);
        }

        return $hash;
    }

    public static function getCurrentUser()
    {
        if(isset($_COOKIE['hash'])){
            $hash = $_COOKIE['hash'];
            $stmt = self::getPdoInstance()->prepare(
                "SELECT * FROM `users` WHERE `hash` = :hash LIMIT 1"
            );
            $stmt->execute([
                ':hash' => $hash,
            ]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!empty($user)){
                return $user;
            }
        }

        return false;
    }

    public static function logout()
    {
        setcookie('hash','',0);

        return true;
    }


}