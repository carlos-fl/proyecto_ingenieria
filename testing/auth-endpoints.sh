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

logout () {
  endpoint=$1
  desc=$2
  echo $endpoint: $desc
  curl -s -X POST -d $body_jo $endpoint | jq
}

auth_login $(jo email=carlos.rodriguez@unah.hn password=1234) "$URL/auth/controllers/adminLogin.php" "auth de admin"
auth_login $(jo email=mfernandez@unah.hn password=1234) "$URL/auth/controllers/teacherAuth.php" "auth de docentes"
auth_login $(jo email=carlos@gmail.com password=1234) "$URL/auth/controllers/applicantReviewer.php" "auth de revisor de datos de aplicante"
auth_login $(jo email=carlos@gmail.com password=1234) "$URL/auth/controllers/examReviewer.php" "auth de revisor de examenes"

# log out
logout "$URL/auth/controllers/logout.php" "Endpoint to logout"

# test applicant
echo "auth de aplicante cuando ingrese a revisar datos enviados"
curl -s -X POST -d $(jo applicantCode=2025ABCDEFGHIJKLMNOPQRSTUV1) "$URL/auth/controllers/applicantAuth.php" | jq