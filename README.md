### Thoughts explanations and apologies
I am very sorry that this code does not complete 100% of the challenge - honestly I overestimated how much time I would be able to commit to it between my interview yesterday afternoon and 12pm today. Between family duties last night and taking my daughter to school today I have simply run out of time.

I have thrown the example together in PHP as that is what I am most familiar with and I thought I would be able to get the most done in limited time.

The routes are not exactly as you had them in your example - you will notice that there are a / at the end so that they hit the index.php file in that directory

The curl for the results is also modified slightly so that the parameters for the test-id and action are passed through as GET variables

### Import process
During the import process I have taken the XML and converted it to an array so that I can loop through the data and save the bits we need as a text file with a json string in it. I have allowed for multiple submissions at different times and multiple submissions for the same student with the same payload

### Results process
When generating the results I am looping through the text files with the directory for the test-id and creating an array for the student results - taking into consideration if there is more than one result then take the highest score.

I have not calculated the standard deviation as I have forgotten how to do it off the top of my head and I was so tight on time I figured it wasn't in the first example output, but was in the second that I would be cheaky and leave it out for the moment.

### No HTTP errors
The output of the results is also not exactly as you had asked because I put some extra data in the json response for indicating whether or not the import or result was successful while leaving the standard 200 type response showing that the endpoint itself was reached. Again, I know this is not what was asked, but I have just omitted it due to time

### Very rudementary automated tests
I have not set up PHPUnit to run automated tests so instead I have created some very rudementary tests to check the results expected:
1. Run the test import: curl "http://localhost:80/import/test.php"
2. Run the test results: curl "http://localhost:80/results/?test_id=1234&action=test"

### No rejection for missing items
I have also not added any checking for if parts of the XML are missing sorry!!

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
