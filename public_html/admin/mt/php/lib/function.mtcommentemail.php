<?php
# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: function.mtcommentemail.php 3455 2009-02-23 02:29:31Z auno $

function smarty_function_mtcommentemail($args, &$ctx) {
    $comment = $ctx->stash('comment');
    $email = $comment['comment_email'];
    $email = strip_tags($email);
    if (!preg_match('/@/', $email))
        return '';
    return((isset($args['spam_protect']) && $args['spam_protect']) ? spam_protect($email) : $email);
}
?>
