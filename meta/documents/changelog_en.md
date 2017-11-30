# Release Notes for Invoice

## 1.1.6 (2017-11-30)

### Fixed

- In the checkout, the settings `Disallow purchase by invoice for guest accounts`and `Invoice address equals delivery address` are validated correctly.

## 1.1.5 (2017-11-23)

### Fixed

- The `$MethodOfPaymentName` variable will now be displayed in the respective language in email templates.

## 1.1.4 (2017-17-22)

### Changed

- Update User guide

## 1.1.3 (2017-11-14)

### Changed

- Update changelog

## 1.1.2 (2017-10-26)

### Changed

- The entry point in the system tree is now **System » Orders » Payment » Invoice » Invoice**.

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
