<?php
$data_directory = '/var/www/html/data';

// Accept the XML input
$dataPOST = trim(file_get_contents('php://input'));
if(!empty($dataPOST))
{
    $xmlData = simplexml_load_string($dataPOST);
    
    foreach($xmlData->children() as $result)
    {
        $student_number = $result->{'student-number'};
        $test_id = $result->{'test-id'};
        
        if(!is_dir($data_directory.'/'.$test_id))
        {
            mkdir($data_directory.'/'.$test_id);
        }

        $f = fopen($data_directory.'/'.$test_id.'/'.$student_number.'.xml', 'wb');
        fputs($f, $dataPOST);
        fclose($f);
    }
}
?>