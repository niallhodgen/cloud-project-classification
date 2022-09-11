<?php

namespace App;

/**
 * Function to determine the overall classification of degree, using the following university
 * guidelines: For Master’s Degrees, a pass with distinction will be awarded only when the following
 * three conditions have been satisfied: 
 * 
 * - an overall programme mark of 70+ is achieved
 * - a mark of 70+ is achieved in the dissertation module
 * - a weighted average (truncated to an integer) of 65+ is achieved in the other modules
 */

function getClassification(array $input): String
{

    $programmingx2 = doubleWeighted($input);

    $average = getAverage($programmingx2);

    $disAverage = getDisAverage($programmingx2);

    $otherAverage = getOtherAverage($programmingx2);

    $classification = classify($average, $disAverage, $otherAverage);

    //Format nicely
    $classification = $classification . " (" . round($average) . "% avg.)";

    return $classification;
}

/**
 * Method that accounts for the double weighting of the
 * Programming module and adds it twice to the array
 */
function doubleWeighted(array $array): array
{

    //$programmingx2 = array();

    foreach ($array as $key => $value) {
        if ($key == 'Programming') {
            $array['Programming2'] = $value;
        }
    }

    return $array;
}

/**
 * Method to obtain an average of array values for all course modules as
 * per university guidelines:
 * 
 * - an overall programme mark of 70+ is achieved
 * 
 * It converts a string array to float array. It then uses an array filter
 * to check if values are numeric or empty and proceeds to calculate and average.
 * 
 */
function getAverage(array $array): float
{

    $array = array_filter($array);
    // Ensure no empty values for accuracy
    if (count($array)) {
        $average = array_sum($array) / count($array);
    }

    return $average;
}

/**
 * Method to determine disseration module averages (x3 'mini modules') as per
 * guidelines:
 * 
 * - A mark of 70+ is achieved in the dissertation module
 */

function getDisAverage(array $array): float
{

    // Isolate the three 'dissertation' modules (mini modules)
    $disArray = array();


    foreach ($array as $key => $value) {
        if (!strcmp('Data Analysis', $key)) {
            $disArray += [$key => $value];
        } elseif (!strcmp('User Experience', $key)) {
            $disArray += [$key => $value];
        } elseif (!strcmp('Cloud Computing', $key)) {
            $disArray += [$key => $value];
        }
    }

    $disArray = array_filter($disArray);
    // Ensure no empty values for accuracy
    if (count($disArray)) {
        $disAverage = array_sum($disArray) / count($disArray);
    }

    return $disAverage;
}

/**
 * Method to determine non-disseration module averages as per
 * guidelines:
 * 
 * - A weighted average (truncated to an integer) of 65+ is achieved in the other modules
 */

function getOtherAverage(array $array): float
{

    // Isolate the non-dissertation modules
    $otherArray = array();

    foreach ($array as $key => $value) {
        if (!strcmp('Programming', $key)) {
            $otherArray += [$key => $value];
        } elseif (!strcmp('Computing Foundations', $key)) {
            $otherArray += [$key => $value];
        } elseif (!strcmp('Web Development', $key)) {
            $otherArray += [$key => $value];
        } elseif (!strcmp('Databases', $key)) {
            $otherArray += [$key => $value];
        } elseif (!strcmp('Software Engineering', $key)) {
            $otherArray += [$key => $value];
        } elseif (!strcmp('Programming2', $key)) {
            $otherArray += [$key => $value];
        }
    }

    $otherArray = array_filter($otherArray);
    // Ensure no empty values for accuracy
    if (count($otherArray)) {
        $otherAverage = array_sum($otherArray) / count($otherArray);
    }

    return $otherAverage;
}

/**
 * Takes value of average and applies to all three guidelines,
 * determining classification before returning as String value.
 */
function classify(float $average, float $disAverage, float $otherAverage): String
{

    $classification = "";

    if ($average >= 70 && $disAverage >= 70 && $otherAverage >= 65) {
        $classification = 'Distinction';
    } elseif ($average >= 60) {
        $classification = 'Commendation';
    } elseif ($average >= 50) {
        $classification = 'Pass';
    } elseif ($average < 50) {
        $classification = 'Fail';
    } else {
        $classification = 'Error processing classification';
    }

    return $classification;
}
