package HWDBCollect;

require Exporter;
@ISA = qw(Exporter);
@EXPORT = qw(gethostname getosmain getosdwa getslno getuser getowner getgcard getgver getcpu getmem getmodel gettype getlocation getstatus);

use strict;

sub gethostname {
    my $host = `hostname --long`;
    chomp($host);
    $host =~ s/db.dreamworks.com/example.com/; # working around RAC naming convention
    return $host;
}

sub getosmain {
    my $osmain;
    my $out = `/bin/cat /etc/redhat-release`;
    chomp($out);

    if ($out =~ m/Red\ Hat/) {
        $osmain = 'RHEL';
        $out =~ m/Red Hat Enterprise Linux (.*) release (.*) \(.*/;
        my $type = $1; my $ver = $2;
        $type = 'C' if $type =~ 'Workstation';
        $type = 'C' if $type =~ 'Client';
        $type = 'S' if $type =~ 'Server';
        $osmain .= "-" . $type . "-" . $ver;
    }
    $osmain ||= '-';
    return $osmain ;
}

sub getosdwa{
    my $osdwa = `/bin/cat /etc/dwa-release | /bin/grep .`;
    chomp($osdwa);
    if ($osdwa =~ m/Linux/) {
        $osdwa =~ s/Linux-RedHat-//;
        $osdwa =~ s/-Client/C/;
        $osdwa =~ s/-Server/S/;
        $osdwa =~ s/-x86_64//;
    }
    $osdwa ||= '-';
    return $osdwa;
}

sub getslno {
    my $slno = `/usr/sbin/dmidecode | grep "Serial Number:" | head -1| awk '{print \$3}'`;
    chomp($slno);
    $slno ||= '-';
    return $slno;
}

sub getuser {
    my $user = `last | awk '{print \$1}' | head -15 | sort | uniq -c | sort -nr -k 1| head -1 | awk '{print \$2}'`;
    chomp($user);
    $user = `/usr/bin/lastlog | grep $user | head -1 | awk '{print \$1}'` if $user ne '-';
    chomp($user);
    $user = '-' if $user =~ m/reboot/;
    $user ||= '-';
    return $user;
}

sub getowner {
    my $user = getuser || '-';
    my $owner;
    if ( $user ne '-' ) {
        $user = `/usr/bin/lastlog | grep $user | head -1 | awk '{print \$1}'` if $user ne '-';
        chomp($user);
        $owner = `/usr/bin/ldapsearch -x -LLL -b 'uid=$user,ou=People,dc=example,dc=com' gecos | grep gecos | awk -F: '{print \$2}'`;
        chomp($owner);
        $owner =~ s/^\s+//;
        $owner =~ s/\s+$//;
    }   
    $owner ||= '-';
    return $owner;
}

sub getgcard {
    my $gcard;
    $gcard = `/sbin/lspci | grep "VGA compatible controller" | /usr/bin/head -1`;
    chomp($gcard);
    $gcard =~ s/.*nVidia Corporation /nVidia / if $gcard;
    $gcard =~ s/.*ATI Technologies Inc /ATI / if $gcard; 
    $gcard =~ s/.*VGA compatible controller: // if $gcard; 
    $gcard =~ s/.*Matrox Graphics, Inc. /Matrox / if $gcard; 
    $gcard =~ s/Device 06d9/Quadro 5000/;
    $gcard =~ s/Device 05fe/Quadro FX 4800/;
    $gcard =~ s/Device 06d8/Quadro 6000/;
    $gcard =~ s/Device 0533/G200eH/;
    chomp($gcard);
    return $gcard;
}

sub getgver {
    my $gver;
    $gver = `grep Kernel /proc/driver/nvidia/version 2> /dev/null`;
    chomp($gver);
    $gver =~ /.*Kernel Module *([0-9]*\.[0-9]*).*/ if $gver;
    $gver = $1;
    $gver = '-' if (! defined $gver);
    return $gver;
}

sub getcpu {
    my $ncpu = `grep -c processor /proc/cpuinfo`;
    chomp($ncpu);
    return $ncpu;
}

sub getmem {
    my $cmd =q:grep MemTotal /proc/meminfo | awk '{print $2}':;
    my $mem = `$cmd`;
    chomp($mem);
    return $mem;
}

sub getmodel {
    my $cmd =q:/usr/sbin/dmidecode | grep 'Product Name' | head -1 :;
    my $model = `$cmd`;
    chomp($model);
    #$model =~ s/DMI: (.*),.*/$1/;
    #$model =~ s/Hewlett-Packard HP (.*) Works.*/$1/;
    #$model =~ s/HP ProLiant (.*) Blade.*/$1/;
    #$model =~ s/HP ProLiant (.*)/$1/;
    $model =~ s/.*ProLiant (.*)/$1/;
    $model =~ s/.*HP (.*) Workstation/$1/;
    $model =~ s/ CMT//;
    return $model;
}
sub gettype {
    my $type = getmodel;
    if ( $type =~ /Z800/i || $type =~ /Z200/i || $type =~ /Z220/i  
            || $type =~ /Z820/i || $type =~ /xw9400/i 
            || $type =~ /xw8600/i || $type =~ /xw4600/i ) {
        return 'desktop';
    } else {
      if ( gethostname =~ /ib\d+.*/ || gethostname =~ /ix\d+.*/ ) {
        return 'farm';
      } else {
        return 'server';
      }
    }
    return '-';
}

sub getlocation {
    my $location;
    if ( gettype == 'desktop' ) {
      my $user = getuser || '-';
      if ( $user ne '-' ) {
          $user = `/usr/bin/lastlog | grep $user | head -1 | awk '{print \$1}'` if $user ne '-';
          chomp($user);
          $location = `/usr/bin/ldapsearch -x -LLL -b 'uid=$user,ou=People,dc=example,dc=com' physicalDeliveryOfficeName | grep physicalDeliveryOfficeName | awk -F: '{print \$2}'`;
          chomp($location);
          $location = $1 if $location =~ /.*-(.*)$/;
      }
    }
    $location ||= '-';
    return $location;
}

sub getstatus {
  my $status = `/usr/bin/facter environment`;
  chomp($status);
  $status ||= '-';
  return $status;
}

1;
