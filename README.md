symfony-bootstrap
=================

Symfony2 Bootstrap project with added goodies we use daily

**Last updated Dec 10, 2013**

*We use this bootstrap for new projects, and it is built on latest branches of it's bundles, so there may be some point between our update (when we find out it's broken) and uncompatible bundle (master branch) updates. If you encounter this situation, please open an issue in this repo, or create a pull request.*

Dem goodies:
-----------

* moved front controllers above one level (more suitable for common hostings than inside /web)
* added `.htaccess` working in most hostings (months of sweating and swearing included)
* added and configured VichUploader and LiipImagine bundles for painless image manipulation (blood has been cried)
* incorporated this nicely into SonataAdmin (http://stackoverflow.com/questions/11366278/how-to-display-the-current-picture-above-the-upload-field-in-sonataadminbundle/12696046#12696046 <--- this is me!)
* added our API helper - currently being used in private projects, but we promise it will be documented soon (lol), meanwhile just read the code, it's built as a layer above FOSRest bundle, it's simple :)
* basic security to protect admin (prompt login)


Moar goodies soon (lol):
-----------------

* documentation for API helper
* update API helper for writing (currently for readonly APIs only)
* FOSUser / SonataUser bundle for better user management

