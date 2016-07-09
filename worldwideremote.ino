/*
Infrared Receiver and LED program for the Particle Photon WiFi IoT microprocessor.

*/

#if defined (PARTICLE)
#include "application.h"
#endif

#include <IRRemote.h>


int RECV_PIN = D1;
IRrecv irrecv(RECV_PIN);
IRsend irsend;

decode_results results;

int infraredLED = D7;

void setup()
{
    Serial.begin(9600);
    irrecv.enableIRIn();        // Start the receiver
  
    pinMode(infraredLED, OUTPUT);
    digitalWrite(infraredLED, LOW);

    Particle.function("led",commandReceived);
}

void loop() {
    if (irrecv.decode(&results)) {
        Serial.print("Received: "); Serial.println(results.value, HEX);
        
        irsend.sendNEC(results.value, 32); // send on A5, this is a part of the header file (todo: double check where defined)
        Serial.print("Sending: "); Serial.println(results.value, HEX); Serial.println();
        
        
        irrecv.enableIRIn(); // Start the receiver
        irrecv.resume(); // Receive the next value
        
        Particle.publish("pushData", String(results.value), PRIVATE); // POST Data for logging/additional purposes
        //Particle.publish("pushData", String(results.value, HEX), PRIVATE); // POST Data for logging/additional purposes
    }
}



int commandReceived(String command) {
    
    /*
    if (command) {
        char inputStr[64];
        command.toCharArray(inputStr,64);
        int value = atoi(inputStr);
        analogWrite(led,value);
        return value;
    }
    else {
        return 0;
    }*/
    
    int result = 1;
    
    Serial.print("Web Command Received: "); Serial.print(command);
    
    
    if (command=="volup") irsend.sendNEC(0x57e3f00f, 32);
    else if (command=="voldown") irsend.sendNEC(0x57e308f7, 32);
    else if (command=="mute") irsend.sendNEC(0x57e304fb, 32);
    else if (command=="up") irsend.sendNEC(0x57e39867, 32);
    else if (command=="down") irsend.sendNEC(0x57e3cc33, 32);
    else if (command=="left") irsend.sendNEC(0x57e37887, 32);
    else if (command=="right") irsend.sendNEC(0x57e3b44b, 32);
    else result = 0;
    
    return result;
}

