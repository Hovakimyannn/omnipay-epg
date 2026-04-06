# Omnipay: SmartVista EPG

**SmartVista E-Commerce Payment Gateway (EPG)** driver for the [Omnipay](https://omnipay.thephpleague.com/) PHP payment library.

Supports any bank running on SmartVista EPG. Comes with a pre-configured gateway for **Arca / iPay** (`ipay.arca.am`).

[![Latest Stable Version](https://poser.pugx.org/hovakimyannn/omnipay-epg/v/stable)](https://packagist.org/packages/hovakimyannn/omnipay-epg)
[![License](https://poser.pugx.org/hovakimyannn/omnipay-epg/license)](https://packagist.org/packages/hovakimyannn/omnipay-epg)

---

## Installation

```bash
composer require hovakimyannn/omnipay-epg
```

---

## Gateways

| Class | Description |
|---|---|
| `Omnipay\Epg\Gateway` | Generic EPG — configure any bank's endpoint |
| `Omnipay\Epg\ArcaGateway` | Arca / iPay — endpoints pre-configured |

---

## Usage

### Arca / iPay

```php
use Omnipay\Omnipay;

$gateway = Omnipay::create('Epg\Arca');
$gateway->setUsername('your_username');
$gateway->setPassword('your_password');
// $gateway->setTestMode(true); // use ipaytest.arca.am
```

### Generic EPG (any bank)

```php
use Omnipay\Epg\Gateway;

$gateway = new Gateway();
$gateway->setEndpoint('https://epg.yourbank.am/payment/rest');
$gateway->setTestEndpoint('https://epg-test.yourbank.am/payment/rest');
$gateway->setUsername('your_username');
$gateway->setPassword('your_password');
```

---

## Supported Operations

### Purchase (one-phase payment)

Registers the order and returns a redirect URL to the bank's hosted payment page.

```php
$response = $gateway->purchase([
    'transactionId' => 'ORDER-001',       // your order number
    'amount'        => '10.00',
    'currency'      => 'AMD',
    'returnUrl'     => 'https://yoursite.com/payment/success',
    'failUrl'       => 'https://yoursite.com/payment/fail',  // optional
    'description'   => 'Order #001',                         // optional
    'language'      => 'en',                                 // optional
    'features'      => 'FORCE_TDS',                          // optional: FORCE_SSL | FORCE_TDS
])->send();

if ($response->isRedirect()) {
    // Store $response->getTransactionReference() (EPG orderId) for later status check
    $orderId = $response->getTransactionReference();

    $response->redirect(); // redirects customer to bank payment page
}
```

### Complete Purchase (check payment status after redirect)

Called after the customer returns from the bank payment page.

```php
$response = $gateway->completePurchase([
    'transactionId' => $orderId, // the EPG orderId saved during purchase
])->send();

if ($response->isSuccessful()) {
    // Payment confirmed — fulfill the order
} else {
    echo $response->getMessage();
}
```

### Pre-Auth (two-phase payment)

**Phase 1** — reserve funds:

```php
$response = $gateway->registerPreAuth([
    'transactionId' => 'ORDER-002',
    'amount'        => '50.00',
    'currency'      => 'AMD',
    'returnUrl'     => 'https://yoursite.com/payment/success',
])->send();

$orderId = $response->getTransactionReference();
```

**Phase 2** — confirm (deposit) reserved funds:

```php
$response = $gateway->deposit([
    'transactionId' => $orderId,
    'amount'        => '50.00',
])->send();
```

### Reverse (cancel / void)

```php
$response = $gateway->reverse([
    'transactionId' => $orderId,
])->send();
```

### Refund

```php
$response = $gateway->refund([
    'transactionId' => $orderId,
    'amount'        => '10.00',
])->send();
```

### Order Status

```php
// Basic status
$response = $gateway->getOrderStatus([
    'transactionId' => $orderId,
])->send();

// Extended status (includes card info, binding info, etc.)
$response = $gateway->getOrderStatusExtended([
    'transactionId' => $orderId,
])->send();

echo $response->getOrderStatus();        // 0–6 (EPG status code)
echo $response->getTransactionReference();
print_r($response->getCardAuthInfo());
```

### Verify Enrollment (3DS check)

```php
$response = $gateway->verifyEnrollment([
    'pan' => '4111111111111111',
])->send();
```

### Bindings (saved cards)

**Pay with a saved card:**

```php
$response = $gateway->bindingPayment([
    'transactionReference' => $orderId,  // mdOrder
    'bindingId'            => $bindingId,
    'language'             => 'en',
])->send();
```

**Get list of bindings for a customer:**

```php
$response = $gateway->getBindings([
    'clientId' => 'customer-123',
])->send();
```

---

## Response Methods

| Method | Description |
|---|---|
| `isSuccessful()` | `true` if payment is fully deposited |
| `isRedirect()` | `true` if customer should be redirected to `formUrl` |
| `getRedirectUrl()` | Hosted payment page URL |
| `getTransactionReference()` | EPG `orderId` |
| `getOrderNumberReference()` | Merchant `orderNumber` |
| `getOrderStatus()` | EPG order status code |
| `getMessage()` | Error message |
| `getCode()` | Error code (`0` = success) |
| `getBindingId()` | Binding ID (if applicable) |
| `getCardAuthInfo()` | Card info array (masked PAN, expiry, etc.) |
| `getRequestId()` | `Request-Id` response header |

### Order status codes

| Code | Meaning |
|---|---|
| 0 | Registered, not paid |
| 1 | Pre-authorized (held) |
| 2 | Deposited (fully paid) ✅ |
| 3 | Cancelled |
| 4 | Refunded |
| 5 | ACS authorization in progress |
| 6 | Authorization declined |

---

## License

MIT

