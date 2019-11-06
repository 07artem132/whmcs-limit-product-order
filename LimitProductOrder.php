<?php
/**
 *  Created by PhpStorm.
 *  User: Артём
 *  Date time: 06.11.19 23:02
 *
 */

/**
 * @return array
 */
function LimitProductOrder_config() {
	$config = [
		"name"        => "Ограничить кол-во активных услуг продукта",
		"description" => "Данный модуль позволяет ввести ограничение на кол-во активных услуг продукта (1 продукт = 1 услуга), для перечисленных в модуле",
		"version"     => "1",
		"author"      => "service-voice",
		"fields"      => [
			"product_ids" => [
				"FriendlyName" => "id продуктов",
				"Type"         => "textarea",
				"Size"         => "25",
				"Description"  => "По одному значению в строку",
				"Default"      => "",
			],
		]
	];

	return $config;
}
