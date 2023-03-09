<?php
$begin_time = time() - 1272000000 + floatval(microtime());
$memcache = memcache_connect('localhost', 11211);


//0.023001998662949s === 20 ms
//0.016001015901566
//0.034001022577286
//0.01400101184845
//0.015000015497208



// 0.017001003026962 === 17 ms
//0.0069999992847443
//0.024001985788345
//0.010000973939896
if ($memcache) {
	$result = $memcache->get("result");
	
	if( $result ){
		define("HOST", "localhost");
		define("DBNAME", "myblog");
		define("DBUSER", "root");
		define("DBPASSWORD", "");

		//подключение через PDO
		try {
			$db = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPASSWORD,
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
		
		$stmt = $db->query("SELECT * FROM `articles`");
		
		while( $row = $stmt->fetch() ){
			$articles['title'][] = $row['title'];
			$articles['author'][] = $row['id_author'];
			$articles['text'][] = $row['text'];
			$articles['id'][] = $row['id'];
			$articles['time'][] = $row['time'];
			$articles['date'][] = $row['date'];
			$articles['img'][] = $row['img'];
			$articles['stat'][] = $row['stat'];
			$articles['category'][] = $row['category'];
		}
		
		$stmt = $db->query("SELECT * FROM `users`");
		while( $row = $stmt->fetch() ){
			$users['id'][] = $row['id'];
			$users['login'][] = $row['login'];
			$users['name'][] = $row['name'];
			$users['surname'][] = $row['surname'];
			$users['age'][] = $row['age'];
			$users['email'][] = $row['email'];
			$users['avatar'][] = $row['avatar'];
			$users['about'][] = $row['about'];
		}
		$result['users'] = $users;
		$result['articles'] = $articles;
		
		$memcache->set("result", $result);
		echo 1;
		var_dump($result);
	}else{
		echo 2;
		var_dump($result);
	}
	
	echo $end_time = time() - 1272000000 + floatval(microtime()) - $begin_time;

 
}
else {
	echo "Connection to memcached failed";
}


//add
//close
//connect
//delete ....


// 1. снижение кол-ва запросов к БД
// 2. уменьшение времени выполнения скрипта (данные хранятся в оперативной памяти)

/*
$result = $memcache->get("articles");
	
	if( !$result ){
		define("HOST", "localhost");
		define("DBNAME", "myblog");
		define("DBUSER", "root");
		define("DBPASSWORD", "");

		//подключение через PDO
		try {
			$db = new PDO('mysql:host=' . HOST . ';dbname=' . DBNAME, DBUSER, DBPASSWORD,
			array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
		} catch (PDOException $e) {
			print "Error!: " . $e->getMessage() . "<br/>";
			die();
		}
		
		$stmt = $db->query("SELECT * FROM `articles`");
		
		while( $row = $stmt->fetch() ){
			$articles['title'][] = $row['title'];
			$articles['author'][] = $row['id_author'];
			$articles['text'][] = $row['text'];
			$articles['id'][] = $row['id'];
			$articles['time'][] = $row['time'];
			$articles['date'][] = $row['date'];
			$articles['img'][] = $row['img'];
			$articles['stat'][] = $row['stat'];
			$articles['category'][] = $row['category'];
		}
		
		$stmt = $db->query("SELECT * FROM `users`");
		while( $row = $stmt->fetch() ){
			$users['id'][] = $row['id'];
			$users['login'][] = $row['login'];
			$users['name'][] = $row['name'];
			$users['surname'][] = $row['surname'];
			$users['age'][] = $row['age'];
			$users['email'][] = $row['email'];
			$users['avatar'][] = $row['avatar'];
			$users['about'][] = $row['about'];
		}
		$result['users'] = $users;
		$result['articles'] = $articles;
		
		$memcache->set("result", $result);
		echo 1;
		var_dump($result);
	}else{
		echo 2;
		var_dump($result);
	}*/