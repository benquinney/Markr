### Thoughts, explanations and apologies
I have thrown the example together in vanilla PHP and have saved the results as a series of text files rather than in a database as I am really not the best at setting up platforms and infrastructure and I didn't want to waste time doing that when I felt like I could achieve the essence of what you were wanting without any extra libraries.

The routes are not exactly as you had them in your example - you will notice that there is a / at the end of them so that they hit the index.php file in that directory.

The curl for the results function is also modified slightly so that the parameters for the test-id and action are passed through as GET variables.

These changes are easily rectified once the appropriate infrastructure and libraries are configured so I was hoping that this wouldn't be too much of a problem.

### Import process
During the import process I have taken the XML and converted it to an array so that I can loop through the data and save the bits we need as a text file with a json string in it. I have allowed for multiple submissions at different times and multiple submissions for the same student with the same payload.

### Results process
When generating the results I am looping through the text files with the directory for the test-id and created an array for the student results - taking into consideration if there is more than one result then take the highest marks attained or highest marks available.

I have not calculated the standard deviation as I have forgotten how to do it off the top of my head and I figured it wasn't in the first example output, but was in the second so I thought I would be cheaky and leave it out for the moment.

### No HTTP errors
The output of the results is also not exactly as you had asked because I put some extra data in the json response for indicating whether or not the import or result was successful while leaving the standard 200 type response showing that the endpoint itself was reached. Again, I know this is not what was asked, but I have just omitted it due to time

### Very rudementary automated tests
I have not set up PHPUnit to run automated tests so instead I have created some very rudementary tests to check the results expected:
1. Run the test import: curl "http://localhost:80/import/test.php"
2. Run the test results: curl "http://localhost:80/results/?test_id=1234&action=test"

### Example import with more than one result for the student
```
curl -X POST -H 'Content-Type: text/xml+markr' http://localhost:80/import/ -d @- <<XML
    <mcq-test-results>
        <mcq-test-result scanned-on="2017-12-04T12:12:10+11:00">
            <first-name>Jane</first-name>
            <last-name>Austen</last-name>
            <student-number>521585128</student-number>
            <test-id>1234</test-id>
            <summary-marks available="20" obtained="13" />
        </mcq-test-result>
        <mcq-test-result scanned-on="2017-12-04T12:12:10+11:00">
            <first-name>John</first-name>
            <last-name>Austen</last-name>
            <student-number>521585129</student-number>
            <test-id>1234</test-id>
            <summary-marks available="20" obtained="11" />
        </mcq-test-result>
        <mcq-test-result scanned-on="2017-12-04T12:12:10+11:00">
            <first-name>John</first-name>
            <last-name>Austen</last-name>
            <student-number>521585129</student-number>
            <test-id>1234</test-id>
            <summary-marks available="20" obtained="14" />
        </mcq-test-result>
    </mcq-test-results>
XML
```

### Example results
```
curl "http://localhost:80/results/?test_id=1234&action=aggregate"
```

### Test import
```
curl "http://localhost:80/import/test.php"
```

### Test results
```
curl "http://localhost:80/results/?test_id=1234&action=test"
```
