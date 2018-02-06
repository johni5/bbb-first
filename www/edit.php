<div id="inner">

    <?php

    use BigBlueButton\Parameters\GetMeetingInfoParameters as GetMeetingInfoParameters;

    $meetingInfo = null;
    if (!empty($_REQUEST['id'])) {
        $meetingId = $_REQUEST['id'];
        $response = $bbb->getMeetingInfo(new GetMeetingInfoParameters($meetingId, $controller->getPassword()));
        if ($controller->goodResponse($response)) {
            $meetingInfo = $response->getMeetingInfo();
        } else {
            $controller->showMessages();
        }
    }

    if (!empty($meetingInfo)) {
        echo '<h1>Информация</h1>';
    } else {
        echo '<h1>Новая комната</h1>';
    }

    ?>

    <form action="" method="<?php echo $controller->getFormMethod() ?>" class="form-horizontal">

        <input type="hidden" name="action" value="save">
        <input type="hidden" name="id" value="<?php echo !empty($meetingInfo) ? $meetingInfo->getMeetingId() : '' ?>">

        <fieldset>
            <?php if (empty($meetingInfo)) { ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="meetingName">Название</label>
                    <div class="col-lg-10">
                        <input class="form-control" id="meetingName" name="meetingName" placeholder="Название"
                               value="<?php echo readParameter('meetingName') ?>"
                               type="text" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label" for="attendeePassword">Пароль</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="attendeePassword" name="attendeePassword"
                               value="<?php echo readParameter('attendeePassword') ?>"
                               placeholder="Гостевой пароль" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label" for="meetingDurationMin">Продолжительность, мин.</label>
                    <div class="col-lg-10">
                        <input class="form-control" id="meetingDurationMin" name="meetingDurationMin" placeholder="не задано"
                               value="<?php echo readParameter('meetingDurationMin') ?>"
                               type="number">
                        <span class="help-block">Продолжительность встречи в минутах с начала входа первого участника</span>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label" for="webcamsOnlyForModerator"></label>
                    <div class="col-lg-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="webcamsOnlyForModerator" id="webcamsOnlyForModerator" value="true" <?php echo "true" == readParameter('webcamsOnlyForModerator') ? 'checked' : '' ?>>Запретить включать камеру слушателям
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label" for="joinMeeting"></label>
                    <div class="col-lg-10">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="joinMeeting" id="joinMeeting" value="true" <?php echo "true" == readParameter('joinMeeting') ? 'checked' : '' ?>>Перейти в комнату сразу после создания
                            </label>
                        </div>
                    </div>
                </div>

            <?php } else { ?>
                <div class="form-group">
                    <label class="col-lg-2 control-label" for="meetingName">Название</label>
                    <div class="col-lg-10">
                        <input class="form-control" id="meetingName" name="meetingName"
                               value="<?php echo $meetingInfo->getMeetingName() ?>"
                               type="text" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label" for="attendeePassword">Пароль</label>
                    <div class="col-lg-10">
                        <input type="text" class="form-control" id="attendeePassword" name="attendeePassword"
                               value="<?php echo $meetingInfo->getAttendeePassword() ?>" readonly>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-lg-2 control-label" for="meetingDurationMin">Продолжительность, мин.</label>
                    <div class="col-lg-10">
                        <input class="form-control" id="meetingDurationMin" name="meetingDurationMin" placeholder="не задано"
                               value="<?php echo $meetingInfo -> getDuration() ?>"
                               type="number" readonly>
                    </div>
                </div>

            <?php }  ?>


            <div class="form-group">
                <div class="col-lg-10 col-lg-offset-2">
                    <div class="btn-group">
                        <?php if (empty($meetingInfo)) { ?>
                            <button type="submit" class="btn btn-primary">Создать</button>
                        <?php } ?>
                        <a href="?action=publics" class="btn btn-default">Отмена</a>
                    </div>
                </div>
            </div>

        </fieldset>

    </form>


</div>