Magento Belco.io integration
==========================================================

This package integrates the Belco.io API with your Magento shop. It synchronizes customer and order data and adds the Belco.io Widget to the front-end. The package is tested in Magento 1.9.

## Setup

__You will need your Belco Shop ID and API secret to complete the setup, which you can find in your Belco settings under 'Api keys'.__

### Installation

#### Package

- Download the [latest release](https://github.com/forwarder/belco-magento/releases).
- Log in to your Magento backend.
- Go to `System > Tools > Backups` and create a `System Backup`.
- Go to `System > Tools > Compilation` and disable compilation if it's enabled.
- Go to `System -> Cache Management` and enable Configuration.

- Go to `System > Magento Connect > Magento Connect Manager` and log in with your admin credentials.
- Under `Direct package file upload` click `Choose file`, find and select the file you just downloaded (`belco-0.x.x.tgz`)
- Click `Upload` to start the installation process.
- After the installation is completed go back to the admin page.

#### Composer

```bash
composer require forwarder/belco-magento
```

If composer throws an error, run this command
```bash
composer config repositories.belco git https://github.com/forwarder/belco-magento.git
```

or add this to your composer.json file manually
```json
    "repositories": [
        "belco": {     
            "url":"https://github.com/forwarder/belco-magento.git",
            "type": "git"
        }
    ]
```

### Configuration

- Log in to your Magento backend.
- Go to `System > Configuration > Belco.io > Settings`
- Enter your `Shop ID` and `API secret`
- Click `Save Config`

### Problems after installing
If you're having problems after installing the package, try to log out and log in again.

## License
The code is licensed under the [GPL v3 licence][gpl-v3-licence]

[api-keys]: https://app.belco.io/settings/api_keys
[belco-api]: http://docs.belco.io/api/
[gpl-v3-licence]: http://choosealicense.com/licenses/gpl-3.0/
