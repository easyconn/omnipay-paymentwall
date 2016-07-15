# omnipay-paymentwall

**PaymentWall driver for the Omnipay PHP payment processing library**

[![Build Status](https://travis-ci.org/incube8/omnipay-paymentwall.svg?branch=master)](https://travis-ci.org/incube8/omnipay-paymentwall)
[![StyleCI](https://styleci.io/repos/35171050/shield)](https://styleci.io/repos/35171050)

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements PaymentWall support for Omnipay.

[PaymentWall](https://www.paymentwall.com/en/) is the leading digital payments
platform for globally monetizing digital goods and services. Paymentwall assists game publishers,
dating sites, rewards sites, SaaS companies and many other verticals to monetize their
digital content and services.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "delatbabel/omnipay-paymentwall": "dev-master"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following transactions are provided by this package via the REST API:

* Create a purchase
* Voiding a purchase

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.  There are also examples in the class API documentation.

### Quirks

There is no separate createCard message in this gateway.  The
PaymentWall gateway only supports card creation at the time of a
purchase.  Instead, a cardReference is returned when a purchase
message is sent, as a component of the response to the purchase
message.  This card token can then be used to make purchases
in place of card data, just like other gateways.

Refunds are not supported, only voids are supported.

Required fields when making a purchase include:

* amount
* currency
* accountId (customer account ID on your system)
* description
* clientIp
* browserDomain

Optional fields:

* packageId (account id will be used if this is not set)
* packageName (description will be used if this is not set)

For a card purchase, an Omnipay CreditCard object can be provided containing
the card data.  For token purchases a cardReference can be provided instead
of the card data.

An additional array of customer data and history data can be provided as the
customerData and historyData parameters.  These can help with fraud prevention
and detection.  See the documentaton for the functions in the PurchaseRequest
class as to the contents of these.

## Unit Testing

Tests are in the tests folder.  Basic unit tests are in place for some of the code including
mock message responses.

Test cases are somewhat limited because the test functionality requires creating mocks of
some of the PaymentWall library functions which are called statically.  Pull requests with
additional tests and test cases are especially welcome.

## API Documentation

You can build the API documentation after running composer update, by using this command
(on Linux/Unix systems):

```
./makedoc.sh
```

The API documentation will be built in documents/main in HTML format.

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.
