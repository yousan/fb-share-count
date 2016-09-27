<?php

/**
 * Created by PhpStorm.
 * User: yousan
 * Date: 2016/09/25
 * Time: 17:01
 */
class FSC_FBShareCount {

	/**
	 * App ID
	 * 数字のみで構成される
	 *
	 * @var string
	 */
	private $appid = '';

	/**
	 * シークレット
	 * 16進数文字列で構成される
	 *
	 * @var string
	 */
	private $appsecret = '';

	/**
	 * アクセストークン
	 * App IDとシークレットを | で繋げたもの
	 *
	 * @var string
	 */
	private $access_token;

	public function __construct($appid, $appsecret) {
		$this->appid = $appid;
		$this->appsecret = $appsecret;
	}


	/**
	 * AppIDとsecretがセットされているかどうかチェックする。
	 * 正当性まではチェックしない。
	 *
	 * @throws Exception
	 */
	private function is_valid_token() {
		if ( empty($this->appid) || empty($this->appsecret) ) {
			throw new Exception('Appid or App Secret is not set.');
		}
	}

	/**
	 * FBのシェア数を取得する
	 *
	 * @link https://blog.hello-world.jp.net/api/2221/
	 * @link https://graph.facebook.com/http://www.yahoo.co.jp
	 *  // https://graph.facebook.com/?id=https://www.yahoo.co.jp　にアクセス　
	 * @link https://developers.facebook.com/docs/graph-api/reference/v2.7/url
	 *
	 * @param $permalink string permalink url
	 *
	 * @return int
	 *
	 * @throws Exception
	 */
	public function get_fb_like_count($permalink) {
		$this->is_valid_token();

		$base_url = 'https://graph.facebook.com/v2.7/'; // OpenGraphプロトコルでアクセスするAPIのエンドポイント
		$parameters = array(
			'id' => urlencode($permalink), // // カウントを取得するURLをエンコードしたもの
			'access_token' => $this->appid .'|'. $this->appsecret,
		);

		$parameter_pair = array();
		foreach ( $parameters as $key => $parameter) { // GETのクエリを=で結ぶ
			$parameter_pair[] = $key . '=' . $parameter;
		}

		$url = $base_url . '?'. implode('&', $parameter_pair);

		$content = file_get_contents($url);
		$json = json_decode($content);
		if ( isset($json->share->share_count) ){
			return $json->share->share_count;
		} else { // 値が無い場合がある (一度もシェアされていない場合
			return 0;
		}
	}
}
