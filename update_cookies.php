<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newCookie = $_POST["newCookie"];
    $arraySelection = $_POST["arraySelection"];

    // Read the existing JSON data from the file
    $jsonFile = 'cookieschegg.json';
    $jsonData = json_decode(file_get_contents($jsonFile), true);

    // Check if the selected array index is within the valid range (0-30)
    if (is_numeric($arraySelection) && $arraySelection >= 0 && $arraySelection <= 30) {
        // Add the new cookie to the selected array
        $jsonData['cookies'][$arraySelection] = $newCookie;

        // Save the updated JSON data back to the file
        file_put_contents($jsonFile, json_encode($jsonData, JSON_PRETTY_PRINT));

        echo "Cookie added successfully to array $arraySelection!";
    } else {
        echo "Invalid array selection. Please select an index between 0 and 30.";
    }
}
?>
