<?php
require_once('config/config_test.php');
require('models/OrderModel.php');

// TODO: Find out why the database doesn't reset.
class SkiModelTest extends \Codeception\Test\Unit{
    public function testGetAllOrdersForCustomer(){
        $orderModel = new OrderModel();

        $res = $orderModel->getAllOrdersForCustomer(1);

        var_dump($res);
        self::assertCount(4, $res);
    }

    public function testCreateOrder(){
        $orderModel = new OrderModel();

        $orderModel->createOrder(
            10000,
            NULL,
            1,
            "new",
            NULL,
            1,
            250
        );

        $res = $orderModel->getAllOrdersForCustomer(1);

        self::assertCount(5,$res);
    }

    public function testChangeOrderState(){
        $orderModel = new OrderModel();

        $orderModel->changeOrderState(1, 'skis available', null);

        $res = $orderModel->getOrderStatement(1);

        self::assertStringContainsString('skis available', $res);
    }

    public function testDeleteOrder(){
        $orderModel = new OrderModel();

        $orderModel->deleteOrder(1);

        $res = $orderModel->getAllOrdersForCustomer(1);

        self::assertCount(3, $res);
    }

    public function testGetOrders(){
        $orderModel = new OrderModel();

        $res1 = $orderModel->getOrders('');
        $res2 = $orderModel->getOrders('2021-04-15');

        self::assertCount(4, $res1);
        self::assertCount(1, $res2);
    }

    public function testGetOrder(){
        $orderModel = new OrderModel();

        $res = $orderModel->getOrder(1);

        self::assertCount(1, $res);
    }

    public function testGetOrderBasedOnState(){
        $orderModel = new OrderModel();

        $res = $orderModel->getOrdersBasedOnState('new');

        self::assertCount(3, $res);
    }

    public function testAddToOrder(){
        $orderModel = new OrderModel();

        $orderModel->addToOrder(1, 1, 100);

        $res = $orderModel->getOrder(1);

        self::assertCount(2, $res[0][1]);
    }
}