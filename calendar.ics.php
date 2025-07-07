<?php
header('Content-Type: application/json');

// URLs des calendriers ICS publics
$icsUrls = [
  'https://calendar.google.com/calendar/ical/edgarbeduneyrinck%40gmail.com/public/basic.ics', // Default
  'https://calendar.google.com/calendar/ical/a0c99bb4307bef20fe7d9333e1d759351c76a41235105231a5c420777a955458%40group.calendar.google.com/public/basic.ics', // Cocotte
  'https://calendar.google.com/calendar/ical/ca71460206105df3d75140d2c0d512c732b7f0c4e6753fd2aa8a87503498e063%40group.calendar.google.com/public/basic.ics', // Avialpes
  'https://calendar.google.com/calendar/ical/ukvj5o47thjhubs9b2g93mhde9em6d1p%40import.calendar.google.com/public/basic.ics' // IUT
];

function parseIcsEvents($url) {
  $lines = explode("\n", file_get_contents($url));
  $events = [];
  $event = null;
  foreach ($lines as $line) {
    $line = trim($line);
    if ($line === 'BEGIN:VEVENT') {
      $event = [];
    } elseif ($line === 'END:VEVENT') {
      if ($event) $events[] = $event;
      $event = null;
    } elseif ($event !== null) {
      if (strpos($line, 'SUMMARY:') === 0) {
        $event['title'] = substr($line, 8);
      } elseif (strpos($line, 'DTSTART') === 0) {
        preg_match('/:(.+)/', $line, $match);
        $event['start'] = date('c', strtotime($match[1]));
      } elseif (strpos($line, 'DTEND') === 0) {
        preg_match('/:(.+)/', $line, $match);
        $event['end'] = date('c', strtotime($match[1]));
      }
    }
  }
  return $events;
}

// Fusionner les événements des différents calendriers
$allEvents = [];
foreach ($icsUrls as $url) {
  $allEvents = array_merge($allEvents, parseIcsEvents($url));
}

echo json_encode($allEvents);
?>
