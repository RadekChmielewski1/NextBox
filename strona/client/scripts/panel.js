//zmienne globalne 
let currentPackageId = null;
let currentLockerId = null;
//pobranie adresu ip z php               
const ipArduino = '';

function initPanel(arduinoIp) {
    ipArduino = arduinoIp;
    console.log('User panel loaded, Arduino IP:', ipArduino);
}

//funkcja otwierania i pokazania modal'u potwierdzenia
function collectPackage(packageId, lockerId) {
    currentPackageId = packageId; //nadpisanie zmiennych globalnych
    currentLockerId = lockerId;

    //otwieranie skrytki 
    if (ipArduino) {
        fetch(ipArduino).catch(() => { });
    }

    // pokazanie modalu potwierdzenia
    new bootstrap.Modal(document.getElementById('confirmModal')).show();
}

//funkcja jesli uzytkownik nacisnął yes
function confirmYes() {
    //Dane do wysłania
    const formData = new FormData();
    formData.append('action', 'confirm_yes');
    formData.append('package_id', currentPackageId);
    formData.append('locker_id', currentLockerId);

    //wysłanie requesta Ajax  do php
    fetch('panel.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide(); //zamknięcie modalu potwierdzenia
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById('successModal')).show(); //pokazanie modalu sukcesu
                    setTimeout(() => location.reload(), 2000); //przeładowanie storny po 2s
                }, 300);
            }
        });
}
//funkcja gdy użytkownik kliknie NO
function confirmNo() {
    //ponownie otwarcie sktryki
    if (ipArduino) {
        fetch(ipArduino).catch(() => { });
    }
    //przygotowanie danych
    const formData = new FormData();
    formData.append('action', 'confirm_no');

    //wysłanie requesta Ajax
    fetch('panel.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide(); //zamknięcie modalu potwierdzenia
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById('unsuccessModal')).show(); //pokazanie modalu niepowodzenia
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('unsuccessModal')).hide(); //ukrycie go po 10 sekundach
                    }, 1000);
                }, 300);
            }
        });

    //zakładamy ze użytkownik odebrał juz paczke i zmieniamy jej status na odebrana i usuwamy z bazy po 1min
    setTimeout(() => {
        //dane do wysłania
        const formData = new FormData();
        formData.append('action', 'confirm_yes');
        formData.append('package_id', currentPackageId);
        formData.append('locker_id', currentLockerId);
        //wysłanie request do ajax 
        fetch('panel.php', { method: 'POST', body: formData })
            .then(() => location.reload());
    }, 60000); // 60 sekund = 1 minuta
}