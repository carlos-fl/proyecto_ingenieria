<?php
  session_start();

  $_SESSION = [];
  session_unset();

  session_destroy();

  if (isset($_COOKIE[session_name()])) {
      setcookie(session_name(), '', 1);
  }

  header("Location: /");
  exit;

