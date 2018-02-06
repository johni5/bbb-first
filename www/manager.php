<div id="inner">

    <h1>Админская</h1>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Информация по интеграции</h3>
        </div>
        <div class="panel-body">
            <?php
            $response = $controller->bbb->getApiVersion();
            echo '<p>Версия API: <b>' . $response->getVersion() . '</b></p>';
            echo '<p>Адрес: <b>' . $controller->bbb->bbbServerBaseUrl . '</b></p>';
            echo '<p>Ключ: <b>' . $controller->bbb->securitySalt . '</b></p>';
            echo '<p>Демо режим: <b>' . ($controller->bbb->dummyMode ? 'ДА' : 'НЕТ') . '</b></p>';

            $response = $controller->bbb->getDefaultConfigXML();
            $response->getRawXml();

            ?>
        </div>
    </div>

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Конфигурация по умолчанию</h3>
        </div>
        <div class="panel-body">
            <?php
            $response = $controller->bbb->getDefaultConfigXML();
            echo '<pre>' . (empty($response->getRawXml()) ? 'Отсутствует' : htmlspecialchars($response->getRawXml()->asXML())) . '</pre>';
            ?>
        </div>
    </div>


</div>