<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtcommentrank.php 3455 2009-02-23 02:29:31Z auno $

require_once('rating_lib.php');

function smarty_function_mtcommentrank($args, &$ctx) {
    return hdlr_rank($ctx, 'comment', $args['namespace'], $args['max'],
        "AND (comment_visible = 1)\n", $args
    );
}
?>
