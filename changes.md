Changes Log
============

**1.1.6 to 1.1.7**

- See [this](https://github.com/pingpong-labs/modules/releases/tag/1.1.7)

**1.1.5 to 1.1.6**

- See [#41](https://github.com/pingpong-labs/modules/pull/41)
- Fix [#40](https://github.com/pingpong-labs/modules/pull/40)
- Start from now this package licensed under The BSD 3-Clause License.

**1.1.4 to 1.1.5**

See [#39](https://github.com/pingpong-labs/modules/pull/39)

**1.1.3 to 1.1.4**

See [#35](https://github.com/pingpong-labs/modules/pull/35)

**1.1.2 to 1.1.3**

- Added new controller validator. Please see [this example](https://github.com/pingpong-modules/Admin/blob/master/Http/Controllers/Auth/RegisterController.php).
- Added `--master` option to `module:seed-make` command.
- Fix `module:seed` command

**1.1.1 to 1.1.2**

- Added new feature, now every module can require other composer package. you can define the required package in your module.json file. for example :

```json
{
    "name": "Admin",
    "alias": "admin",
    "description": "Admin Modules",
    "keywords": [
      "admin",
      "modules",
      "pingpong"
    ],
    "require": {
      "pingpong/trusty": "1.*",
      "pingpong/shortcode": "1.*"
    },
    "active": 1
}
```

If the `require` key is not empty, then we will install its packages automatically when you install that module.

**1.1.0 to 1.1.1**

- Added new artisan commands `module:install`. This command is useful for installing the modules.
For example i have `Admin` modules [here](https://github.com/pingpong-modules/Admin). You can install it using this command.

```
php artisan module:install pingpong-modules/Admin
```

By default, that module will stored in current modules directory. If you want to store that in other directory or other path, simply specify the `--path` option. For example :
```
php artisan module:install pingpong-modules/Admin --path=App/Modules
```

**1.0.* to 1.1.0**

See [#32](https://github.com/pingpong-labs/modules/pull/32)

**1.0.10 to 1.0.11**

See [#26](https://github.com/pingpong-labs/modules/pull/26)

**1.0.9 to 1.0.10**

- Added support for [#13](https://github.com/pingpong-labs/modules/pull/13) : enable and disable module.
- There is new artisan command `module:enable` and `module:disable`.

**1.0.8 to 1.0.9**

- Fix `module:seed-make` command when running `module:make` command.
- Command Improvement.

**1.0.7 to 1.0.8**

- There is command name changed :
  -  `php artisan module:migrate:make` to `php artisan module:migration`
  -  `php artisan module:seed:make` to `php artisan module:seed-make`
- Merged [#8](https://github.com/pingpong-labs/modules/pull/18) : Fix constructor error.
- Package improvement.