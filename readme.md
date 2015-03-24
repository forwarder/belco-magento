Magento Belco.io integration
==========================================================

This package integrates the Belco.io API with your Magento shop. It synchronizes customer and order data and adds the Belco.io Widget to the front-end. The package is tested in Magento 1.9.

## Setup

__You will need your Belco Shop ID and API secret to complete the setup, which you [can find here][api-keys].__

### Installation

- Clone the git repo, `git clone git@github.com:forwarder/belco-magento.git`.
- Log in to your Magento backend.
- Go to `System > Tools > Backups` and create a `System Backup`.
- Go to `System > Tools > Compilation` and disable compilation if it's enabled. 
- Go to `System -> Cache Management` and enable Configuration.

- Go to `System > Magento Connect > Magento Connect Manager` and log in with your admin credentials.
- Under `Direct package file upload` click `Choose file`, browse to the package repo, go to `package` and select the file `Belco_Widget-0.0.1.tgz`
- Click `Upload` to start the installation process.
- After the installation is completed go back to the admin page.

### Configuration

- Log in to your Magento backend.
- Go to `System > Configuration > Belco.io > Settings`
- Enter your `Shop ID` and `API secret`
- Click `Save Config`

##License
The code is licensed under the [GPL v3 licence][gpl-v3-licence] 

[api-keys]: https://app.belco.io/settings/api_keys
[belco-api]: http://docs.belco.io/api/
[gpl-v3-licence]: http://choosealicense.com/licenses/gpl-3.0/
