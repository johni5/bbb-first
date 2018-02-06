<div id="inner">

    <h1>Список доступных комнат</h1>
    <?php

    $response = $bbb->getMeetings();
    $available = array();

    if ($controller->goodResponse($response)) {
        foreach ($response->getMeetings() as $m) {
            if (empty($m->getAttendeePassword()) && empty($m->getModeratorPassword())
                || $m->getAttendeePassword() == $controller->getPassword()
                || $m->getModeratorPassword() == $controller->getPassword()
                || $controller->isManager()
            ) {
//            $m->getParticipantCount()
                $available[] = $m;
            }
        }
    } else {
        $controller->showMessages();
    }

    if (!empty($available)) {
        ?>
        <table class="table table-striped table-hover ">
            <thead>
            <tr>
                <th>Название</th>
                <th>Начало трансляции</th>
                <th>Участников</th>
                <th style="width: 30%"></th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($available as $m) {
                ?>
                <tr>
                    <td>
                        <?php echo $m->getMeetingName() ?>
                    </td>
                    <td>
                        <?php echo $m->getCreationDate() ?>
                    </td>
                    <td>
                        <?php echo $m->getParticipantCount() ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <a id="btn-join" href="?action=join&id=<?php echo $m->getMeetingId() ?>"
                               class="btn btn-default">Зайти</a>
                            <?php
                            if ($controller->isManager()) {
                                ?>
                                <a id="btn-info" href="?action=info&id=<?php echo $m->getMeetingId() ?>"
                                   class="btn btn-default">Инфо</a>
                                <a id="btn-stop" href="?action=stop&id=<?php echo $m->getMeetingId() ?>"
                                   class="btn btn-default">Закрыть</a>
                                <?php
                            }
                            ?>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php
    } else {
        echo '<div class="alert alert-dismissible alert-warning">На данный момент трансляция не ведется!</div>';
    }
    ?>

    <div class="btn-group">
        <a id="btn-stop" href="?action=publics" class="btn btn-default">Обновить</a>

        <?php if ($controller->isManager()) { ?>
            <a href="?action=create" class="btn btn-primary">Создать</a>
        <?php } ?>
    </div>

</div>