#include "DoubleLinkList.h"
int DoubleLinkList::getSize()
{
  return size;
}
void DoubleLinkList::push_front(String key, String val)
{
  if (pHead == nullptr)
  {
    pHead = new Dates(key, val, pHead);
    pEnd = pHead;
    size++;
  }
  else
  {
    if (pEnd == pHead)
    {
      pHead = new Dates(key, val, pHead);
      pEnd->pPrev = pHead;
      size++;
    }
    else
    {
      Dates *tmp = pHead;
      pHead = new Dates(key, val, pHead);
      tmp->pPrev = pHead;
      size++;
    }
  }
}
String DoubleLinkList::get_key_value_string()
{
  String result = "";
  Dates *tmp = this->pHead;
  for (int i = 0; i < size; i++)
  {
    result + "\"" + tmp->key + "\":\"" + tmp->value + "\"" + (i != size - 1 ? "," : "");
  }
}
String DoubleLinkList::get_key_value_string(String key)
{
  return "\"" + key + "\":\"" + this->operator[](key) + "\"";
}
void DoubleLinkList::push_back(String key, String val)
{
  if (pHead == nullptr)
  {
    push_front(key, val);
  }
  else
  {
    pEnd->pNext = new Dates(key, val, nullptr, pEnd);
    pEnd = pEnd->pNext;
    size++;
  }
}
// Удаление с начала
String &DoubleLinkList::pop_front()
{
  if (pHead != nullptr)
  {
    String tmp = pHead->key;
    pHead = pHead->pNext;
    delete[] pHead->pPrev;
    pHead->pPrev = nullptr;
    size--;
    return tmp;
  }
}
// Удаление с конца
String &DoubleLinkList::pop_end()
{
  if (pHead != nullptr)
  {
    String tmp = pEnd->key;
    pEnd = pEnd->pPrev;
    delete[] pEnd->pNext;
    pEnd->pNext = nullptr;
    size--;
    return tmp;
  }
}
void DoubleLinkList::dellItem(int index)
{
  if (index == 0)
  {
    pop_front();
    return;
  }
  else if (index == size - 1)
  {
    pop_end();
    return;
  }
  else if (index >= 0 && index < size)
  {
    Dates *tmp = this->pHead;
    for (int i = 0; i < size; i++)
    {
      if (i < index - 1)
        tmp = tmp->pNext;
      else if (i == (index - 1))
      {
        Dates *y = tmp->pNext;
        delete[] tmp->pNext;
        tmp->pNext = y->pNext;
        break;
      }
    }
    size--;
  }
}

// Обнуление стека
void DoubleLinkList::deleteAll()
{

  Dates *tmp = pEnd;
  for (int i = this->size - 1; i != 0; i--)
  {
    Dates *free = tmp;
    tmp = tmp->pPrev;
    delete[] free;
  }
  delete[] pHead;
  pHead = nullptr, pEnd = nullptr;
}
String DoubleLinkList::sceartchVariable(String old)
{
  Dates *tmp = this->pHead;
  for (int i = 0; i < size; i++)
  {
    if (tmp->key == old)
    {
      return tmp->value;
    }
    tmp = tmp->pNext;
  }
  return NULL;
}

String &DoubleLinkList::operator[](const String index)
{
  String finded = sceartchVariable(index);
  if (finded == NULL)
  {
    push_back(index, "");
    return pEnd->value;
  }

  return finded;
}
const String &DoubleLinkList::operator[](const String index) const
{
  String finded = sceartchVariable(index);
  if (finded == NULL)
  {
    push_back(index, "");
    return pEnd->value;
  }

  return finded;
}