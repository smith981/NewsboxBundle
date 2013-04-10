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
  * "smith981/newsboxbundle": "dev-master"

3. run `composer update` to install the new dependencies.
4. Make sure all of these are present in your AppKernel::registerBundles() method:

```
<?php

// app/AppKernel.php

  $bundles = array(
            //...
            new JMS\AopBundle\JMSAopBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new Lexik\Bundle\FormFilterBundle\LexikFormFilterBundle(),
            new Smith981\CrudGeneratorBundle\Smith981CrudGeneratorBundle(),
            new Smith981\SecurityBundle\Smith981SecurityBundle(),
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

Be sure `strict_variables` is turned off. For a standard install that means commenting this line under `twig`:

```
twig:
  #strict_variables: %kernel.debug%
```

In app/config/security.yml, remove all of the demo firewalls (if installed), and import those created by Smith981SecurityBundle:

```
# app/config/security.yml
#Added this import manually, and cleared all the default firewalls and access control that had been generated
imports:
    - { resource: "@Smith981SecurityBundle/Resources/config/security.yml" }

# ... whatever other security settings you need, then remove the demo firewalls below:

#firewalls:
#        dev:
#            pattern:  ^/(_(profiler|wdt)|css|images|js)/
#            security: false

#        login:
#            pattern:  ^/demo/secured/login$
#            security: false

#        secured_area:
#            pattern:    ^/demo/secured/
#            form_login:
#                check_path: _security_check
#                login_path: _demo_login
#            logout:
#                path:   _demo_logout
#                target: _demo
#        
#        #access_control:
#        #- { path: ^/login, roles: IS_AUTHENTICATED_ANONYMOUSLY, requires_channel: https }
```

In app/config/routing.yml, add the following:

```
# app/config/routing.yml

#Added this entry manually
smith981_security_additional:
    resource: "@Smith981SecurityBundle/Resources/config/routing.yml"

smith981_security:
    resource: "@Smith981SecurityBundle/Controller/"
    type:     annotation
    prefix:   /

smith981_newsbox:
    resource: "@Smith981NewsboxBundle/Controller/"
    type:     annotation
    prefix:   /
```

Finally, under `framework` in the same file:

```yaml
framework:
    translator:      { fallback: en }
```
Note this can be whatever language you choose, or possible %locale% if you actually took the time to set this up correctly.

6. You should be ready to go at this point. Browse to /app_dev.php/admin to log in and start adding issues, and stories which relate to those issues. Those are the only two entity types added by this package. The rest is just an admin console and view templates.
