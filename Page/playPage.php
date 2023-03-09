<?php
header("Refresh: 1");
?>
<link rel="stylesheet" href="Assets/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<?php if($duel['start_time'] > time()):?><div class="start-timer"><?=$duel['start_time'] - time()?></div><?php endif;?>
<div class="logs">
    <?php foreach ($logs as $log):?>
        <div><?=$log[$user['id']]?></div>
    <?endforeach;?>
</div>
<div class="war-zone">
    <div class="player">
        <div class="player-nickname"><?=$players['player']['nickname']?></div>
        <div class="player-hp-bar" style="background: linear-gradient(90deg, rgba(73,232,24,1) <?=$players['player']['health_points_percent']?>%, rgb(181 181 181) <?=$players['player']['health_points_percent']?>%)"><?=$players['player']['health_points']?></div>
        <div class="player-damage">FORCE: <?=$players['player']['damage']?></div>
    </div>
    <div class="player enemy">
        <div class="player-nickname"><?=$players['enemy']['nickname']?></div>
        <div class="player-hp-bar" style="background: linear-gradient(90deg, rgba(73,232,24,1) <?=$players['enemy']['health_points_percent']?>%, rgb(181 181 181) <?=$players['enemy']['health_points_percent']?>%)"><?=$players['enemy']['health_points']?></div>
        <div class="player-damage">FORCE: <?=$players['enemy']['damage']?></div>
        <div class="player-activity"><a href="?attack=1"><button>АТАКОВАТЬ</button></a></div>
    </div>
</div>