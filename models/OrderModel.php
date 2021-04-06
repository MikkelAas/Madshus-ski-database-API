<?php


/**
 * Class OrderModel
 * This class generates JSON documents
 */
class OrderModel
{

    /**
     * OrderModel constructor.
     */
    public function __construct()
    {
        $this->db = require_once('db/pdo_connection.php');
    }

    /**
     * Retrieves information about all orders for a customer
     * @param int $customerId The id of the customer
     * @return array an array of associative arrays of the form:
     *         array('id' => '...', 'make' => '...', 'model' => '...', 'model_year' => '...', ...), ...)), ...)
     */
    public function getAllOrdersForCustomer(int $customerId): array
    {
        $query = '
                SELECT ski_order.order_number, 
                    ski_order.total_price, 
                    ski_order.state, 
                    ski_order.reference_to_larger_order, 
                    ski_order.customer_id
                FROM `ski_order`
                WHERE ski_order.customer_id = :customer_id
            ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':customer_id', $customerId);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Creates an order.
     * @param int $orderNumber Sets order id.
     * @param int $totalPrice Sets the total price.
     * @param string $stateMessage Sets the state of the order.
     * @param mixed $refToLargerOrder Is either an int or null
     * @param int $customerId Sets the customer id.
     */
    public function setOrder(int $orderNumber, int $totalPrice, string $stateMessage, $refToLargerOrder, int $customerId)
    {
        if ($stateMessage != 'new' && $stateMessage != 'open' && $stateMessage != "skis available"){
            throw new InvalidArgumentException("Invalid state.");
        }

        $query = '
                INSERT INTO `ski_order` (
                            `order_number`, 
                            `total_price`, 
                            `state`,
                            `reference_to_larger_order`,
                            `customer_id`) 
                VALUES (
                    :order_number, 
                    :total_price, 
                    :state_message,
                    :reference_order,
                    :customer_id);
            ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_number', $orderNumber);
        $stmt->bindValue(':total_price', $totalPrice);
        $stmt->bindValue(':state_message', $stateMessage);
        $stmt->bindValue(':reference_order', $refToLargerOrder);
        $stmt->bindValue(':customer_id', $customerId);
        $stmt->execute();
    }

    /**
     * Changes the state of an order.
     * @param int $orderId The id of the order you want to change.
     * @param string $newState The new state of the order.
     */
    public function changeOrderState(int $orderId, string $newState){
        if ($newState != 'new' && $newState != 'open' && $newState != "skis available"){
            throw new InvalidArgumentException("Invaid state.");
        }

        $query = '
            UPDATE `ski_order` 
            SET `state` = :new_state 
            WHERE `ski_order`.`order_number` = :order_id
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_id', $orderId);
        $stmt->bindValue(':new_state', $newState);
        $stmt->execute();
    }

    /**
     * Deletes an entry from the ski order table.
     * @param int $orderNumber The id of the order that will be deleted.
     */
    public function deleteOrder(int $orderNumber){
        $query = '
            DELETE FROM ski_order
            WHERE ski_order.order_number = :order_number
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_number',$orderNumber);
        $stmt->execute();
    }
}