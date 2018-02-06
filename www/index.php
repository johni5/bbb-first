<!DOCTYPE html>
<html>
<head>
    <?php include_once('header.php'); ?>
</head>
<body>

<div id="root">

    <nav class="navbar navbar-inverse">
        <div class="container-fluid">

            <div class="navbar-header">
                <?php if ($controller->isLoggedIn()) { ?>
                    <button class="navbar-toggle collapsed" type="button" data-toggle="collapse"
                            data-target="#bs-example-navbar-collapse-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                <?php } ?>
                <a class="navbar-brand" href="?action=firstPage">Наша Беседка</a>
            </div>

            <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-2" aria-expanded="false"
                 style="height: 1px;">
                <ul class="nav navbar-nav">
                    <?php if ($controller->isLoggedIn()) { ?>
                        <li><a href="?action=publics">Комнаты</a></li>
                    <?php } ?>
                    <?php if ($controller->isManager()) { ?>
                        <li><a href="?action=managers">Админская</a></li>
                    <?php } ?>
                </ul>
                <?php if ($controller->isLoggedIn()) { ?>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="?action=logout">Выйти</a></li>
                    </ul>
                    <span class="navbar-text navbar-right"><?php echo $controller->getLogin(); ?></span>
                <?php } ?>
            </div>


        </div>

    </nav>

    <main role="main" class="container">
        <?php
        $controller->showMessages();
        include($controller->viewPage);
        ?>
    </main>

</div>

</body>
</html>