# Release Notes für Kauf auf Rechnung

## 2.0.6 (2020-15-07)
### Geändert
- Icon für das Backend hinzugefügt

### Gefixt
- Die Einstellung "Rechnungsadresse gleich Lieferadresse" wird nun korrekt angewendet.

## 2.0.5 (2020-04-03)
### Geändert
- Performance-Optimierung für den Ladevorgang der Plugin-Einstellungen und verfügbaren Lieferländer.

## 2.0.4 (2020-02-25)
### Gefixt
- Alle Einstellungen / Beschränkungen werden nun korrekt abgefragt.

## 2.0.3 (2020-02-10)
### Gefixt
- Der Mindest- und Maximalbetrag wird nun korrekt geprüft, wenn der Kunde die Zahlungsart in Mein Konto ändern will.

## 2.0.2 (2019-12-17)
### Geändert
- Funktionalitäten hinzugefügt für Backend-Sichtbarkeiten und Backend-Name

## 2.0.1 (2019-11-28)

### Gefixt
- Der Mindest- und Maximalbetrag wird nun korrekt in der Systemwährung angezeigt statt pauschal "Euro" zu nehmen.
- Der Mindest- und Maximalbetrag wird nun korrekt in die Warenkorbwährung umgerechnet, bevor dieser mit dem Bestellwert verglichen wird. 

## 2.0.0 (2019-11-18)

### Hinweis 
- Die Einstellungen für das Kauf auf Rechnung-Plugin wurden in einen Assistenten überführt und sind nun unter **Einrichtung » Assistenten » Payment** zu finden.

### Geändert
- Die Beschreibung und der Name der Zahlungsart wird nun auch über **CMS » Mehrsprachigkeit** gepflegt.

## 1.3.4 (2019-03-22)

### Geändert
- Der User Guide wurde aktualisiert.

## 1.3.3 (2019-02-25)

### Geändert
- Die Einstellungen für Lieferländer wurden optimiert.

## 1.3.2 (2018-10-23)

### Gefixt
- Ein eventuell auftretendes Problem beim Bereitstellen des Plugins wurde behoben.

## 1.3.1 (2018-10-04)

### Geändert
- Support-Informationen wurden ergänzt.

## 1.3.0 (2018-09-18)

### Hinzugefügt
- Die Auftrags-ID kann direkt im Verwendungszweck angezeigt werden. Dafür muss der Platzhalter **%s** im Textfeld der Plugin-Einstellungen eingegeben werden.

## 1.2.4 (2018-08-06)

### Hinzugefügt
- Weitere Sprachen für die Plugin-UI wurden hinzugefügt.
- Sprachabhängige Texte können nun über das Mehrsprachigkeits-Interface angepasst werden.

## 1.2.1 (2018-04-23)

### Gefixt
- Die Einstellungen `Mindestanzahl an Bestellungen` wird nun im Checkout korrekt überprüft.
- Problem nach dem Ausloggen tritt nicht mehr auf.

## 1.2.0 (2018-04-20)

### Hinzugefügt
- "Rechnung erlauben" am Kundenstamm wird nun im Checkout berücksichtigt.

## 1.1.8 (2018-01-26)

### Geändert
- Der User Guide wurde aktualisiert.

## 1.1.7 (2018-01-09)

### Geändert
- Neuer Menüpfad **System&nbsp;» Aufträge&nbsp;» Zahlung » Plugins » Rechnung**.

## 1.1.6 (2017-11-30)

### Gefixt
- Die Einstellungen `Kauf auf Rechnung für Gastbestellungen verbieten` und `Rechnungsadresse gleich Lieferadresse` werden nun im Checkout korrekt überprüft.

## 1.1.5 (2017-11-23)

### Gefixt
- Die Variable `$MethodOfPaymentName` in E-Mail-Vorlagen wird nun sprachabhängig ausgegeben.

## 1.1.4 (2017-17-22)

### Geändert
- Der User Guide wurde aktualisiert.

## 1.1.3 (2017-11-14)

### Geändert
- Der Changelog wurde aktualisiert.

## 1.1.2 (2017-10-26)

### Geändert
- Der Einhängepunkt im Systembaum ist nun **System » Aufträge » Zahlung » Rechnung**.

## 1.1.1 (2017-08-30)

### Gefixt
- Prüfung, ob die Zahlungsart gewechselt werden darf, funktioniert wieder korrekt.

## 1.1.0 (2017-07-31)

### Hinzugefügt
- Einstellungen für **Infoseite** wurden hinzugefügt.
- Einstellungen für **Beschreibung** wurden hinzugefügt.

### Geändert
- Aufpreise der Zahlungsart wurden entfernt.

## 1.0.3 (2017-07-13)

### Hinzugefügt
- Es wurde eine Methode hinzugefügt, um festzulegen, ob ein Kunde von dieser Zahlungsart auf eine andere wechseln kann.
- Es wurde eine Methode hinzugefügt, um festzulegen, ob ein Kunde von einer anderen Zahlungsart auf diese wechseln kann.

### Gefixt
- Es wird nun der korrekte Pfad für die Anzeige des Logos der Zahlungsart verwendet.

## 1.0.2 (2017-03-15)

### Gefixt
- Das CSS der Einstellungen im Backend wurde angepasst, so dass die Einstellungen nun über die ganze Seitenbreite angezeigt werden.

### Bekannte Probleme
- Die Einstellungen für **Aufpreise** haben derzeit noch keine Funktion bei der Preisberechnung in der Kaufabwicklung (Checkout)

## 1.0.1 (2017-03-14)

### Geändert
- Es wird die ID der Zahlungsart "Rechnung" aus dem System verwendet.

## 1.0.0 (2017-03-10)

### Funktionen
- Zahlungsart **Rechnung** für plentymarkets Webshops
- Anzeige von Verwendungszweck und Bankdaten auf der Bestellbestätigungsseite
