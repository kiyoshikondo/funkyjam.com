<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtentrybody.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtentrybody($args, &$ctx) {
    $entry = $ctx->stash('entry');
    $text = $entry['entry_text'];

    $cb = $entry['entry_convert_breaks'];
    if (isset($args['convert_breaks'])) {
        $cb = $args['convert_breaks'];
    } elseif (!isset($cb)) {
        $blog = $ctx->stash('blog');
        $cb = $blog['blog_convert_paras'];
    }
    if ($cb) {
        if (($cb == '1') || ($cb == '__default__')) {
            # alter EntryBody, EntryMore in the event that
            # we're doing convert breaks
            $cb = 'convert_breaks';
        }
        require_once 'MTUtil.php';
        $text = apply_text_filter($ctx, $text, $cb);
    }
    if (isset($args['words'])) {
        require_once("MTUtil.php");
        return first_n_text($text, $args['words']);
    } else {
        if (preg_match('/\smt:asset-id="\d+"/', $text)) {
            require_once("MTUtil.php");
            $text = asset_cleanup($text);
        }
        return $text;
    }
}
?>
