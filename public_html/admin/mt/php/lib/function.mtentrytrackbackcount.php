<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtentrytrackbackcount.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtentrytrackbackcount($args, &$ctx) {
    $entry = $ctx->stash('entry');
    $count = $entry['entry_ping_count'];
    return $ctx->count_format($count, $args);
}
