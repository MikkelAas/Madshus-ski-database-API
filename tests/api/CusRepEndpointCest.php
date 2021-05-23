<?php

declare(strict_types = 0);

class CusRepEndpointCest {

    public function _before(ApiTester $I) {}

    public function testGETOrders(ApiTester $I) {
        $I->haveHttpHeader('TOKEN', 'test-token');
        $I->sendGet("com/cus/orders");
        $I->seeResponseIsJson();
        $I->seeResponseContains('{
    "order_number": "1",
    "total_price": "100",
    "reference_to_larger_order": null,
    "customer_id": "1",
    "skis": [
      {
        "ski_type_id": "1",
        "quantity": "4"
      }
    ]
  },');
    }

}
