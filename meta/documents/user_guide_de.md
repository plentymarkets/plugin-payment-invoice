# plentymarkets Payment – Rechnung

Mit diesem Plugin binden Sie die Zahlungsart **Rechnung** in Ihren Webshop ein.

## Zahlungsart einrichten

Bevor die Zahlungsart in Ihrem Webshop verfügbar ist, müssen Sie Einstellungen in Ihrem plentymarkets Backend vornehmen.

##### Zahlungsart einrichten:

1. Öffnen Sie das Menü **Einstellungen&nbsp;» Aufträge&nbsp;» Rechnung**.
2. Wählen Sie einen Mandanten.
3. Nehmen Sie die Einstellungen vor. Beachten Sie dazu die Erläuterungen in Tabelle 1.
4. **Speichern** Sie die Einstellungen.

<table>
<caption>Tab. 1: Einstellungen für die Zahlungsart vornehmen</caption>
	<thead>
		<th>
			Einstellung
		</th>
		<th>
			Erläuterung
		</th>
	</thead>
	<tbody>
		<tr>
		<td class="th" align=CENTER colspan="2">Allgemein</td>
		</tr>
		<tr>
			<td>
				<b>Infoseite</b>
			</td>
			<td>
				Als <a href="https://www.plentymarkets.eu/handbuch/payment/bankdaten-verwalten/#2-2"><strong>Information zur Zahlungsart</strong></a> eine Kategorieseite vom Typ <strong>Content</strong> anlegen oder die URL einer Webseite eingeben.
			</td>
		</tr>
		<tr>
			<td>
				<b>Anzeigename</b>
			</td>
			<td>
				Die Bezeichnung, die in der Übersicht der Zahlungsarten in der Kaufabwicklung für die Zahlung per Rechnung angezeigt wird.
			</td>
		</tr>
		<tr>
			<td>
				<b>Logo</b>
			</td>
			<td>
			Eine https-URL, die zum Logo-Bild führt. Gültige Dateiformate sind .gif, .jpg oder .png. Die Maximalgröße beträgt 190 Pixel in der Breite und 60 Pixel in der Höhe.
			</td>
		</tr>
		<tr>
		<td class="th" align=CENTER colspan="2">Weitere Einstellungen</td>
		</tr>
		<tr>
			<td>
				<b>Lieferländer</b>
			</td>
			<td>
				Nur für die hier eingestellten Lieferländer ist diese Zahlungsar freigegeben.
			</td>
		</tr>
		<tr>
			<td>
				<b>Aufpreis Webshop</b>
			</td>
			<td>
Wenn bei der Zahlung mit dieser Zahlungsart zusätzliche Kosten berechnet werden, den Prozentwert oder Pauschalwert gemäß der Vertragskonditionen eingeben.<br />
     
<strong>Inland (Pauschal):</strong> Pauschalen Wert eingeben, der bei Aufträgen berücksichtigt wird, bei denen das Systemland gewählt wurde. Diese Kosten werden im Bestellvorgang bei der Wahl der Zahlungsart zum Auftrag addiert. Der Betrag fließt in die Gesamtsumme des Auftrags ein und wird nicht einzeln ausgewiesen.<br />

<strong>Inland (Prozentual):</strong> Prozentualen Wert eingeben, der bei Aufträgen berücksichtigt wird, bei denen das Systemland gewählt wurde.<br />
   
<strong>Ausland (Pauschal):</strong> Pauschalen Wert eingeben, der bei Aufträgen berücksichtigt wird, bei denen nicht das Systemland gewählt wurde. Diese Kosten werden im Bestellvorgang bei der Wahl der Zahlungsart zum Auftrag addiert. Der Betrag fließt in die Gesamtsumme des Auftrags ein und wird nicht einzeln ausgewiesen.<br />

<strong>Ausland (Prozentual):</strong> Prozentualen Wert eingeben, der bei Aufträgen berücksichtigt wird, bei denen nicht das Systemland gewählt wurde.<br />

<strong><i>Wichtig:</i></strong> Nicht in beide Felder (Pauschal und Prozentual) einen Wert eingeben.
		</tr>
	</tbody>
</table>

## Logo der Zahlungsart auf der Startseite anzeigen

Das Template-Plugin **Ceres** bietet Ihnen auf der Startseite einen Template-Container, in dem Sie die Logos Ihrer Zahlungsart anzeigen können. Gehen Sie wie im Folgenden beschrieben vor, um das Logo der Zahlungsart zu verknüpfen.

##### Logo mit Template-Container verknüpfen:

1. Klicken Sie auf **Start&nbsp;» Plugins**.
2. Wechseln Sie in das Tab **Content**. 
3. Wählen Sie den Bereich **Invoice icon**.
4. Aktivieren Sie den Container **Homepage: Payment method container**.
5. **Speichern** Sie die Einstellungen.<br />→ Das Logo der Zahlungsart wird auf der Startseite des Webshops angezeigt.