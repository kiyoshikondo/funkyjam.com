<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtcgipath.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtcgipath($args, &$ctx) {
    // status: complete
    // parameters: none
    $path = $ctx->mt->config('CGIPath');
    if (substr($path, 0, 1) == '/') {  # relative
        $blog = $ctx->stash('blog');
        $host = $blog['blog_site_url'];
        if (!preg_match('!/$!', $host))
            $host .= '/';
        if (preg_match('!^(https?://[^/:]+)(:\d+)?/!', $host, $matches)) {
            $path = $matches[1] . $path;
        }
    }
    if (substr($path, strlen($path) - 1, 1) != '/')
        $path .= '/';
    return $path;
}
?>
