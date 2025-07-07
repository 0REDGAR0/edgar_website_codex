<?php
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="calendar.ics"');

// URLs des calendriers ICS publics
$icsUrls = [
  'https://calendar.google.com/calendar/ical/edgarbeduneyrinck%40gmail.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/a0c99bb4307bef20fe7d9333e1d759351c76a41235105231a5c420777a955458%40group.calendar.google.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/ca71460206105df3d75140d2c0d512c732b7f0c4e6753fd2aa8a87503498e063%40group.calendar.google.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/ukvj5o47thjhubs9b2g93mhde9em6d1p%40import.calendar.google.com/public/basic.ics'
];

function outputEvents($url) {
  $data = @file_get_contents($url);
  if ($data === false) return;
  $lines = explode("\n", $data);
  $inEvent = false;
  foreach ($lines as $line) {
    $line = rtrim($line);
    if ($line === 'BEGIN:VEVENT') {
      $inEvent = true;
      echo "BEGIN:VEVENT\r\n";
    } elseif ($line === 'END:VEVENT') {
      echo "END:VEVENT\r\n";
      $inEvent = false;
    } elseif ($inEvent) {
      echo $line . "\r\n";
    }
  }
}

echo "BEGIN:VCALENDAR\r\n";
echo "VERSION:2.0\r\n";
echo "PRODID:-//Calendrier Edgar//EN\r\n";
foreach ($icsUrls as $url) {
  outputEvents($url);
}
echo "END:VCALENDAR\r\n";
?>
