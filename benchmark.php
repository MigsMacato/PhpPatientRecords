<?php
// Define database variables
$host = 'us-cdbr-east-06.cleardb.net';
$username = 'bd23a9c4ce8b91';
$password = 'b5e77f65';
$database = 'heroku_309a5d5b1f3d4ae';
$active_group = 'default';
$query_builder = TRUE;

// Connect to database
$conn = mysqli_connect($host, $username, $password, $database);

// Binary Search
function binarySearch($arr, $find)
{
    $l = 0;
    $r = count($arr) - 1;
    while ($l <= $r)
    {
        $m = $l + (int)(($r - $l) / 2);
        $res = strcmp($find, $arr[$m]);
 
        // Check if x is present at mid
        if ($res == 0)
            return $m;
 
        // If x greater, ignore left half
        if ($res > 0)
            $l = $m + 1;
 
        // If x is smaller, ignore right half
        else
            $r = $m - 1;
    }
 
    return -1;
}

// Modified Binary Search
function modifiedBinarySearch($arr, $find) {
    $l = 0;
    $r = count($arr) - 1;
    while ($l <= $r) {
        $m = $l + (int)(($r - $l) / 2);
 
        // Check if find is present at low
        $res = strcmp($find, $arr[$l]);
        if ($res == 0)
            return $l;

        // Check if find is present at high
        $res = strcmp($find, $arr[$r]);
        if ($res == 0)
            return $r;
        
        // Check if find is present at mid
        $res = strcmp($find, $arr[$m]);
        if ($res == 0)
            return $m;

        // If find greater, ignore left half
        if ($res > 0) {
            $l = $m + 1;
            $r -= 1;
        }
        // If find is smaller, ignore right half
        else {
            $r = $m - 1;
            $l += 1;
        }
    }
    return -1;
}

// Binary Time
function binaryTime($arr, $find) {
    $start = microtime(true);
    binarySearch($arr, $find);
    return $time_elapsed_secs = (microtime(true) - $start) * 1000;
}

// Modified Binary Time
function modifiedBinaryTime($arr, $find) {
    $start = microtime(true);
    modifiedBinarySearch($arr, $find);
    return $time_elapsed_secs = (microtime(true) - $start) * 1000;

}

// Select data from database
$sql = "SELECT pr_fname, pr_lname FROM patient_record";
$queryResults = $conn->query($sql);
$index = 0;

// Store data from database here
$data = array();

if ($queryResults->num_rows > 0) {
    // Output data of each row
    while ($row = $queryResults->fetch_assoc()) {
        array_push($data, $row["pr_fname"] . "  " . $row["pr_lname"]);
        $index++;
    }
} else {
    print "0 results!";
}

// Sorted data, turn to upper case for ease of use
$data = array_map('strtoupper', $data);
sort($data);

$binaryAvg = array();
$modifiedBinaryAvg = array();
$differenceAvg = array();

for ($row = 0; $row < count($data); $row++) {
    // Data to look for
    $find = $data[0];

    // Temporarily store data here
    $binarySpeed = array();
    $modifiedBinarySpeed = array();

    // Loop to get average
    for ($repeat = 0; $repeat < 1000; $repeat++) {
        $binaryResult = binaryTime($data, $find);
        $modifiedBinaryResult = modifiedBinaryTime($data, $find);

        array_push($binarySpeed, $binaryResult);
        array_push($modifiedBinarySpeed, $modifiedBinaryResult);
    }

    $binarySpeedAvg = array_sum($binarySpeed) / count($binarySpeed);
    $modifiedBinarySpeedAvg = array_sum($modifiedBinarySpeed) / count($modifiedBinarySpeed);
    $difference = (1 - $modifiedBinarySpeedAvg / $binarySpeedAvg) * 100;

    array_push($binaryAvg, $binarySpeedAvg);
    array_push($modifiedBinaryAvg, $modifiedBinarySpeedAvg);
    array_push($differenceAvg, $difference);

    // print("Element found at index: " . $row . "<br>");
    // print("(Binary search/Locale) Time spent computing, Average (in miliseconds): " . $binarySpeedAvg . "<br>");
    // print("(Modified Binary search) Time spent computing, Average (in miliseconds): " . $modifiedBinarySpeedAvg . "<br>");
    // print("Execution time improvement: " . $difference . "%" . "<br>" . "<br>");

}

// Print data
print '<pre>'; print("Binary Avg."); print_r($binaryAvg); print '</pre>';
print '<pre>'; print("Modified Binary Avg."); print_r($modifiedBinaryAvg); print '</pre>';
print '<pre>'; print("Difference Avg."); print_r($differenceAvg); print '</pre>';

$conn->close();
?>