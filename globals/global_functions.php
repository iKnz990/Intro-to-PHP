<?php

// *****2-1: PHP Basics***** \\
// Create a variable called yourName.  Assign it a value of your name.
$yourName = "Alexander Kelly";

// Variables for 
$number1 = 10;
$number2 = 20;
$total = $number1 + $number2;

// PHP array
$phpArray = ['PHP', 'HTML', 'Javascript'];

//***** 4-1 PHP Functions *****\\
$phoneNumber = "7192130553"; // My Phone Number
$assignedCurrency = "8675309"; // Jenny's Bank Account Balance
$stringInfo = manipulateString("Hello DMACC"); // Test String

// Function to format timestamp into mm/dd/yyyy
function formatDate_mm_dd_yyyy($timestamp) {
    return date("m/d/Y", $timestamp);
}

// Function to format timestamp into dd/mm/yyyy for international dates
function formatDate_dd_mm_yyyy($timestamp) {
    return date("d/m/Y", $timestamp);
}

// Function to manipulate string $stringInfo
function manipulateString($inputString) {
    $length = strlen($inputString);
    $trimmedString = trim($inputString);
    $lowercaseString = strtolower($trimmedString);
    $containsDMACC = (stripos($lowercaseString, 'dmacc') !== false) ? 'Yes' : 'No';

    return [
        'length' => $length,
        'trimmed' => $trimmedString,
        'lowercase' => $lowercaseString,
        'containsDMACC' => $containsDMACC
    ];
}

// Function to format number as phone number
function formatPhoneNumber($number) {
    return preg_replace("/^(\d{3})(\d{3})(\d{4})$/", "$1-$2-$3", $number);
}

// Function to format number as US currency
function formatCurrency($number) {
    return '$' . number_format($number, 2);
}
// *****End of the Line / Fill***** \\

?>