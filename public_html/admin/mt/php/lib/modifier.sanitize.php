<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: modifier.sanitize.php 3455 2009-02-23 02:29:31Z auno $

function smarty_modifier_sanitize($text, $spec = '1') {
    if ($spec == '1') {
        global $mt;
        $ctx =& $mt->context();
        $blog = $ctx->stash('blog');
        $spec = $blog['blog_sanitize_spec'];
        $spec or $spec = $mt->config('GlobalSanitizeSpec');
    }
    require_once("sanitize_lib.php");
    return sanitize($text, $spec);
}
?>
