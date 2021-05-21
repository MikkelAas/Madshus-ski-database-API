<?php

class TestInvalidEndpointCest {

    public function _before(ApiTester $I) {}

    // Test that an endpoint that does not exist returns an error
    public function testEndpointNotExisting (ApiTester $I) {
        $I->sendGet('fdgdfg');
        $I->seeResponseContains('That endpoint does not exist');
    }

}
