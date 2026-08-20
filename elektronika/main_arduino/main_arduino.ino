#include <Arduino_FreeRTOS.h>
#include "pass.h"
#include <WiFiS3.h>
#include <ArduinoMDNS.h>
#include <Wire.h> 
// #include <LiquidCrystal_I2C.h> 

#define simulateTempRead 0

// LiquidCrystal_I2C lcd(0x27, 16, 2); //Uruchom wyswietlacz na I2C pod adresem 0x27

char ssid[] = ssidWifi;
char pass[] = passWifi;

int port = serverPort;
WiFiServer server(port);

WiFiUDP udp;
MDNS mdns(udp);
const char* deviceName = "arduino";

TaskHandle_t serverHTTPHandle;
TaskHandle_t blinkTaskHandle;
TaskHandle_t tempReadHandle;
TaskHandle_t lcdControlHandle;
TaskHandle_t lockerControlHandle;
QueueHandle_t tempQueue;
QueueHandle_t lockerQueue;
QueueHandle_t lcdQueue;

const uint8_t sLockerPin = 2;
const uint8_t mLockerPin = 3;
const uint8_t bLockerPin = 4;
const uint8_t peltierPin = 5;

int8_t lockerNumber;

void taskServer(void *pvParameters);
void peltierControl(void *pvParameters);

void setup() {
  //Inicjalizacja wyświetlacza:
  // lcd.init();
  // lcd.backlight();
  // lcd.setCursor(0,0);
  // lcd.print("    NEXTBOX    "); 
  // lcd.setCursor(0,1);
  // lcd.print("    Starting... ");

  //Ustawienia pinów
  pinMode(LED_BUILTIN, OUTPUT);
  pinMode(sLockerPin, OUTPUT);
  pinMode(mLockerPin, OUTPUT);
  pinMode(bLockerPin, OUTPUT);
  pinMode(peltierPin, OUTPUT);

  //Uruchom serial
  Serial.begin(9600);
  //Sekcja komunikacji z wifi
  Serial.print("Lacze z ");
  Serial.print(ssid);

  //Wystartuj wifi
  WiFi.begin(ssid,pass);
int attempts = 0;
  while (WiFi.status() != WL_CONNECTED || WiFi.localIP() == IPAddress(0,0,0,0)) {
    delay(500);
    Serial.print(".");
    attempts++;
    
    if (attempts > 40) {
       Serial.println("\nZbyt długo! Ponawiam WiFi.begin...");
       WiFi.begin(ssid, pass);
       attempts = 0;
    }
  }

  Serial.println("\nPołączono IP: " + WiFi.localIP().toString());
  server.begin();
  //Start mDNS:
  if(mdns.begin(WiFi.localIP(), deviceName)){
    Serial.println("Uruchomiono mDNS jako: " + String(deviceName) + ".local");
    mdns.addServiceRecord("_http", 25565, MDNSServiceTCP);
  }
  else{
    Serial.println("Błąd mDNS");
  }

  //elementy od freeRTOS
  tempQueue = xQueueCreate(1, sizeof(float));
  lockerQueue = xQueueCreate(1, sizeof(uint8_t));
  lcdQueue = xQueueCreate(1, sizeof(uint8_t));
  // xTaskCreate(taskBlink, "BlinkTest",256,nullptr,2,&blinkTaskHandle);
  xTaskCreate(tempRead, "TempRead", 128, nullptr,2,&tempReadHandle);
  xTaskCreate(taskServer, "serverHTTP", 1024, nullptr, 1, &serverHTTPHandle);
  xTaskCreate(openLocker, "OpenLocker", 128, nullptr, 3, &lockerControlHandle);
  // xTaskCreate(lcdControl, "LcdControl", 128, nullptr, 3, &lcdControlHandle);
  Serial.println("Uruchamianie freeRTOS");
  vTaskStartScheduler();
}

void loop() {
  //Wgrany freeRTOS i to musi być puste
}

void taskServer(void *pvParameters) {
  float currentTemp;
  for (;;) {
    WiFiClient client = server.available();
    mdns.run();
    if (client) {
      Serial.println("Polaczono klienta");
      String currentLine = "";
      String request = "";

      while (client.connected()) {
        if (client.available()) {
          char c = client.read();
          request += c;

          if (c == '\n') {
            if (currentLine.length() == 0) {
              client.println("HTTP/1.1 200 OK");
              client.println("Content-type:text/plain");
              client.println("Access-Control-Allow-Origin: *");
              client.println("Connection: close");
              client.println();
              
              if (request.indexOf("GET /openLockerSmall") >= 0) {
                lockerNumber = 1;
                xQueueOverwrite(lockerQueue, &lockerNumber);
                vTaskDelay(20);
                  
              }
              else if (request.indexOf("GET /openLockerMedium") >= 0) {
                lockerNumber = 2;
                xQueueOverwrite(lockerQueue, &lockerNumber);
                vTaskDelay(1);
              }
              else if (request.indexOf("GET /openLockerLarge") >= 0) {
                lockerNumber = 3;
                xQueueOverwrite(lockerQueue, &lockerNumber);
                vTaskDelay(1);
              }
              else if (request.indexOf("GET /tempRead") >= 0) {
                if (xQueuePeek(tempQueue, &currentTemp, 0) == pdPASS) {
                  Serial.println(currentTemp);
                }
                client.println(currentTemp);
              }
              break; 
            } else {
              currentLine = "";
            }
          } else if (c != '\r') {
            currentLine += c;
          }
        }
        else {
             vTaskDelay(1); 
        }
      }
      client.stop();
      Serial.println("Klient rozlaczony");
    } 
    vTaskDelay(10 / portTICK_PERIOD_MS);
  }
}