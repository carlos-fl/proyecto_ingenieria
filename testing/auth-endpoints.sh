#!/bin/bash

readonly URL='http://localhost:8080/api'

# receive body request and endpoint url
auth_login () {
  body_jo=$1
  endpoint=$2
  desc=$3

  echo $endpoint: $desc
  curl -s -X POST -d $body_jo $endpoint | jq
}

auth_login $(jo email=carlos@gmail.com password=1234) "$URL/auth/controllers/adminLogin.php" "auth de admin"
auth_login $(jo email=carlos@gmail.com password=1234) "$URL/auth/controllers/applicantReviewer.php" "auth de revisor de datos de aplicante"
auth_login $(jo email=carlos@gmail.com password=1234) "$URL/auth/controllers/examReviewer.php" "auth de revisor de examenes"

# test applicant
echo "auth de aplicante para revisar datos"
curl -s -X POST -d $(jo applicantCode=12345 email=applicant@gmail.com) "$URL/auth/controllers/applicantAuth.php" | jq