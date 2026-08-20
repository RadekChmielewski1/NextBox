//zmienne globalne 
let currentPackageId = null;
let currentLockerId = null;
//pobranie adresu ip z php               
const ipArduino = '';

//funkcja otwierania i pokazania modal'u potwierdzenia
function collectPackage(packageId, lockerId) {
    currentPackageId = packageId; //nadpisanie zmiennych globalnych
    currentLockerId = lockerId;

    //otwieranie skrytki 
    if (ipArduino) {
        fetch(ipArduino).catch(() => { });
    }

    // pokazanie modalu potwierdzenia
    new bootstrap.Modal(document.getElementById('confirmDeliveryModal')).show();
}

//funkcja jesli uzytkownik nacisnął yes
function confirmYes() {
    //Dane do wysłania
    const formData = new FormData();
    formData.append('action', 'confirm_yes');
    formData.append('package_id', currentPackageId);
    formData.append('locker_id', currentLockerId);

    //wysłanie requesta Ajax  do php
    fetch('delivery.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('confirmDeliveryModal')).hide(); //zamknięcie modalu potwierdzenia
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById('deliveryModal')).show(); //pokazanie modalu dostarczenia
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
    fetch('delivery.php', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('confirmDeliveryModal')).hide(); //zamknięcie modalu potwierdzenia
                setTimeout(() => {
                    new bootstrap.Modal(document.getElementById('noDeliveryModal')).show(); //pokazanie modalu niedostarczenia
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('noDeliveryModal')).hide(); //ukrycie go po 10 sekundach
                    }, 10000);
                }, 300);
            }
        });

    //zakładamy ze kurier dostarczył juz paczke i zmieniamy jej status na dostarczony i usuwamy z bazy po 1min
    setTimeout(() => {
        //dane do wysłania
        const formData = new FormData();
        formData.append('action', 'confirm_yes');
        formData.append('package_id', currentPackageId);
        formData.append('locker_id', currentLockerId);
        //wysłanie request do ajax 
        fetch('delivery.php', { method: 'POST', body: formData })
            .then(() => location.reload());
    }, 60000); // 60 sekund 

}

function showSupport() {
    new bootstrap.Modal(document.getElementById('supportModal')).show();
}