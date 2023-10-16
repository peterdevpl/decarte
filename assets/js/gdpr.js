const gdprCookie = 'gdpr=1';

if (document.cookie.indexOf(gdprCookie) === -1) {
    const gdprFrame = document.createElement('div');
    gdprFrame.id = 'gdpr-frame';
    gdprFrame.innerHTML = '<p>Korzystając ze&nbsp;strony decarte-zaproszenia.pl zgadzasz się na&nbsp;używanie plików cookie, które są instalowane na&nbsp;Twoim urządzeniu. ' +
        'W&nbsp;naszym sklepie używamy plików cookie w&nbsp;celu obsługi zamówień oraz&nbsp;przygotowania anonimowych statystyk przez&nbsp;usługę Google Analytics. ' +
        'Więcej informacji w&nbsp;<a href="/informacje/polityka-prywatnosci">Polityce&nbsp;prywatności</a>.</p>' +
        '<button class="accept-cookie">Rozumiem</button>';
    document.body.appendChild(gdprFrame);

    const gdprWarning = document.createElement('div');
    gdprWarning.id = 'gdpr-warning';
    gdprWarning.innerHTML = gdprFrame.innerHTML;
    document.body.appendChild(gdprWarning);

    let i;
    const buttons = document.getElementsByClassName('accept-cookie');
    for (i = 0; i < buttons.length; i++) {
        buttons[i].addEventListener('click', function () {
            document.cookie = gdprCookie;
            document.body.removeChild(gdprWarning);
            document.body.removeChild(gdprFrame);
        });
    }
}
