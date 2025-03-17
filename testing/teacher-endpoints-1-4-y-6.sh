#!/bin/bash

readonly URL="http://localhost:8080/api/teachers/controllers"

get () {
  file=$1
  desc=$2

  endpoint="$URL/$file"
  echo $endpoint $desc
  curl -s -X GET -b "PHPSESSID=3ogidfbn51m90ms0dpepj12l9a" $endpoint | jq
}

post () {
  file=$1
  body=$2
  desc=$3

  endpoint="$URL/$file"
  echo $endpoint $desc
  curl -s -X POST -b "PHPSESSID=3ogidfbn51m90ms0dpepj12l9a" -d $body $endpoint | jq
}

delete () {
  file=$1
  body=$2
  desc=$3

  endpoint="$URL/$file"
  echo $endpoint $desc
  curl -s -X DELETE -b "PHPSESSID=3ogidfbn51m90ms0dpepj12l9a" -d $body $endpoint | jq
}

get "getTeacher.php?teacher-number=1" "Endpoint to get data of a teacher"
get "teacherSections.php?teacher-number=1" "Endpoint to get active sections of a teacher"
get "section.php?section-id=1" "Endpoint to get section data"
post "addVideo.php" $(jo sectionID=1 URL=https://www.youtube.com/watch?v=jfKfPfyJRdk) "endpoint to add a video in a section"
delete "deleteVideo.php" $(jo sectionID=1) "Endpoint to delete a video in a section"