document.addEventListener('DOMContentLoaded', function () {
  const body = document.body;
  const toggle = document.getElementById('input');
  const loader = document.getElementById('loader');
  const app = document.getElementById('app');

  const frLocale = {
    code: 'fr',
    week: { dow: 1, doy: 4 },
    buttonText: {
      prev: 'Précédent',
      next: 'Suivant',
      today: 'Aujourd\'hui',
      year: 'Année',
      month: 'Mois',
      week: 'Semaine',
      day: 'Jour',
      list: 'Planning'
    },
    weekText: 'Sem.',
    allDayText: 'Toute la journée',
    moreLinkText: 'en plus',
    noEventsText: 'Aucun évènement à afficher'
  };
  if (window.FullCalendar && FullCalendar.globalLocales) {
    FullCalendar.globalLocales.push(frLocale);
  }

  function applyTheme(dark) {
    if (dark) {
      body.classList.add('dark');
      toggle.checked = true;
    } else {
      body.classList.remove('dark');
      toggle.checked = false;
    }
  }

  applyTheme(window.matchMedia('(prefers-color-scheme: dark)').matches);

  toggle.addEventListener('change', () => {
    applyTheme(toggle.checked);
  });

  const calendar = new FullCalendar.Calendar(app, {
    initialView: 'timeGridWeek',
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },
    height: calcHeight(),
    locale: navigator.language.startsWith('fr') ? 'fr' : 'en',
    eventColor: '#b22222',
    eventSources: [{ url: 'calendar.ics.php', format: 'ics' }],
    eventSourceFailure: function() {
      loader.classList.add('hidden');
      app.classList.remove('blurred');
    },

    loading: function (isLoading) {
      if (isLoading) {
        loader.classList.remove('hidden');
        app.classList.add('blurred');
      } else {
        loader.classList.add('hidden');
        app.classList.remove('blurred');
      }
    }
  });

  calendar.render();
  adjustHeader();

  window.addEventListener('resize', () => {
    calendar.setOption('height', calcHeight());
    adjustHeader();
  });

  function calcHeight() {
    const bannerHeight = document.querySelector('.top-banner').offsetHeight;
    const margin = parseFloat(getComputedStyle(app).marginTop) || 0;
    return window.innerHeight - bannerHeight - margin;

  }

  function adjustHeader() {
    const fcHeader = document.querySelector('.fc-scrollgrid-section-header');
    if (fcHeader) {
      const margin = parseFloat(getComputedStyle(app).marginTop) || 0;
      fcHeader.style.top = margin + 'px';
      fcHeader.style.zIndex = '5';
      fcHeader.style.background = 'var(--bg)';
    }
  }
});
