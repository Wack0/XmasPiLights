#include <wiringPi.h>
#include <stdio.h>
#include <string.h>

#define CE 22
#define MODSEL 18
#define K0 11
#define K1 15
#define K2 16
#define K3 13

// Statuses
#define STATUS_SUCCESS 0;
#define STATUS_PARAMS_REQD -1;
#define STATUS_GPIO_SETUP_FAILED -2;

// Socket codes
#define SOCKET_1_ON "1111"
#define SOCKET_1_OFF "0111"
#define SOCKET_2_ON "1110"
#define SOCKET_2_OFF "0110"
#define SOCKET_3_ON "1101"
#define SOCKET_3_OFF "0101"
#define SOCKET_4_ON "1100"
#define SOCKET_4_OFF "0100"
#define SOCKET_ALL_ON "1011"
#define SOCKET_ALL_OFF "0011"

int retPin(int kNum) {
	if (kNum < 0) return STATUS_PARAMS_REQD;
	if (kNum > 3) return STATUS_PARAMS_REQD;
	if (kNum == 0) return K0;
	else if (kNum == 1) return K1;
	else if (kNum == 2) return K2;
	else return K3; // kNum == 3
}

int sendCode(char *code,char commit) {
	if (strlen(code) != 4) return STATUS_PARAMS_REQD;
	int i;
	for (i = 3; i >= 0; i--) {
		int value = HIGH;
		if (code[i] == '0') value = LOW;
		int pin = retPin(3-i);
		printf("Writing %d to %d\n",value,pin);
		digitalWrite(pin,value);
	}
	if (commit == 0) return STATUS_SUCCESS;
	delay(100);
	printf("Setting CE high\n");
	digitalWrite(CE,HIGH);
	delay(250);
	printf("Setting CE low\n");
	digitalWrite(CE,LOW);
	return STATUS_SUCCESS;
}

int main(int argc,char** argv) {
	if (argc < 2) return STATUS_PARAMS_REQD;
	// Setup the GPIO, using the board numbering
	if (wiringPiSetupPhys() == -1) return STATUS_GPIO_SETUP_FAILED;
	// K0-K3 data inputs 
	pinMode(K0,OUTPUT);
	pinMode(K1,OUTPUT);
	pinMode(K2,OUTPUT);
	pinMode(K3,OUTPUT);
	// ASK/FSK
	pinMode(CE,OUTPUT);
	// Enable/disable modulator
	pinMode(MODSEL,OUTPUT);
	// Disable the modulator
	digitalWrite(CE,LOW);
	// Set the modulator to ASK for on/off keying
	digitalWrite(MODSEL,LOW);
	// Init K0-K3
	sendCode("0000",0);
	if (!strcmp(argv[1],"11")) sendCode(SOCKET_1_ON,1);
	else if (!strcmp(argv[1],"10")) sendCode(SOCKET_1_OFF,1);
	else if (!strcmp(argv[1],"21")) sendCode(SOCKET_2_ON,1);
	else if (!strcmp(argv[1],"20")) sendCode(SOCKET_2_OFF,1);
	else if (!strcmp(argv[1],"31")) sendCode(SOCKET_3_ON,1);
	else if (!strcmp(argv[1],"30")) sendCode(SOCKET_3_OFF,1);
	else if (!strcmp(argv[1],"41")) sendCode(SOCKET_4_ON,1);
	else if (!strcmp(argv[1],"40")) sendCode(SOCKET_4_OFF,1);
	else if (!strcmp(argv[1],"A1")) sendCode(SOCKET_ALL_ON,1);
	else if (!strcmp(argv[1],"A0")) sendCode(SOCKET_ALL_OFF,1);
	else return STATUS_PARAMS_REQD;
	
	return STATUS_SUCCESS;
}
