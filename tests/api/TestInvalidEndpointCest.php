<?php

class TestInvalidEndpointCest
{
    public function _before(ApiTester $I) {}

    // tests
    public function tryToTest(ApiTester $I) {
        $I->sendGet('dfgffdgd');
        $I->seeResponseContains('That endpoint does not exist');
    }

}
