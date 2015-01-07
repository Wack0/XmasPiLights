import std.net.curl; // for http post :P
import std.stdio; // for quick status printing
import std.string; // for split/join
import std.conv; // for to!...
import std.process; // for executeShell
// for sleep
import core.thread;
import core.time;

const string treeapi = "http://example.com/treeapi/"; // WITH TRAILING SLASH.
const string EnergenieApp = "/usr/bin/dopin";

void doAction(string action) {
	switch (action[0]) {
		case '1':
		case '2':
		case '3':
		case '4':
		case '5':
			DoPin(action[0].to!int,(action[1] != '0'));
			break;
		case 'A':
			DoPin(5,(action[1] != '0'));
			break;
		case 'W':
		case 'w':
			Thread.sleep(dur!("msecs")(action[1..$].to!int));
			return;
		default:
			return;
	}
}

void DoPin(int PinNum,bool turnOn) {
	string arg = "";
	if (PinNum > 4) arg ~= "A";
	else arg ~= PinNum.to!string;
	arg ~= (turnOn?"1":"0");
	executeShell(EnergenieApp~" "~arg);
}

void main(string[] args) {
	writeln("TreeClient by slipstream, Nov-2014");
	if (args.length < 2) {
		writeln("Usage: "~args[0]~" name");
		return;
	}
	string name = args[1..$].join(" ");
	string postdata = "name="~name;
	writeln("Now going into loop to contact the server.");
	writeln("Do NOT turn off or unplug the raspberry pi now!");
	writeln("In fact, just leave it.");
	writeln("Unless you want to see the backlog played out that is!");
	writeln("If you REALLY want to exit, press Ctrl+C.. BUT ONLY IF YOU KNOW WTF YOU ARE DOING!");
	writeln("Again: DO NOT TURN OFF THE RASPBERRY PI!");
	while (true) {
		try {
			string APIOut = cast(string)post(treeapi~"get",postdata);
			if (APIOut.indexOf(";") != -1) {
				// got a result from the API. let's do this.
				string[] actions = APIOut.split(";");
				foreach (string action; actions) doAction(action);
			}
		} catch (Exception) { } catch (Error) { }
		Thread.sleep(dur!("seconds")(1));
	}
}