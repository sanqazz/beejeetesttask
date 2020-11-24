<nav class="navbar navbar-expand-lg fixed-top navbar-light d-flex flex-row-reverse" >
    <?php

    if(!$token):

    ?>
        <a class="navbar-brand text-right" href="login">Логин</a>
    <?php
        else:
    ?>
        <a class="navbar-brand text-right" href="exit">Логаут</a>
        <a class="navbar-brand text-right" href="/">Основная</a>
    <?php
        endif;
    ?>


    </nav>
<div class="container" style="margin-top: 4rem">

    <?php
        if(!$token):
    ?>
    <p>Вы не авторизированы! Вернитесь на <a href="/">главную</a> страницу или авторизируйтесь!</p>
    <?php
        else:
    ?>
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
            <option value="ASC" <?php if ($order == "ASC"){echo "selected";}?>>По возрастанию</option>
            <option value="DESC" <?php if ($order == "DESC"){echo "selected";}?>>По убыванию</option>
        </select>
        </div>
        <input type="text" name="page" value="<?= $page?>" hidden="true">
        <button type="submit" class="btn btn-primary">Отсортировать</button>
    </div>
    </form>
    <table class="table table-light table-bordered table-hover">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>E-mail</th>
            <th>Задача</th>
            <th>Состояние</th>
            <th>Отредактирована</th>
            <th>Изменить</th>
            <th class="text-danger">Удалить</th>
        </tr>
    </thead>
    <tbody>
    <?php
        foreach($posts as $post):
    ?>
        <tr>
            <td><?=$post['id'] ?></td>
            <td><?=$post['name'] ?></td>
            <td><?=$post['email'] ?></td>
            <td><?=$post['task'] ?></td>
            <td><?php if ($post['state']){echo "ВЫПОЛНЕНА";} else {echo "ВЫПОЛНЯЕТСЯ";} ?></td>
            <td><?php if ($post['edited']){echo "ДА";} else {echo "НЕТ";} ?></td>
            <td><a href="admin/edit?id=<?=$post['id'] ?>">Изменить</a></td>
            <td><a href="admin/delete?id=<?=$post['id'] ?>" class="text-danger"><strong>X</strong></a></td>
        </tr>
    <?php
        endforeach;
    ?>
    </tbody>
    </table>


    <?php
        echo "<a href=\"?page=1".$sortReq.$orderReq."\"><<</a>";
            for ($i = 1; $i <= $pagesCount; $i++){
                if ($page == $i) {
                    $class = " class =\"active m-2\"";
                } else {
                    $class = " class =\"\"";
                }
                echo "<a href=\"?page=$i".$sortReq.$orderReq."\"$class>$i</a>";
            }
        echo "<a href=\"?page=$pagesCount".$sortReq.$orderReq."\">>></a>";
        endif;
    ?>

</div>
