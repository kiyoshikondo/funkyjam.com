<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtcommenterauthiconurl.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtcommenterauthiconurl($args, &$ctx) {
    $a =& $ctx->stash('commenter');
    if (!isset($a)) {
        return '';
    }
    require_once "function.mtstaticwebpath.php";
    $static_path = smarty_function_mtstaticwebpath($args, $ctx);
    require_once "commenter_auth_lib.php";
    return _auth_icon_url($static_path, $a);
}
?>
