#include "Assistent.h"
void AssistenWiFi::LoadIpFromEprom(String SetIp)
{
    if (ipLoaded)
        return;
    if (!ValidateIP())
    {
        EEPROM.get(0, server_ip);

        if (!ValidateIP())
        {

            strncpy(server_ip, SetIp.c_str(), sizeof(server_ip) - 1);
            ASSISTENT_debugln(server_ip);

            EEPROM.put(0, server_ip);
            EEPROM.commit();
        }
        else
            ipLoaded = true;
    }
    else
        ipLoaded = true;
}

bool AssistenWiFi::ValidateIP()
{
    int determinator = 0;
    for (int i = 0, j = 0; i < 16; i++)
    {
        if (server_ip[i] == '\0')
            break;
        else if (server_ip[i] == '.' && j == 0)
            return false;
        else if (server_ip[i] == '.')
        {
            determinator++;
            j = 0;
        }
        else if (!isdigit(server_ip[i]))
            return false;
        else if (isdigit(server_ip[i]) && j == 3)
            return false;
        else if (isdigit(server_ip[i]))
        {
            j++;
        }
    }
    if (determinator != 3)
        return false;
    return true;
}
void AssistenWiFi::clear_eprom()
{

    EEPROM.put(0, "FREEEPROM");
    EEPROM.commit();
}
String AssistenWiFi::StandartHandler(String str, bool &flag)
{
    ASSISTENT_debug("IP EPROM Start");
    ASSISTENT_debugln(server_ip);

    flag = true;

    String ipStr = webServer.client().remoteIP().toString();
    LoadIpFromEprom(ipStr.c_str());
    ASSISTENT_debug("IP Client ");
    ASSISTENT_debugln(ipStr.c_str());
    ASSISTENT_debug("IP EPROM ");
    ASSISTENT_debugln(server_ip);

    if (!(ipStr == String(server_ip)))
    {
        flag = false;
        return "Error rebind plate! Unauthorizted";
    }
    String response = ThisStandartCommand(str);

    if (response.length() != 0)
        return response;

    for (int i = 0; i < SizeCMD; i++)
        if (CMD[i] == str)
        {
            HandlerCMDS[i]();
            return "Succses";
        }

    for (int i = 0; i < SizeCMDRec; i++)
        if ((CMDRec[i] + "_REC") == str)
            return HandlerCMDSRec[i]().GetStringVal();
    ASSISTENT_debugln("END StandartHandler");

    flag = false;
    return "Not find command [" + str + "]";
}

void AssistenWiFi::hexStringToByteArray(const String &hexStr, byte *byteArray)
{
    for (int i = 0; i < hexStr.length(); i += 2)
    {
        String byteString = hexStr.substring(i, i + 2);
        byteArray[i / 2] = strtol(byteString.c_str(), NULL, 16); // Преобразуем 2 символа hex в байт
    }
}
#ifdef ASSISTENT_OTA
void AssistenWiFi::ReadOTA()
{
    String url = webServer.arg("url");
    ASSISTENT_debugln("Пришл URL: " + url);
    webServer.send(200, "text/plain", "Update started");
    t_httpUpdate_return ret = ESPhttpUpdate.update(wifiClient, url);
    ;
    ASSISTENT_debugln("Начало OTA обновления...");
    WriteOled("Начало OTA");
    switch (ret)
    {
    case HTTP_UPDATE_FAILED:
        ASSISTENT_debugln("No Updates Available");
        WriteOled("Ошибка обновления.");

        webServer.send(500, "text/plain", "Update Failed");
        break;
    case HTTP_UPDATE_NO_UPDATES:
        ASSISTENT_debugln("No Updates Available");
        WriteOled("Ошибка обновления.");

        webServer.send(200, "text/plain", "No Updates Available");
        break;
    case HTTP_UPDATE_OK:
        ASSISTENT_debugln("Обновление успешно, перезагрузка...");
        WriteOled("Обновление успешно");
        webServer.send(200, "text/plain", "Update Successful");
        delay(100);
        ESP.restart(); // Перезагружаем ESP после успешного обновления
        break;
    }
}
#endif
void AssistenWiFi::ASSISTENT_debugln(String Text)
{
#ifdef ASSISTENT_DEBUG

    Serial.println(Text);
#endif
}
void AssistenWiFi::ASSISTENT_debug(String Text)
{
#ifdef ASSISTENT_DEBUG
    Serial.print(Text);
#endif
}
void AssistenWiFi::WriteOled(String Text)
{
#ifdef ASSISTENT_OLED

    display.print(Text);
#endif
}
String AssistenWiFi::ThisStandartCommand(String str)
{
    if (strcmp(str.c_str(), "SERV_GAI") == 0)
    {

        ASSISTENT_debug("DEBUG : ");
        String msg(this->PlatName);

        // Command non send message
        for (int i = 0; i < SizeCMD; i++)
        {
            ASSISTENT_debugln(CMD[i]);
            msg += "." + CMD[i];
        }

        // Command send result
        for (int i = 0; i < SizeCMDRec; i++)
        {
            ASSISTENT_debugln(CMDRec[i]);
            msg += "." + CMDRec[i] + "_REC";
        }

        return msg;
    }

    else if (strcmp(str.c_str(), "SERV_GP") == 0)
    {
        ASSISTENT_debug("DEBUG : ");
        ASSISTENT_debug(str);
        ASSISTENT_debug(" = ");
        ASSISTENT_debugln("SERV_GP");
        return "Arduino";
    }
    else
    {
        return "";
    }
}

void AssistenWiFi::Begin(String name, String *CMD, HandlerCMD *HandlerCMDS,
                         int SizeCMD, String *CMDRec, HandlerCMDRec *HandlerCMDSRec, int SizeCMDRec,
                         char *ssid, char *password, int BhaudRate, OnNewMessageFromServer handler)
{
    EEPROM.begin(1000);
#ifdef ASSISTENT_OLED
    display.begin();
#endif

    this->CMD = CMD;
    this->HandlerCMDS = HandlerCMDS;
    this->SizeCMD = SizeCMD;
    this->CMDRec = CMDRec;
    this->HandlerCMDSRec = HandlerCMDSRec;
    this->SizeCMDRec = SizeCMDRec;
#ifdef ASSISTENT_DEBUG
    Serial.begin(BhaudRate);
#endif

    PlatName = name;
    WiFi.begin(ssid, password);
    ASSISTENT_debug("Connecting");
    WriteOled("Connecting");

    while (WiFi.status() != WL_CONNECTED)
    {
        delay(1000);
        ASSISTENT_debug(" .");
    }
    ASSISTENT_debug("\nConnected to WiFi.\n SSID:");
    WriteOled("Connect to " + (String)ssid);

    ASSISTENT_debugln(ssid);
    ASSISTENT_debug("IP:");
    ASSISTENT_debugln(WiFi.localIP().toString());

    WriteOled("IP: " + WiFi.localIP().toString());

    // SetupHandler
    if (handler == NULL)
    {
        this->handler = handler;
        UsingStandartHandler = false;
    }
    webServer.on("/command", HTTP_POST, [this]()
                 { this->Reader(); });
    webServer.on("/clear", HTTP_POST, [this]()
                 { this->clear_eprom(); });
#ifdef ASSISTENT_OTA
    webServer.on("/ota", HTTP_POST, [this]()
                 { this->ReadOTA(); });
#endif
    webServer.begin();
}

void AssistenWiFi::IoTMessage(AssistentVariable data)
{

    unsigned long currentMillis = millis();

    if (currentMillis - lastExecutionTime >= 10000)
    {
        lastExecutionTime = currentMillis;

        String jsonResponse = "{" + myAes.encryptMessage(data.GetStringVal()) + ",\"name\":\"" + PlatName + "\"}";
        LoadIpFromEprom();
        if (ValidateIP())
            SendPostRequest(("http://" + String(server_ip) + "/iot/receive").c_str(), jsonResponse.c_str());
    }
}

String AssistenWiFi::SendPostRequest(const char *url, const char *jsonPayload)
{
    if (WiFi.status() == WL_CONNECTED)
    {
        HTTPClient http;
        ASSISTENT_debugln(url);
        if (http.begin(wifiClient, url))
        {
            http.addHeader("Content-Type", "application/json");

            int httpCode;
            if (jsonPayload)
            {
                httpCode = http.POST(jsonPayload);
            }
            else
            {
                httpCode = http.POST("");
            }
            if (httpCode > 0)
            {
                String response = http.getString();
                ASSISTENT_debugln("Response from server: " + response);
                http.end();
                return response;
            }
            else
            {
                Serial.printf("Error on POST request, HTTP code: %d\n", httpCode);
                http.end();
                return "";
            }
        }
        else
        {
            ASSISTENT_debugln("Unable to connect to the URL");
            return "";
        }
    }
    else
    {
        ASSISTENT_debugln("WiFi not connected");
        return "";
    }
}
void AssistenWiFi::Reader()
{
    if (webServer.hasArg("plain"))
    {

        String requestBody = webServer.arg("plain");
        bool responce_status = true;
        String responce = StandartHandler(decryptMessageFromJSON(requestBody), responce_status);

        if (!responce_status)
        {
            webServer.send(400, "application/json", "{\"error\":\"" + responce + "\"}");
            return;
        }

        String msg = myAes.encryptMessage(responce);
        String jsonResponse = "{" + msg + "}";
        webServer.send(200, "application/json", jsonResponse);
    }
    else
    {
        webServer.send(400, "application/json", "{\"error\":\"No data received\"}");
    }
}
void AssistenWiFi::Handle()
{
    webServer.handleClient();
}

String AssistenWiFi::decryptMessageFromJSON(String jsonMessage)
{
    DynamicJsonDocument doc(1024);
    deserializeJson(doc, jsonMessage);
    const char *encryptedMessage = doc["command"];
    String IV = doc["IV"];
    byte AESIV[16];
    myAes.hexStringToByteArray(IV, AESIV, 16);
    ASSISTENT_debug("Message: ");
    ASSISTENT_debugln(encryptedMessage);
    String decryptedMessage = myAes.decryptMessage(encryptedMessage, AESIV);

    ASSISTENT_debugln("Decrypted message: " + decryptedMessage);
    WriteOled("Message" + decryptedMessage);
    return decryptedMessage;
}