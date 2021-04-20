<?php

class ShipmentModel {
  protected $db;

  public function __construct() {
    $this->db = require('db/pdo_connection.php');
  }

  /**
   * Returns a collection containing all the shipments in the database
   * @return array an array of shipments
   */
  function getAllShipments(): array {
    $query = '
        SELECT shipment.`shipment_num`, `store_name`, `shipping_address`, 
               `sched_pickup_date`, `driver_id`, `transport_company`, 
               `state`, shipment_orders.`order_num`
        FROM `shipment`
        LEFT OUTER JOIN shipment_orders ON shipment.`shipment_num` = shipment_orders.`shipment_num`
    ';

    $stmt = $this->db->prepare($query);
    $stmt->execute();

    return $this->reformatArray($stmt);
  }

  /**
   * Updates the state of an order
   * @param int $shipmentNum shipment number of the shipment to update
   * @param string $newState new state of the shipment
   */
  function updateShipment(int $shipmentNum, string $newState) {
    $query = '
      UPDATE `shipment` 
      SET `state`= :newState
      WHERE `shipment_num` = :shipment_num
    ';

    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':shipment_num', $shipmentNum);
    $stmt->bindValue(':newState', $newState);

    $stmt->execute();

    // if zero rows affected, throw exception
    if ($stmt->rowCount() == 0) {
      throw new \http\Exception\InvalidArgumentException($shipmentNum." does not exist!");
    };

  }

  /**
   * Reformat array to collect affiliated data
   * @param PDOStatement $stmt statement to get the data from
   * @return array newly formatted array
   */
  private function reformatArray(PDOStatement $stmt): array {
    $res = array();

    while ($row = $stmt->fetch()) {
      $shipmentNum = $row['shipment_num'];

      if (!array_key_exists($shipmentNum, $res)) {
        $res[$shipmentNum] = array(
          array(
            'shipment_num' => $shipmentNum,
            'store_name' => $row['store_name'],
            'shipping_address' => $row['shipping_address'],
            'sched_pickup_date' => $row['sched_pickup_date'],
            'driver_id' => $row['driver_id'],
            'transport_company' => $row['transport_company']
          ),
          array()
        );
      }

      array_push($res[$shipmentNum][1], $row['order_num']);
    }

    return array_values($res);
  }
}