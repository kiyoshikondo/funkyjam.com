<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtpagerlink.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtpagerlink($args, &$ctx) {
    $page = $ctx->__stash['vars']['__value__'];
    if ( !$page ) return '';

    $limit = $ctx->stash('__pager_limit');
    $offset = ( $page - 1 ) * $limit;

    if ( strpos($link, '?') ) {
        $link .= '&';
    }
    else {
        $link .= '?';
    }

    $link .= "limit=$limit";
    if ( $offset )
        $link .= "&offset=$offset";
    return $link;
}
?>

