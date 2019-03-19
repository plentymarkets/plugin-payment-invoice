# plentymarkets Payment&nbsp;– Invoice

With this plugin, you integrate the payment method **Invoice** into your online store.

## Setting up a payment method

To make the payment method available in your online store, you have to carry out settings in the back end of your plentymarkets system.

First of all, activate the payment method once in the **System » System Settings » Orders » Payment » Methods** menu. More information on carrying out this setting is available on the <strong><a href="https://knowledge.plentymarkets.com/en/payment/managing-payment-methods#20" target="_blank">Managing payment methods</a></strong> page of the manual.

In addition, make sure that the payment method is included among the Permitted payment methods in the <strong><a href="https://knowledge.plentymarkets.com/en/crm/managing-contacts#15" target="_blank">customer classes</a></strong> and that it is not listed among the Blocked payment methods in the <strong><a href="https://knowledge.plentymarkets.com/en/order-processing/fulfilment/preparing-the-shipment#1000" target="_blank">shipping profiles</a></strong>.

##### Setting up a payment method:

1. Go to **System&nbsp;» System settings » Orders&nbsp;» Payment » Plugins » Invoice**.
2. Select a Client (store).
3. Carry out the settings. Pay attention to the information given in table 1.
4. **Save** the settings.

<table>
<caption>Table 1: Carrying out settings for the payment method</caption>
	<thead>
		<th>
			Setting
		</th>
		<th>
			Explanation
		</th>
	</thead>
	<tbody>
        <tr>
			<td>
				<b>Language</b>
			</td>
			<td>
				Select a language. Other settings, such as name, info page, etc., will be saved depending on the selected language.
			</td>
		</tr>
        <tr>
			<td>
				<b>Name</b>
			</td>
			<td>
				The name of the payment method that will be displayed in the overview of payment methods in the checkout.
			</td>
		</tr>
		<tr>
			<td>
				<b>Info page</b>
			</td>
			<td>
				Select a category page of the type <strong>content</strong> or an external website to provide <strong><a href="https://knowledge.plentymarkets.com/en/payment/managing-bank-details#40">information about the payment method</a></strong>.
			</td>
		</tr>
		<tr>
			<td>
				<b>Info page internal/<br />Info page external</b>
			</td>
			<td>In the description of the payment method, a link to the <strong>details</strong> of the payment method is displayed.<br /><strong>Infopage (internal):</strong> By entering the category ID or using the selector, pick a category page of the type <strong>content</strong> to provide additional information on the payment method.<br /><strong>Info page (external):</strong> Enter the URL of an external information page. <strong><i>Important:</i></strong>Use either http:// or https://.<br />If no input is made, no link will be shown.
			</td>
		</tr>
        <tr>
			<td>
				<b>Logo</b>
			</td>
			<td>
			Select if the <strong>Standard logo</strong> of the payment method provided by the plugin or an individual logo is displayed.
			</td>
		</tr>				
		<tr>
			<td>
				<b>Logo-URL</b>
			</td>
			<td>
			An https URL that leads to the logo. Valid file formats are .gif, .jpg or .png. The image may not exceed a maximum size of 190 pixels in width and 60 pixels in height.
			</td>
		</tr>
		<tr>
			<td>
				<b>Description</b>
			</td>
			<td>
				Enter a description for the payment method to inform the customer in the checkout. The text will be cut with an ellipsis after 150 characters.
			</td>
		</tr>
		<tr>
			<td>
				<b>Countries of delivery</b>
			</td>
			<td>
				This payment method is active only for the countries in this list.
			</td>
		</tr>
		<tr>
			<td colspan="2" class="th">Display data</td>  
		</tr>
		<tr>
			<td>
				<b>Minimum number of orders</b>
			</td>  
			<td>
Enter a minimum number of orders to be completed by the customer with a different payment method before allowing the purchase on invoice.
			</td>
		</tr>
		<tr>
			<td>
				<b>Minimum amount for purchase on invoice</b>
			</td>  
			<td>
			Enter the amount, that must be exceeded when purchasing on invoice.
			</td>
		</tr>
		<tr>
			<td>
				<b>Maximum invoice amount</b>
			</td>  
			<td>
			Enter the amount, that may not be exceeded when purchasing on invoice.
			</td>
		</tr>
		<tr>
			<td>
				<b>Designated use</b>
			</td>  
			<td>
			Enter designated use for purchase on invoice. Use the placeholder <code>%s</code> to display the order ID.
			</td>
		</tr>
		<tr>
			<td>
				<b>Show designated use</b>
			</td>  
			<td>
			Activate to display the designated use on the order confirmation page. This information must be linked with the respective <a href="#10."><strong>template container</strong></a>.
			</td>
		</tr>
		<tr>
			<td>
				<b>Show bank details</b>
			</td>  
			<td>
			Activate to display the bank details on the order confirmation page. Go to <strong>Settings » Basic settings » Bank</strong> to save bank details.
			</td>
		</tr>
		<tr>
			<td>
				<b>Invoice address and delivery address must match</b>
			</td>  
			<td>
			Activate if the invoice address and the delivery address must match when purchasing on invoice.
			</td>
		</tr>
		<tr>
			<td>
				<b>Do not allow purchase on invoice for guest accounts</b>
			</td>  
			<td>
			Activate to allow the purchase on invoice for registered and logged in users only.
			</td>
		</tr>
	</tbody>
</table>

## Displaying the logo of the payment method on the homepage

The template plugin **Ceres** allows you to display the logo of your payment method on the homepage by using template containers. Proceed as described below to link the logo of the payment method.

##### Linking the logo with a template container:

1. Go to **CMS » Container links**.
3. Click on **Invoice icon**.
4. Activate the container **Homepage: Payment method container**.
5. **Save** the settings.<br />→ The logo of the payment method will be displayed on the homepage of the online store.

## Displaying the bank details on the order confirmation page <a id="10." name="10."></a>

Proceed as follows to display the bank details that are saved in the system as well as a designated use on the order confirmation page.

##### Displaying bank details:

1. Go to **System&nbsp;» System settings » Orders&nbsp;» Payment » Plugins » Invoice**.
2. Select a Client (store).
3. Enter the **Designated use** in the **Display data** area.
4. Activate the option **Show designated use**.
5. Activate the option **Show bank details**.
6. **Save** the settings.

After these settings, link the bank details with a template container.

##### Linking the bank details with a template container:

1. Go to **CMS » Container links**.
2. Click on **Invoice bank details**.
3. Activate the container **Order confirmation: Additional payment information**.
4. **Save** the settings.<br />→ The bank details will be displayed on the order confirmation page.

## Generating invoice documents

Learn how to create [templates for invoice documents](https://knowledge.plentymarkets.com/en/orders/order-documents#300).
Invoice documents can be generated [manually](https://knowledge.plentymarkets.com/en/orders/order-documents/generating-invoices#200) or automatically by setting up an [event procedure](https://knowledge.plentymarkets.com/en/automation/best-practices-automation#_generating_and_sending_an_invoice).

## License

This project is licensed under the GNU AFFERO GENERAL PUBLIC LICENSE. – find further information in the [LICENSE.md](https://github.com/plentymarkets/plugin-payment-invoice/blob/master/LICENSE.md).
