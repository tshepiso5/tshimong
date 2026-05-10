<?php
$python_path = 'C:\Users\Smart Axis\AppData\Local\Microsoft\WindowsApps\python.exe';
$test_device = "dirty-farm@testcsp.net";

$cmd = "\"$python_path\" nac_bridge.py ". escapeshellarg($test_device) . "2>&1";
$output = shell_exec($cmd);

if($output === null || $output === false){
    die("Error: Could not execute Python Script. Output empty");
}


$data = json_decode($output, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Python Error or Invalid JSON: " . htmlspecialchars($output));
}

if($data['status'] === 'success'){
    echo "<h3>Simulated Device Found!</h3>";
    echo "Latitude: " .$data['location']['latitude'];
    echo "Longitude: ".$data['location']['longitude'];

}else{
    echo "Error: " .$data['message'];
}


?>