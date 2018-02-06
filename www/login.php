<div class="main-container">
    <div class="form-area panel panel-default">

        <div class="panel-body">

            <form action="" method="post" class="form-horizontal">

                <input type="hidden" name="action" value="login">

                <fieldset>
                    <legend>Вход в беседку</legend>
                    <div class="form-group">
                        <label class="input-group-addon" for="username"><i class="fa fa-user"></i></label>
                        <div class="col-lg-10">
                            <input class="form-control" id="username" name="login" placeholder="Введите ваш логин"
                                   type="text" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="input-group-addon" for="password"><i class="fa fa-lock"></i></label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control" id="password" name="password"
                                   placeholder="Введите ваш пароль" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button type="submit" class="btn btn-primary">Войти</button>
                        </div>
                    </div>

                </fieldset>
            </form>

        </div>
    </div>
</div>
