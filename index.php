<?php
use Pine\App\BaseModel;
use Pine\App\Database;
use Pine\App\Route;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/src/config/bootstrap.php";
require_once __DIR__ . "/src/config/db.php";
require_once __DIR__ . "/src/routes.php";

$config = include(__DIR__ . "/src/config/db.php");
$db = Database::getInstance($config)->getConnection();

require_once __DIR__ . "/src/config/models.php";

// Initialize all models
$models = BaseModel::getModels();
foreach ($models as $model) {
    $sql = $model::createTable();
    $db->exec($sql);
}


// Should be at the end of the page
Route::load();

