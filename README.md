VlabsAddressBundle
================

Installation
------------

### Step 1: Download the bundle

Open your command console, browse to your project and execute the following:

```sh
$ composer require vlabs/address-bundle
```

### Step 2: Enable the bundle

``` php
// app/AppKernel.php
public function registerBundles()
{
    return array(
        // ...
        new FOS\RestBundle\FOSRestBundle(),
        new JMS\SerializerBundle\JMSSerializerBundle(),
        new Vlabs\AddressBundle\VlabsAddressBundle(),
    );
}
```
### Step 3: Add tbbc error configuration

``` php
// app/config/config.yml
imports:
    - { resource: "@VlabsAddressBundle/Resources/config/packages/tbbc_rest_util.yml" }
    ...
```

### Step 3: Update the configuration

```yaml
# app/config/config.yml
// ...

fos_rest:
    format_listener:
        enabled: true
        rules:
            - { path: '^/address/*', priorities: ['json'], fallback_format: 'html' }
            - { path: '^/', priorities: ['html'] }
    view:
        view_response_listener: force

```


### Step 4: Register the routing definitions

```yaml
# app/config/routing.yml
// ...

vlabs_address:
    resource: "@VlabsAddressBundle/Resources/config/routing.yml"
    prefix:   /address


```

### Step 5: Import address data

```sh
$ app/console vlabs:address:install
```
