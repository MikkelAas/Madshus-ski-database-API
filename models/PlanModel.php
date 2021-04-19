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

        $res = [
            'start_date'=>$row['start_date'],
            'planned_skis'=>[
                [
                    'ski_type_id'=>$row['ski_type_id'],
                    'daily_amount'=>$row['daily_amount']
                ]
            ]
        ];

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
