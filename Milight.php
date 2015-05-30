<?php
/*
 * Milight/LimitlessLED/EasyBulb PHP API
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Yashar Rashedi <info@rashedi.com>
 * Copyright (c) 2015 Phil Parsons <phil@parsons.uk.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
*/

class Milight {
  
  private $host;
  private $port;
  private $delay = 10000; //microseconds
  private $rgbwActiveGroup = 0; // 0 means all
  private $whiteActiveGroup = 0; // 0 means all
  private $commandCodes = array(

    //RGBW Bulb commands
    'rgbwAllOff' => array(0x41, 0x00),
    'rgbwAllOn' => array(0x42, 0x00),
    'rgbwGroup0Off' => array(0x41, 0x00),
    'rgbwGroup0On' => array(0x42, 0x00),
    'rgbwDiscoSlower' => array(0x43, 0x00),
    'rgbwDiscoFaster' => array(0x44, 0x00),
    'rgbwGroup1On' => array(0x45, 0x00),
    'rgbwGroup1Off' => array(0x46, 0x00),
    'rgbwGroup2On' => array(0x47, 0x00),
    'rgbwGroup2Off' => array(0x48, 0x00),
    'rgbwGroup3On' => array(0x49, 0x00),
    'rgbwGroup3Off' => array(0x4a, 0x00),
    'rgbwGroup4On' => array(0x4B, 0x00),
    'rgbwGroup4Off' => array(0x4c, 0x00),
    'rgbwDiscoMode' => array(0x4d, 0x00),
    'rgbwBrightnessMax' => array(0x4e, 0x1b),
    'rgbwBrightnessMin' => array(0x4e, 0x02),
    'rgbwAllSetToWhite' => array(0xc2, 0x00),
    'rgbwGroup0SetToWhite' => array(0xc2, 0x00),
    'rgbwGroup1SetToWhite' => array(0xc5, 0x00),
    'rgbwGroup2SetToWhite' => array(0xc7, 0x00),
    'rgbwGroup3SetToWhite' => array(0xc9, 0x00),
    'rgbwGroup4SetToWhite' => array(0xcb, 0x00),

    // White Bulb commands
    'whiteAllOn' => array(0x35, 0x00),
    'whiteAllOff' => array(0x39, 0x00),
    'whiteGroup0On' => array(0x35, 0x00),
    'whiteGroup0Off' => array(0x39, 0x00),
    'whiteBrightnessUp' => array(0x3c, 0x00),
    'whiteBrightnessDown' => array(0x34, 0x00),
    'whiteAllBrightnessMax' => array(0xb5, 0x00),
    'whiteAllNightMode' => array(0xbb, 0x00),
    'whiteGroup0BrightnessMax' => array(0xb5, 0x00),
    'whiteGroup0NightMode' => array(0xbb, 0x00),
    'whiteWarmIncrease' => array(0x3e, 0x00),
    'whiteCoolIncrease' => array(0x3f, 0x00),
    'whiteGroup1On' => array(0x38, 0x00),
    'whiteGroup1Off' => array(0x3b, 0x00),
    'whiteGroup2On' => array(0x3d, 0x00),
    'whiteGroup2Off' => array(0x33, 0x00),
    'whiteGroup3On' => array(0x3a, 0x00),
    'whiteGroup3Off' => array(0x32, 0x00),
    'whiteGroup4On' => array(0x32, 0x00),
    'whiteGroup4Off' => array(0x36, 0x00),
    'whiteGroup1BrightnessMax' => array(0xb8, 0x00),
    'whiteGroup2BrightnessMax' => array(0xbd, 0x00),
    'whiteGroup3BrightnessMax' => array(0xb7, 0x00),
    'whiteGroup4BrightnessMax' => array(0xb2, 0x00),
    'whiteGroup1NightMode' => array(0xbb, 0x00),
    'whiteGroup2NightMode' => array(0xb3, 0x00),
    'whiteGroup3NightMode' => array(0xba, 0x00),
    'whiteGroup4NightMode' => array(0xb6, 0x00)
  );

  public function setDelay($delay) {
    $this->delay = $delay;
  }

  public function getDelay() {
    return $this->delay;
  }

  public function setRgbwActiveGroup($rgbwActiveGroup) {
    $this->rgbwActiveGroup = $this->setActiveGroup($rgbwActiveGroup);
  }

  public function rgbwSetActiveGroup($rgbwActiveGroup) {
    $this->setRgbwActiveGroup($rgbwActiveGroup);
  }

  public function getRgbwActiveGroup() {
    return $this->rgbwActiveGroup;
  }

  public function setWhiteActiveGroup($whiteActiveGroup) {
    $this->whiteActiveGroup = $this->setActiveGroup($whiteActiveGroup);
  }

  public function setActiveGroup($Group) {
    if ($Group < 0 || $Group > 4) {
      throw new \Exception('Active group must be between 0 and 4. 0 means all groups');
    }
    return $Group;
  }

  public function whiteSetActiveGroup($whiteActiveGroup) {
    $this->setWhiteActiveGroup($whiteActiveGroup);
  }
  
  public function getWhiteActiveGroup() {
    return $this->whiteActiveGroup;
  }

  public function __construct($host = '10.10.100.254', $port = 8899) {
    $this->host = $host;
    $this->port = $port;
  }
  
  public function sendCommand(Array $command) {
    $command[] = 0x55; // last byte is always 0x55, will be appended to all commands
    $message = vsprintf(str_repeat('%c', count($command)), $command);
    for ($try=0;$try<10;$try++) {
      if ($socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP)) {
        socket_sendto($socket, $message, strlen($message), 0, $this->host, $this->port);
        socket_close($socket);
        usleep($this->getDelay()); //wait 10ms before sending next command
      }
    }
  }

  public function command($commandName) {
    $this->sendCommand($this->commandCodes[$commandName]);
  }

  public function rgbwSendOnToActiveGroup() {
    $this->rgbwSendOnToGroup($this->getRgbwActiveGroup());
  }

  public function rgbwSendOnToGroup($group) {
    $activeGroupOnCommand = 'rgbwGroup' . $group . 'On';
    $this->command($activeGroupOnCommand);
  }

  public function rgbwSendOffToGroup($group) {
    $activeGroupOffCommand = 'rgbwGroup' . $group . 'Off';
    $this->command($activeGroupOffCommand);
  }

  public function rgbwSetGroupToWhite($group) {
    $activeCommand = 'rgbwGroup' . $group . 'SetToWhite';
    $this->command($activeCommand);
  }

  public function rgbwSendOffToActiveGroup() {
    $this->rgbwSendOffToGroup($this->getRgbwActiveGroup());
  }

  public function whiteSendOnToActiveGroup() {
    $this->rgbwSetGroupToWhite($this->getRgbwActiveGroup());
  }

  public function rgbwBrightnessMax($group=null) {
    $this->setRgbwBrightnessMin(1000,$group);
  }

  public function rgbwBrightnessMin($group=null) {
    $this->setRgbwBrightnessMin(0,$group);
  }

  public function rgbwAllBrightnessMin() {
    $this->rgbwBrightnessPercent(0,0);
  }

  public function rgbwAllBrightnessMax() {
    $this->rgbwBrightnessPercent(100,0);
  }

  public function rgbwBrightnessPercent($brightnessPercent,$group=null) {
    if ($brightnessPercent < 0 || $brightnessPercent > 100) {
      throw new \Exception('Brightness percent must be between 0 and 100');
    }
    $brightnessPercent = round(2+(($brightnessPercent/100)*25));
    $group = isset($group) ? $group : $this->getRgbwActiveGroup();
    $this->rgbwSendOnToGroup($group);
    $this->sendCommand(array(0x4e, $brightnessPercent));
  }

  public function rgbwDiscoMode() {
    $this->rgbwSendOnToActiveGroup();
    $this->command('rgbwDiscoMode');
  }

  public function rgbwDiscoSlower() {
    $this->rgbwSendOnToActiveGroup();
    $this->command('rgbwDiscoSlower');
  }

  public function rgbwDiscoFaster() {
    $this->rgbwSendOnToActiveGroup();
    $this->command('rgbwDiscoFaster');
  }

  public function rgbwAllSetToWhite() {
    $this->command('rgbwAllSetToWhite');
  }

    public function whiteAllOn()
    {
        $this->command('whiteAllOn');
    }

    public function whiteAllOff()
    {
        $this->command('whiteAllOff');
    }

    public function whiteBrightnessUp()
    {
        $this->whiteSendOnToActiveGroup();
        $this->command('whiteBrightnessUp');
    }

    public function whiteBrightnessDown()
    {
        $this->whiteSendOnToActiveGroup();
        $this->command('whiteBrightnessDown');
    }

    public function whiteAllBrightnessMax()
    {
        $this->command('whiteAllBrightnessMax');
    }

    public function whiteAllBrightnessMin()
    {
        $this->setWhiteActiveGroup(0);
        $this->whiteSendOnToActiveGroup();
        for ($i = 0; $i < 10; $i++) {
            $this->command('whiteBrightnessDown');
        }

    }

    public function whiteAllNightMode()
    {
        $this->command('whiteAllNightMode');
    }


    public function whiteWarmIncrease()
    {
        $this->whiteSendOnToActiveGroup();
        $this->command('whiteWarmIncrease');
    }

    public function whiteCoolIncrease()
    {
        $this->whiteSendOnToActiveGroup();
        $this->command('whiteCoolIncrease');
    }

    public function whiteGroup1On()
    {
        $this->command('whiteGroup1On');
    }

    public function whiteGroup1Off()
    {
        $this->command('whiteGroup1Off');
    }

    public function whiteGroup2On()
    {
        $this->command('whiteGroup2On');
    }

    public function whiteGroup2Off()
    {
        $this->command('whiteGroup2Off');
    }

    public function whiteGroup3On()
    {
        $this->command('whiteGroup3On');
    }

    public function whiteGroup3Off()
    {
        $this->command('whiteGroup3Off');
    }

    public function whiteGroup4On()
    {
        $this->command('whiteGroup4On');
    }

    public function whiteGroup4Off()
    {
        $this->command('whiteGroup4Off');
    }

    public function whiteGroup1BrightnessMax()
    {
        $this->command('whiteGroup1BrightnessMax');
    }

    public function whiteGroup2BrightnessMax()
    {
        $this->command('whiteGroup2BrightnessMax');
    }

    public function whiteGroup3BrightnessMax()
    {
        $this->command('whiteGroup3BrightnessMax');
    }

    public function whiteGroup4BrightnessMax()
    {
        $this->command('whiteGroup4BrightnessMax');
    }

    public function whiteGroup1BrightnessMin()
    {
        $this->setWhiteActiveGroup(1);
        $this->whiteSendOnToActiveGroup();
        for ($i = 0; $i < 10; $i++) {
            $this->command('whiteBrightnessDown');
        }
    }

    public function whiteGroup2BrightnessMin()
    {
        $this->setWhiteActiveGroup(2);
        $this->whiteSendOnToActiveGroup();
        for ($i = 0; $i < 10; $i++) {
            $this->command('whiteBrightnessDown');
        }
    }

    public function whiteGroup3BrightnessMin()
    {
        $this->setWhiteActiveGroup(3);
        $this->whiteSendOnToActiveGroup();
        for ($i = 0; $i < 10; $i++) {
            $this->command('whiteBrightnessDown');
        }
    }

    public function whiteGroup4BrightnessMin()
    {
        $this->setWhiteActiveGroup(4);
        $this->whiteSendOnToActiveGroup();
        for ($i = 0; $i < 10; $i++) {
            $this->command('whiteBrightnessDown');
        }
    }

    public function whiteGroup1NightMode()
    {
        $this->command('whiteGroup1NightMode');
    }

    public function whiteGroup2NightMode()
    {
        $this->command('whiteGroup2NightMode');
    }

    public function whiteGroup3NightMode()
    {
        $this->command('whiteGroup3NightMode');
    }

    public function whiteGroup4NightMode()
    {
        $this->command('whiteGroup4NightMode');
    }

    public function rgbwSetColorHsv(Array $hsvColor)
    {
        $milightColor = $this->hslToMilightColor($hsvColor);
        $activeGroupOnCommand = 'rgbwGroup' . $this->getRgbwActiveGroup() . 'On';
        $this->command($activeGroupOnCommand);
        $this->sendCommand(array(0x40, $milightColor));
    }


    public function rgbwSetColorHexString($color)
    {
        $rgb = $this->rgbHexToIntArray($color);
        $hsl = $this->rgbToHsl($rgb[0], $rgb[1], $rgb[2]);
        $milightColor = $this->hslToMilightColor($hsl);
        $this->rgbwSendOnToActiveGroup();
        $this->sendCommand(array(0x40, $milightColor));
    }


    public function rgbHexToIntArray($hexColor)
    {
        $hexColor = ltrim($hexColor, '#');

        $hexColorLenghth = strlen($hexColor);
        if ($hexColorLenghth != 8 && $hexColorLenghth != 6) {
            throw new \Exception('Color hex code must match 8 or 6 characters');
        }
        if ($hexColorLenghth == 8) {
            $r = hexdec(substr($hexColor, 2, 2));
            $g = hexdec(substr($hexColor, 4, 2));
            $b = hexdec(substr($hexColor, 6, 2));
            if (($r == 0 && $g == 0 && $b == 0) || ($r == 255 && $g == 255 && $b == 255)) {
                throw new \Exception('Color cannot be black or white');
            }
            return array($r, $g, $b);
        }

        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));
        if (($r == 0 && $g == 0 && $b == 0) || ($r == 255 && $g == 255 && $b == 255)) {
            throw new \Exception('Color cannot be black or white');
        }
        return array($r, $g, $b);
    }


    public function rgbToHsl($r, $g, $b)
    {
        $r = $r / 255;
        $g = $g / 255;
        $b = $b / 255;
        $max = max($r, $g, $b);
        $min = min($r, $g, $b);
        $l = ($max + $min) / 2;
        $d = $max - $min;
        $h = '';

        if ($d == 0) {
            $h = $s = 0;
        } else {
            $s = $d / (1 - abs(2 * $l - 1));

            switch ($max) {
                case $r:
                    $h = 60 * fmod((($g - $b) / $d), 6);
                    if ($b > $g) {
                        $h += 360;
                    }
                    break;

                case $g:
                    $h = 60 * (($b - $r) / $d + 2);
                    break;

                case $b:
                    $h = 60 * (($r - $g) / $d + 4);
                    break;
            }
        }
        return array($h, $s, $l);
    }

  public function hslToMilightColor($hsl)
  {
    $color = (256 + 176 - (int)($hsl[0] / 360.0 * 255.0)) % 256;
    return $color + 0xfa;
  }
  
}
