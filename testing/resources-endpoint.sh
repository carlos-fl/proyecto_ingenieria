#!/bin/bash

readonly URL='http://localhost:8080/src/services'

# get endpoint path and description
get_resources () {
  endpoint=$1
  desc=$2

  echo $endpoint: $desc
  curl -s -X GET $endpoint | jq
}


get_resources "$URL/resources/controllers/eventImages.php" "endpoint to get event images"
get_resources "$URL/resources/controllers/majorsByCenter.php" "endpoint to get majors by chosen center"
get_resources "$URL/resources/controllers/majorsByLevelAndCenter.php" "endpoint to get majors by center chosen and available level"
get_resources "$URL/resources/controllers/majorsData.php" "endpoint to get majors general data"
get_resources "$URL/resources/controllers/regionalCenters.php" "endpoint to get regional centers data"