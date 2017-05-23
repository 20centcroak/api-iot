#include <ESP8266WiFi.h>

// valeurs pour le WiFi
const char* ssid     = "";        // Nom du réseau
const char* password = "";        // clef 

// valeurs pour le serveur Web
const char* host     = "api-iot.croak.fr";

void setup() {
  Serial.begin(115200);
  delay(10);

  // We start by connecting to a WiFi network

  Serial.print("Connexion au WiFi ");
  Serial.println(ssid);
  
  WiFi.begin(ssid, password);    // On se connecte
  
  while (WiFi.status() != WL_CONNECTED) { // On attend
    delay(500);
    Serial.print(".");
  }

  Serial.println("");  // on affiche les paramètres 
  Serial.println("WiFi connecté");  
  Serial.print("Adresse IP du module EPC: ");  
  Serial.println(WiFi.localIP());
  Serial.print("Adresse IP de la box : ");
  Serial.println(WiFi.gatewayIP());
}


void loop() {
  float temperature = 32.3;

  Serial.print("Connexion au serveur : ");
  Serial.println(host);
  
  // On se place dans le rôle du  client en utilisant WifiClient
  WiFiClient client;

  // le serveur Web attend tradionnellement sur le port 80
  const int httpPort = 80;

  // Si la connexion échoue ca sera pour la prochaine fois
  if (!client.connect(host, httpPort)) {
    Serial.println("connection failed");
    return;
  }

  
  // La connexion a réussie on forme le chemin 
  // URL  complexe composé du chemin et de deux 
  // questions contenant le nom de ville et l'API key
  
  String url = String("/src/index.php/add/0000");
  
  Serial.print("demande URL: ");
  Serial.println(url);
  
//  client.print(String("GET ") + url + " HTTP/1.1\r\n" +  "Host: " + host + "\r\n" + "Connection: close\r\n\r\n");
  client.print(String("PUT ") + url + " HTTP/1.1\r\n" +  "Host: " + host + "\r\n" + "Connection: Keep-Alive\r\n" + "Content-Type: application/x-www-form-urlencoded\r\n" + "Content-Length: 15\r\n\r\n" + "{\"temp\":33.5}");
 
  //client.println("PUT /src/index.php/profile/0000 HTTP/1.1");
//  client.print("Host: api-iot.croak.fr\r\n");                         
//  client.print("Connection: close\r\n\r\n");
//  client.println("Content-Type: application/x-www-form-urlencoded");
//  client.println("Content-Length: 10\r\n");
//  client.print("{\"on\":false}"); 

  // On attend 1 seconde
  delay(10000);

  
  // On lit les données reçues, s'il y en a
  while(client.available()){
    String line = client.readStringUntil('\r');
    Serial.println (line);

  } /* fin data avalaible */

  //Serial.println("connexion fermée");
  Serial.println("end");
  // On attend 1 seconde
  delay(10000);
}
