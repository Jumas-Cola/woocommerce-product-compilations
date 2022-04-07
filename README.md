=== WooCommerce Product Compilations ===
Contributors: hlashbrooke
Donate link: http://www.hughlashbrooke.com/donate
Tags: wordpress, plugin, template
Requires at least: 3.9
Tested up to: 4.0
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==

Plugin for making custom product compilations in WordPress + WooCommerce.

== Installation ==

Installing "WooCommerce Product Compilations" can be done either by searching for "WooCommerce Product Compilations" via the "Plugins > Add New" screen in your WordPress dashboard, or by using the following steps:

1. Download the plugin via WordPress.org
1. Upload the ZIP file through the 'Plugins > Add New > Upload' screen in your WordPress dashboard
1. Activate the plugin through the 'Plugins' menu in WordPress

== Usage ==

```php
use WCProductCompilations\Models\Compilation;


$user_id = get_current_user_id();

// Создание подборки
$compilation = Compilation::create([
  "name" => "Test",
  "user_id" => $user_id,
]);

// Получение конкретной подборки по id
$compilation = Compilation::find(1);

// Добавление товаров в подборку
$compilation->addProducts([431, 432]);

// Удаление товаров из подборки
$compilation->removeProducts([431, 433]);

// Перемещение товаров из одной подборки в другую
$compilation2 = Compilation::find(2);
$compilation->moveProductsTo($compilation2);

// Обновление подборки
$compilation->update([
  "name" => "Test2",
]);

// Получение всех подборок пользователя
$compilations = Compilation::where("user_id", $user_id)->get();
foreach ($compilations as $compilation) {
  echo 'Id: ' . $compilation->id . '<br>';
  echo 'Name: ' . $compilation->name . '<br>';
  echo 'Count: ' . $compilation->count . '<br>';
  echo '<br>';
}

// Получение товаров в подборке
foreach ($compilation->products as $product) {
  echo $product->description . '<br>';
}

// Удаление подборки
$compilation->delete();

== Screenshots ==

1. Description of first screenshot named screenshot-1
2. Description of second screenshot named screenshot-2
3. Description of third screenshot named screenshot-3

== Frequently Asked Questions ==

= What is the plugin template for? =

This plugin template is designed to help you get started with any new WordPress plugin.

== Changelog ==

= 1.0 =
* 2022-04-07
* Initial release

== Upgrade Notice ==

= 1.0 =
* 2022-04-07
* Initial release
