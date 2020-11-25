
<nav class="navbar navbar-expand-lg fixed-top navbar-light d-flex flex-row-reverse" >

        <a class="navbar-brand text-right" href="/">Основная</a>

    </nav>
<div class="container mt-4">
    <?php

        if(!$token):
    ?>
    <div class="row">
        <div class="col">
        <h1>Логин</h1>
                <form action="auth" method="POST">
                <input type="text" class="form-control" name="login" id="login" placeholder="Введите Ваш логин" required><br>
                <input type="password" class="form-control" name="password" id="password" placeholder="Введите Ваш пароль" required><br>
                <button class="btn btn-success" type="submit">Логин</button>
            </form>
        </div>
    </div>
    <?php
        if($message != ''){
            echo "<div class=\"alert alert-warning col-4 alert-center\" style=\"margin-right: auto; margin-left: auto;\" role=\"alert\">
                $message
                </div>";
            }
    ?>
    <?php
        else:
            header("location: /admin");
        endif;

    ?>
</div>
