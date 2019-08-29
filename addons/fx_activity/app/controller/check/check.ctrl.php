<?php
$op = !empty($_GPC['op']) ? $_GPC['op'] : 'code';

if($op =='follow'){//判断是否关注公众号
	die(json_encode(array('result'=>$_W['fans']['follow']?1:0)));
}
if($op =='m_follow'){//判断是否关注主办方
	
}
if($op =='favorite'){//判断是否收藏
	
}
if($op =='join'){//判断是否报名
	
}