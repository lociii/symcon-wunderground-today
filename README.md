# UNMAINTAINED
This project is not maintained anymore. Feel free to fork and use it.
No support nor development will be done!

# Weather Underground today
Ermittlung von Temperatur, Feuchtigkeit, Windgeschwindigkeit und Niederschlagsvorhersage fuer den laufenden Tag an einem beliebigen Standort (ZMW)

### Inhaltverzeichnis

1. [Funktionsumfang](#1-funktionsumfang)
2. [Einrichten der Instanzen in IP-Symcon](#2-einrichten-der-instanzen-in-ip-symcon)
3. [PHP-Befehlsreferenz](#3-php-befehlsreferenz)

### 1. Funktionsumfang

* Wetterdaten des aktuellen Tages (Temperatur, Feuchtigkeit, Windgeschwindigkeit und Niederschlagsvorhersage) auslesen und in Variablen ablegen

### 2. Einrichten der Instanzen in IP-Symcon

* Unter 'Instanz hinzufuegen das 'WeatherUndergroundToday'-Modul auswaehlen und eine neue Instanz erzeugen
* Wunderground Weather API Key angeben (siehe https://www.wunderground.com/weather/api)
* ZMW code des Standortes angeben (zu finden auf der Detailseite eines Ortes beim Klick auf "Change station", der Code steht rechts neben der Wetterstation)
* Aktualisierungsinterval waehlen (maximal 500 Requests am Tag, das Intervall sollte also maximal auf 3 Minuten reduziert werden, wenn nur eine Instanz aktiv ist)

### 3. PHP-Befehlsreferenz

`LOCIWUT_Update(id);`
Aktualisiert die Werte der Instanz
