<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: block.mtifregistrationrequired.php 3455 2009-02-23 02:29:31Z auno $

function smarty_block_mtifregistrationrequired($args, $content, &$ctx, &$repeat) {
    if (!isset($content)) {
        $blog = $ctx->stash('blog');
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat, $blog['blog_allow_reg_comments'] && $blog['blog_commenter_authenticators'] && !$blog['blog_allow_unreg_comments']);
    } else {
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat);
    }
}
?>
