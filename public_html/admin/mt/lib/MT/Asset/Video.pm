# Movable Type (r) Open Source (C) 2001-2009 Six Apart, Ltd.
# This program is distributed under the terms of the
# GNU General Public License, version 2.
#
# $Id: Video.pm 3455 2009-02-23 02:29:31Z auno $

package MT::Asset::Video;

use strict;
use base qw( MT::Asset );

__PACKAGE__->install_properties( { class_type => 'video', } );

# List of supported file extensions (to aid the stock 'can_handle' method.)
sub extensions {
    my $pkg = shift;
    return $pkg->SUPER::extensions(
        [   qr/mov/i, qr/avi/i, qr/3gp/i, qr/asf/i, qr/mp4/i, qr/qt/i,
            qr/wmv/i, qr/asx/i,  qr/mpg/i, qr/flv/i, qr/mkv/i, qr/ogm/i
        ]
    );
}

sub class_label {
    MT->translate('Video');
}

sub class_label_plural {
    MT->translate('Videos');
}

# translate('video')

1;

__END__

=head1 NAME

MT::Asset::Video

=head1 AUTHOR & COPYRIGHT

Please see the L<MT/"AUTHOR & COPYRIGHT"> for author, copyright, and
license information.

=cut
