<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: block.mtifnonzero.php 3455 2009-02-23 02:29:31Z auno $

function smarty_block_mtifnonzero($args, $content, &$ctx, &$repeat) {
    // status: complete
    // parameters: tag
    if (!isset($content)) {
        $ctx->localize(array('conditional', 'else_content'));
        if (isset($args['tag'])) {
            $tag = $args['tag'];
            $tag = preg_replace('/^mt:?/i', '', $tag);
            $largs = $args; // local arguments without 'tag' element
            unset($largs['tag']);
            $output = $ctx->tag($tag, $largs);
        } elseif (isset($args['var'])) {
            $output = $ctx->__stash['vars'][$args['var']];
        }
        $ctx->stash('conditional', ($output != '0' && $output != ''));
        $ctx->stash('else_content', null);
    } else {
        if (!$ctx->stash('conditional')) {
            $content = $ctx->stash('else_content');
        }
        $ctx->restore(array('conditional', 'else_content'));
    }
    return $content;
}
?>
