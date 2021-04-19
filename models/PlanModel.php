<?php
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

    public function createPlan(){

    }

    /**
     * Returns the production plan for the current week.
     * @return array Returns an array with the specified information.
     */
    public function getPlan():array{
        $currentDate = date('Y-m-d');
        // Retrieves the start date, the ski type, and the amount for the current date.
        $query = '
            SELECT production_plan.start_date, production_plan_ski.ski_type_id, production_plan_ski.daily_amount
            FROM production_plan
            INNER JOIN production_plan_ski ON production_plan.id = production_plan_ski.production_plan_id
            WHERE production_plan.date = :date_now
        ';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':date_now', $currentDate);
        $stmt->execute();

        $res = array();

        while ($row = $stmt->fetch()){
            $currentDate = $row['date'];
            if (!array_key_exists($currentDate, $res)){
                $res[$currentDate] = array(array(
                    'date'=>$currentDate,
                    'ski_type_id'=>$row['ski_type_id'],
                    'daily_amount'=>$row['daily_amount'],
                    array()
                ));
            }
            array_push($res[$currentDate][1], array('ski_type_id'=>$row['ski_type_id'], 'daily_amount'=>$row['daily_amount']));
        }

        return array_values($res);
    }
}
