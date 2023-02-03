<?php

use App\Http\Factory\JsonResponseFactory;

header('Content-Type: application/json');
echo JsonResponseFactory::methodForbidden()->getContent();
?>
