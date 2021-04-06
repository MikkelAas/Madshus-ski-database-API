<?php


/**
 * Class OrderModel
 * This class generates JSON documents
 */
class OrderModel
{
    protected $db;

    /**
     * OrderModel constructor.
     */
    public function __construct()
    {
        $this->db = new PDO('mysql:host=' . DB_HOST . '.dbname=' . DB_NAME . ';charset=utf8',
            DB_USER, DB_PWD,
            array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    /**
     * Retrieves information about all orders for a customer
     * @param int $customerId The id of the customer
     * @return array an array of associative arrays of the form:
     *         array('id' => '...', 'make' => '...', 'model' => '...', 'model_year' => '...', ...), ...)), ...)
     */
    public function getOrderForCustomer(int $customerId): array
    {
        $res = array();

        $query = '
                SELECT ski_order.order_number, 
                    ski_order.total_price, 
                    ski_order.state, 
                    ski_order.reference_to_larger_order, 
                    ski_order.customer_id
                FROM `ski_order`
                INNER JOIN ski_order_ski_type ON ski_order_ski_type.order_id = ski_order.order_number
                WHERE ski_order.customer_id = :customer_id
            ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':customer_id', $customerId);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $pos = count($res);
            $res[] = $row;
        }
        return $res;
    }

    /**
     * Creates an order.
     * @param int $orderNumber Sets order id.
     * @param int $totalPrice Sets the total price.
     * @param string $stateMessage Sets the state of the order.
     * @param int $refToLargerOrder Sets the reference to a lager order. This can be null.
     * @param int $customerId Sets the customer id.
     */
    public function setOrder(int $orderNumber, int $totalPrice, string $stateMessage, int $refToLargerOrder, int $customerId)
    {
        $res = array();

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

        // Do something with the $res[]
    }

    /**
     * Changes the state of an order.
     * @param int $orderId The id of the order you want to change.
     * @param string $newState The new state of the order.
     */
    public function changeOrderState(int $orderId, string $newState)
    {
        $res = array();

        $query = '
                UPDATE `ski_order` 
                SET `state` = :new_state 
                WHERE `ski_order`.`order_number` = :order_id
            ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_id', $orderId);
        $stmt->bindValue(':new_state', $newState);
        $stmt->execute();

        // Do something with the $res[]
    }

}