<nav class="navbar  navbar-expand-lg fixed-top navbar-light d-flex flex-row-reverse" >
    <?php

    if(!$token):

    ?>
        <a class="navbar-brand text-right" href="login">Логин</a>
    <?php
        else:
    ?>
        <a class="navbar-brand text-right" href="exit">Логаут</a>
        <a class="navbar-brand text-right" href="admin">Администрирование</a>
    <?php
        endif;
    ?>
</nav>

<div class="container" style="margin-top: 4rem">

<form method="GET">
    <div class="form-row">
    <label for="props">Сортировать по:</label>
        <div class="col-2">
        <select id="props" name="sort" class="form-control">
            <option value="name" <?php if ($sort == "name"){echo "selected";}?>>Имя</option>
            <option value="email" <?php if ($sort == "email"){echo "selected";}?>>E-mail</option>
            <option value="state" <?php if ($sort == "state"){echo "selected";}?>>Состояние</option>
        </select>
        </div>
        <div class="col-2">
        <select id="order" name="order" class="form-control" value="desc">
            <option value="ASC" <?php if ($order == "ASC"){echo "selected";}?>>По возростанию</option>
            <option value="DESC" <?php if ($order == "DESC"){echo "selected";}?>>По убыванию</option>
        </select>
        </div>
        <input type="text" name="page" value="<?= $page?>" hidden="true">
        <button type="submit" class="btn btn-primary">Отсортировать</button>
    </div>
</form>

    <ul>
    <?php
        foreach($posts as $post):
    ?>

    <div class="container mt-4">
        <p>Имя: <?=$post['name'] ?></p>
        <p>E-mail: <?=$post['email'] ?></p>
        <p>Задача: <?=$post['task'] ?></p>
        <p>Состояние: <?php if ($post['state']){echo "ВЫПОЛНЕНА";} else {echo "ВЫПОЛНЯЕТСЯ";} ?></p>
        <?php if ($post['edited']){echo "<p class=\"alert alert-info\">Отредактировано Администратором</p>";}?>
    </div><hr>

    <?php
        endforeach;
    ?>
    </ul>

    <?php
        echo "<a href=\"?page=1".$existReq."\"><<</a>";
            for ($i = 1; $i <= $pagesCount; $i++){
                if ($page == $i) {
                    $class = " class =\"active m-2\"";
                } else {
                    $class = " class =\"\"";
                }
                echo "<a href=\"?page=$i".$existReq."\"$class>$i</a>";
            }
        echo "<a href=\"?page=$pagesCount".$existReq."\">>></a>";
    ?>
    <?php
        if($message != ''){
            echo "<div class=\"alert alert-success col-4 alert-center\" style=\"margin-right: auto; margin-left: auto;\" role=\"alert\">
                Задача успешно добавлена!
                </div>";
            }
    ?>
</div>

<div class="container">
    <form method="POST">
        <div class="form-group">
            <label for="inputName">Имя:</label>
            <input type="text" name="userName" id="inputName" class="form-control form-control-sm" placeholder="Введите Ваше имя" required>
            <label for="inputEmail">E-mail</label>
            <input type="email" name="email" id="inputEmail" class="form-control form-control-sm" placeholder="Введите Ваш e-mail" required>
            <label for="textareaTask">Задача:</label>
            <textarea name="task" cols="30" rows="10" id="textareaTask" class="form-control" required></textarea><br>
            <button type="submit" class="btn btn-primary">Отправить</button>
        </div>
    </form>
</div>
