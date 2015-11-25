<?php
$I = new ApiTester($scenario);
$I->wantTo('Get all labels in JSON format');
$I->haveHttpHeader('Accept', 'application/json');
$I->haveHttpHeader('Content-Type', 'application/json');
$I->sendGet('labels.json');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();