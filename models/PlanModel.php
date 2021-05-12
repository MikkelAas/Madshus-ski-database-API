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

  /**
   * Creates a new production plan by adding to the production plan and production plan skis table.
   * @param string $startDate
   * @param array $plan
   */
    public function createPlan(string $startDate, array $plan) {

      $date = DateTime::createFromFormat('Y-m-d', $startDate);
      if (!$date || $date->format('Y-m-d') != $startDate) {
        throw new InvalidArgumentException("Invalid date.");
      }

      try {
        $this->db->beginTransaction();

        // create new plan with specified start date
        $query1 = '
                INSERT INTO `production_plan` (`id`, `start_date`) 
                VALUES (NULL, :date_now);
            ';

        $stmt = $this->db->prepare($query1);
        $stmt->bindValue(':date_now', $startDate);
        $stmt->execute();

        // get id of the newly created production plan in a bad way
        $query2 = 'SELECT MAX(production_plan.id) FROM production_plan';

        $stmt = $this->db->prepare($query2);
        $stmt->execute();
        $id = $stmt->fetchColumn(0);

        $query3 = '
                INSERT INTO `production_plan_ski` (`id`, `production_plan_id`, `ski_type_id`, `daily_amount`) 
                VALUES (
                    NULL, 
                    :id, 
                    :ski_type_id, 
                    :quantity
                );
            ';

        // try to add ever ski type and quantity for the plan
        foreach ($plan as $value) {
          if (!array_key_exists("ski_type_id", $value) || !array_key_exists("daily_amount", $value)) {
            throw new Exception("Incorrect json");
          }

          $stmt = $this->db->prepare($query3);
          $stmt->bindValue(':id', $id);
          $stmt->bindValue(':ski_type_id', $value["ski_type_id"]);
          $stmt->bindValue(':quantity', $value["daily_amount"]);
          $stmt->execute();
        }

        $this->db->commit();
      } catch (Exception $e){
        $this->db->rollBack();
        throw new Exception("Error inserting skis and quantities");
      }
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
