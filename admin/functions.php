<?php




function CleanString($con, string $data){
    $data = trim($data);

    
    $data = strip_tags($data);

    $data = mysqli_real_escape_string($con, $data);

    return $data;


}

function GetCoordinates(string $address){
    $url = "https://nominatim.openstreetmap.org/search?format=json&q=" . urlencode($address);

    $opts = [
        "http" => ["header" => "User-Agent: TshimongFarmApp/1.0\r\n"]
    ];

    $context = stream_context_create($opts);
    $response = file_get_contents($url, false, $context);
    $data = json_decode($response, true);

    if (!empty($data)) {
        return [
            'lat' => $data[0]['lat'],
            'lng' => $data[0]['lon']
        ];
    }
    return null; 

}

function CheckEmptyStrings(string $data, string $fieldName,$erroDest){
    $trimmedData = trim($data);

    if(empty($trimmedData)){
        $_SESSION['message'] = $fieldName.' Cannot be Empty';
        header("Location: $erroDest");
        exit(0);
    }

    return true;
}

function ContainsNumbers(string $data){
    

    return boolval(preg_match('/\d/', $data));

    
}

function CreateSlug(string $data){

    
    $slug = iconv('UTF-8', 'ASCII//TRANSLIT', $data);

    $slug = preg_replace('/[^a-zA-Z0-9\s-]/', '', $slug);

    $slug = strtolower($slug);
    $slug = preg_replace('/[\s_-]+/', '-', $slug);
    $slug = trim($slug, '-');

    return $slug;

}

function AddToBalance(float $transactionAmt, float $balance){
    $effect = $transactionAmt + $balance;

    return $effect;
}

function CheckforOneDuplicate($con, string $dbVar, string $db, string $data, string $redirectUrl){
    DbAccessWhiteList($db, $dbVar);

    $checkDuplicate = "SELECT $dbVar FROM $db WHERE $dbVar = ?";
    $dupRes = mysqli_execute_query($con, $checkDuplicate, [$data]);

    if(mysqli_num_rows($dupRes) > 0){
        $_SESSION['message'] = $data .' Already Exists';
        header("Location: $redirectUrl");
        exit(0);
    }


}

function CheckforTwoDuplicates($con, string $colm1, string $colm2, string $db, string $data1, string $data2, string $redirectPath){


    $checkDuplicates = "SELECT $colm1, $colm2 FROM $db WHERE $colm1 = ? AND $colm2 = ?";
    $duplicatesRes = mysqli_execute_query($con, $checkDuplicates, [$data1, $data2]);

    if(mysqli_num_rows($duplicatesRes) > 0){
        $_SESSION['message'] = $data1 .' and '.$data2 .' Already Exist';
        header("Location: $redirectPath");
        exit(0);
    }
}

function DbAccessWhiteList(string $table, string $column){
    $allowedTables = ['users', 'permissions', 'avail_resources', 'authorization', 'land_data'];
    $allowedColumns = ['resource_name', 'role_as', 'resource', 'authorized_to', 'user_id', 'id', 'permission', 'resources', 'ph_balance', 'soil_colour'];

    if(!in_array($table, $allowedTables) || !in_array($column, $allowedColumns)){
        die('Invalid db Reference');
    }
}

function checkNumericOnly(string $data, string $fieldName, string $redirectPath) {
    
    if (!is_numeric($data)) {
        $_SESSION['message'] = $fieldName.' can only be numbers';
        header("Location: $redirectPath");
        exit(0);
    }
    return (float)$data;
}
?>