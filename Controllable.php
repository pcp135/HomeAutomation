<?php

require 'Milight.php';
require './Orvibo/Orvibo.php';
    
class Controllable {
  
  private $controller;
  private $type;

  public function __construct($type, $host, $port = 8899, $mac = null) {
    $this->type=$type;
    if ($this->type === "RGBWMilight") {
      $this->controller = new Milight($host, $port);
    }
    elseif ($this->type === "Orvibo") {
      $this->controller = new Orvibo($host, $port, $mac);
    }
  }

  public function sendOn($group) {
    if ($this->type === "RGBWMilight") $this->controller->rgbwSendOnToGroup($group);
    elseif ($this->type === "Orvibo") $this->controller->on();
  }
  public function sendOff($group) {
    if ($this->type === "RGBWMilight") $this->controller->rgbwSendOffToGroup($group);
    elseif ($this->type === "Orvibo") $this->controller->off();
  }
  public function sendNight($group) {
    if ($this->type === "RGBWMilight") $this->controller->rgbwSendNightModeToGroup($group);
    elseif ($this->type === "Orvibo") $this->controller->off();
  }
  public function setWhite($group) {
    $this->sendOn($group);
    if ($this->type === "RGBWMilight") $this->controller->rgbwSetGroupToWhite($group);
  }
  public function setRandom($group) {
    $this->sendOn($group);
    if ($this->type === "RGBWMilight") {
      $this->controller->rgbwSetActiveGroup($group);
      $this->controller->rgbwSetColorHexString(sprintf('#%06X', mt_rand(0, 0xFFFFFF)));
    }
  }
  public function setBrightness($group, $percent) {
    $this->sendOn($group);
    if ($this->type === "RGBWMilight") {
      $this->controller->rgbwSetActiveGroup($group);
      $this->controller->rgbwBrightnessPercent((int)$percent);
    }
  }
  public function discoMode($group) {
    $this->sendOn($group);
    if ($this->type === "RGBWMilight") {
      $this->controller->rgbwSetActiveGroup($group);
      $this->controller->rgbwDiscoMode();
    }
  }
}
