<?php
require_once('config/config_test.php');
require('models/SkiModel.php');

class SkiModelTest extends \Codeception\Test\Unit{
    public function testGetAllSkiTypes() {
        $skiModel = new SkiModel();

        $res = $skiModel->getAllSkiTypes();

        self::assertCount(3, $res);
    }

    public function testGetSkiTypeById() {
        $skiModel = new SkiModel();

        $res = $skiModel->getSkiTypeById(1);

        self::assertCount(1, $res);
        self::assertEquals("Fast", $res[0]['model']);
    }

    public function testGetSkiTypeByModel() {
        $skiModel = new SkiModel();

        $res = $skiModel->getSkiTypeByModel("Fast");

        self::assertCount(2, $res);
        self::assertNotEquals("Fastest", $res[0]['model']);
    }

    public function testGetSkiTypeByGrip() {
        $skiModel = new SkiModel();

        $res = $skiModel->getSkiTypeByGrip("Grippier");

        self::assertCount(2, $res);
        self::assertNotEquals("Grippy", $res[0]['grip_system']);
    }
}