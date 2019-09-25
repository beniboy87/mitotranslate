# Mito Translate

## Version 0.1

### Telepítés
Először telepíteni kell a csomagot composer-el.

Telepítés után publish-olhatod a konfigurációs fájlt a csomagból ha szükséged van rá

`php artisan vendor:publish --tag=config`

Telepítés után az API kulcsot be kell állítani, ezt megteheted vagy a config fájlban, vagy környezeti változó beállításával.
A `MITO_TRANSLATE_KEY` változó fogadja az API kulcsot.

### CLI Funkciók
- Translate fájlok frissítése

`php artisan translate:refresh`
