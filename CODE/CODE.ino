#include <DFRobot_SSD1306_I2C.h>
#include <SoftwareSerial.h>
#include <DFRobot_Servo.h>
#include <DFRobot_URM10.h>
#include <UNO_Obloq.h>
#include <OneWire.h>

String Ma_name = "HOME-1";
int DS18S20_Pin = 11; 
OneWire ds(DS18S20_Pin); 
DFRobot_SSD1306_I2C oled12864(0x3c);
UNO_Obloq olq;
DFRobot_URM10 urm(7,6);
Servo servo_door;
SoftwareSerial olqsoft(2,3);//IoT Module serial number(software)
//SoftwareSerial to_beetle(12,13);

void dfrobotTone(int tonePin, int frequency, int duration) {
  if(frequency == 0){
    digitalWrite(tonePin,LOW);
    return;
  }
  int period = 1000000.0 / frequency;
  int pulse = period / 2.0;
  pinMode(tonePin,OUTPUT);
  for (int i = 1; i <= ((duration * 1000.0) / period); i++ ) {
    digitalWrite(tonePin,HIGH);
    delayMicroseconds(pulse);
    digitalWrite(tonePin,LOW);
    delayMicroseconds(pulse);
  }
}
float getTemp(){
  //returns the temperature from one DS18S20 in DEG Celsius

  byte data[12];
  byte addr[8];

  if ( !ds.search(addr)) {
      //no more sensors on chain, reset search
      ds.reset_search();
      return -1000;
  }

  if ( OneWire::crc8( addr, 7) != addr[7]) {
      Serial.println("CRC is not valid!");
      return -1000;
  }

  if ( addr[0] != 0x10 && addr[0] != 0x28) {
      Serial.print("Device is not recognized");
      return -1000;
  }

  ds.reset();
  ds.select(addr);
  ds.write(0x44,1); // start conversion, with parasite power on at the end

  byte present = ds.reset();
  ds.select(addr);
  ds.write(0xBE); // Read Scratchpad


  for (int i = 0; i < 9; i++) { // we need 9 bytes
    data[i] = ds.read();
  }

  ds.reset_search();

  byte MSB = data[1];
  byte LSB = data[0];

  float tempRead = ((MSB << 8) | LSB); //using two's compliment
  float TemperatureSum = tempRead / 16;

  return TemperatureSum;

}

void setup(){
  oled12864.begin();
  oled12864.setTextColor(1);
  oled12864.setCursorLine(1);
  oled12864.printLine("    Welcome");
  oled12864.setCursorLine(2);
  oled12864.printLine("     back");
  oled12864.setCursorLine(3);
  oled12864.printLine("     home");
  Serial.begin(9600);
  olqsoft.begin(9600);
  //to_beetle.begin(9600);
  servo_door.attach(5);
  olq.startConnect(&olqsoft, "SSID", "Password", "None of your bussiness,Please keep it none", 1234);
}

int sport_sensor;
int urm_data;
int val;
float temp_now;
char* server_url = "Your server's address";

void loop(){
  //Main code start
  sport_sensor = analogRead(A5);
  urm_data = urm.getDistanceCM();
  temp_now = getTemp();
  Serial.println(temp_now);
  if(sport_sensor >= 700){
    olq.post(server_url,Ma_name+":There is someone outside the door",3000);
    dfrobotTone(8,784,500);
    delay(500);
  }
  if(urm_data <= 10){
    
    olq.post(server_url,Ma_name+":The door is open",3000);
    servo_door.angle(90);
    delay(1000);
    servo_door.angle(0);
    delay(1000);
    
  }
  if(temp_now >= 35.0){
    //to_beetle.write("open_fan");
    analogWrite(A2,255);
    olq.post(server_url,Ma_name+":Fan open",3000);
  }
  if(temp_now < 35.0){
    //to_beetle.write("close_fan");
    analogWrite(A2,0);
  }
  
  //delay(100);
  /*servo_door.angle(90);
  delay(1000);
  servo_door.angle(0);
  delay(1000);*/
}
