<?php
require_once('config/config_test.php');
require('models/PlanModel.php');
class PlanModelTest extends \Codeception\Test\Unit{

    public function testCreatePlan(){

    }

    public function testGetPlan(){
        $planModel = new PlanModel();

        $res = $planModel->getPlan();

        self::assertCount(1, $res);
        self::assertStringContainsStringIgnoringCase("2021-04-08", $res[0]);
    }
}
