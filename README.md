<p align="center">
  <img src="http://i.imgur.com/xvO494q.png" alt="Atlas"/>
</p>

## Content

Provide a Symfony 2.X bundle for easy conversion between GPS (WGS84) to Lambert coordinates.
For more details please read this following links : 

- <a href="https://en.wikipedia.org/wiki/Lambert_conformal_conic_projection">https://en.wikipedia.org/wiki/Lambert_conformal_conic_projection</a>
- <a href="http://www.ign.fr/sites/all/files/geodesie_projections.pdf">http://www.ign.fr/sites/all/files/geodesie_projections.pdf</a>
- <a href="http://geodesie.ign.fr/contenu/fichiers/documentation/pedagogiques/TransformationsCoordonneesGeodesiques.pdf">http://geodesie.ign.fr/contenu/fichiers/documentation/pedagogiques/TransformationsCoordonneesGeodesiques.pdf</a>

## How to install ?

Install it with Composer 

```sh
composer require olousouzian/atlasbundle
```

Finally, register the bundle into app/AppKernel : 

```sh
new Olousouzian\AtlasBundle\OlousouzianAtlasBundle(),
```

## How to use it ?

Helper : 

```sh
$ app/console atlas:convert --help

Usage:
  atlas:convert [options]

Options:
      --format=FORMAT        Output format like: lambertI, lambertII, lambertIIExtended, lambertIII, lambertIV, lambert93, all [default: "all"]
      --output[=OUTPUT]      json [default: 1]
      --latitude=LATITUDE    
      --longitude=LONGITUDE  
  -h, --help                 Display this help message
  -q, --quiet                Do not output any message
  -V, --version              Display this application version
      --ansi                 Force ANSI output
      --no-ansi              Disable ANSI output
  -n, --no-interaction       Do not ask any interactive question
  -s, --shell                Launch the shell.
      --process-isolation    Launch commands from shell as a separate process.
  -e, --env=ENV              The Environment name. [default: "dev"]
      --no-debug             Switches off debug mode.
  -v|vv|vvv, --verbose       Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Help:
 Convert WGS84 coordinates to Lambert coordinates
```

- Example *Get all Lambert coordinates for Lyon city* :

```sh
$ app/console atlas:convert  --latitude=45.7484600 --longitude=4.8467100
``` 

- Example *Get Lambert93 coordinates for Lyon city printed in JSON* :

```sh
$ app/console atlas:convert  --latitude=45.7484600 --longitude=4.8467100 --format=lambert93 --output=json

{"Lambert93":{"x":843562.53347857,"y":6518219.0605733}}
```
 

## License

This solution is under MIT license.
