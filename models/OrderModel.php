<?php


/**
 * Class OrderModel.
 * This class generates JSON documents.
 */
class OrderModel{

    /**
     * @var mixed
     */
    private PDO $db;

    /**
     * OrderModel constructor.
     */
    public function __construct(){
        $this->db = require_once('db/pdo_connection.php');
    }

    /**
     * Retrieves information about all orders for a customer.
     * @param int $customerId The id of the customer.
     * @return array an array of associative arrays of the form:
     *         array('order_number' => '...', 'total_price' => '...', 'state' => '...', ...), ...)), ...)
     */
    public function getAllOrdersForCustomer(int $customerId): array{

        // This query selects everything from ski_order where the customer id matches.
        $query = '
                SELECT ski_order.order_number, 
                    ski_order.total_price, 
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
     * @param int $totalPrice Sets the total price.
     * @param mixed $refToLargerOrder Is either an int or null.
     * @param int $customerId
     * @param string $stateMessage Sets the state of the order.
     * @param mixed $employeeId Is either an int or null.
     */
    public function createOrder(int $totalPrice, $refToLargerOrder, int $customerId, string $stateMessage , $employeeId){

        // Checks if the state message is valid.
        if ($stateMessage != 'new' && $stateMessage != 'open' && $stateMessage != "skis available"){
            throw new InvalidArgumentException("Invalid state.");
        }

        // Inserts a new ski order entry.
        $query1 = '
                INSERT INTO `ski_order` ( 
                            `total_price`, 
                            `reference_to_larger_order`,
                            `customer_id`
                            ) 
                VALUES (
                    :total_price, 
                    :reference_order,
                    :customer_id
                    );
        ';

        $stmt = $this->db->prepare($query1);
        $stmt->bindValue(':total_price', $totalPrice);
        $stmt->bindValue(':reference_order', $refToLargerOrder);
        $stmt->bindValue(':customer_id', $customerId);
        $stmt->execute();

        // Selects the highest ID. Not ideal, but it is what it is.
        // TODO: Find a way to save the newly inserted id. Perhaps find another way to store id.
        $query2 = '
            SELECT MAX(ski_order.order_number) 
            FROM ski_order
        ';

        $stmt = $this->db->prepare($query2);
        $stmt->execute();

        $orderId = (int)$stmt->fetchColumn(0);




        // Inserts a new entry in the ski order state history.
        $query3 = '
            INSERT INTO `ski_order_state_history` (`ski_order_id`, `employee_id`, `state`, `date`) 
            VALUES (  
                :order_id,
                :employee_id,
                :state_message,
                :date_now 
                )
        ';

        $stmt = $this->db->prepare($query3);
        $stmt->bindValue(':order_id', $orderId);
        $stmt->bindValue('employee_id', $employeeId);
        $stmt->bindValue(':state_message', $stateMessage);
        $stmt->bindValue(':date_now', date("Y-d-m"));
        $stmt->execute();
    }

    /**
     * Changes the state of an order.
     * @param int $orderId The id of the order you want to change.
     * @param string $newState The new state of the order.
     * @param $employeeId
     */
    public function changeOrderState(int $orderId, string $newState, $employeeId){

        // Checks if the state message is valid.
        if ($newState != 'new' && $newState != 'open' && $newState != "skis available"){
            throw new InvalidArgumentException("Invalid state.");
        }

        // Selects the id, and the ski order id where the order id matches.
        $query1 = '
            SELECT id, ski_order_id 
            FROM ski_order_state_history 
            WHERE ski_order_state_history.`ski_order_id` = :order_id
        ';

        $stmt = $this->db->prepare($query1);
        $stmt->bindValue(':order_id', $orderId);
        $stmt->execute();

        // Saves the original order number and ski order number in two variables
        $originalSkiOrderId = (int)$stmt->fetchColumn(1);

        // Inserts a new entry in the ski order state history table with the same order number.
        $query2 = '
            INSERT INTO `ski_order_state_history` (`ski_order_id`, `employee_id`, `state`, `date`)
            VALUES (
                :original_ski_order_id,
                :employee_id,
                :new_state,
                :date_now)
        ';

        print (date('Y-d-m'));
        $stmt = $this->db->prepare($query2);
        $stmt->bindValue(':original_ski_order_id', $originalSkiOrderId);
        $stmt->bindValue(':employee_id', $employeeId);
        $stmt->bindValue(':new_state', $newState);
        $stmt->bindValue(':date_now', date("Y-d-m"));
        $stmt->execute();
    }

    /**
     * Deletes an entry from the ski order table.
     * @param int $orderNumber The id of the order that will be deleted.
     */
    public function deleteOrder(int $orderNumber){

        // Deletes an entry from the ski order table.
        $query = '
            DELETE FROM ski_order
            WHERE ski_order.order_number = :order_number
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_number',$orderNumber);
        $stmt->execute();
    }

    /**
     * Retrieves an order
     * @param $date
     * @return array an array of associative arrays of the form:
     *         array('order_number' => '...', 'total_price' => '...', 'state' => '...', ...), ...)), ...)
     */
    public function getOrders($date): array{

        // Selects every over from the ski order table.
        $query = '
                SELECT ski_order.order_number, 
                    ski_order.total_price, 
                    ski_order.reference_to_larger_order, 
                    ski_order.customer_id
                FROM `ski_order`
                %s
            ';

        // If the date is not present, just run the basic query.
        if (!$date){
            $query = sprintf($query,'');
        }
        // Modifies the query to select everything after a specific date.
        else {
            $query = sprintf($query, ' 
            INNER JOIN ski_order_state_history 
            ON ski_order.order_number = ski_order_state_history.ski_order_id
            WHERE
            ski_order_state_history.date >= "2021-07-04"');
            print ($query);
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':date', $date);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}