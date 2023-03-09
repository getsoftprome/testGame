<?php
if($user['status'] === 'searching'){
    header("Refresh: 2");
}
?>

<link rel="stylesheet" href="Assets/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<div class="popup">
    <div class="popup-input">
        <div>
            <?php if($user['status']  === 'searching'):?>
                <div>ПОИСК ДУЭЛИ...</div>
                <a href="?cancel_search=1">
                    <button>ОТМЕНИТЬ ПОИСК</button>
                </a>
            <?php elseif ($user['status']  === ''):?>
            <a href="?search=1">
                <button>НАЧАТЬ ПОИСК</button>
            </a>
            <?php endif;?>
        </div>

    </div>
</div>