# image-upload
Laravel package for upload image with auto generated multiple sizes

## Installation

Require this package with composer.

```shell
composer require mark-villudo/image-upload
```


## Usage - Generate multiple size
```
  //Get file and declare path to store image
  $file = $request->file('file');
  $origFilePath = '/storage/categories';
  
  //use helper to store images with multiple sizes
  storeImages($file, $origFilePath);
```

## Usage - Upload single image
```
  //Get file and declare path to store image
  $file = $request->file('file');
  $origFilePath = '/storage/categories';
  
  //use helper to store single image
  storeSingleImage($file, $origFilePath);
```

## Credits

```
Master Jes Dolfo.

Facebook Profile
https://www.facebook.com/jesdolfo

```

