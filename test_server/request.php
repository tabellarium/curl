<?php

declare(strict_types=1);

echo sprintf('%s %s %s', $_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], $_SERVER['SERVER_PROTOCOL']);
