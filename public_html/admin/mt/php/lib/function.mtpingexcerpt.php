<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtpingexcerpt.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtpingexcerpt($args, &$ctx) {
    $ping = $ctx->stash('ping');
    return $ping['tbping_excerpt'];
}
?>
