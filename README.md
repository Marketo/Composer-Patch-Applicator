# ApplyPatches

A Composer post-update/install-cmd script to automatically apply patches located, 
by default, in mysite/patches (obviously, based on an SS layout). This 
can be altered by setting the `marketo_patch_dir` environment variable. 

## Install instructions

### Add it to your project with:

`composer require marketo/composer-patch-applicator`

### Add the following to your composer.json

```
"scripts": {
     "post-update-cmd": "Marketo\\CliTools\\ApplyPatches::run",
     "post-install-cmd": "Marketo\\CliTools\\ApplyPatches::run"
}
```
