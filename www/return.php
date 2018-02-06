<?php
ob_start();
header('Location: index.php?action=return', true, 301);
ob_end_flush();