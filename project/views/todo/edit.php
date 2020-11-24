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
    <form id="change" method="POST">
    <div class="container mt-4 col-8">
        <div>Id: <input  class="form-control" type="text" name="id" value="<?=$post['id'] ?>"></div><br>
        <div>Имя: <input  class="form-control" type="text" name="name" value="<?=$post['name'] ?>"></div><br>
        <div>E-mail: <input class="form-control" type="text" name="email" value="<?=$post['email'] ?>"></div><br>
        <div>Задача: <textarea class="form-control" cols="30" rows="5" name="task"><?=$post['task'] ?></textarea></div><br>
        <div>
        Состояние:  <input  class="form-control" type="checkbox" name="state" value="0" checked hidden="true">
                <input  class="form-control" type="checkbox" name="state" value="1" <?php if($post['state']) echo "checked" ?>>

        </div><br>
        <input type="submit" class="btn btn-primary" name="accept" value="Accept">

    </div>

    </form>


    <?php
        endif;
    ?>


</div>
