<?php

declare(strict_types = 0);

class CusRepEndpointCest {

    public function _before(ApiTester $I) {}

    // Test that an error is returned if no access token is provided
    public function testGETOrdersWithoutToken (ApiTester $I) {
        $I->sendGet("com/cus/orders");
        $I->seeResponseIsJson();
        $I->seeResponseContains('error');
    }

    // Test if GET Orders works
    public function testGETOrders(ApiTester $I) {
        $I->haveHttpHeader('TOKEN', 'test-token');
        $I->sendGet("com/cus/orders");
        $I->seeResponseIsJson();
        $I->seeResponseContains('[{"order_number":"1","total_price":"100","reference_to_larger_order":null,"customer_id":"1","skis":[{"ski_type_id":"1","quantity":"4"}]}');
    }

    // Test if patching an order (changing state) works
    public function testPATCHOrder (ApiTester $I) {
        $I->haveHttpHeader('TOKEN', 'test-token');
        $I->sendPATCH("com/cus/orders/1", json_encode(["id"=>1, "state"=>"new"]));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

    // Test if creating a shipment for orders works
    public function testPOSTShipmentCreate (ApiTester $I) {
        $I->haveHttpHeader('TOKEN', 'test-token');
        $I->sendPOST("com/cus/req_ship", json_encode(["orders"=>[1, 10]]));
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
    }

}
