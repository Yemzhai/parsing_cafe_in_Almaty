    <?php 

    include 'functions.php';
    include 'db.php';
    // delete the table
    $stmt = $pdo->prepare("TRUNCATE TABLE `cafes`");
    $stmt->execute();

    $maxPage = getMaxPage(1);
    $rests = [];
    for($i = 1; $i <= $maxPage; $i++){
        $rests = array_merge($rests, getCofesFromPage($i));
    }

    print_r($rests);
    
    $stmt = $pdo->prepare("
        INSERT INTO
            `cafes` (
                `name`,
                `link`,
                `cuisine`,
                `price_min`,
                `price_max`,
                `worktime`,
                `address`
            ) VALUES (
                :name,
                :link,
                :cuisine,
                :price_min,
                :price_max,
                :worktime,
                :address
            )
    ");


    foreach($rests as $rest){
        $stmt->execute([
            ':name' => $rest['name'],
            ':link' => $rest['link'],
            ':cuisine' => $rest['cuisine'],
            ':price_min' => $rest['price']['min'],
            ':price_max' => $rest['price']['max'],
            ':worktime' => $rest['worktime'],
            ':address' => $rest['address']
        ]);
    }
          
        
    