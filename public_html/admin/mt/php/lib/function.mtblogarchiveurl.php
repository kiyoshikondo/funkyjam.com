<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtblogarchiveurl.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtblogarchiveurl($args, &$ctx) {
    // status: complete
    // parameters: none
    $blog = $ctx->stash('blog');
    $url = $blog['blog_archive_url'];
    if ($url == '') {
        $url = $blog['blog_site_url'];
    }
    if (!preg_match('/\/$/', $url)) $url .= '/';
    return $url;
}
?>
