template <typename K, typename V>
class MyMap
{
private:
  struct KeyValuePair
  {
    K key;
    V value;
  };

  KeyValuePair *mapArray;
  int capacity;
  int size;

public:
  MyMap(int maxSize)
  {
    capacity = maxSize;
    size = 0;
    mapArray = new KeyValuePair[capacity];
  }

  ~MyMap()
  {
    delete[] mapArray;
  }

  bool put(const K &key, const V &value)
  {
    if (size >= capacity)
    {
      return false; // Map is full
    }

    // Check if key already exists and update value
    for (int i = 0; i < size; ++i)
    {
      if (mapArray[i].key == key)
      {
        mapArray[i].value = value;
        return true;
      }
    }

    // Insert new key-value pair
    mapArray[size].key = key;
    mapArray[size].value = value;
    size++;
    return true;
  }

  bool get(const K &key, V &value)
  {
    for (int i = 0; i < size; ++i)
    {
      if (mapArray[i].key == key)
      {
        value = mapArray[i].value;
        return true;
      }
    }
    return false; // Key not found
  }
  V get(const K &key)
  {
    for (int i = 0; i < size; ++i)
    {
      if (mapArray[i].key == key)
      {
        return mapArray[i].value;
      }
    }
    return V();
  }

  bool contains(const K &key)
  {
    for (int i = 0; i < size; ++i)
    {
      if (mapArray[i].key == key)
      {
        return true;
      }
    }
    return false;
  }

  int length() const
  {
    return size;
  }

  void clear()
  {
    size = 0;
  }
};
