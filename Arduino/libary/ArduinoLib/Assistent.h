#ifndef Assistent_h
#define Assistent_h
// CFG
#define ASSISTENT_DEBUG
#define ASSISTENT_OLED
#define ASSISTENT_OTA

#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>
#include <ArduinoJson.h>
#include <ESP8266HTTPClient.h>
#include <string.h>
#include <MyAes.h>
#include <EEPROM.h>
= 
#ifdef ASSISTENT_OLED
#include "DisplayOledHelper.h"
#endif
#ifdef ASSISTENT_OTA
#include <ESP8266httpUpdate.h>
#include <FS.h>
#endif

class AssistentVariable
{

    String obj;

public:
    AssistentVariable(String *NameVar, String *Date, int size)
    {
        obj = "{";

        for (int i = 0; i < size; i++)
        {
            if (i == 0)
                obj += "\"" + NameVar[i] + "\":\"" + Date[i] + "\"";
            else
                obj += ",\"" + NameVar[i] + "\":\"" + Date[i] + "\"";
        }
        obj += "}";
    }

    String GetStringVal()
    {
        return obj;
    }
};
typedef void (*OnNewMessageFromServer)(String message);
typedef void (*HandlerCMD)();
typedef AssistentVariable (*HandlerCMDRec)();

class AssistenWiFi
{

    unsigned long lastExecutionTime = 0;
    char *hostname = "";
    String PlatName;
    ESP8266WebServer webServer;
    MyAes myAes;
    String *CMD;
    HandlerCMD *HandlerCMDS;
    int SizeCMD;
    String *CMDRec;
    HandlerCMDRec *HandlerCMDSRec;
    int SizeCMDRec;
    OnNewMessageFromServer handler;
    WiFiClient wifiClient;
    bool UsingStandartHandler = true;
    bool ipLoaded = false;
    char server_ip[16] = "\0";
#ifdef ASSISTENT_OLED
    DisplayOledHelper display;
#endif

private:
    String StandartHandler(String str, bool &flag);
    void hexStringToByteArray(const String &hexStr, byte *byteArray);
    String decryptMessageFromJSON(String jsonMessage);
    String ThisStandartCommand(String str);
    String SendPostRequest(const char *url, const char *jsonPayload = nullptr);
    bool ValidateIP();
    void LoadIpFromEprom(String SetIp = "");
#ifdef ASSISTENT_OTA
    void ReadOTA();
#endif
    void clear_eprom();
    void ASSISTENT_debug(String Text);
    void ASSISTENT_debugln(String Text);
    void WriteOled(String Text);

public:
    AssistenWiFi()
        : webServer(80)
    {
    }
    void Begin(String name, String *CMD, HandlerCMD *HandlerCMDS, int SizeCMD, String *CMDRec, HandlerCMDRec *HandlerCMDSRec, int SizeCMDRec, char *ssid, char *password, int BhaudRate = 9600, OnNewMessageFromServer handler = NULL);
    void Reader();
    void Handle();
    void IoTMessage(AssistentVariable data);
};
#endif