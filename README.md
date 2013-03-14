NewsboxBundle
=============

A simple issue-based online publishing platform. Not a blog.

## Installation

### Composer
1. Install from the command line the Symfony Standard Edition. Specify version 2.2.

```bash
php composer.phar create-project symfony/framework-standard-edition path/to/install
```

2. Add the following requirements to your `composer.json`:
  
  * "jms/security-extra-bundle": "1.4.*",
  * "jms/di-extra-bundle": "1.3.*",
  * "jordillonch/crud-generator": "dev-master",
  * "smith981/NewsboxBundle"

3. run `composer update` to install the new dependencies.
4. Make sure all of these are present in your AppKernel::registerBundles() method:
5. 
```php
<?php

// app/AppKernel.php

  $bundles = array(
            ...
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new JordiLlonch\Bundle\CrudGeneratorBundle\JordiLlonchCrudGeneratorBundle(),
            new Smith981\NewsboxBundle\Smith981NewsboxBundle(),
        );
```

5. In app/config/config.yml, add this to your `assetic` configuration:

```yaml
assetic:
  bundles:        [ Smith981NewsboxBundle ]
```

and add this to the `twig` configuration in the same file:

```yaml
twig:
  form:
      resources:
          - LexikFormFilterBundle:Form:form_div_layout.html.twig
```

Also, add the following global variables to your `twig` configuration in the same file:

```yaml
twig:
  globals:
    site_name: "YourSiteName"
    site_url:  "http://www.yoursite.com"
```

Finally, under `framework` in the same file:

```yaml
    translator:      { fallback: en }
```
Note this can be whatever language you choose, or possible %locale% if you actually took the time to set this up correctly.

6. You should be ready to go at this point. Browse to /app_dev.php/admin to log in and start adding issues, and stories which relate to those issues. Those are the only two entity types added by this package. The rest is just an admin console and view templates.
