//Wyprowadzimy diode led która będzie informowała nas o błedach
void taskBlink(void *pvParameters){
  for(;;){
    digitalWrite(LED_BUILTIN, HIGH);
    vTaskDelay(500);
    digitalWrite(LED_BUILTIN, LOW);
    vTaskDelay(500);
  }
}