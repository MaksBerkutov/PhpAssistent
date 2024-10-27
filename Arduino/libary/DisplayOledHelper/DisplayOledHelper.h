#ifndef DisplayOledHelper_h
#define DisplayOledHelper_h
#include <Wire.h>
#include <Adafruit_SSD1306.h>

#define SCREEN_WIDTH 128
#define SCREEN_HEIGHT 64
#define OLED_RESET -1
#define SCREEN_ADDRESS 0x3C
#define MAX_CHARS_PER_LINE 21

class DisplayOledHelper
{
private:
  Adafruit_SSD1306 display;
  String FreeLine = "";
  int FreeIndex = 55;
  void GetNextFreeIndex()
  {
    FreeIndex += 9;

    if (FreeIndex > 54)
    {
      Clear(FreeIndex);

      FreeIndex = 0;
    }
  }
  void PrintLine(int index, String Text)
  {
    display.setCursor(0, index);

    for (int i = 0; i < Text.length(); i++)
    {
      // Print one character at a time
      display.write(Text[i]);

      // Yield control to allow background tasks like Wi-Fi
      yield();
    }

    display.display(); // Refresh the display after printing the line
  }
  void Clear(int index)
  {
    display.clearDisplay(); // Очистка дисплея
  }

public:
  DisplayOledHelper() : display(SCREEN_WIDTH, SCREEN_HEIGHT, &Wire, OLED_RESET) {}
  void begin()
  {
    for (int i = 0; i < MAX_CHARS_PER_LINE; i++)
      FreeLine += " ";

    Wire.begin(14, 12);
    display.begin(SSD1306_SWITCHCAPVCC, SCREEN_ADDRESS);
    display.clearDisplay();      // Очистка дисплея
    display.setTextColor(WHITE); // Цвет текста — белый
    display.setTextSize(1);      // Размер текста (увеличен)
    display.display();           // Обновление дисплея для отображения
  }
  void print(String text)
  {
    if (text.length() > MAX_CHARS_PER_LINE)
    {
    }
    else
    {
      GetNextFreeIndex();
      PrintLine(FreeIndex, text);
    }
    display.display(); // Обновление дисплея для отображения
  }
};

#endif
