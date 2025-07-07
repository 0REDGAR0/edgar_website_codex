<?php
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="calendar.ics"');

$icsUrls = [
  'https://calendar.google.com/calendar/ical/edgarbeduneyrinck%40gmail.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/a0c99bb4307bef20fe7d9333e1d759351c76a41235105231a5c420777a955458%40group.calendar.google.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/ca71460206105df3d75140d2c0d512c732b7f0c4e6753fd2aa8a87503498e063%40group.calendar.google.com/public/basic.ics',
  'https://calendar.google.com/calendar/ical/ukvj5o47thjhubs9b2g93mhde9em6d1p%40import.calendar.google.com/public/basic.ics'
];

$printedHeader = false;
$inEvent = false;

foreach ($icsUrls as $index => $url) {
  $data = @file_get_contents($url);
  if ($data === false) continue;
  $lines = explode("\n", $data);
  foreach ($lines as $line) {
    $line = rtrim($line);
    if (!$printedHeader) {
      if (stripos($line, 'END:VCALENDAR') === 0) {
        $printedHeader = true;
        break;
      }
      echo $line . "\r\n";
    } else {
      if ($line === 'BEGIN:VEVENT') {
        $inEvent = true;
      }
      if ($inEvent) {
        echo $line . "\r\n";
      }
      if ($line === 'END:VEVENT') {
        $inEvent = false;
      }
    }
  }
}

echo "END:VCALENDAR\r\n";
?>
