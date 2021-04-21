<?php
require_once('config/config_test.php');
require('models/ShipmentModel.php');

class ShipmentModelTest extends \Codeception\Test\Unit {

  /**
   * Object used for asserting values in db
   * @var \UnitTester
   */
  protected UnitTester $tester;

  /**
   * Test for getting all shipments in the db
   */
  public function testGetAllShipments() {
    $shipmentModel = new ShipmentModel();

    $res = $shipmentModel->getAllShipments();

    self::assertCount(2,$res);
  }

  /**
   * Test for creating a shipment request
   */
  public function testCreateShipmentRequest() {
    $shipmentModel = new ShipmentModel();

    $shipmentModel->createShipmentRequest(array(10, 1));

    $this->tester->seeInDatabase('shipment', ['shipment_num' => 5]);
    $this->tester->seeNumRecords(2, 'shipment_orders', ['shipment_num' => 5]);
  }

  /**
   * Test to make sure exception is thrown if no orders are given when
   * creating a shipment request
   */
  public function testEmptyCreateShipmentRequest() {
    $shipmentModel = new ShipmentModel();

    try {
      $shipmentModel->createShipmentRequest(array());
      self::fail();
    } catch (Exception $e) {
      // win :)
    }
  }

  /**
   * Test for updating the state of a shipment
   */
  public function testUpdateShipment() {
    $shipmentModel = new ShipmentModel();

    $shipmentModel->updateShipment(1, "picked up");
    $this->tester->seeInDatabase('shipment', ['shipment_num' => 1, 'state' => "picked up"]);
  }
}