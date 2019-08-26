# AutomaticDiscount

This module allows to automatically use coupons.

## Instalation

### Manually

* Copy the module into ```<thelia_root>/local/modules/``` directory and be sure that the name of the module is AutomaticDiscount.
* Activate it in your thelia administration panel

### Composer

Add it in your main thelia composer.json file

```
composer thelia/automatic-discount-module:~1.0
```
## Uses

This module add a new field `Automatic coupon` of type checkbox in the coupon edit form. If this field is check the coupon will be used automatically.
