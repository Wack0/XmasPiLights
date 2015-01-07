# ircbot  
The XmasPiLights IRC bot. Uses the [Net_SmartIRC](https://github.com/pear/Net_SmartIRC) library.

Change the information at the top of `ircbot.php`, and run it with `php ircbot.php`, ideally in its own `screen`.

The IRC bot will only act on one command every five seconds to prevent denial-of-service attacks related to people flooding the IRC channel with triggers.  
The rate limiting time can be changed by ops with the `!seconds` trigger. The syntax is `!seconds <seconds>` where `<seconds>` is the new number of seconds to use.