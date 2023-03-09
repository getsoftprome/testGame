<link rel="stylesheet" href="Assets/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1"/>
<div class="popup">
    <div class="popup-input">
        <form action="">
            <?php if($message):?><div class="popup-err"><?=$message?></div><?endif;?>
            <div><input type="text" placeholder="nickname" name="nickname"/></div>
            <div><input type="password" placeholder="password" name="password"/></div>
            <div>
                <button>ВОЙТИ</button>
            </div>
        </form>
    </div>
</div>