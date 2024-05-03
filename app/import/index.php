<?php
$data_directory = '/var/www/html/data';

$json_response = array(
    'result' => 'fail',
    'message' => 'No XML data provided',
    'data' => '',
);

// Accept the XML input
$dataPOST = trim(file_get_contents('php://input'));
if(!empty($dataPOST))
{
    $xml = simplexml_load_string($dataPOST);
    $xml_array = json_decode(json_encode((array)$xml), true);

    $students = array();

    // Check to make sure that all the parts we need are there - otherwise reject the entire submission
    $process_document = true;
    foreach($xml_array['mcq-test-result'] as $result)
    {
        if(
            empty($result['student-number']) || 
            empty($result['test-id']) || 
            empty($result['summary-marks']['@attributes']['obtained']) || 
            empty($result['summary-marks']['@attributes']['available'])
        )
        {
            $process_document = false;
            $json_response = array(
                'result' => 'fail',
                'message' => 'XML missing required data',
                'data' => $result,
            );
        }
    }
    
    if($process_document)
    {
        foreach($xml_array['mcq-test-result'] as $result)
        {
            $student_number = $result['student-number'];
            $test_id = $result['test-id'];
            $marks_obtained = $result['summary-marks']['@attributes']['obtained'];
            $marks_available = $result['summary-marks']['@attributes']['available'];
            $saved_results = array(
                "student_number" => $student_number,
                "test_id" => $test_id,
                "marks_obtained" => $marks_obtained,
                "marks_available" => $marks_available,
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
            'data' => '',
        );
    }
}

echo json_encode($json_response);
?>