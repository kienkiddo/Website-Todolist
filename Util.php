<?php

function json_notify($type, $text)
{
  $notify = new stdClass();
  $notify->message = $text;
  $notify->type = $type;
  echo json_encode($notify);
}

function json_notify_with($selector, $type, $text)
{
  $notify = new stdClass();
  $notify->message = $text;
  $notify->type = $type;
  $notify->selector = $selector;
  echo json_encode($notify);
}

function set_notify($type, $text)
{
  $notify = new stdClass();
  $notify->message = $text;
  $notify->type = $type;
  $_SESSION['notify'] = serialize($notify);
}

function sql_refesh($text)
{
  $text = str_replace("'", "", $text);
  $text = str_replace('"', "", $text);
  $text = str_replace('<', "", $text);
  $text = str_replace('>', "", $text);
  //$text = str_replace('/', "", $text);
  //$text = str_replace('(', "", $text);
  //$text = str_replace(')', "", $text);
  $text = str_replace(';', "", $text);
  return $text;
}

function save_session($m)
{
  $_SESSION['id'] = $m->getId();
}

function save_cookie($m)
{
  $cookie_time = 86400 * 365;
  setcookie('user', $m->getUser(), time() + $cookie_time);
  setcookie('pass', $m->getPass(), time() + $cookie_time);
}

function member_logout()
{
  if (isset($_SESSION['id'])) {
    unset($_SESSION['id']);
  }
  if (isset($_COOKIE['user'])) {
    setcookie('user', "-1", time() - 3600);
  }
  if (isset($_COOKIE['pass'])) {
    setcookie('pass', "-1", time() - 3600);
  }
}
