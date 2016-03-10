<p align="center">
  <img src="http://i.imgur.com/xvO494q.png" alt="Atlas"/>
</p>

## Content

Provide a Symfony 2.X bundle for easy conversion between GPS and Lambert coordinates.
For more details please read this following links : 

- <a href="https://en.wikipedia.org/wiki/Lambert_conformal_conic_projection">https://en.wikipedia.org/wiki/Lambert_conformal_conic_projection</a>
- <a href="http://www.ign.fr/sites/all/files/geodesie_projections.pdf">http://www.ign.fr/sites/all/files/geodesie_projections.pdf</a>
- <a href="http://geodesie.ign.fr/contenu/fichiers/documentation/pedagogiques/TransformationsCoordonneesGeodesiques.pdf">http://geodesie.ign.fr/contenu/fichiers/documentation/pedagogiques/TransformationsCoordonneesGeodesiques.pdf</a>

## How to install ?

*Work in progress : **COMING SOON** *

Install it with Composer 

```sh
composer require olousouzian/atlasbundle
```


Finally, register the bundle into app/AppKernel : 

```sh
new Olousouzian\AtlasBundle\OlousouzianAtlasBundle(),
```

## License

This solution is under MIT license.