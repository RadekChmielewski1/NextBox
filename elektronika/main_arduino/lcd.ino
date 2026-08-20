// void lcdControl(void *pvParameters) {
//   uint8_t message;
//   lcd.clear();
//   lcd.setCursor(0,0);
//   lcd.print("    NEXTBOX    "); 
//   lcd.setCursor(0,1);
//   lcd.print("Have a nice day!");
//   while(1){
//      if (xQueueReceive(lcdQueue, &message, portMAX_DELAY) == pdPASS) {
//       lcd.clear();
//       lcd.setCursor(0,0);
//       lcd.print("Opening: ");
//       lcd.setCursor(0,1);
//       switch (message) {
//         case 1:
//             lcd.print("Small box");
//             break;

//         case 2:
//             lcd.print("Medium box");
//             break;
          
//         case 3:
//             lcd.print("Large box");
//             break;

//       }
//       vTaskDelay(6000 / portTICK_PERIOD_MS);
//       lcd.clear();
//       lcd.setCursor(0,0);
//       lcd.print("    NEXTBOX    "); 
//       lcd.setCursor(0,1);
//       lcd.print("Have a nice day!");
//     }
//   }
// }