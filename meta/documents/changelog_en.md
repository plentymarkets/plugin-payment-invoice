# Release Notes for Invoice

## 2.0.4 (2020-02-25)
### Fixed
- All settings / restrictions are now queried correctly.

## 2.0.3 (20120-02-10)
### Fixed
- The minimum and maximum limit is now checked properly, when a customer wants to change the payment method in an order under My Account.

## 2.0.2 (2019-12-17)
### Changed
- Added methods for the backend visibility and backend name

## 2.0.1 (2019-11-28)

### Fixed
- The minimum and maximum amount is now displayed correctly in the system currency instead of a flat rate of "Euro".
- The minimum and maximum amount is now converted correctly to the cart currency before it is compared with the order value.

## 2.0.0 (2019-11-18)
 
### Note 
- The settings for the Invoice plugin have been transferred to an assistant in the **Setup » Assistants » Payment** menu.

### Changed
- The description and the name of the payment method is now also maintained via **CMS » Multilingualism**.

## 1.3.4 (2019-03-22)

### Changed
- The user guide has been updated.

## 1.3.3 (2018-02-25)

### Changed
- The settings for shipping countries have been optimized.

## 1.3.2 (2018-10-23)

### Fixed
- A possible problem with deploying the plugin has been fixed.

## 1.3.1 (2018-10-04)

### Changed
- Update support information

## 1.3.0 (2018-09-18)

### Added
- The order ID can now be displayed in the designated use. To do so, use the placeholder **%s** in the respective text area in the plugin settings.

## 1.2.4 (2018-08-06)

### Added
- More languages for the plugin UI have been added.
- Language-dependent texts can now be edited via the multilingualism interface.

## 1.2.1 (2018-04-23)

### Fixed
- In the checkout, the setting `Minimum number of orders` is validated correctly.
- Issue after logout not longer occurs.

## 1.2.0 (2018-04-20)

### Added
- "Allow invoice" will be considered in the checkout.

## 1.1.8 (2018-01-26)

### Changed
- The user guide has been updated.

## 1.1.7 (2018-01-09)

### Changed
- New menu path **System&nbsp;» Orders&nbsp;» Payment » Plugins » Invoice**.

## 1.1.6 (2017-11-30)

### Fixed
- In the checkout, the settings `Disallow purchase by invoice for guest accounts`and `Invoice address equals delivery address` are validated correctly.

## 1.1.5 (2017-11-23)

### Fixed
- The `$MethodOfPaymentName` variable will now be displayed in the respective language in email templates.

## 1.1.4 (2017-17-22)

### Changed
- The user guide has been updated.

## 1.1.3 (2017-11-14)

### Changed
- Update changelog

## 1.1.2 (2017-10-26)

### Changed
- The entry point in the system tree is now **System » Orders » Payment » Invoice**.

## 1.1.1 (2017-08-30)

### Fixed
- The check if the payment method can be changed is working properly again.

## 1.1.0 (2017-07-31)

### Added
- Settings for **Info page** were added.
- Settings for **Description** were added.

### Changed
- Removed surcharges for the payment method.

## 1.0.3 (2017-07-13)

### Added
- A method was added to determine if a customer can switch from this payment method to another payment method.
- A method was added to determine if a customer can switch to this payment method from another payment method.

### Fixed
- The correct path for displaying the icon of the payment method is now used.

## 1.0.2 (2017-03-15)

### Fixed
- The CSS of the **Settings** in the back end has been fixed. The settings will now cover the entire width.

### Known issues
- At the moment, the **Surcharges** settings have no functionality in the price calculation of the checkout page

## 1.0.1 (2017-03-14)

### Changed
- Use the payment method id from the system

## 1.0.0 (2017-03-10)

### Features
- Payment method **Invoice** for plentymarkets online stores
- Display of designated use and bank details on the order confirmation page
