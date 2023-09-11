# Release Notes for Invoice

## 2.0.12

### Fixed
- Error when try to save the settings

## 2.0.11

### Fixed
- PHP 8 issue in the assistant.

## 2.0.10

### Fixed
- Adjusted the link in the user guide.

## 2.0.9

### Fixed
- The URL for the internal info page is now generated correctly.

## 2.0.8

### Fixed
- The link to an internal info page is now displayed correctly again.

## 2.0.7 

### Fixed
- On completing the assistant the old invoice payment method is now activated.

## 2.0.6 

### Changed
- Added Icon for the backend

### Fixed
- The setting "Invoice address equals delivery address" is now applied correctly.

## 2.0.5 

### Changed
- Performance optimization for loading the plugin settings and available delivery countries.

## 2.0.4 

### Fixed
- All settings / restrictions are now queried correctly.

## 2.0.3

### Fixed
- The minimum and maximum limit is now checked properly, when a customer wants to change the payment method in an order under My Account.

## 2.0.2 

### Changed
- Added methods for the backend visibility and backend name

## 2.0.1 

### Fixed
- The minimum and maximum amount is now displayed correctly in the system currency instead of a flat rate of "Euro".
- The minimum and maximum amount is now converted correctly to the cart currency before it is compared with the order value.

## 2.0.0 
 
### Note 
- The settings for the Invoice plugin have been transferred to an assistant in the **Setup » Assistants » Payment** menu.

### Changed
- The description and the name of the payment method is now also maintained via **CMS » Multilingualism**.

## 1.3.4 

### Changed
- The user guide has been updated.

## 1.3.3 

### Changed
- The settings for shipping countries have been optimized.

## 1.3.2 

### Fixed
- A possible problem with deploying the plugin has been fixed.

## 1.3.1 

### Changed
- Update support information

## 1.3.0 

### Added
- The order ID can now be displayed in the designated use. To do so, use the placeholder **%s** in the respective text area in the plugin settings.

## 1.2.4 

### Added
- More languages for the plugin UI have been added.
- Language-dependent texts can now be edited via the multilingualism interface.

## 1.2.1 

### Fixed
- In the checkout, the setting `Minimum number of orders` is validated correctly.
- Issue after logout not longer occurs.

## 1.2.0 

### Added
- "Allow invoice" will be considered in the checkout.

## 1.1.8 

### Changed
- The user guide has been updated.

## 1.1.7 

### Changed
- New menu path **System&nbsp;» Orders&nbsp;» Payment » Plugins » Invoice**.

## 1.1.6 

### Fixed
- In the checkout, the settings `Disallow purchase by invoice for guest accounts`and `Invoice address equals delivery address` are validated correctly.

## 1.1.5 

### Fixed
- The `$MethodOfPaymentName` variable will now be displayed in the respective language in email templates.

## 1.1.4 

### Changed
- The user guide has been updated.

## 1.1.3 

### Changed
- Update changelog

## 1.1.2 

### Changed
- The entry point in the system tree is now **System » Orders » Payment » Invoice**.

## 1.1.1 

### Fixed
- The check if the payment method can be changed is working properly again.

## 1.1.0 

### Added
- Settings for **Info page** were added.
- Settings for **Description** were added.

### Changed
- Removed surcharges for the payment method.

## 1.0.3 

### Added
- A method was added to determine if a customer can switch from this payment method to another payment method.
- A method was added to determine if a customer can switch to this payment method from another payment method.

### Fixed
- The correct path for displaying the icon of the payment method is now used.

## 1.0.2 

### Fixed
- The CSS of the **Settings** in the back end has been fixed. The settings will now cover the entire width.

### Known issues
- At the moment, the **Surcharges** settings have no functionality in the price calculation of the checkout page

## 1.0.1 

### Changed
- Use the payment method id from the system

## 1.0.0 

### Features
- Payment method **Invoice** for plentymarkets online stores
- Display of designated use and bank details on the order confirmation page
