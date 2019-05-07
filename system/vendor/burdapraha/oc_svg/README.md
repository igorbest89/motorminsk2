# SVG support for [OpenCart 2.x](https://github.com/opencart/opencart)

Because jpg / png logo are retro!

## Installation

1. Requiring installed [vQmod](https://github.com/vqmod/vqmod) because vQmod doesn't support installing via composer itself.
2. `composer require burdapraha/oc_svg`
3. `composer require sasedev/composer-plugin-filecopier` for files manipulating
4. Add this code to your composer.json project file, extra section:

```
    "extra": {
        "filescopier": [
            {
                "source": "vendor/burdapraha/oc_svg/upload",
                "destination": "upload",
                "debug": "true"
            }
        ]
    }  
```
    
It will move vQmod xml file to correct folder.

5. optionally you can add row to your `.gitignore` file with path to svg.xml (example: upload/vqmod/xml/svg.xml)
6. celebrate!