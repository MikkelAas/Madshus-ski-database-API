<?php


/**
 * Class OrderModel.
 * This class generates JSON documents.
 */
class OrderModel{

    /**
     * @var mixed
     */
    protected $db;

    /**
     * OrderModel constructor.
     */
    public function __construct(){
        $this->db = require('db/pdo_connection.php');
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
                SELECT ski_order_view.order_number, 
                    ski_order_view.total_price, 
                    ski_order_view.reference_to_larger_order, 
                    ski_order_view.customer_id,
                    ski_order_view.ski_type_id,
                    ski_order_view.quantity
                FROM ski_order_view
                WHERE ski_order_view.customer_id = :customer_id
            ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':customer_id', $customerId);
        $stmt->execute();

        return $this->reformatArray($stmt);
    }

    /**
     * Creates an order.
     * @param int $totalPrice Sets the total price.
     * @param mixed $refToLargerOrder Is either an int or null.
     * @param int $customerId The id of the customer that the order belongs to.
     * @param string $stateMessage Sets the state of the order.
     * @param mixed $employeeId Is either an int or null.
     * @param int $skipTypeId The id of the type os ski that is ordered.
     * @param int $quantity The quantity of the type of ski.
     */
    public function createOrder(
        int $totalPrice,
        $refToLargerOrder,
        int $customerId,
        string $stateMessage ,
        $employeeId,
        int $skipTypeId,
        int $quantity){
        try {
            $this->db->beginTransaction();

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
            $stmt->bindValue(':date_now', date("Y-m-d"));
            $stmt->execute();

            $query4 = '
            INSERT INTO `ski_order_ski_type` (`id`, `order_id`, `ski_type_id`, `quantity`) 
            VALUES (
                NULL,
                :order_number,
                :ski_type_id,
                :number_of_skis
            );
        ';

            $stmt = $this->db->prepare($query4);
            $stmt->bindValue(':order_number', $orderId);
            $stmt->bindValue(':ski_type_id', $skipTypeId);
            $stmt->bindValue(':number_of_skis', $quantity);
            $stmt->execute();

            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            echo "Failed: " . $e->getMessage();
        }
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

            $stmt = $this->db->prepare($query2);
            $stmt->bindValue(':original_ski_order_id', $originalSkiOrderId);
            $stmt->bindValue(':employee_id', $employeeId);
            $stmt->bindValue(':new_state', $newState);
            $stmt->bindValue(':date_now', date("Y-m-d"));
            $stmt->execute();

            if ($stmt->rowCount() == 0){
                throw new \http\Exception\InvalidArgumentException($orderId . " does not exist.");
            }
    }

    /**
     * Deletes an order.
     * @param int $orderId The id of the order that will be deleted.
     */
    public function deleteOrder(int $orderId){
        try {
            $this->db->beginTransaction();
            // Deletes all history of an order from the ski order history table.
            $query1 = '
            DELETE FROM ski_order_state_history
            WHERE ski_order_id = :order_number
        ';

            $stmt = $this->db->prepare($query1);
            $stmt->bindValue(':order_number', $orderId);
            $stmt->execute();

            // Deletes an entry from the ski order ski type table.
            $query2 = '
            DELETE FROM ski_order_ski_type
            WHERE ski_order_ski_type.order_id = :order_number
        ';

            $stmt = $this->db->prepare($query2);
            $stmt->bindValue(':order_number', $orderId);
            $stmt->execute();

            // Deletes an entry from the ski order table.
            $query3 = '
            DELETE FROM ski_order
            WHERE ski_order.order_number = :order_number
        ';

            $stmt = $this->db->prepare($query3);
            $stmt->bindValue(':order_number',$orderId);
            $stmt->execute();

            $this->db->commit();
        } catch(Exception $e){
            $this->db->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }

    /**
     * Gets the details of one specific order
     * @param int $orderId The id of the order.
     * @return array Returns an array with order information
     */
    public function getOrder(int $orderId): array{
        $query = '
            SELECT 
                   ski_order.order_number, 
                   ski_order.total_price, 
                   ski_order.reference_to_larger_order, 
                   ski_order.customer_id,
                   ski_order_ski_type.ski_type_id,
                   ski_order_ski_type.quantity
            FROM ski_order
            INNER JOIN ski_order_ski_type ON ski_order.order_number = ski_order_ski_type.order_id
            WHERE ski_order.order_number = :order_number
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':order_number', $orderId);
        $stmt->execute();

        return $this->reformatArray($stmt);
    }

    /**
     * Retrieves all orders.
     * @param $date
     * @return array an array of associative arrays of the form:
     *         array('order_number' => '...', 'total_price' => '...', 'state' => '...', ...), ...)), ...)
     */
    public function getOrders($date): array{

        // Selects every over from the ski order table, and the ski type id and the quantity.
        $query = '
                SELECT ski_order_view.order_number, 
                    ski_order_view.total_price, 
                    ski_order_view.reference_to_larger_order, 
                    ski_order_view.customer_id,
                    ski_order_view.ski_type_id,
                    ski_order_view.quantity
                FROM ski_order_view
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
            ON ski_order_view.order_number = ski_order_state_history.ski_order_id
            WHERE
            ski_order_state_history.date >= :date');
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':date', $date);
        $stmt->execute();

        return $this->reformatArray($stmt);
    }

    /**
     * Returns an array of orders based on their state.
     * @param string $searchState The state you want.
     * @return array An array of orders.
     */
    public function getOrdersBasedOnState(string $searchState): array{

        // Selects all orders that matches the state.
        $query = '
            SELECT 
                   ski_order_view.order_number, 
                   ski_order_view.total_price, 
                   ski_order_view.reference_to_larger_order, 
                   ski_order_view.customer_id,
                   ski_order_view.ski_type_id,
                   ski_order_view.quantity   
            FROM ski_order_view
            INNER JOIN ski_order_state_history ON ski_order_view.order_id = ski_order_state_history.ski_order_id
            WHERE ski_order_state_history.state = :search_state
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':search_state', $searchState);
        $stmt->execute();

        return $this->reformatArray($stmt);
    }

    /**
     * Retrieves the latest state of an order.
     * @param int $orderId Takes the order id.
     * @return string Returns the newest statement of an order.
     */
    public function getOrderStatement(int $orderId): string{

        // Selects the newest input by taking the maximum of the auto increment id (not ideal)
        // TODO: Find a better solution to the ID-problem
        $query1 = '
            SELECT MAX(id) 
            FROM ski_order_state_history
        ';
        $stmt = $this->db->prepare($query1);
        $stmt->execute();

        $mostRecentId = $stmt->fetchColumn();

        // Selects the state where the order id and most recent id matches.
        $query2 = '
            SELECT ski_order_state_history.state 
            FROM ski_order_state_history 
            WHERE ski_order_id = :order_number AND id = :most_recent_id
        ';

        $stmt = $this->db->prepare($query2);
        $stmt->bindValue(':order_number', $orderId);
        $stmt->bindValue(':most_recent_id', $mostRecentId);
        $stmt->execute();

        return $stmt->fetchColumn(0);
    }

    /**
     * Adds to an already existing order.
     * @param int $orderId The order id that you want to add to.
     * @param int $skiTypeId The id of the ski type that you want to add.
     * @param int $quantity The number of skis you want.
     */
    public function addToOrder(int $orderId, int $skiTypeId, int $quantity){
        try {
            $this->db->beginTransaction();

            // Inserts into the ski order ski type table.
            $query = '
            INSERT INTO `ski_order_ski_type` (`order_id`, `ski_type_id`, `quantity`) 
            VALUES (:order_number, :ski_type_id, :number_of_skis);
        ';

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':order_number', $orderId);
            $stmt->bindValue(':ski_type_id', $skiTypeId);
            $stmt->bindValue(':number_of_skis', $quantity);

            $stmt->execute();

            $this->db->commit();
        } catch (Exception $e){
            $this->db->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }

    /**
     * Calculates the number of unfilled skis.
     * @param int $id The id of the ski type.
     * @return int Returns the number of unfilled skis.
     */
    public function getUnfilledSkis(int $id): int{
        $query1 = 'SELECT COUNT(*) FROM `produced_skis` WHERE ski_type = :ski_type_id';

        $stmt = $this->db->prepare($query1);
        $stmt->bindValue(':ski_type_id', $id);
        $stmt->execute();
        $producedSkis = $stmt->fetchColumn(0);

        $query2 = '
            SELECT SUM(ski_order_ski_type.quantity) 
            FROM `ski_order_ski_type` 
            WHERE ski_order_ski_type.ski_type_id = :ski_type_id
        ';

        $stmt = $this->db->prepare($query2);
        $stmt->bindValue(':ski_type_id', $id);
        $stmt->execute();
        $orderedSkis = $stmt->fetchColumn(0);

        return $orderedSkis - $producedSkis;
    }

    /**
     * Changes the quantity of an existing ski type in an order.
     * @param int $id The id of the ski order ski type.
     * @param int $quantity The new quantity.
     */
    public function changeQuantity(int $id, int $quantity){

        print ("Before");
        $query = '
            UPDATE `ski_order_ski_type` 
            SET `quantity` = :new_quantity 
            WHERE `ski_order_ski_type`.`id` = :ski_order_ski_type_id
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':ski_order_ski_type_id', $id);
        $stmt->bindValue(':new_quantity', $quantity);
        $stmt->execute();

        print ("After");
    }

    /**
     * Reformats an array.
     * @param PDOStatement $stmt Statement to get data from.
     * @return array Returns the reformatted array.
     */
    private function reformatArray(PDOStatement $stmt): array
    {
        $res = array();

        while ($row = $stmt->fetch()) {
            $orderNumber = $row['order_number'];
            if (!array_key_exists($orderNumber, $res)) {
                $res[$orderNumber] = array(
                    'order_number' => $orderNumber,
                    'total_price' => $row['total_price'],
                    'reference_to_larger_order' => $row['reference_to_larger_order'],
                    'customer_id' => $row['customer_id'],
                    'skis' => array()
                );
            }
            array_push($res[$orderNumber]["skis"], array('ski_type_id' => $row['ski_type_id'], 'quantity' => $row['quantity']));
        }
        return array_values($res);
    }
}