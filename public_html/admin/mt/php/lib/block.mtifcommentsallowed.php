<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: block.mtifcommentsallowed.php 3455 2009-02-23 02:29:31Z auno $

function smarty_block_mtifcommentsallowed($args, $content, &$ctx, &$repeat) {
    # status: complete
    if (!isset($content)) {
        $blog = $ctx->stash('blog');
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat,
                              ($blog['blog_allow_unreg_comments']
                               || ($blog['blog_allow_reg_comments']
                                   && $blog['blog_commenter_authenticators']))
                              && $ctx->mt->config('AllowComments'));
    } else {
        return $ctx->_hdlr_if($args, $content, $ctx, $repeat);
    }
}
?>
