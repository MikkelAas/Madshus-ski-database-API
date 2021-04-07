<?php
require_once('config/config_test.php');
require('models/OrderModel.php');

class SkiModelTest extends \Codeception\Test\Unit{
    public function testGetAllOrdersForCustomer(){
        $orderModel = new OrderModel();

        $res = $orderModel->getAllOrdersForCustomer(1);

        self::assertCount(12, $res);
    }

    public function testCreateOrder(){
        $orderModel = new OrderModel();

        $orderModel->createOrder(10000, NULL, 1, "new", NULL);

        $res = $orderModel->getAllOrdersForCustomer(1);

        self::assertCount(13,$res);
    }

    public function testChangeOrderState(){
        $orderModel = new OrderModel();

        $orderModel->changeOrderState(1, 'skis available', null);

        $res = $orderModel->getOrderStatement(1);

        self::assertStringContainsString('skis available', $res);
    }

    public function testDeleteOrder(){
        $orderModel = new OrderModel();

        $orderModel->deleteOrder(2);

        $res = $orderModel->getAllOrdersForCustomer(1);

        self::assertCount(12, $res);
    }

    public function testGetOrders(){
        $orderModel = new OrderModel();

        $res = $orderModel->getOrders('');

        self::assertCount(12, $res);
    }
}