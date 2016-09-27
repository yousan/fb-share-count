<?php

/**
 * Created by PhpStorm.
 * User: yousan
 * Date: 2016/09/22
 * Time: 19:34
 */
class FSC_Loader {
	/**
	 * ショートコードを登録する
	 */
	public static function register_shortcode() {
		add_shortcode('hoge', 'hogeFunc');
	}

	public static function get_app_id() {

	}
}
