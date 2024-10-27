#ifndef My_Variable
#define My_Variable
#include <DoubleLinkList.h>
class MyVariable
{
private:
  DoubleLinkList values();

public:
  String &operator[](const String index)
  {
    return values[index]
  }
  const String &operator[](const String index) const
  {
    return values[index]
  }
  String print(const String index)
  {
    return "\"" + index + "\":\"" + values[index] + "\"";
  }
  String print(const String index)
  {
    

    return "\"" + index + "\":\"" + values[index] + "\"";
  }
}
#endif