### Документация по наполнению сайта через API.
`` Вместо `localhost` необходимо указать домен Вашего сайта.
``
---

1. Добавление категорий
	```angular2html
	http://localhost/index.php?route=extension/module/import/addCategories
	```
	
2. Добавление производителей
	```angular2html
	http://localhost/index.php?route=extension/module/import/addManufacturer
	```
		
3. Добавление товаров
	```angular2html
	http://localhost/index.php?route=extension/module/import/addProduct
	```
	* Обновление или добавление товаров через консоль или cron
    	```angular2html
    	php oc_cli.php catalog extension/module/import/updateProduct
    	```
	
4. Обновление URL у фильтров
	```angular2html
	http://localhost/index.php?route=extension/module/import/updateFilterUrl
	```

5. Добавление фильтров к категориям
	```angular2html
	http://localhost/index.php?route=extension/module/import/filtersToCategories
	```
