# spambarrier-api-php
use spambarrier api with php

# with composer:

	composer require spambarrier/spambarrier-api-php
	
# without composer:

	clone or download sources and put it to the library folder of your project
	
# usage

```php
# create the client with account-id and api-key
$client = new Sb\Client(YOUR_ACCOUNT_ID, 'YOUR_API_KEY');

# get list of all domains in account
var_dump($client->getDomains());

# add domain
var_dump($client->addDomain('example.org', 'target.example.org'));

# edit domain
var_dump($client->editDomain('example.org', 'another-target.example.org'));

# delete domain
var_dump($client->deleteDomain('example.org'));

# get filter settings for domain
var_dump($client->getFilterSettings('example.org'));

# set filter settings for domain
var_dump($client->setFilterSettings('example.org', 'quarantine', 10, 'quarantine', false));

# get quarantine contents for domain
var_dump($client->getQuarantine('example.org'));

# resend a quarantined message
var_dump($client->resendQuarantineMessage('example.org', MESSAGE_ID));

# delete a quarantined message
var_dump($client->deleteQuarantineMessage('example.org', MESSAGE_ID));

# get domain statistics
var_dump($client->getDomainStatistics('example.org', '2016-01-01', '2016-01-31', 'day'));
```