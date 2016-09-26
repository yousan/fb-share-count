<?php
/**
 * Created by PhpStorm.
 * User: yousan
 * Date: 2016/09/25
 * Time: 16:51
 */

function fb_share_count($permalink='') {
	if ( empty($permalink) ) { // 引数が無い場合
		$permalink = get_the_permalink();
		if ( empty($permalink) ) {
			$permalink = site_url(); // サイトURLにする
		} else { // シングルなどでループを廻っていた場合
		}
	}
	$fsc = new FSC_FBShareCount(
		FSC_Option::get_('app_id'),
		FSC_Option::get_('app_secret')
	);
	return $fsc->get_fb_like_count($permalink);
}

add_shortcode('fb_share_count', 'fb_share_count');
