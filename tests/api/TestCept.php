<?php

$I = new ApiTester($scenario);
//$I->haveHttpHeader("Set-Cookie", "wordpress_logged_in_c87c8c739570b74ad637e0201911784a=admin%7C1581231162%7Cw8nVXGATZimV3WYMjxggq4xzlfeW2d0frM7SLBB6Xuv%7Cc57e7e6dc9741b7c254e7fd6878401b2f62f975210fb46da942e816ceab3dea0");
//$I->haveHttpHeader("Set-Cookie", "wordpress_c87c8c739570b74ad637e0201911784a=admin%7C1581231162%7Cw8nVXGATZimV3WYMjxggq4xzlfeW2d0frM7SLBB6Xuv%7C31c748e9eb84b3a202a9fcd507ce6dd798ca0f5d53e5b5b3ee6e24f05a6b93be");


//$I->haveHttpHeader("X-WP-Nonce", "736765369c");
$response = $I->sendPOST("/wp-json/developer-contest/v1/fetch-title", [
    "_wpnonce" => "736765369c"
]);
//var_dump($response);die();
$I->seeResponseContains('HAPPY');