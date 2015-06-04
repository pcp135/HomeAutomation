Home Automation
=============

This code base started as a project to control some [LimitlessLED Smart-Lights](http://limitlessled.com) bulbs through a web interface hosted on a Raspberrypi.  The initial starting point for this was the [complete PHP API](https://github.com/yasharrashedi/LimitlessLED) for these bulbs produced by Yashar Rashedi.

Over time my project expanded to include also some Orvibo smart sockets for which I couldn't find any existing PHP API so wrote my own using the reverse engineered data available from various sources.

Then to better generalise the commands for these two different APIs I added a master Controllable class to generalise the commands I was using against both of these APIs and switched both of those sub-APIs to be submodules of this master project so that development could happen more independently.

Please feel free to include this code in your own projects and further develop and expand to support more of the features of the products or add other items.