<?php
$data_directory = '/var/www/html/data';

$json_response = array(
    'result' => 'fail',
    'message' => 'No XML data provided',
);

// Accept the XML input
$dataPOST = trim(file_get_contents('php://input'));
if(!empty($dataPOST))
{
    $xml = simplexml_load_string($dataPOST);
    $xml_array = json_decode(json_encode((array)$xml), true);

    $students = array();
    
    foreach($xml_array['mcq-test-result'] as $result)
    {
        $student_number = $result['student-number'];
        $test_id = $result['test-id'];
        $marks_obtained = $result['summary-marks']['@attributes']['obtained'];
        $saved_results = array(
            "student_number" => $student_number,
            "test_id" => $test_id,
            "marks_obtained" => $marks_obtained,
        );

        if(empty($students[$student_number]))
        {
            $students[$student_number] = 1;
        }
        else
        {
            $students[$student_number] = $students[$student_number] + 1;
        }
        
        if(!is_dir($data_directory.'/'.$test_id))
        {
            mkdir($data_directory.'/'.$test_id);
        }

        $f = fopen($data_directory.'/'.$test_id.'/'.$student_number.'-'.date("Ymdhis").'-'.$students[$student_number].'.txt', 'wb');
        fputs($f, json_encode($saved_results));
        fclose($f);
    }

    $json_response = array(
        'result' => 'success',
        'message' => 'XML data imported',
    );
}

echo json_encode($json_response);
?>