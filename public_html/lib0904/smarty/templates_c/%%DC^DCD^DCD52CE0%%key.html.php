<?php /* Smarty version 2.6.18, created on 2015-08-27 10:02:25
         compiled from premium/artist/dl/key.html */ ?>
<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=shift_jis" />
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" lang="ja" xml:lang="ja">
<?php if ($this->_tpl_vars['checkAuthResult']): ?>
<font size="-1">コンテンツ・キーを正しく取得すること出来ませんでした。</font>
<font size="-1" color="#2D0B0A">[<a href="http://TMS/RO?sid=EC9D&lid=<?php echo $this->_tpl_vars['voice']['lid']; ?>
">コンテンツ・キー取得</a>]</font><br />
<?php else: ?>
<font size="-1" color="#2D0B0A">ダウンロードするには、有料会員登録が必要となります。</font><br />
<?php endif; ?>
</body></html>