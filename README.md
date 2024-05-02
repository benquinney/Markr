### Thoughts explanations and apologies


### Example import with more than one result for the student
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

### Example results
curl "http://localhost:80/results/?test_id=1234&action=aggregate"
