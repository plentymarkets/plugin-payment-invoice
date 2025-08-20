# Release Notes für Kauf auf Rechnung

## 2.0.13

### Behoben
- Error when missing settings for a specific client.

## 2.0.12

### Behoben
- Fehler beim Speichern der Einstellungen

## 2.0.11

### Behoben
- PHP 8 Problem im Asssistenten.

## 2.0.10 

### Behoben
- Die Verlinkung im User Guide wurde angepasst.

## 2.0.9

### Behoben
- Die URL der internen Infoseite wird nun für Fremdsprachen korrekt erzeugt.

## 2.0.8

### Behoben
- Fehler wenn Einstellungen für einen speziellen Kunden fehlen.

## 2.0.7 

### Behoben
- Beim Abschluss des Assistenten wird nun auch die alte Rechnungs-Zahlungsart aktiviert.

## 2.0.6 

### Geändert
- Icon für das Backend hinzugefügt

### Behoben
- Die Einstellung "Rechnungsadresse gleich Lieferadresse" wird nun korrekt angewendet.

## 2.0.5

### Geändert
- Performance-Optimierung für den Ladevorgang der Plugin-Einstellungen und verfügbaren Lieferländer.

## 2.0.4 

### Behoben
- Alle Einstellungen / Beschränkungen werden nun korrekt abgefragt.

## 2.0.3

### Behoben
- Der Mindest- und Maximalbetrag wird nun korrekt geprüft, wenn der Kunde die Zahlungsart in Mein Konto ändern will.

## 2.0.2 

### Geändert
- Funktionalitäten hinzugefügt für Backend-Sichtbarkeiten und Backend-Name

## 2.0.1 

### Behoben
- Der Mindest- und Maximalbetrag wird nun korrekt in der Systemwährung angezeigt statt pauschal "Euro" zu nehmen.
- Der Mindest- und Maximalbetrag wird nun korrekt in die Warenkorbwährung umgerechnet, bevor dieser mit dem Bestellwert verglichen wird. 

## 2.0.0 

### Hinweis 
- Die Einstellungen für das Kauf auf Rechnung-Plugin wurden in einen Assistenten überführt und sind nun unter **Einrichtung » Assistenten » Payment** zu finden.

### Geändert
- Die Beschreibung und der Name der Zahlungsart wird nun auch über **CMS » Mehrsprachigkeit** gepflegt.

## 1.3.4 

### Geändert
- Der User Guide wurde aktualisiert.

## 1.3.3 

### Geändert
- Die Einstellungen für Lieferländer wurden optimiert.

## 1.3.2 

### Behoben
- Ein eventuell auftretendes Problem beim Bereitstellen des Plugins wurde behoben.

## 1.3.1 

### Geändert
- Support-Informationen wurden ergänzt.

## 1.3.0 

### Hinzugefügt
- Die Auftrags-ID kann direkt im Verwendungszweck angezeigt werden. Dafür muss der Platzhalter **%s** im Textfeld der Plugin-Einstellungen eingegeben werden.

## 1.2.4 

### Hinzugefügt
- Weitere Sprachen für die Plugin-UI wurden hinzugefügt.
- Sprachabhängige Texte können nun über das Mehrsprachigkeits-Interface angepasst werden.

## 1.2.1 

### Behoben
- Die Einstellungen `Mindestanzahl an Bestellungen` wird nun im Checkout korrekt überprüft.
- Problem nach dem Ausloggen tritt nicht mehr auf.

## 1.2.0 

### Hinzugefügt
- "Rechnung erlauben" am Kundenstamm wird nun im Checkout berücksichtigt.

## 1.1.8 

### Geändert
- Der User Guide wurde aktualisiert.

## 1.1.7 

### Geändert
- Neuer Menüpfad **System&nbsp;» Aufträge&nbsp;» Zahlung » Plugins » Rechnung**.

## 1.1.6 

### Behoben
- Die Einstellungen `Kauf auf Rechnung für Gastbestellungen verbieten` und `Rechnungsadresse gleich Lieferadresse` werden nun im Checkout korrekt überprüft.

## 1.1.5 

### Behoben
- Die Variable `$MethodOfPaymentName` in E-Mail-Vorlagen wird nun sprachabhängig ausgegeben.

## 1.1.4 

### Geändert
- Der User Guide wurde aktualisiert.

## 1.1.3 

### Geändert
- Der Changelog wurde aktualisiert.

## 1.1.2 

### Geändert
- Der Einhängepunkt im Systembaum ist nun **System » Aufträge » Zahlung » Rechnung**.

## 1.1.1 

### Behoben
- Prüfung, ob die Zahlungsart gewechselt werden darf, funktioniert wieder korrekt.

## 1.1.0 

### Hinzugefügt
- Einstellungen für **Infoseite** wurden hinzugefügt.
- Einstellungen für **Beschreibung** wurden hinzugefügt.

### Geändert
- Aufpreise der Zahlungsart wurden entfernt.

## 1.0.3 

### Hinzugefügt
- Es wurde eine Methode hinzugefügt, um festzulegen, ob ein Kunde von dieser Zahlungsart auf eine andere wechseln kann.
- Es wurde eine Methode hinzugefügt, um festzulegen, ob ein Kunde von einer anderen Zahlungsart auf diese wechseln kann.

### Behoben
- Es wird nun der korrekte Pfad für die Anzeige des Logos der Zahlungsart verwendet.

## 1.0.2 

### Behoben
- Das CSS der Einstellungen im Backend wurde angepasst, so dass die Einstellungen nun über die ganze Seitenbreite angezeigt werden.

### Bekannte Probleme
- Die Einstellungen für **Aufpreise** haben derzeit noch keine Funktion bei der Preisberechnung in der Kaufabwicklung (Checkout)

## 1.0.1 

### Geändert
- Es wird die ID der Zahlungsart "Rechnung" aus dem System verwendet.

## 1.0.0 

### Funktionen
- Zahlungsart **Rechnung** für plentymarkets Webshops
- Anzeige von Verwendungszweck und Bankdaten auf der Bestellbestätigungsseite
