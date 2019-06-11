<?php

include 'db.php';

$stmt = $pdo->prepare("
    SELECT
        *
    FROM
        `cafes`
    WHERE
        `price_min` >= :min AND `price_max` <= :max
   
");

$stmt->execute([
    'min' => 500,
    'max' => 2000
]); 
$result = $stmt->fetchAll();

print_r($result);