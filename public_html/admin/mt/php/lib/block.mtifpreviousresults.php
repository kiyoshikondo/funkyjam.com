<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: block.mtifpreviousresults.php 3455 2009-02-23 02:29:31Z auno $

function smarty_block_mtifpreviousresults($args, $content, &$ctx, &$repeat) {
    if (!isset($content)) {
        $offset = $ctx->stash('__pager_offset');
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat, $offset ? true : false);
    } else {
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat);
    }
}
?>
