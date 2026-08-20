// const float setpoint = 15.0;
// const float histereza = 0.5;


// void peltierControl (void *pvParameters){
//   for(;;){
//     float receivedTemp = 0; 
//     if (xQueueReceive(peltierQueue, &receivedTemp, portMAX_DELAY) == pdPASS) {
//       if(receivedTemp < (setpoint - histereza)){
//         digitalWrite(peltierPin, LOW);
//         //Wylacz peltiera
//       }
//       if(receivedTemp > (setpoint + histereza)){
//         digitalWrite(peltierPin, HIGH);
//         //Wlacz peltiera
//       }

//   }
//   vTaskDelay(100/portTICK_PERIOD_MS);
//   }
// }