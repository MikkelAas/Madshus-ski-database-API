<?php

/**
 * Class PlanModel
 * This class gets, creates, and updates the production plan and production plan skis table.
 */
class PlanModel{

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

    public function createPlanWithMultipleSkis(){

    }

    /**
     * Creates a new production plan by adding to the production plan and production plan skis table.
     * @param int $skiTypeId
     * @param int $quantity
     */
    public function createPlan(int $skiTypeId, int $quantity){
        try {
            $this->db->beginTransaction();

            // Inserts a new entry to the production plan table ith the current date.
            $query1 = '
                INSERT INTO `production_plan` (`id`, `start_date`) 
                VALUES (NULL, :date_now);
            ';

            $stmt = $this->db->prepare($query1);
            $stmt->bindValue(':date_now', date("Y-m-d"));
            $stmt->execute();

            // Selects the max id to get the newest entry in the table.
            // TODO: Find a better way to do this...
            $query2 = 'SELECT MAX(production_plan.id) FROM production_plan';

            $stmt = $this->db->prepare($query2);
            $stmt->execute();
            $id = $stmt->fetchColumn(0);

            // Inserts into the production plan skis table for each entry in the array.
            $query3 = '
                INSERT INTO `production_plan_ski` (`id`, `production_plan_id`, `ski_type_id`, `daily_amount`) 
                VALUES (
                    NULL, 
                    $id, 
                    :ski_type_id, 
                    :quantity
                );
            ';

            $stmt = $this->db->prepare($query3);
            $stmt->bindValue(':ski_type_id', $skiTypeId);
            $stmt->bindValue('quantity', $quantity);
            $stmt->execute();

            $this->db->commit();

        } catch (Exception $e){
            echo "Failed: " . $e->getMessage();
            $this->db->rollBack();
        }
    }

    /**
     * Adds an entry to an already existing production plan.
     * @param int $id
     * @param int $skiTypeId
     * @param int $quantity
     */
    public function addToPlan(int $id, int $skiTypeId, int $quantity){
        $query = '
            INSERT INTO `production_plan_ski` (`id`, `production_plan_id`, `ski_type_id`, `daily_amount`) 
            VALUES (
                NULL, 
                :production_plan_id, 
                :ski_type_id, 
                :quantity
            );
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':production_plan_id', $id);
        $stmt->bindValue(':ski_type_id', $skiTypeId);
        $stmt->bindValue(':quantity', $quantity);
        $stmt->execute();
    }

    /**
     * Returns the production plan for the current week.
     * @return array Returns an array with the specified information.
     */
    public function getPlan():array{

        // Retrieves the maximum start date from the existing table.
        $query1 = 'SELECT MAX(start_date) FROM production_plan';

        $stmt = $this->db->prepare($query1);
        $stmt->execute();

        $mostRecentDate = $stmt->fetchColumn(0);

        // Retrieves the start date, the ski type, and the amount for the current date.
        $query2 = '
            SELECT production_plan.start_date, production_plan_ski.ski_type_id, production_plan_ski.daily_amount
            FROM production_plan
            INNER JOIN production_plan_ski ON production_plan.id = production_plan_ski.production_plan_id
            WHERE production_plan.start_date = :date_now
        ';

        $stmt = $this->db->prepare($query2);
        $stmt->bindValue(':date_now', $mostRecentDate);
        $stmt->execute();

        $row = $stmt->fetch();

        if (!$row) {
            return [];
        }

        // The response array.
        $res = [
            'start_date'=>$row['start_date'],
            'planned_skis'=>[
                [
                    'ski_type_id'=>$row['ski_type_id'],
                    'daily_amount'=>$row['daily_amount']
                ]
            ]
        ];

        // For each row, push ski type id and daily amount into the response array.
        while ($row = $stmt->fetch()){
            array_push($res['planned_skis'],
                [
                    'ski_type_id'=>$row['ski_type_id'],
                    'daily_amount'=>$row['daily_amount']
                ]
            );
        }
        return $res;
    }
}
