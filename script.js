document.addEventListener('DOMContentLoaded', function () {
  const body = document.body;
  const toggle = document.getElementById('input');
  const loader = document.getElementById('loader');
  const app = document.getElementById('app');

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
    locale: navigator.language,
    eventColor: '#b22222',
    eventSources: [{ url: 'calendar.ics.php', format: 'ics' }],
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
    return window.innerHeight - document.querySelector('.top-banner').offsetHeight;
  }

  function adjustHeader() {
    const fcHeader = document.querySelector('.fc-scrollgrid-section-header');
    if (fcHeader) {
      fcHeader.style.top = document.querySelector('.top-banner').offsetHeight + 'px';
      fcHeader.style.zIndex = '5';
      fcHeader.style.background = 'var(--bg)';
    }
  }
});
