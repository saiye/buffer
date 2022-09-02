<?php

define('BUFFER_START', microtime(true));

include_once   'boostrap.php';

(new \Engine\App())->run();

