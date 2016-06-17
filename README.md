# ApplyPatches

A Composer post-update/install-cmd script to automatically apply patches located,
by default, in mysite/patches (obviously, based on an SS layout). This
can be altered by setting the `marketo_patch_dir` environment variable.

## Install instructions


1. `composer require marketo/composer-patch-applicator`
2. Configure your patch directory (if needed). You can do this either by setting
   the `marketo_patch_dir` environment variable, or by adding this to your `composer.json`:
   ```
"extra": {
  "marketo_patch_dir": "path/to/your/patch/dir"
}
   ```
