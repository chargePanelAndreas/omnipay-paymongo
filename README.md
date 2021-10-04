# Omnipay: Paymongo **Note! This is not an official Omnipay/Paymongo Package.**

**Paymongo driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP. This package implements common classes required by Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "reillo/omnipay-paymongo": "~3.0"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Usage

[TODO]

for now, see [example](./example).

## TODO

- Documentation
- complete all tests
- make the other requests available in paymentIntents and Sources
- Publicly available
- Workaround for Offsite E-Payment Session and its cleaner?

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/reillo/omnipay-paymongo/issues),
or better yet, fork the library and submit a pull request.
