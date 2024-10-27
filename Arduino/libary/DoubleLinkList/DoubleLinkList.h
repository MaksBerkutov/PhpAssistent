#ifndef DoubleLink
#define DoubleLink
#include <string.h>
#include <Arduino.h>

class DoubleLinkList
{
  class Dates
  {
  public:
    String key;   // Значение
    String value; // Значение
    Dates *pNext; // Указатель на следующую ячейку
    Dates *pPrev; // Указатель на предыдущую ячейку
  public:
    // Конструктор
    Dates(String _key = "", String _value = "", Dates *pN = nullptr, Dates *pP = nullptr)
    {
      this->key = _key;
      this->value = _value;
      this->pNext = pN;
      this->pPrev = pP;
    }
  };
  Dates *pHead; // Указатель на голову стека
  Dates *pEnd;  // Указатель на хвост стека
  int size = 0; // Размер стека
public:
  // Узнать текуций размера стека
  int getSize();

  // Констркутор по умолчанию
  DoubleLinkList()
  {
    pHead = nullptr;
    pEnd = nullptr;
  }
  // Конструктор со значением
  DoubleLinkList(String key, String val)
  {
    pHead = new Dates(key, val, pHead);
    pEnd = pHead;
    size++;
  }
  String get_key_value_string();
  String get_key_value_string(String key);
  String &operator[](const String index);
  const String &operator[](const String index) const;
  // Добавление в начало
  void push_front(String key, String val);
  // Добавление в конец
  void push_back(String key, String val);
  // Удаление с начала
  String &pop_front();
  // Удаление с конца
  String &pop_end();
  // Удаление по индексу
  void dellItem(int index);
  // Обнуление стека
  void deleteAll();

  // Поиск переменной в стеке
  String sceartchVariable(String old);
};

#endif