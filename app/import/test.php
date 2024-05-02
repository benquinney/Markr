<?php

$data_directory = '/var/www/html/data';

$test_id = 1234;
$test_data = array(
    array(
        "student_number" => 1111,
        "marks_obtained" => 10,
    ),
    array(
        "student_number" => 2222,
        "marks_obtained" => 12,
    ),
    array(
        "student_number" => 3333,
        "marks_obtained" => 14,
    ),
    array(
        "student_number" => 4444,
        "marks_obtained" => 16,
    ),
    array(
        "student_number" => 2222,
        "marks_obtained" => 18,
    ),
);

foreach($test_data as $sd)
{
    $saved_results = array(
        "student_number" => $sd['student_number'],
        "test_id" => $test_id,
        "marks_obtained" => $sd['marks_obtained'],
    );

    if(empty($students[$sd['student_number']]))
    {
        $students[$sd['student_number']] = 1;
    }
    else
    {
        $students[$sd['student_number']] = $students[$sd['student_number']] + 1;
    }
    
    if(!is_dir($data_directory.'/'.$test_id))
    {
        mkdir($data_directory.'/'.$test_id);
    }
    
    $f = fopen($data_directory.'/'.$test_id.'/'.$sd['student_number'].'-'.date("Ymdhis").'-'.$students[$sd['student_number']].'.txt', 'wb');
    fputs($f, json_encode($saved_results));
    fclose($f);
}

$json_response = array(
    'result' => 'success',
    'message' => 'Test data imported',
);

echo json_encode($json_response);
?>