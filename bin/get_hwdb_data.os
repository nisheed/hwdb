#!/usr/bin/perl

use strict;

my $osmain;
my $out = `/bin/cat /etc/redhat-release`;
chomp($out);

if ($out =~ m/Red\ Hat/) {
    $osmain = 'RHEL';
    $out =~ m/Red Hat Enterprise Linux (.*) release (.*) \(.*/;
    my $type = $1; my $ver = $2;
    $type = 'W' if $type =~ 'Workstation';
    $type = 'C' if $type =~ 'Client';
    $type = 'S' if $type =~ 'Server';
    $osmain .= "-" . $type . "-" . $ver;
}

#print $osmain . "\n" ;

my $osdwa = `/bin/cat /etc/dwa-release`;
chomp($osdwa);
if ($osdwa =~ m/Linux/) {
    $osdwa =~ s/Linux-RedHat-//;
    $osdwa =~ s/-Client/C/;
    $osdwa =~ s/-Server/S/;
    $osdwa =~ s/-x86_64//;
}

my $host = `hostname --long`;
chomp($host);

#print "./update_host -h $hostname -osmain $osmain -osdwa $osdwa\n" ;

#my $slno = `dmidecode | grep "Serial Number:" | head -2| tail -1| awk '{print \$3}'`;
my $slno = `dmidecode | grep "Serial Number:" | head -1| awk '{print \$3}'`;
chomp($slno);

my $user = `last | awk '{print \$1}' | head -15 | sort | uniq -c | sort -nr -k 1| head -1 | awk '{print \$2}'`;
chomp($user);
$user = '-' if $user =~ m/reboot/;

my $owner;
$host ||= '-'; $slno ||= '-'; $user ||= '-'; $owner ||= '-'; $osmain ||= '-'; $osdwa ||= '-';

if ( $user ne '-' ) {
    $user = `/usr/bin/lastlog | grep $user | head -1 | awk '{print \$1}'` if $user ne '-';
    chomp($user);

    $owner = `/usr/bin/ldapsearch -x -LLL -b 'uid=$user,ou=People,dc=example,dc=com' gecos | grep gecos | awk -F: '{print \$2}'`;
    chomp($owner);
    $owner =~ s/^\s+//;
    $owner =~ s/\s+$//;
}

my $gcard;
$gcard = `lspci | grep "VGA compatible controller" | head -1`;
chomp($gcard);
$gcard =~ s/.*nVidia Corporation // if $gcard;
$gcard =~ s/.*ATI Technologies Inc // if $gcard; 
$gcard =~ s/.*VGA compatible controller: // if $gcard; 
$gcard =~ s/Device 06d9/Quadro 5000/;
$gcard =~ s/Device 05fe/Quadro FX 4800/;
$gcard =~ s/Device 06d8/Quadro 6000/;
chomp($gcard);

my $gver;
$gver = `grep Kernel /proc/driver/nvidia/version 2> /dev/null`;
chomp($gver);
$gver =~ /.*Kernel Module *([0-9]*\.[0-9]*).*/ if $gver;
$gver = $1;
$gver = '-' if (! defined $gver);

my $ncpu = `grep -c processor /proc/cpuinfo`;
chomp($ncpu);

my $cmd =q:grep MemTotal /proc/meminfo | awk '{print $2}':;
my $mem = `$cmd`;
chomp($mem);

$host ||= '-'; $slno ||= '-'; $user ||= '-'; $owner ||= '-'; $osmain ||= '-'; $osdwa ||= '-';

print "/work/intranet/hwdb/bin/update_host -name $host -u \'$user\' -osmain \'$osmain\' -osdwa \'$osdwa\' -gcard \'$gcard\' -gver \'$gver\' -cpu \'$ncpu\' -ram \'$mem\'\n" ;

