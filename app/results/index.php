<?php
$data_directory = '/var/www/html/data';

$json_response = array(
    'result' => 'fail',
    'message' => 'Action not found',
    'data' => '',
);

$test_results_array = array();

$test_id = $_GET['test_id'];
$action = $_GET['action'];

if($action == 'aggregate')
{
    $json_response = array(
        'result' => 'fail',
        'message' => 'No results found',
        'data' => '',
    );

    if(!is_dir($data_directory.'/'.$test_id))
    {
        echo json_encode($json_response);
        die();
    }
    
    $test_results = scandir($data_directory.'/'.$test_id);
    foreach($test_results as $test_result)
    {
        if($test_result != '.' && $test_result != '..')
        {
            $file_content = file_get_contents($data_directory.'/'.$test_id.'/'.$test_result);
            $student_result = json_decode($file_content, true);
    
            if(empty($test_results_array[$student_result['student_number']]))
            {
                $test_results_array[$student_result['student_number']] = array(
                    "marks_obtained" => $student_result['marks_obtained'],
                    "marks_available" => $student_result['marks_available'],
                );
            }
            else
            {
                if(
                    $student_result['marks_obtained'] > $test_results_array[$student_result['student_number']]['marks_obtained'] || 
                    $student_result['marks_available'] > $test_results_array[$student_result['student_number']]['marks_available']
                )
                {
                    $test_results_array[$student_result['student_number']] = array(
                        "marks_obtained" => $student_result['marks_obtained'],
                        "marks_available" => $student_result['marks_available'],
                    );
                }
            }
        }
    }
    sort($test_results_array);
    
    if(count($test_results_array) > 0)
    {
        $result_data = array(
            'mean' => 0,
            'min' => null,
            'max' => null,
            'p25' => 0,
            'p50' => 0,
            'p75' => 0,
            'count' => count($test_results_array),
        );

        $score_total = 0;
        foreach($test_results_array as $tra)
        {
            if($tra < $result_data['min'] || empty($result_data['min']))
            {
                $result_data['min'] = round(($tra['marks_obtained'] / $tra['marks_available']),2);
            }

            if($tra > $result_data['max'] || empty($result_data['max']))
            {
                $result_data['max'] = round(($tra['marks_obtained'] / $tra['marks_available']),2);
            }

            $score_total += round(($tra['marks_obtained'] / $tra['marks_available']),2);
        }

        $result_data['mean'] = round($score_total / count($test_results_array),2);

        $p25_location = round(count($test_results_array) * 0.25, 0) - 1;
        $result_data['p25'] = round(($test_results_array[$p25_location]['marks_obtained'] / $test_results_array[$p25_location]['marks_available']),2);

        $p50_location = round(count($test_results_array) * 0.5, 0) - 1;
        $result_data['p50'] = round(($test_results_array[$p50_location]['marks_obtained'] / $test_results_array[$p50_location]['marks_available']),2);

        $p75_location = round(count($test_results_array) * 0.75, 0) - 1;
        $result_data['p75'] = round(($test_results_array[$p75_location]['marks_obtained'] / $test_results_array[$p75_location]['marks_available']),2);

        $json_response = array(
            'result' => 'success',
            'message' => 'Results found',
            'data' => $result_data,
        );
    }
}
elseif($action == 'test')
{
    $json_response = array(
        'result' => 'fail',
        'message' => 'No results found',
        'data' => '',
    );

    if(!is_dir($data_directory.'/'.$test_id))
    {
        echo json_encode($json_response);
        die();
    }
    
    $test_results = scandir($data_directory.'/'.$test_id);
    foreach($test_results as $test_result)
    {
        if($test_result != '.' && $test_result != '..')
        {
            $file_content = file_get_contents($data_directory.'/'.$test_id.'/'.$test_result);
            $student_result = json_decode($file_content, true);

            if(empty($test_results_array[$student_result['student_number']]))
            {
                $test_results_array[$student_result['student_number']] = array(
                    "marks_obtained" => $student_result['marks_obtained'],
                    "marks_available" => $student_result['marks_available'],
                );
            }
            else
            {
                if(
                    $student_result['marks_obtained'] > $test_results_array[$student_result['student_number']]['marks_obtained'] || 
                    $student_result['marks_available'] > $test_results_array[$student_result['student_number']]['marks_available']
                )
                {
                    $test_results_array[$student_result['student_number']] = array(
                        "marks_obtained" => $student_result['marks_obtained'],
                        "marks_available" => $student_result['marks_available'],
                    );
                }
            }
        }
    }
    sort($test_results_array);
    
    if(count($test_results_array) > 0)
    {
        $result_data = array(
            'mean' => 0,
            'min' => null,
            'max' => null,
            'p25' => 0,
            'p50' => 0,
            'p75' => 0,
            'count' => count($test_results_array),
        );

        $score_total = 0;
        foreach($test_results_array as $tra)
        {
            if($tra < $result_data['min'] || empty($result_data['min']))
            {
                $result_data['min'] = round(($tra['marks_obtained'] / $tra['marks_available']),2);
            }

            if($tra > $result_data['max'] || empty($result_data['max']))
            {
                $result_data['max'] = round(($tra['marks_obtained'] / $tra['marks_available']),2);
            }

            $score_total += round(($tra['marks_obtained'] / $tra['marks_available']),2);
        }

        $result_data['mean'] = round($score_total / count($test_results_array),2);

        $p25_location = round(count($test_results_array) * 0.25, 0) - 1;
        $result_data['p25'] = round(($test_results_array[$p25_location]['marks_obtained'] / $test_results_array[$p25_location]['marks_available']),2);

        $p50_location = round(count($test_results_array) * 0.5, 0) - 1;
        $result_data['p50'] = round(($test_results_array[$p50_location]['marks_obtained'] / $test_results_array[$p50_location]['marks_available']),2);

        $p75_location = round(count($test_results_array) * 0.75, 0) - 1;
        $result_data['p75'] = round(($test_results_array[$p75_location]['marks_obtained'] / $test_results_array[$p75_location]['marks_available']),2);

        if(
            $result_data['mean'] == 0.73 && 
            $result_data['min'] == 0.5 && 
            $result_data['max'] == 0.9 && 
            $result_data['p25'] == 0.5 && 
            $result_data['p50'] == 0.7 && 
            $result_data['p75'] == 0.8 && 
            $result_data['count'] == 4
        )
        {
            $json_response = array(
                'result' => 'success',
                'message' => 'Test Results Passed',
                'data' => $result_data,
            );
        }
        else
        {
            $json_response = array(
                'result' => 'fail',
                'message' => 'Test Results Failed',
                'data' => $result_data,
            );
        }
        
    }
}

echo json_encode($json_response);
?>