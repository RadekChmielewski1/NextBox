void openLocker(void *pvParameters) {
  (void) pvParameters;
  uint8_t receivedPin; 
  uint8_t lcdMessage;

  for (;;) {
    if (xQueueReceive(lockerQueue, &receivedPin, portMAX_DELAY) == pdPASS) {
      switch (receivedPin) {
        case 1:
          Serial.println("-> Otwieram mala szafke (Pin 3)");
          lcdMessage = 1;
          xQueueOverwrite(lcdQueue, &lcdMessage);
          vTaskDelay(5);
          digitalWrite(sLockerPin, HIGH);
          vTaskDelay(100 / portTICK_PERIOD_MS); 
          digitalWrite(sLockerPin, LOW);
          break;

        case 2:
          Serial.println("-> Otwieram srednia szafke (Pin 4)");
          lcdMessage = 2;
          xQueueOverwrite(lcdQueue, &lcdMessage);
          vTaskDelay(5);
          digitalWrite(mLockerPin, HIGH);
          vTaskDelay(100 / portTICK_PERIOD_MS);
          digitalWrite(mLockerPin, LOW);
          break;
          
        case 3:
          Serial.println("-> Otwieram duza szafke (Pin 5)");
          lcdMessage = 3;
          xQueueOverwrite(lcdQueue, &lcdMessage);
          vTaskDelay(5);
          digitalWrite(bLockerPin, HIGH);
          vTaskDelay(100 / portTICK_PERIOD_MS);
          digitalWrite(bLockerPin, LOW);
          break;
      }
    }
  }
}