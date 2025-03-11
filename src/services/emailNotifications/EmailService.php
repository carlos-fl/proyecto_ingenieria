<?php

  include_once __DIR__ . '/../../../utils/classes/HTMLTemplate.php';
  include_once __DIR__ . '/../../../config/env/Environment.php';

  class EmailService {
    public static function sendEmail(string $toEmail, string $subject, array $data, string $filename) {
      $HTML_CONTENT = HTMLTemplate::parse($filename, $data);
      $env = Environment::getVariables();
      $UNAH_EMAIL = $env['EMAIL_USER'];
      $APP_PASSWORD = $env['APP_PASSWORD'];
      $command = "echo -e | swaks --to \"$toEmail\" --from \"$UNAH_EMAIL\" --server smtp.gmail.com:587 --tls --auth LOGIN --auth-user \"$UNAH_EMAIL\" --auth-password \"$APP_PASSWORD\" --header \"Subject: $subject\" --header \"Content-Type: text/html; charset=UTF-8\" --body - <<EOF\n$HTML_CONTENT\nEOF";
      try {
        shell_exec($command);
      } catch(Throwable $error) {
        echo "ERROR SENDING EMAIL...";
      }
    }
  }

  EmailService::sendEmail("josue.ham@unah.hn", "CONFIRMACIÓN DE APLICACIÓN", ["name" => "ham"], "postulantFormSent.html");

