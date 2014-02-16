#UserAgentString

**Integrates User Agent String project into Symfony**

After enabling the bundle, you can access the `user_agent` service

``` php
<?php
    class HomeController extends Controller
    {
        public function indexAction() {
            $ua = $this->get('user_agent');
            if ($ua->getCurrent()->isMobile()) {
                ...
            } else {
                ...
            }
        }
    }
```

This bundle is inspired by the libraries here: http://user-agent-string.info/download,
and uses the XML also from there, so keep your's updated!

You can specify your updated version in the `config.yml` 
``` yaml
andres_montanez_user_agent_string:
    source: "%kernel.root_dir%/Resources/uas_20140211-01.xml"
    robots: true
```

By default, the Robots section of the file is NOT parsed nor used. If you want to know if a user agent is a bot, you have to enable it yourself.
This bundle is focused on speed for the real users, not the bots.

