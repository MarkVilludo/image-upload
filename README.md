# image-upload
Laravel package for upload image with auto generated multiple sizes

## Installation

Require this package with composer.

```shell
composer require mark-villudo/image-upload
```


##Usage
```
  //Get file and declare path to store image
  $file = $request->file('file');
  $origFilePath = '/storage/categories';
  
  //use helper to store images with multiple data
  storeImages($file, $origFilePath);
```
