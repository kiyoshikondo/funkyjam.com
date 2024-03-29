<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtcommentauthoridentity.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtcommentauthoridentity($args, &$ctx) {
    $cmt = $ctx->stash('comment');
    $cmntr = $ctx->stash('commenter');
    if (!$cmntr) {
        if ($cmt['comment_commenter_id']) {
            # load author related to this commenter.
            $cmntr = $ctx->mt->db->fetch_author($cmt['comment_commenter_id']);
            if (!$cmntr) return "";
        }
    }
    if (!$cmntr) return "";
    if (isset($cmntr['author_url']))
        $link = $cmntr['author_url'];
    require_once "function.mtstaticwebpath.php";
    $static_path = smarty_function_mtstaticwebpath($args, $ctx);
    require_once "commenter_auth_lib.php";
    $logo = _auth_icon_url($static_path, $cmntr);
    if (!$logo) {
        $root_url = $static_path . 'images/';
        if (!preg_match('/\/$/', $root_url)) {
            $root_url .= '/';
        }
        $logo = $root_url . "nav-commenters.gif";
    }
    $result = "<img alt=\"Author Profile Page\" src=\"$logo\" width=\"16\" height=\"16\" />";
    if ($link) {
        $result = "<a class=\"commenter-profile\" href=\"$link\">$result</a>";
    }
    return $result;
}
