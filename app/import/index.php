<?php
$data_directory = '/var/www/html/data';

$json_response = array(
    'result' => 'fail',
    'message' => 'No XML data provided',
    'data' => '',
);

$action = $_GET['action'];

// Accept the XML input
$dataPOST = trim(file_get_contents('php://input'));
if(!empty($dataPOST))
{
    if($action == 'test')
    {
        $xml_array = array(
            "mcq-test-result" => array(
                array(
                    "@attributes" => array(
                        "scanned-on" => "2017-12-04T12:12:10+11:00",
                    ),
                    "first-name" => "Jane",
                    "last-name" => "Austen",
                    "student-number" => "1111",
                    "test-id" => "1234",
                    "summary-marks" => array(
                        "@attributes" => array(
                            "available" => "20",
                            "obtained" => "10",
                        ),
                    ),
                ),
                array(
                    "@attributes" => array(
                        "scanned-on" => "2017-12-04T12:12:10+11:00",
                    ),
                    "first-name" => "John",
                    "last-name" => "Austen",
                    "student-number" => "2222",
                    "test-id" => "1234",
                    "summary-marks" => array(
                        "@attributes" => array(
                            "available" => "20",
                            "obtained" => "12",
                        ),
                    ),
                ),
                array(
                    "@attributes" => array(
                        "scanned-on" => "2017-12-04T12:12:10+11:00",
                    ),
                    "first-name" => "Jim",
                    "last-name" => "Austen",
                    "student-number" => "3333",
                    "test-id" => "1234",
                    "summary-marks" => array(
                        "@attributes" => array(
                            "available" => "20",
                            "obtained" => "14",
                        ),
                    ),
                ),
                array(
                    "@attributes" => array(
                        "scanned-on" => "2017-12-04T12:12:10+11:00",
                    ),
                    "first-name" => "Jackie",
                    "last-name" => "Austen",
                    "student-number" => "4444",
                    "test-id" => "1234",
                    "summary-marks" => array(
                        "@attributes" => array(
                            "available" => "20",
                            "obtained" => "16",
                        ),
                    ),
                ),
                array(
                    "@attributes" => array(
                        "scanned-on" => "2017-12-04T12:12:10+11:00",
                    ),
                    "first-name" => "John",
                    "last-name" => "Austen",
                    "student-number" => "2222",
                    "test-id" => "1234",
                    "summary-marks" => array(
                        "@attributes" => array(
                            "available" => "20",
                            "obtained" => "18",
                        ),
                    ),
                ),
            ),
        );
    }
    else
    {
        $xml = simplexml_load_string($dataPOST);
        $xml_array = json_decode(json_encode((array)$xml), true);
    }

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
            // Just keep the bits we actually need
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

            // Save each submission to a text file - obviously this would be better done in a database
            $f = fopen($data_directory.'/'.$test_id.'/'.$student_number.'-'.date("Ymdhis").'-'.$students[$student_number].'.txt', 'wb');
            fputs($f, json_encode($saved_results));
            fclose($f);
        }

        $json_response = array(
            'result' => 'success',
            'message' => 'XML data imported',
            'data' => '',
        );
        
        if($action == 'test')
        {
            $test_results = scandir($data_directory.'/1234');
            $student_counts = array();
            foreach($test_results as $test_result)
            {
                if($test_result != '.' && $test_result != '..')
                {
                    $student_id = substr($test_result,0,4);
                    if(empty($student_counts[$student_id]))
                    {
                        $student_counts[$student_id] = 1;
                    }
                    else
                    {
                        $student_counts[$student_id] = $student_counts[$student_id] + 1;
                    }
                }
            }

            $expected_results = array(
                '1111' => 1,
                '2222' => 2,
                '3333' => 1,
                '4444' => 1,
            );
            
            if($student_counts == $expected_results)
            {
                $json_response = array(
                    'result' => 'success',
                    'message' => 'XML data import test passed',
                    'data' => '',
                );
            }
            else
            {
                $json_response = array(
                    'result' => 'fail',
                    'message' => 'XML data import test failed',
                    'data' => $student_counts,
                );
            }
        }
    }
}

echo json_encode($json_response);
?>