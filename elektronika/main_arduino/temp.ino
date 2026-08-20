void tempRead(void *pvParameters){
  const uint8_t simulate = simulateTempRead;
  for(;;){
    int odczyt;
    float napiecie;
    float temperatura;
      float currentTemp;
  const float histereza = 0.5;
  const float setpoint = 10.0;
    switch(simulate){
      case 0:
          odczyt = analogRead(tempPin);
          napiecie = odczyt * (5.0/1023.0);

          temperatura = napiecie * 100.0 - 10.0;
          vTaskDelay(pdMS_TO_TICKS(100));
          Serial.println(temperatura);
           if(temperatura < (setpoint + histereza)){
                    //to wylacz
                    digitalWrite(peltierPin, LOW);
                  }
                  if (temperatura > (setpoint - histereza)){
                    //wlacz
                    Serial.println("chlodze");
                    digitalWrite(peltierPin, HIGH);
                  }
          break;
        case 1: //Tryb symulowanego nadawania (do testu kolejek i strony internetowej)
            temperatura = 20.0 + (random(0, 50) / 10.0);
            Serial.println(temperatura);
            xQueueOverwrite(tempQueue, &temperatura);
            vTaskDelay(pdMS_TO_TICKS(500));
        break;
    }
  }
}