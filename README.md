
2Bill.ie PHP Api (v2.5) Client

AllPoints 2Bill API Specifications: http://bit.ly/1fTu6zb

```php
Example usage:

use Square1\MobileBill\MobileBill;
use Square1\MobileBill\Exception\AuthenticationError;
use Square1\MobileBill\Exception\ConnectionError;
use Square1\MobileBill\Exception\InvalidNumber;
use Square1\MobileBill\Exception\InvalidRequest;
use Square1\MobileBill\Exception\TransactionError;

try {
    var_dump(
        MobileBill::create('username', 'password')
            ->content('999999', 'The content description goes here')
            ->phone('35387123456')
            ->charge('100', 'This is a test transaction')
            ->send();
    );
} catch (AuthenticationError $e) {
    echo "Auth issue.. " . $e->getMessage() . " do something!";    
} catch (InvalidRequest $e) {
    echo "Invalid request.. " . $e->getMessage() . " do something!";
} catch (InvalidNumber $e) {
    echo "Number is invalid.. " . $e->getMessage() . " do something!";
} catch (ConnectionError $e) {
    echo "Connection issue.. " . $e->getMessage() . " do something!";
} catch (TransactionError $e) {
    echo "Transaction issue.. " . $e->getMessage() . " do something!";
} catch (\Exception $e) {
    echo "General exception.. " . $e->getMessage() . " do something!";
}
```